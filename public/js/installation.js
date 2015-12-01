/**
 * Created by Marek on 14.11.2015.
 */
$(document).ready(function() {
	$("select[name='database_type']").change(function() {
		var database_type = $(this).val();
		$("tr[data-databases]").hide();
		$("tr[data-databases~='"+database_type+"']").show();
	});

	$("select[name='database_type']").trigger('change');

	$("select#timezone").select2();
});
//# sourceMappingURL=installation.js.map
