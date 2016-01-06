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