$(document).ready(function() {
	if($('.segmentation').size() > 0) { // if segmentation page
		var drawer = new LogstatsGraphDrawer('.graph-area', {
			enablePointHover: true,
			enableLineManipulation: true,
			enableSelectionZooming: true,
			timezone: $("#data-holder").attr("data-timezone")
		});
		var queryUrl = $("input[name='query-url']").val();
		var projectToken = $("input[name='project-token']").val();
		var eventPageCount = 30;
		var displayedMessageLength = 100;
		var filterId = 0;

		$("#query-form").submit(function(e) {
			show_loader();
			var query = $(this).serializeObject();
			if ($('select#event').val()) {
				var split = $('select#event').val().split(',');
				query.event = split[1];
			}
			console.log(query);
			if (typeof query.filters != "undefined") {
				query.filters = $.grep(query.filters, function(value) {
					return typeof value != "undefined";
				});
			}
			if (query.interval == "None") {
				delete query.interval;
			}
			var logstatsQuery = new LogstatsQuery(queryUrl, projectToken);
			logstatsQuery.get(query, function(data) {
				drawer.draw(data.data, data.timeframe);
				hide_loader();
			}, function(data) {
				var errors = typeof data.responseJSON != "undefined" ? data.responseJSON : ["Error while retrieving data"];
				drawer.displayErrors(errors);
				hide_loader();
			});
			e.preventDefault();
		});

		$("select#event").select2({
			ajax: {
				url: $('select#event').attr('data-url'),
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						'message-search': params.term, // search term
						'page': params.page,
						'page-count': eventPageCount,
						'project-id': $('input[name="project-id"]').val(),
						'level': $('select[name="level"]').val()
					};
				},
				processResults: function (data, params) {
					params.page = params.page || 1;

					return {
						results: $.map(data.items, function (item, key) {
							var message = item;
							if (message.length > displayedMessageLength) {
								message = message.substring(0, displayedMessageLength) + '...'
							}
							return {
								text: message,
								slug: message,
								id: key + ',' + item // option value in format "id,message"
							}
						}),
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			minimumInputLength: 0,
			placeholder: "Choose event..."
			//templateResult: formatRepo, // omitted for brevity, see the source of this page
			//templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		});

		$(".group-by select").select2({placeholder:'Property name', allowClear: true});
		$(".target-property select").select2({placeholder:'Property name'});

		$("select#event").change(function() {
			var split = $("select#event").val().split(',');
			$("select[name='groupBy']").select2("val", "");
			$("select[name='targetProperty']").select2("val", "");
			reload_property_names(split[0]);
		});

		$(".aggregation select").change(function() {
			if ($(this).val() == 'count') {
				$(".target-property select").attr('disabled', 'disabled');
			} else {
				$(".target-property select").removeAttr('disabled');
			}
		});

		$(".add-filter").click(function() {
			add_filter_row();
			$(".down-control").show();
		});

		$("#add-filter-row").click(add_filter_row);
		$(".filters").on('click', '.remove-filter-row', function() {
			$("div#" + $(this).attr('data-id')).remove();
		});


		function reload_property_names(messageId) {
			$.ajax({
				type: "GET",
				url: $(".segmentation").attr('data-property-names-url'),
				data: {
					'message-id': messageId
				}, // serializes the form's elements.
				success: function(data)
				{
					$('.property-options').html('');
					$('.property-options').append('<option>&nbsp;</option>');
					$.each(data, function(key, value) {
						$('.property-options').append('<option value="'+value+'">'+value+'</option>');
					});
				}
			});
		}

		function add_filter_row() {
			var html = $("#example-filter-row").html();
			$(".filters").append('<div id="'+filterId+'">'+html+'</div>');
			var row = $('div#'+filterId);
			$(".property-name", row).select2({placeholder:'Property name'});
			$(".property-name", row).attr('name', 'filters['+filterId+'][propertyName]');
			$(".comparison", row).attr('name', 'filters['+filterId+'][comparisonType]');
			$(".value", row).attr('name', 'filters['+filterId+'][propertyValue]');
			$(".remove-filter-row", row).attr("data-id", filterId);
			filterId++;
		}

		function show_loader() {
			$("#loader").show();
		}

		function hide_loader() {
			$("#loader").hide();
		}
	}



});