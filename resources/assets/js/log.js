$(document).ready(function() {
	if ($('.log').size() > 0) {
		var graphDrawer = new LogstatsGraphDrawer('.log-graph-area', {
			enablePointHover: true,
			enableLineManipulation: false,
			enableSelectionZooming: false,
			timezone: $("#data-holder").attr("data-timezone")
		});
		function load_records() {
			show_loader();
			$.ajax({
				type: "GET",
				url: $("form#get-records").attr("action"),
				data: $("form#get-records").serialize(), // serializes the form's elements.
				success: function(data)
				{
					if (data.count == 0) {
						$(".log-records").html("No records found");
					} else {
						$(".log-records").html(data.html);
					}

					totalRecordCount = data.count;
					generate_page_numbers();
					console.log(data.graphData);
					if (data.graphData.data && data.graphData.data.length > 0) {
						$(".log-graph").show();
						graphDrawer.draw(data.graphData.data, data.graphData.timeframe);
					} else {
						$(".log-graph").hide();
					}
					hide_loader();
				}
			});
		}

		function generate_page_numbers() {
			var pageCount = Math.ceil(totalRecordCount / recordPerPage);
			$('.page-numbers ul').html('');
			for (var i = 1; i <= pageCount; i++) {
				var active = "";
				if (i == page) {
					active = "active";
				}
				$('.page-numbers ul').append('<li class="'+active+'" data-page="'+i+'"><a href="">'+i+'</a></li>');
			}
		}

		function show_loader() {
			$("#loader").show();
		}

		function hide_loader() {
			$("#loader").hide();
		}

		function add_filter_row() {
			var html = $("#example-filter-row").html();
			$(".filters").append('<div id="'+filterId+'">'+html+'</div>');
			var row = $('div#'+filterId);
			$(".property-name", row).attr('name', 'filters['+filterId+'][property-name]');
			$(".property-type", row).attr('name', 'filters['+filterId+'][property-type]');
			$(".comparison", row).attr('name', 'filters['+filterId+'][comparison-type]');
			$(".value", row).attr('name', 'filters['+filterId+'][property-value]');
			$(".remove-filter-row", row).attr("data-id", filterId);
			$('select[name="filters['+filterId+'][comparison-type]"]').chained('select[name="filters['+filterId+'][property-type]"]');
			filterId++;
		}

		var page = 1;
		var totalRecordCount = 0;
		var recordPerPage = 100;
		var filterId = 1;

		$("form#get-records").submit(function(e) {
			$("input[name='page']").val('1');
			load_records();
			e.preventDefault();
		})

		$(".page-numbers").on('click', 'li a', function(e) {
			page = $(this).parent().attr('data-page');
			$('.page-numbers li').removeClass('active');
			$(this).parent().addClass('active');
			$('input[name="page"]').val(page);
			load_records();
			window.scrollTo(0,0);
			e.preventDefault();
			return false;
		});

		$(".add-filter").click(function() {
			$(".down-control").show();
		});

		$("#add-filter-row").click(add_filter_row);
		$(".filters").on('click', '.remove-filter-row', function() {
			$("div#" + $(this).attr('data-id')).remove();
		});

		if($('div.log')) { // if log page
			add_filter_row();
			load_records();
		}




	} // end of if
});

