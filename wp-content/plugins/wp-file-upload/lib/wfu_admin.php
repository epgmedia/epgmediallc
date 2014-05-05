<?php

function wordpress_file_upload_add_admin_pages() {
	add_options_page('Wordpress File Upload', 'Wordpress File Upload', 10, 'wordpress_file_upload', 'wordpress_file_upload_manage_dashboard');
}

function wordpress_file_upload_install() {
	global $wpdb;
	global $wfu_tb_log_version;
	global $wfu_tb_userdata_version;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$table_name1 = $wpdb->prefix . "wfu_log";
	$installed_ver = get_option( "wordpress_file_upload_table_log_version" );
	if( $installed_ver != $wfu_tb_log_version ) {
		$sql = "CREATE TABLE " . $table_name1 . " ( 
			idlog mediumint(9) NOT NULL AUTO_INCREMENT,
			userid mediumint(9) NOT NULL,
			uploaduserid mediumint(9) NOT NULL,
			filepath TEXT NOT NULL,
			filehash VARCHAR(100) NOT NULL,
			filesize bigint NOT NULL,
			uploadid VARCHAR(20) NOT NULL,
			pageid mediumint(9),
			sid VARCHAR(10),
			date_from DATETIME,
			date_to DATETIME,
			action VARCHAR(20) NOT NULL,
			linkedto mediumint(9),
			PRIMARY KEY  (idlog))
			DEFAULT CHARACTER SET = utf8
			DEFAULT COLLATE = utf8_general_ci;";
		dbDelta($sql);
		update_option("wordpress_file_upload_table_log_version", $wfu_tb_log_version);
	}

	$table_name2 = $wpdb->prefix . "wfu_userdata";
	$installed_ver = get_option( "wordpress_file_upload_table_userdata_version" );
	if( $installed_ver != $wfu_tb_userdata_version ) {
		$sql = "CREATE TABLE " . $table_name2 . " ( 
			iduserdata mediumint(9) NOT NULL AUTO_INCREMENT,
			uploadid VARCHAR(20) NOT NULL,
			property VARCHAR(100) NOT NULL,
			propkey mediumint(9) NOT NULL,
			propvalue TEXT,
			date_from DATETIME,
			date_to DATETIME,
			PRIMARY KEY  (iduserdata))
			DEFAULT CHARACTER SET = utf8
			DEFAULT COLLATE = utf8_general_ci;";
		dbDelta($sql);
		update_option("wordpress_file_upload_table_userdata_version", $wfu_tb_userdata_version);
	}
}

function wordpress_file_upload_update_db_check() {
	global $wfu_tb_log_version;
	global $wfu_tb_userdata_version;
	update_option("wordpress_file_upload_table_log_version", "0");
	update_option("wordpress_file_upload_table_userdata_version", "0");
	if ( get_option('wordpress_file_upload_table_log_version') != $wfu_tb_log_version || get_option('wordpress_file_upload_table_userdata_version') != $wfu_tb_userdata_version ) {
		wordpress_file_upload_install();
	}
}

// This is the callback function that generates dashboard page content
function wordpress_file_upload_manage_dashboard() {
	global $wpdb;
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$action = (!empty($_POST['action']) ? $_POST['action'] : (!empty($_GET['action']) ? $_GET['action'] : ''));
	$dir = (!empty($_POST['dir']) ? $_POST['dir'] : (!empty($_GET['dir']) ? $_GET['dir'] : ''));
	$file = (!empty($_POST['file']) ? $_POST['file'] : (!empty($_GET['file']) ? $_GET['file'] : ''));

	if ( $action == 'edit_settings' ) {
		wfu_update_settings();
		$echo_str = wfu_manage_settings();
	}
	elseif ( $action == 'shortcode_composer' ) {
		$echo_str = wfu_shortcode_composer();
	}
	elseif ( $action == 'file_browser' ) {
		$echo_str = wfu_browse_files($dir);
	}
	elseif ( $action == 'view_log' ) {
		$echo_str = wfu_view_log();
	}
	elseif ( $action == 'rename_file' && $file != "" ) {
		$echo_str = wfu_rename_file_prompt($file, 'file', false);
	}
	elseif ( $action == 'rename_dir' && $file != "" ) {
		$echo_str = wfu_rename_file_prompt($file, 'dir', false);
	}
	elseif ( $action == 'renamefile' && $file != "" ) {
		if ( wfu_rename_file($file, 'file') ) $echo_str = wfu_browse_files($dir);
		else $echo_str = wfu_rename_file_prompt($file, 'file', true);
	}
	elseif ( $action == 'renamedir' && $file != "" ) {
		if ( wfu_rename_file($file, 'dir') ) $echo_str = wfu_browse_files($dir);
		else $echo_str = wfu_rename_file_prompt($file, 'dir', true);
	}
	elseif ( $action == 'delete_file' && $file != "" ) {
		$echo_str = wfu_delete_file_prompt($file, 'file');
	}
	elseif ( $action == 'delete_dir' && $file != "" ) {
		$echo_str = wfu_delete_file_prompt($file, 'dir');
	}
	elseif ( $action == 'deletefile' && $file != "" ) {
		wfu_delete_file($file, 'file');
		$echo_str = wfu_browse_files($dir);		
	}
	elseif ( $action == 'deletedir' && $file != "" ) {
		wfu_delete_file($file, 'dir');
		$echo_str = wfu_browse_files($dir);		
	}
	elseif ( $action == 'create_dir' ) {
		$echo_str = wfu_create_dir_prompt($dir, false);
	}
	elseif ( $action == 'createdir' ) {
		if ( wfu_create_dir($dir) ) $echo_str = wfu_browse_files($dir);
		else $echo_str = wfu_create_dir_prompt($dir, true);
	}
	elseif ( $action == 'file_details' && $file != "" ) {
		$echo_str = wfu_file_details($file, false);
	}
	elseif ( $action == 'edit_filedetails' && $file != "" ) {
		wfu_edit_filedetails($file);
		$echo_str = wfu_file_details($file, false);
	}
	elseif ( $action == 'sync_db' ) {
		$affected_items = wfu_sync_database();
		$echo_str = wfu_manage_settings('Database updated. '.$affected_items.' items where affected.');
	}
	else {
		$echo_str = wfu_manage_settings();		
	}

	echo $echo_str;
}

function wfu_manage_settings($message = '') {
	if ( !current_user_can( 'manage_options' ) ) return wfu_shortcode_composer();

	global $wpdb;
	$siteurl = site_url();
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	
	$echo_str = '<div class="wfu_wrap">';
	$echo_str .= "\n\t".'<h2>Wordpress File Upload Control Panel</h2>';
	if ( $message != '' ) {
		$echo_str .= "\n\t".'<div class="updated">';
		$echo_str .= "\n\t\t".'<p>'.$message.'</p>';
		$echo_str .= "\n\t".'</div>';
	}
	$echo_str .= "\n\t".'<div style="margin-top:10px;">';
	if ( current_user_can( 'manage_options' ) ) $echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=file_browser" class="button" title="File browser">File Browser</a>';
	$echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=shortcode_composer" class="button" title="Shortcode composer">Shortcode Composer</a>';
	if ( current_user_can( 'manage_options' ) ) $echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=view_log" class="button" title="View log">View Log</a>';
	if ( current_user_can( 'manage_options' ) ) $echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=sync_db" class="button" title="Update database to reflect current status of files">Sync Database</a>';
	$echo_str .= "\n\t\t".'<h3 style="margin-bottom: 10px; margin-top: 40px;">Settings</h3>';
	$echo_str .= "\n\t\t".'<form enctype="multipart/form-data" name="editsettings" id="editsettings" method="post" action="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=edit_settings" class="validate">';
	$echo_str .= "\n\t\t\t".'<input type="hidden" name="action" value="edit_settings">';
	$echo_str .= "\n\t\t\t".'<table class="form-table">';
	$echo_str .= "\n\t\t\t\t".'<tbody>';
	$echo_str .= "\n\t\t\t\t\t".'<tr class="form-field">';
	$echo_str .= "\n\t\t\t\t\t\t".'<th scope="row">';
	$echo_str .= "\n\t\t\t\t\t\t\t".'<label for="wfu_hashfiles">Hash Files</label>';
	$echo_str .= "\n\t\t\t\t\t\t".'</th>';
	$echo_str .= "\n\t\t\t\t\t\t".'<td>';
	$echo_str .= "\n\t\t\t\t\t\t\t".'<input name="wfu_hashfiles" id="wfu_hashfiles" type="checkbox"'.($plugin_options['hashfiles'] == '1' ? ' checked="checked"' : '' ).' style="width:auto;" /> Enables better control of uploaded files, but slows down performance when uploaded files are larger than 100MBytes';
	$echo_str .= "\n\t\t\t\t\t\t\t".'<p style="cursor: text; font-size:9px; padding: 0px; margin: 0px; width: 95%; color: #AAAAAA;">Current value: <strong>'.($plugin_options['hashfiles'] == '1' ? 'Yes' : 'No' ).'</strong></p>';
	$echo_str .= "\n\t\t\t\t\t\t".'</td>';
	$echo_str .= "\n\t\t\t\t\t".'</tr>';
	$echo_str .= "\n\t\t\t\t\t".'<tr class="form-field">';
	$echo_str .= "\n\t\t\t\t\t\t".'<th scope="row">';
	$echo_str .= "\n\t\t\t\t\t\t\t".'<label for="wfu_basedir">Base Directory</label>';
	$echo_str .= "\n\t\t\t\t\t\t".'</th>';
	$echo_str .= "\n\t\t\t\t\t\t".'<td>';
	$echo_str .= "\n\t\t\t\t\t\t\t".'<input name="wfu_basedir" id="wfu_basedir" type="text" value="'.$plugin_options['basedir'].'" />';
	$echo_str .= "\n\t\t\t\t\t\t\t".'<p style="cursor: text; font-size:9px; padding: 0px; margin: 0px; width: 95%; color: #AAAAAA;">Current value: <strong>'.$plugin_options['basedir'].'</strong></p>';
	$echo_str .= "\n\t\t\t\t\t\t".'</td>';
	$echo_str .= "\n\t\t\t\t\t".'</tr>';
	$echo_str .= "\n\t\t\t\t".'</tbody>';
	$echo_str .= "\n\t\t\t".'</table>';
	$echo_str .= "\n\t\t\t".'<p class="submit">';
	$echo_str .= "\n\t\t\t\t".'<input type="submit" class="button-primary" name="submit" value="Update" />';
	$echo_str .= "\n\t\t\t".'</p>';
	$echo_str .= "\n\t\t".'</form>';
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n".'</div>';
	
	echo $echo_str;
}

function wfu_update_settings() {
	if ( !current_user_can( 'manage_options' ) ) return;
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	$new_plugin_options = array();

//	$enabled = ( isset($_POST['wfu_enabled']) ? ( $_POST['wfu_enabled'] == "on" ? 1 : 0 ) : 0 ); 
	$hashfiles = ( isset($_POST['wfu_hashfiles']) ? ( $_POST['wfu_hashfiles'] == "on" ? 1 : 0 ) : 0 ); 
	if ( isset($_POST['wfu_basedir']) && isset($_POST['submit']) ) {
		if ( $_POST['submit'] == "Update" ) {
			$new_plugin_options['version'] = '1.0';
			$new_plugin_options['shortcode'] = $plugin_options['shortcode'];
			$new_plugin_options['hashfiles'] = $hashfiles;
			$new_plugin_options['basedir'] = $_POST['wfu_basedir'];
			$encoded_options = wfu_encode_plugin_options($new_plugin_options);
			update_option( "wordpress_file_upload_options", $encoded_options );
			if ( $new_plugin_options['hashfiles'] == '1' && $plugin_options['hashfiles'] != '1' )
				wfu_reassign_hashes();
		}
	}

	return true;
}

?>
