$(document).ready(function() {

	function set_daterange(start, end) {
		var label = start.format('DD MMMM YYYY, HH:mm:ss');
		label += ' - ' + end.format('DD MMMM YYYY, HH:mm:ss');
		$('div#daterange label').html(label);
	}



	function moment_as_default_timezone_to_UTC_timestamp(time) {
		var offset = moment().tz(timezone).utcOffset() - time.utcOffset();
		return time.unix() - offset * 60;
	}

	var timezone = $("#data-holder").attr("data-timezone");
	var currentTimestamp = Date.now();
	$('.daterange input[name=from]').val(moment.tz(currentTimestamp,timezone).subtract(1, 'hour').unix());
	$('.daterange input[name=to]').val(moment.tz(currentTimestamp,timezone).unix());
	$('div#daterange').daterangepicker({
		"timePicker": true,
		"timePicker24Hour": true,
		"startDate": moment.tz(currentTimestamp,timezone).subtract(1, 'hour'),
		"endDate": moment.tz(currentTimestamp,timezone),
		"opens" : "left",
		ranges: {
			'Today': [moment.tz(currentTimestamp,timezone).startOf('day'), moment.tz(currentTimestamp,timezone)],
			'Yesterday': [moment.tz(currentTimestamp,timezone).subtract(1, 'days').startOf('day'), moment.tz(currentTimestamp,timezone).subtract(1, 'days').endOf('day')],
			'Last 20 Minutes': [moment.tz(currentTimestamp,timezone).subtract(20, 'minutes'), moment.tz(currentTimestamp,timezone)],
			'Last Hour': [moment.tz(currentTimestamp,timezone).subtract(1, 'hours'), moment.tz(currentTimestamp,timezone)],
			'Last 7 Days': [moment.tz(currentTimestamp,timezone).subtract(7, 'days'), moment.tz(currentTimestamp,timezone)],
			'Last 14 Days': [moment.tz(currentTimestamp,timezone).subtract(14, 'days'), moment.tz(currentTimestamp,timezone)],
			'Last 30 Days': [moment.tz(currentTimestamp,timezone).subtract(30, 'days'), moment.tz(currentTimestamp,timezone)],
			'This Month': [moment.tz(currentTimestamp,timezone).startOf('month'), moment.tz(currentTimestamp,timezone).endOf('month')]
		}
	}, function(start,end,label) {
		if (label == "Custom Range") {
			$('input[name=from]').val(moment_as_default_timezone_to_UTC_timestamp(start));
			$('input[name=to]').val(moment_as_default_timezone_to_UTC_timestamp(end));
			set_daterange(start, end)
		} else {
			$('input[name=from]').val(start.unix());
			$('input[name=to]').val(end.unix());
			set_daterange(start.tz(timezone), end.tz(timezone));
		}
	});



	set_daterange(moment().tz(timezone).subtract(1, 'hour'), moment().tz(timezone));


});


timezoneJS.timezone.zoneFileBasePath = "public/libraries/flot/tz";
timezoneJS.timezone.defaultZoneFile = [];
timezoneJS.timezone.init({async: false});

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


$(document).ready(function() {
	if($('.segmentation').size() > 0) { // if segmentation page

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



	}



});
function LogstatsQuery(url, projectToken) {
	this.url = url;
	this.projectToken = projectToken;
}

LogstatsQuery.prototype.get = function(queryData, successFunction, errorFunction) {
	$.ajax({
		type: "GET",
		url: this.url,
		data: {
			query: queryData,
			projectToken: this.projectToken
		},
		success: successFunction,
		error: errorFunction
	});
}
/**
 * Class which converts raw logstats data to better-working-with associative data
 *
 * @param data logstats data
 * @param interval interval in which were data queried
 * @param timeframe timeframe object
 * @constructor
 */
function LogstatsDataConverter(data, interval, timeframe) {
	this.data = data;
	this.interval = interval;
	this.timeframe = timeframe;
}

/**
 * Get associative data for chart with more lines in this format:
 * groupByName.year.month.day.hour.minute = numberValue
 */
LogstatsDataConverter.prototype.getMoreLinesAssociativeWithZeros = function () {
	var emptyGroupAssocc = this.getEmptyGroupAssociativeData();
	var groupAssocc = this.getGroupAssoc(emptyGroupAssocc);
	return groupAssocc;
}

/**
 * Get associative data for chart with one lines in this format:
 * year.month.day.hour.minute = numberValue
 */
LogstatsDataConverter.prototype.getOneLineAssociativeWithZeros = function() {
	var emptyDateAssoc = this.getEmptyDateAssociativeData();
	return this.getAssoc(emptyDateAssoc)
}

/**
 * Get all used group by names in current data
 */
LogstatsDataConverter.prototype.getAllGroupByNames = function () {
	var names = [];
	for (i = 0; i < this.data.length; i++) {
		if (names.indexOf(this.data[i].group) == -1) { // value does not exist
			names.push(this.data[i].group);
		}
	}

	return names;
};

/**
 * Get empty associative data in this format
 * groupByName.year.month.day.hour.minute = 0
 */
LogstatsDataConverter.prototype.getEmptyGroupAssociativeData = function() {
	var names = this.getAllGroupByNames();
	var data = {};
	for (var i = 0; i < names.length; i++) {
		var emptyDateData = this.getEmptyDateAssociativeData();
		data[names[i]] = emptyDateData;
	}
	return data;
}

/**
 * Get empty associative data in this format
 * year.month.day.hour.minute = 0
 */
LogstatsDataConverter.prototype.getEmptyDateAssociativeData = function() {
	var startDate = this.getStartDate();
	var endDate = this.getEndDate();
	var currentDate = startDate;
	var dateData = {};
	while (currentDate.isBefore(endDate)) {
		this.initializeDateDate(dateData, currentDate);
		dateData[currentDate.year()][currentDate.month()][currentDate.date()][currentDate.hour()][currentDate.minute()] = 0;
		this.incrementDate(currentDate);
	}
	return dateData;
}

/**
 * Get start date from timeframe or data
 */
LogstatsDataConverter.prototype.getStartDate = function() {
	var startDate;
	if (typeof this.timeframe != "undefined") {
		startDate = this.roundDateDown(moment(this.timeframe.from, "X").tz('GMT'));
	} else {
		var date = this.data[0];
		startDate = moment.tz({ year :date.year, month :date.month - 1, day :date.day, hour :date.hour, minute :date.minute}, 'GMT');
	}
	return startDate;
}

/**
 * Round date according to interval
 * @param date
 */
LogstatsDataConverter.prototype.roundDateDown = function(date) {
	date.second(0);
	if (this.interval == "minutely") {
		return date
	}
	date.minute(0);
	if (this.interval == "hourly") {
		return date;
	}
	date.hours(0);
	if (this.interval == "daily") {
		return date;
	}
	date.date(1);
	if (this.interval == "monthly") {
		return date;
	}
	date.month(0);
	return date;
}

/**
 * Get end date from timeframe or data
 */
LogstatsDataConverter.prototype.getEndDate = function() {
	var endDate;
	if (typeof this.timeframe != "undefined") {
		endDate = moment(this.timeframe.to, "X").tz('GMT');
	} else {
		var date = this.data[this.data.length - 1];
		endDate = moment.tz({ year :date.year, month :date.month - 1, day :date.day, hour :date.hour, minute :date.minute}, 'GMT');
	}
	return endDate;
}

/**
 * Increment date according to interval
 */
LogstatsDataConverter.prototype.incrementDate = function(date) {
	if (this.interval == "minutely") {
		date.add(1, 'm');
	}
	if (this.interval == "hourly") {
		date.add(1, 'h');
	}
	if (this.interval == "daily") {
		date.add(1, 'd');
	}
	if (this.interval == "monthly") {
		date.add(1, 'M');
	}
	if (this.interval == "yearly") {
		date.add(1, 'y');
	}

	return date;
}

/**
 * Initialize empty objects for date to avoid writing to not set keys
 *
 * @param dateData
 * @param currentDate
 */
LogstatsDataConverter.prototype.initializeDateDate = function(dateData, currentDate) {
	if (typeof dateData[currentDate.year()] == "undefined") {
		dateData[currentDate.year()] = {};
	}
	if (typeof dateData[currentDate.year()][currentDate.month()] == "undefined") {
		dateData[currentDate.year()][currentDate.month()] = {};
	}
	if (typeof dateData[currentDate.year()][currentDate.month()][currentDate.date()] == "undefined") {
		dateData[currentDate.year()][currentDate.month()][currentDate.date()] = {};
	}
	if (typeof dateData[currentDate.year()][currentDate.month()][currentDate.date()][currentDate.hour()] == "undefined") {
		dateData[currentDate.year()][currentDate.month()][currentDate.date()][currentDate.hour()] = {};
	}
	if (typeof dateData[currentDate.year()][currentDate.month()][currentDate.date()][currentDate.hour()][currentDate.minute()]== "undefined") {
		dateData[currentDate.year()][currentDate.month()][currentDate.date()][currentDate.hour()][currentDate.minute()] = {};
	}
}

/**
 * Add values to empty data in this format
 * groupByName.year.month.day.hour.minute = 0
 * and return data in this format
 * groupByName.year.month.day.hour.minute = value
 */
LogstatsDataConverter.prototype.getGroupAssoc = function(emptyGroupAssoc) {
	for (var i = 0; i < this.data.length; i++) {
		var row = this.data[i];
		var group = row.group;
		var year = row.year
		var month = typeof row.month != "undefined" ? row.month : 1;
		var day = typeof row.day != "undefined" ? row.day : 1;
		var hour = typeof row.hour != "undefined" ? row.hour : 0;
		var minute = typeof row.minute != "undefined" ? row.minute : 0;
		emptyGroupAssoc[group][year][month - 1][day][hour][minute] = row.value;
	}

	return emptyGroupAssoc;
}

/**
 * Add values to empty data in this format
 * year.month.day.hour.minute = 0
 * and return data in this format
 * year.month.day.hour.minute = value
 */
LogstatsDataConverter.prototype.getAssoc = function(emptyAssoc) {
	for (var i = 0; i < this.data.length; i++) {
		var row = this.data[i];
		var year = row.year
		var month = typeof row.month != "undefined" ? row.month : 1;
		var day = typeof row.day != "undefined" ? row.day : 1;
		var hour = typeof row.hour != "undefined" ? row.hour : 0;
		var minute = typeof row.minute != "undefined" ? row.minute : 0;
		emptyAssoc[year][month - 1][day][hour][minute] = row.value;
	}

	return emptyAssoc;
}



/**
 * @param selector jquery selector where to draw graph
 * @param options (timezone,enablePointHover,eneableSelectionZooming,enableTimeManipulation)
 * @constructor
 */
function LogstatsGraphDrawer(selector, options) {
	this.selector = selector;
	this.currentShowedData = [];
	this.options = typeof options != "undefined" ? options : [];
	this.flotLineOptions = this.getFlotLineOptions();

	if (this.options.enablePointHover) {
		this.enableFlotHover();
	}

	if (this.options.enableSelectionZooming) {
		this.enableSelectionZooming();
	}
}

/**
 * Draw chart
 *
 * @param data Data in logstats format
 * @param timeframe Timeframe object in which to display data
 * 			(timeframe.from, timefram.to in timestamp)
 */
LogstatsGraphDrawer.prototype.draw = function(data, timeframe) {
	this.logstatsData = data;
	this.timeframe = timeframe;
	this.interval = this.determineInterval();
	this.groupBySet = this.determineGroupBy();
	var graphType = this.determineBestGraphType();
	this.clearGraph();

	if (!this.validDataCount()) {
		this.displayErrors(['Too much datapoints to display. Please choose larger interval or smaller timeframe']);
		return;
	}

	switch (graphType) {
		case GraphType.NO_DATA:
			this.drawNoData();
			break;
		case GraphType.ONE_VALUE:
			this.drawOneValue();
			break;
		case GraphType.BAR:
			this.drawBar();
			break;
		case GraphType.ONE_LINE:
			this.drawOneLine();
			break;
		case GraphType.MULITPLE_LINES:
			this.drawMultipleLines();
			break;
	}
}

/**
 * Prepare graph area for new display
 */
LogstatsGraphDrawer.prototype.clearGraph = function() {
	$(".graph-checkboxes").html("");
	$(this.selector).removeClass("graph-one-value-container");
}

/**
 * Display that no data has been found
 */
LogstatsGraphDrawer.prototype.drawNoData = function() {
	this.displayErrors(['No data found by given query'])
}

/**
 * Display errors
 *
 * @param array errors
 */
LogstatsGraphDrawer.prototype.displayErrors = function(errors) {
	self = this;
	$(this.selector).html("<div class='bg-danger errors message-div'><ul class='list-unstyled'></ul></div>");
	$.each(errors, function(key, error) {
		$("ul", $(self.selector)).append('<li>'+error+'</li>');
	});
}

/**
 * Display only one (number) value
 */
LogstatsGraphDrawer.prototype.drawOneValue = function() {
	var value = this.logstatsData[0].value;
	$(this.selector).html("<div class='graph-one-value'>Result: "+value+"</div>");
	$(this.selector).addClass("graph-one-value-container");
}

/**
 * Draw bar chart
 */
LogstatsGraphDrawer.prototype.drawBar = function() {
	var data = this.getFlotBarData();
	this.flot = $.plot(this.selector, data, this.getFlotBarOptions());
}

/**
 * Draw bar chart with one line
 */
LogstatsGraphDrawer.prototype.drawOneLine = function() {
	var data = this.getFlotOneLineData();
	this.currentShowedData = data;
	this.flot = $.plot(this.selector, data, this.flotLineOptions);
}

/**
 * Draw chart with multiple lines
 */
LogstatsGraphDrawer.prototype.drawMultipleLines = function() {
	var multiLineAssocData = this.getMultipleLineAssociativeDataWithZeros();
	var data = this.getFlotMultiLineData(multiLineAssocData);
	this.currentShowedData = data;
	if (this.options.enableLineManipulation) {
		this.showCheckboxes();
		this.enableLineManipulation();
	}
	this.flot = $.plot(this.selector, data, this.flotLineOptions);
}

/**
 * Get complet data for all lines in flot format
 *
 * @param multiLineAssocData associative data which contain values to display, in this format:
 * 		groupByName.year.month.day.hour.minute = value
 * @returns {Array}
 */
LogstatsGraphDrawer.prototype.getFlotMultiLineData = function(multiLineAssocData) {
	var self = this;
	var flotData = [];
	var colorNumber = 0;
	$.each(multiLineAssocData, function(groupName, groupAssoc) {
		flotData.push({
			label: groupName,
			data: self.getFlotDateData(groupAssoc),
			clickable: true,
			hoverable: true,
			shadowSize: 2,
			color: colorNumber++
		});
	});
	return flotData;
}

/**
 * Get data in flot format for one line
 * Returned data are in this format: [[timestamp,value],[timestamp,value],...]
 *
 * @param data associative data for dates (without group by names)
 * @returns {Array}
 */
LogstatsGraphDrawer.prototype.getFlotDateData = function(data) {
	var returnData = [];
	$.each(data, function(yearName, yearAssoc) {
		$.each(yearAssoc, function(monthName, monthAssoc) {
			$.each(monthAssoc, function(dayName, dayAssoc) {
				$.each(dayAssoc, function(hourName, hourAssoc) {
					$.each(hourAssoc, function(minuteName, value) {
						var microTimestamp = moment.tz({year:yearName, month: monthName, day:dayName, hour:hourName, minute:minuteName}, 'GMT').unix() * 1000;
						returnData.push([microTimestamp, value]);
					});
				});
			});
		});
	});

	return returnData;
}

/**
 * Get associative data in this format:
 * groupByName.year.month.day.hour.minute = value
 */
LogstatsGraphDrawer.prototype.getMultipleLineAssociativeDataWithZeros = function() {
	var zeroAdder = new LogstatsDataConverter(this.logstatsData, this.interval, this.timeframe);
	return zeroAdder.getMoreLinesAssociativeWithZeros();
}

/**
 * Get complet data in flot format
 */
LogstatsGraphDrawer.prototype.getFlotOneLineData = function() {
	var zeroAdder = new LogstatsDataConverter(this.logstatsData, this.interval, this.timeframe);
	var data = zeroAdder.getOneLineAssociativeWithZeros();
	return [{
		data: this.getFlotDateData(data),
		label: 'Result',
		clickable: true,
		hoverable: true,
		shadowSize: 2,
		color: $("#data-holder").css('color')
	}];
}

/**
 * Get complet data in flot format for bar chart
 */
LogstatsGraphDrawer.prototype.getFlotBarData = function() {
	var data = [];
	$.each(this.logstatsData, function(key, value) {
		data.push([key, value.value]);
	});

	return [{
		label: 'Result',
		data: data,
		clickable: true,
		hoverable: true,
		color: $("#data-holder").css('color')
	}];
}

/**
 * Determine which graph type is best for current data
 */
LogstatsGraphDrawer.prototype.determineBestGraphType = function() {
	if (this.logstatsData.length == 0) {
		return GraphType.NO_DATA;
	}

	if (typeof this.interval == "undefined" && !this.groupBySet) {
		return GraphType.ONE_VALUE;
	}

	if (typeof this.interval != "undefined" && this.groupBySet) {
		return GraphType.MULITPLE_LINES;
	}

	if (typeof this.interval == "undefined") {
		return GraphType.BAR;
	}
	return GraphType.ONE_LINE;
}

/**
 * Determine which interval was used to receive current data
 */
LogstatsGraphDrawer.prototype.determineInterval = function() {
	if (this.logstatsData.length == 0) {
		return;
	}

	var value = this.logstatsData[0];
	if (typeof value.minute != "undefined") {
		return "minutely";
	}
	if (typeof value.hour != "undefined") {
		return "hourly"
	}
	if (typeof value.day != "undefined") {
		return "daily"
	}
	if (typeof value.month != "undefined") {
		return "monthly"
	}
	if (typeof value.year != "undefined") {
		return "yearly"
	}

}

/**
 * Determine whether group by function was used to retreive current data
 */
LogstatsGraphDrawer.prototype.determineGroupBy = function() {
	if (this.logstatsData.length == 0) {
		return;
	}

	var value = this.logstatsData[0];
	return (typeof value.group != "undefined");
}

/**
 * Enable to hover over flot points and to display value in that point
 */
LogstatsGraphDrawer.prototype.enableFlotHover = function() {
	var self = this;
	$(this.selector).bind("plothover", function (event, pos, item) {
		if (item) {
			if (item.datapoint[0] > 1000) { // is date
				upper = self.getDateInFormat(moment(item.datapoint[0].toFixed(2),'x')) + "<br>";
			} else {
				upper = '';
			}
			var value = item.datapoint[1].toFixed(2),
				label = item.series.label;

			$(".graph-tooltip").html(upper  + label + " : " + value)
				.css({top: item.pageY+5, left: item.pageX+5})
				.show();
		} else {
			$(".graph-tooltip").hide();
		}
	});
}

/**
 * Display given time in format which is best for current interval
 * @param moment time
 */
LogstatsGraphDrawer.prototype.getDateInFormat = function(moment) {
	if (this.interval == "yearly") {
		return moment.tz(this.options.timezone).format('YYYY');
	}
	if (this.interval == "monthly") {
		return moment.tz(this.options.timezone).format('MMMM YYYY');
	}
	if (this.interval == "daily") {
		return moment.tz(this.options.timezone).format('MMMM Do YYYY');
	}
	if (this.interval == "hourly") {
		return moment.tz(this.options.timezone).format('MMMM Do YYYY, H:mm');
	}

	return moment.tz(this.options.timezone).format('MMMM Do YYYY, H:mm');
}

/**
 * Enable remove lines from graph with checkboxes
 */
LogstatsGraphDrawer.prototype.enableLineManipulation = function() {
	var self = this;
	$(".graph-checkboxes input").click(function() {
		var data = [];

		$(".graph-checkboxes input:checked").each(function () {
			var key = $(this).attr("name");
			if (key !== false && self.currentShowedData[key]) {
				data.push(self.currentShowedData[key]);
			}
		});

		if (data.length > 0) {
			//self.flot = $.plot(self.selector, data, self.flotOptions);
			self.flot.setData(data);
			self.flot.draw();
		}
	});
}

/**
 * Display checkboxes for line manipulation
 */
LogstatsGraphDrawer.prototype.showCheckboxes = function() {
	$(".graph-checkboxes").html("");
	$.each(this.currentShowedData, function(key, value) {
		$(".graph-checkboxes").append("<input type='checkbox' name='" + key +
		"' checked='checked' id='id" + key + "'></input>" +
		"<label for='id" + key + "'>"
		+ value.label + "</label>");
	});
}

/**
 * Enable chart zooming with seleciton
 */
LogstatsGraphDrawer.prototype.enableSelectionZooming = function() {
	var self = this;
	$(this.selector).bind("plotselected", function (event, ranges) {
		$.each(self.flot.getXAxes(), function(_, axis) {
			var opts = axis.options;
			opts.min = ranges.xaxis.from;
			opts.max = ranges.xaxis.to;
		});
		self.flot.setupGrid();
		self.flot.draw();
		self.flot.clearSelection();
	});
}

/**
 * Get flot options for line chart
 */
LogstatsGraphDrawer.prototype.getFlotLineOptions = function() {
	var options =  {
		series: {
			lines: {show: true},
			points: {show: true}
		},
		grid: {
			clickable: true,
			hoverable: true,
			autoHighlight: true
		},
		xaxis: {
			mode: "time",
			timezone: this.options.timezone
		}
	}

	if (this.options.enableSelectionZooming) {
		options.selection = {
			mode: "x"
		}
	}

	return options;
}

/**
 * Get flot options for bar chart
 */
LogstatsGraphDrawer.prototype.getFlotBarOptions = function() {
	var ticks = [];

	$.each(this.logstatsData, function(key, value) {
		ticks.push([key,value.group]);
	});

	return {
		series: {
			lines: {show: false},
			bars: { show: true },
			points: {show: false}
		},
		bars: {
			align: 'center',
			barWidth: 0.5
		},
		grid: {
			clickable: true,
			hoverable: true,
			autoHighlight: true
		},
		xaxis: {
			ticks: ticks
		}
	}
}

/**
 * Determine whether there is not too much data points to display for current data
 * @returns {boolean}
 */
LogstatsGraphDrawer.prototype.validDataCount = function() {
	if (!this.timeframe || !this.interval)
		return true;
	console.log(this.interval);
	var minutesToDisplay = (this.timeframe.to - this.timeframe.from) / 60;
	console.log(minutesToDisplay);
	var step = 1;
	if (this.interval == "hourly") {
		step *= 60;
	}
	if (this.interval == "daily") {
		step *= 24;
	}
	if (this.interval == "monthly") {
		step *= 30;
	}
	if (this.interval == "yearly") {
		step *= 365;
	}

	const MAX_INTERVAL_POINTS_TO_DISPLAY = 50000;
	return minutesToDisplay / step < MAX_INTERVAL_POINTS_TO_DISPLAY;
}

var GraphType = {
	NO_DATA : "no data",
	BAR: "bar",
	ONE_VALUE: "one value",
	ONE_LINE: "one line",
	MULITPLE_LINES: "mulitple lines"
}
//# sourceMappingURL=main.js.map
