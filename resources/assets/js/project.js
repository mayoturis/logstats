$(document).ready(function() {
	$(".delete-project").click(function() {
		var projectName = $(this).attr('data-project-name');
		if (confirm("Do you really want to delete project: " + projectName + "? All records will be lost")) {
			$(this).parent('form').submit();
		}
	});
});

