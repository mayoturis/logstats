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


	$(".nav-tabs li").click(function() {
		$(".nav-tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab-div").hide();
		var id = $(this).attr('data-id');
		$("#"+id).show();
	});

	$(".submitable-link").click(function() {
		var formId = $(this).attr('target-form-id');
		if (formId) {
			$('#'+formId).submit();
		} else {
			$(this).closest('form').submit();
		}
	})

});


timezoneJS.timezone.zoneFileBasePath = "public/libraries/flot/tz";
timezoneJS.timezone.defaultZoneFile = [];
timezoneJS.timezone.init({async: false});
