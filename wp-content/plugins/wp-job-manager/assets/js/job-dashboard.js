jQuery(document).ready(function($) {

	$('.job-dashboard-action-delete').click(function() {
		var answer = confirm( job_manager_job_dashboard.i18n_confirm_delete );

		if (answer)
			return true;

		return false;
	});

});