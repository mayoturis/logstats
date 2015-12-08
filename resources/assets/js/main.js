$(document).ready(function() {
	$('div#daterange').daterangepicker({
		"timePicker": true,
		"timePicker24Hour": true,
		"startDate": moment().subtract(1, 'hour'),
		"endDate": moment(),
		ranges: {
			'Today': [moment().startOf('day'), moment()],
			'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
			'Last 20 Minutes': [moment().subtract(20, 'minutes'), moment()],
			'Last Hour': [moment().subtract(1, 'hours'), moment()],
			'Last 7 Days': [moment().subtract(7, 'days'), moment()],
			'Last 14 Days': [moment().subtract(14, 'days'), moment()],
			'Last 30 Days': [moment().subtract(30, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment()]
		}
	}, function(start,end,label) {
		$('input[name=from]').val(start.unix());
		$('input[name=to]').val(end.unix());
		set_daterange(start, end);
	});

	set_daterange(moment().subtract(1, 'hour'), moment());
});

function set_daterange(start, end) {
	label = start.format('DD MMMM YYYY, HH:mm:ss');
	label += ' - ' + end.format('DD MMMM YYYY, HH:mm:ss');
	$('div#daterange label').html(label);
}