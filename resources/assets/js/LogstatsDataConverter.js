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


