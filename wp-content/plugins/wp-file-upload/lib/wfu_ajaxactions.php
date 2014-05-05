<?php


function wfu_ajax_action_send_email_notification() {
	$user = wp_get_current_user();
	if ( 0 == $user->ID ) $is_admin = false;
	else $is_admin = current_user_can('manage_options');

	$arr = wfu_get_params_fields_from_index($_POST['params_index']);
	//check referer using server sessions to avoid CSRF attacks
	if ( $_SESSION["wfu_token_".$arr['shortcode_id']] != $_POST['session_token'] ) die();
	if ( $user->user_login != $arr['user_login'] ) die();

	$params_str = get_option('wfu_params_'.$arr['unique_id']);
	$params = wfu_decode_array_from_string($params_str);

	/* initialize return array */
	$params_output_array["version"] = "full";
	$params_output_array["general"]['shortcode_id'] = $params["uploadid"];
	$params_output_array["general"]['unique_id'] = ( isset($_POST['unique_id']) ? $_POST['unique_id'] : "" );
	$params_output_array["general"]['state'] = 0;
	$params_output_array["general"]['files_count'] = 0;
	$params_output_array["general"]['update_wpfilebase'] = "";
	$params_output_array["general"]['redirect_link'] = "";
	$params_output_array["general"]['upload_finish_time'] = "";
	$params_output_array["general"]['message'] = "";
	$params_output_array["general"]['message_type'] = "";
	$params_output_array["general"]['admin_messages']['wpfilebase'] = "";
	$params_output_array["general"]['admin_messages']['notify'] = "";
	$params_output_array["general"]['admin_messages']['redirect'] = "";
	$params_output_array["general"]['admin_messages']['other'] = "";
	$params_output_array["general"]['errors']['wpfilebase'] = "";
	$params_output_array["general"]['errors']['notify'] = "";
	$params_output_array["general"]['errors']['redirect'] = "";
	$params_output_array["general"]['color'] = "black";
	$params_output_array["general"]['bgcolor'] = "#F5F5F5";
	$params_output_array["general"]['borcolor'] = "#D3D3D3";
	$params_output_array["general"]['notify_only_filename_list'] = "";
	$params_output_array["general"]['notify_target_path_list'] = "";
	$params_output_array["general"]['notify_attachment_list'] = "";
	$params_output_array["general"]['fail_message'] = WFU_ERROR_UNKNOWN;

	// prepare user data 
	$userdata_fields = $params["userdata_fields"]; 
	foreach ( $userdata_fields as $userdata_key => $userdata_field ) 
		$userdata_fields[$userdata_key]["value"] = ( isset($_POST['userdata_'.$userdata_key]) ? $_POST['userdata_'.$userdata_key] : "" );

	$send_error = wfu_send_notification_email($user, $_POST['only_filename_list'], $_POST['target_path_list'], $_POST['attachment_list'], $userdata_fields, $params);

	/* suppress any errors if user is not admin */
	if ( !$is_admin ) $send_error = "";

	if ( $send_error != "" ) {
		$params_output_array["general"]['admin_messages']['notify'] = $send_error;
		$params_output_array["general"]['errors']['notify'] = "error";
	}

	/* construct safe output */
	$sout = "0;".WFU_DEFAULTMESSAGECOLORS.";0";

	die("wfu_fileupload_success:".$sout.":".wfu_encode_array_to_string($params_output_array)); 
}

function wfu_ajax_action_callback() {
	$user = wp_get_current_user();
	$arr = wfu_get_params_fields_from_index($_POST['params_index']);
	//check referer using server sessions to avoid CSRF attacks
	if ( $_SESSION["wfu_token_".$arr['shortcode_id']] != $_POST['session_token'] ) {
		echo "Session failed!<br/><br/>Session Data:<br/>";
		print_r($_SESSION);
		echo "<br/><br/>Post Data:<br/>";
		print_r($_POST);
		die();
	}

	if ( $user->user_login != $arr['user_login'] ) {
		echo "User failed!<br/><br/>User Data:<br/>";
		print_r($user);
		echo "<br/><br/>Post Data:<br/>";
		print_r($_POST);
		echo "<br/><br/>Params Data:<br/>";
		print_r($arr);
		die();
	}

	$params_str = get_option('wfu_params_'.$arr['unique_id']);
	$params = wfu_decode_array_from_string($params_str);

	$params['subdir_selection_index'] = $_POST['subdir_sel_index'];
	$_SESSION['wfu_check_refresh_'.$params["uploadid"]] = 'do not process';

	$wfu_process_file_array = wfu_process_files($params, 'ajax');
	// extract safe_output from wfu_process_file_array and pass it as separate part of the response text
	$safe_output = $wfu_process_file_array["general"]['safe_output'];
	unset($wfu_process_file_array["general"]['safe_output']);
	die("wfu_fileupload_success:".$safe_output.":".wfu_encode_array_to_string($wfu_process_file_array)); 
}

function wfu_ajax_action_save_shortcode() {
	if ( !isset($_POST['shortcode']) ) die();
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));

	$new_plugin_options['version'] = '1.0';
	$new_plugin_options['shortcode'] = wfu_plugin_decode_string($_POST['shortcode']);
	$new_plugin_options['basedir'] = $plugin_options['basedir'];
	$encoded_options = wfu_encode_plugin_options($new_plugin_options);
	update_option( "wordpress_file_upload_options", $encoded_options );

	die("save_shortcode_success"); 
}

?>
