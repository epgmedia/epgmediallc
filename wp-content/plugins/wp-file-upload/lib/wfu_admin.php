<?php

function wordpress_file_upload_add_admin_pages() {
	add_options_page('Wordpress File Upload', 'Wordpress File Upload', 10, 'wordpress_file_upload', 'wordpress_file_upload_manage_dashboard');
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
	else {
		$echo_str = wfu_manage_settings();		
	}

	echo $echo_str;
}

function wfu_manage_settings() {
	if ( !current_user_can( 'manage_options' ) ) return wfu_shortcode_composer();

	global $wpdb;
	$siteurl = site_url();
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	
	$echo_str = '<div class="wfu_wrap">';
	$echo_str .= "\n\t".'<h2>Wordpress File Upload Control Panel</h2>';
	$echo_str .= "\n\t".'<div style="margin-top:10px;">';
	if ( current_user_can( 'manage_options' ) ) $echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=file_browser" class="button" title="File browser">File Browser</a>';
	$echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=shortcode_composer" class="button" title="Shortcode composer">Shortcode Composer</a>';
	$echo_str .= "\n\t\t".'<h3 style="margin-bottom: 10px; margin-top: 40px;">Settings</h3>';
	$echo_str .= "\n\t\t".'<form enctype="multipart/form-data" name="editsettings" id="editsettings" method="post" action="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=edit_settings" class="validate">';
	$echo_str .= "\n\t\t\t".'<input type="hidden" name="action" value="edit_settings">';
	$echo_str .= "\n\t\t\t".'<table class="form-table">';
	$echo_str .= "\n\t\t\t\t".'<tbody>';
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

function wfu_shortcode_composer() {
	global $wpdb;
	global $wp_roles;
	$siteurl = site_url();
 
	$components = wfu_component_definitions();

	$cats = wfu_category_definitions();
	$defs = wfu_attribute_definitions();

	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	$shortcode_attrs = wfu_shortcode_string_to_array($plugin_options['shortcode']);
	foreach ( $defs as $key => $def ) {
		$defs[$key]['default'] = $def['value'];
		if ( array_key_exists($def['attribute'], $shortcode_attrs) ) {
			$defs[$key]['value'] = $shortcode_attrs[$def['attribute']];
		}
	}

	// index $components
	$components_indexed = array();
	foreach ( $components as $component ) $components_indexed[$component['id']] = $component;
	// index dependiencies
	$governors = array();

	$echo_str = '<div id="wfu_wrapper" class="wrap">';
	if ( current_user_can( 'manage_options' ) ) $echo_str .= "\n\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=manage_settings" class="button" title="go back">Go to Settings</a>';
	$echo_str .= "\n\t".'<h2>Wordpress File Upload Shortcode Composer</h2>';
	$echo_str .= "\n\t".'<div style="margin-top:10px;">';
	$echo_str .= "\n\t\t".'<div class="wfu_shortcode_container">';
	$echo_str .= "\n\t\t\t".'<span><strong>Generated Shortcode</strong></span>';
	$echo_str .= "\n\t\t\t".'<span id="wfu_save_label" class="wfu_save_label">saved</span>';
	$echo_str .= "\n\t\t\t".'<textarea id="wfu_shortcode" class="wfu_shortcode" rows="5">[wordpress_file_upload]</textarea>';
	$echo_str .= "\n\t\t\t".'<div id="wfu_attribute_defaults" style="display:none;">';
	foreach ( $defs as $def )
		$echo_str .= "\n\t\t\t\t".'<input id="wfu_attribute_default_'.$def['attribute'].'" type="hidden" value="'.$def['default'].'" />';
	$echo_str .= "\n\t\t\t".'</div>';
	$echo_str .= "\n\t\t\t".'<div id="wfu_attribute_values" style="display:none;">';
	foreach ( $defs as $def )
		$echo_str .= "\n\t\t\t\t".'<input id="wfu_attribute_value_'.$def['attribute'].'" type="hidden" value="'.$def['value'].'" />';
	$echo_str .= "\n\t\t\t".'</div>';
	$echo_str .= "\n\t\t".'</div>';
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n\t".'<h3 id="wfu_tab_container" class="nav-tab-wrapper">';
	$is_first = true;
	foreach ( $cats as $key => $cat ) {
		$echo_str .= "\n\t\t".'<a id="wfu_tab_'.$key.'" class="nav-tab'.( $is_first ? ' nav-tab-active' : '' ).'" href="javascript: wfu_admin_activate_tab(\''.$key.'\');">'.$cat.'</a>';
		$is_first = false;
	}
	$echo_str .= "\n\t".'</h3>';

	$prevcat = "";
	$prevsubcat = "";
	$is_first = true;
	$block_open = false;
	$subblock_open = false;
	foreach ( $defs as $def ) {
		$attr = $def['attribute'];
		$subblock_active = false;
		//detect if the dependencies of this attribute will be disabled or not
		if ( ( $def['type'] == "onoff" && $def['value'] == "true" ) ||
			( $def['type'] == "radio" && in_array("*".$def['value'], $def['listitems']) ) )
			$subblock_active = true;
		// assign dependencies if exist
		if ( $def['dependencies'] != null )
			foreach ( $def['dependencies'] as $dependency ) {
				if ( substr($dependency, 0, 1) == "!" ) //invert state for this dependency if an exclamation mark is defined
					$governors[substr($dependency, 1)] = array( 'attribute' => $attr, 'active' => !$subblock_active, 'inv' => '_inv' );
				else
					$governors[$dependency] = array( 'attribute' => $attr, 'active' => $subblock_active, 'inv' => '' );
			}
		//check if this attribute depends on other
		if ( $governors[$attr] != "" ) $governor = $governors[$attr];
		else $governor = array( 'attribute' => "independent", 'active' => true, 'inv' => '' );

		//close previous blocks
		if ( $def['parent'] == "" ) {
			if ( $subblock_open ) {
				$echo_str .= "\n\t\t\t\t\t\t\t".'</tbody>';
				$echo_str .= "\n\t\t\t\t\t\t".'</table>';
				$subblock_open = false;
			}
			if ( $block_open ) {
				$echo_str .= "\n\t\t\t\t\t".'</div></td>';
				$echo_str .= "\n\t\t\t\t".'</tr>';
				$block_open = false;
			}
		}
		//check if new category must be generated
		if ( $def['category'] != $prevcat ) {
			if ( $prevcat != "" ) {
				$echo_str .= "\n\t\t\t".'</tbody>';
				$echo_str .= "\n\t\t".'</table>';
				$echo_str .= "\n\t".'</div>';
			}
			$prevcat = $def['category'];
			$prevsubcat = "";
			$echo_str .= "\n\t".'<div id="wfu_container_'.$prevcat.'" class="wfu_container"'.( $is_first ? '' : ' style="display:none;"' ).'">';
			$echo_str .= "\n\t\t".'<table class="form-table wfu_main_table">';
			$echo_str .= "\n\t\t\t".'<thead><tr><th></th><td></td><td></td></tr></thead>';
			$echo_str .= "\n\t\t\t".'<tbody>';
			$is_first = false;
		}
		//check if new sub-category must be generated
		if ( $def['subcategory'] != $prevsubcat ) {
			$prevsubcat = $def['subcategory'];
			$echo_str .= "\n\t\t\t\t".'<tr class="form-field wfu_subcategory">';
			$echo_str .= "\n\t\t\t\t\t".'<th scope="row" colspan="3">';
			$echo_str .= "\n\t\t\t\t\t\t".'<h3 style="margin-bottom: 10px; margin-top: 10px;">'.$prevsubcat.'</h3>';
			$echo_str .= "\n\t\t\t\t\t".'</th>';
			$echo_str .= "\n\t\t\t\t".'</tr>';
		}
		//draw attribute element
		if ( $def['parent'] == "" ) {
			$dlp = "\n\t\t\t\t";
		}
		else {
			if ( !$subblock_open ) {
				$echo_str .= "\n\t\t\t\t\t\t".'<div class="wfu_shadow wfu_shadow_'.$def['parent'].$governor['inv'].'" style="display:'.( $governor['active'] ? 'none' : 'block' ).';"></div>';
				$echo_str .= "\n\t\t\t\t\t\t".'<table class="form-table wfu_inner_table" style="margin:0;">';
				$echo_str .= "\n\t\t\t\t\t\t\t".'<tbody>';
			}
			$dlp = "\n\t\t\t\t\t\t\t\t";
		}
		$echo_str .= $dlp.'<tr class="form-field">';
		$echo_str .= $dlp."\t".'<th scope="row"><div class="wfu_td_div">';
		if ( $def['parent'] == "" ) $echo_str .= $dlp."\t\t".'<div class="wfu_shadow wfu_shadow_'.$governor['attribute'].$governor['inv'].'" style="display:'.( $governor['active'] ? 'none' : 'block' ).';"></div>';
		$echo_str .= $dlp."\t\t".'<div class="wfu_restore_container" title="Double-click to restore defaults setting"><img src="'.WFU_IMAGE_ADMIN_RESTOREDEFAULT.'" ondblclick="wfu_apply_value(\''.$attr.'\', \''.$def['type'].'\', \''.$def['default'].'\');" ></div>';
		$echo_str .= $dlp."\t\t".'<label for="wfu_attribute_'.$attr.'">'.$def['name'].'</label>';
		$echo_str .= $dlp."\t\t".'<div class="wfu_help_container" title="'.$def['help'].'"><img src="'.WFU_IMAGE_ADMIN_HELP.'" ></div>';
		$echo_str .= $dlp."\t".'</div></th>';
		$echo_str .= $dlp."\t".'<td style="vertical-align:top;"><div class="wfu_td_div">';
		if ( $def['parent'] == "" ) $echo_str .= $dlp."\t\t".'<div class="wfu_shadow wfu_shadow_'.$governor['attribute'].$governor['inv'].'" style="display:'.( $governor['active'] ? 'none' : 'block' ).';"></div>';
		if ( $def['type'] == "onoff" ) {
			$echo_str .= $dlp."\t\t".'<div id="wfu_attribute_'.$attr.'" class="wfu_onoff_container_'.( $def['value'] == "true" ? "on" : "off" ).'" onclick="wfu_admin_onoff_clicked(\''.$attr.'\');">';
			$echo_str .= $dlp."\t\t\t".'<div class="wfu_onoff_slider"></div>';
			$echo_str .= $dlp."\t\t\t".'<span class="wfu_onoff_text">ON</span>';
			$echo_str .= $dlp."\t\t\t".'<span class="wfu_onoff_text">OFF</span>';
			$echo_str .= $dlp."\t\t".'</div>';
		}
		elseif ( $def['type'] == "text" ) {
			$echo_str .= $dlp."\t\t".'<input id="wfu_attribute_'.$attr.'" type="text" name="wfu_text_elements" value="'.$def['value'].'" />';
			if ( $def['variables'] != null ) $echo_str .= wfu_insert_variables($def['variables'], 'wfu_variable wfu_variable_'.$attr);
		}
		elseif ( $def['type'] == "placements" ) {
			$components_used = array();
			foreach ( $components as $component ) $components_used[$component['id']] = false;
			$centered_content = '<div style="display:table; width:100%; height:100%;"><div style="display:table-cell; text-align:center; vertical-align:middle;">XXX</div></div>';
			$echo_str .= $dlp."\t\t".'<div class="wfu_placements_wrapper">';
			$echo_str .= $dlp."\t\t\t".'<div id="wfu_placements_container" class="wfu_placements_container">';
			$itemplaces = explode("/", $def['value']);
			foreach ( $itemplaces as $section ) {
				$echo_str .= $dlp."\t\t\t\t".'<div class="wfu_component_separator_hor"></div>';
				$echo_str .= $dlp."\t\t\t\t".'<div class="wfu_component_separator_ver"></div>';
				$items_in_section = explode("+", trim($section));
				$section_array = array( );
				foreach ( $items_in_section as $item_in_section ) {
					if ( key_exists($item_in_section, $components_indexed) ) {
						$components_used[$item_in_section] = true;
						$echo_str .= $dlp."\t\t\t\t".'<div id="wfu_component_box_'.$item_in_section.'" class="wfu_component_box" draggable="true">'.str_replace("XXX", $components_indexed[$item_in_section]['name'], $centered_content).'</div>';
						$echo_str .= $dlp."\t\t\t\t".'<div class="wfu_component_separator_ver"></div>';
					}
				}
			}
			$echo_str .= $dlp."\t\t\t\t".'<div class="wfu_component_separator_hor"></div>';
			$echo_str .= $dlp."\t\t\t\t".'<div id="wfu_component_bar_hor" class="wfu_component_bar_hor"></div>';
			$echo_str .= $dlp."\t\t\t\t".'<div id="wfu_component_bar_ver" class="wfu_component_bar_ver"></div>';
			$echo_str .= $dlp."\t\t\t".'</div>';
			$echo_str .= $dlp."\t\t\t".'<div id="wfu_componentlist_container" class="wfu_componentlist_container">';
			$echo_str .= $dlp."\t\t\t\t".'<div id="wfu_componentlist_dragdrop" class="wfu_componentlist_dragdrop" style="display:none;"></div>';
			$ii = 1;
			foreach ( $components as $component ) {
				$echo_str .= $dlp."\t\t\t\t".'<div id="wfu_component_box_container_'.$component['id'].'" class="wfu_component_box_container">';
				$echo_str .= $dlp."\t\t\t\t\t".'<div class="wfu_component_box_base">'.str_replace("XXX", $component['name'], $centered_content).'</div>';
				if ( !$components_used[$component['id']] )
					$echo_str .= $dlp."\t\t\t\t\t".'<div id="wfu_component_box_'.$component['id'].'" class="wfu_component_box wfu_inbase" draggable="true">'.str_replace("XXX", $component['name'], $centered_content).'</div>';
				$echo_str .= $dlp."\t\t\t\t".'</div>'.( ($ii++) % 3 == 0 ? '<br />' : '' );
			}
			$echo_str .= $dlp."\t\t\t".'</div>';
			$echo_str .= $dlp."\t\t".'</div>';
		}
		elseif ( $def['type'] == "ltext" ) {
			$echo_str .= $dlp."\t\t".'<input id="wfu_attribute_'.$attr.'" type="text" name="wfu_text_elements" class="wfu_long_text" value="'.$def['value'].'" />';
			if ( $def['variables'] != null ) $echo_str .= wfu_insert_variables($def['variables'], 'wfu_variable wfu_variable_'.$attr);
		}
		elseif ( $def['type'] == "integer" ) {
			$echo_str .= $dlp."\t\t".'<input id="wfu_attribute_'.$attr.'" type="number" name="wfu_text_elements" class="wfu_short_text" min="1" value="'.$def['value'].'" />';
		}
		elseif ( $def['type'] == "float" ) {
			$echo_str .= $dlp."\t\t".'<input id="wfu_attribute_'.$attr.'" type="number" name="wfu_text_elements" class="wfu_short_text" step="any" min="0" value="'.$def['value'].'" />';
		}
		elseif ( $def['type'] == "radio" ) {
			$echo_str .= $dlp."\t\t";
			$ii = 0;
			foreach ( $def['listitems'] as $item )
				$echo_str .= '<input name="wfu_radioattribute_'.$attr.'" type="radio" value="'.$item.'" '.( $item == $def['value'] || $item == "*".$def['value'] ? 'checked="checked" ' : '' ).'style="width:auto; margin:0px 2px 0px '.( ($ii++) == 0 ? '0px' : '8px' ).';" onchange="wfu_admin_radio_clicked(\''.$attr.'\');" />'.( $item[0] == "*" ? substr($item, 1) : $item );
//			$echo_str .= '<input type="button" class="button" value="empty" style="width:auto; margin:-2px 0px 0px 8px;" />';
		}
		elseif ( $def['type'] == "ptext" ) {
			$parts = explode("/", $def['value']);
			$singular = $parts[0];
			if ( count($parts) < 2 ) $plural = $singular;
			else $plural = $parts[1];
			$echo_str .= $dlp."\t\t".'<span class="wfu_ptext_span">Singular</span><input id="wfu_attribute_s_'.$attr.'" type="text" name="wfu_ptext_elements" value="'.$singular.'" />';
			if ( $def['variables'] != null ) if ( count($def['variables']) > 0 ) $echo_str .= $dlp."\t\t".'<br /><span class="wfu_ptext_span">&nbsp;</span>';
			if ( $def['variables'] != null ) $echo_str .= wfu_insert_variables($def['variables'], 'wfu_variable wfu_variable_s_'.$attr);
			$echo_str .= $dlp."\t\t".'<br /><span class="wfu_ptext_span">Plural</span><input id="wfu_attribute_p_'.$attr.'" type="text" name="wfu_ptext_elements" value="'.$plural.'" />';
			if ( $def['variables'] != null ) if ( count($def['variables']) > 0 ) $echo_str .= $dlp."\t\t".'<br /><span class="wfu_ptext_span">&nbsp;</span>';
			if ( $def['variables'] != null ) $echo_str .= wfu_insert_variables($def['variables'], 'wfu_variable wfu_variable_p_'.$attr);
		}
		elseif ( $def['type'] == "mtext" ) {
			$val = str_replace("%n%", "\n", $def['value']);
			$echo_str .= $dlp."\t\t".'<textarea id="wfu_attribute_'.$attr.'" name="wfu_text_elements" rows="5">'.$val.'</textarea>';
			if ( $def['variables'] != null ) $echo_str .= wfu_insert_variables($def['variables'], 'wfu_variable wfu_variable_'.$attr);
		}
		elseif ( $def['type'] == "rolelist" ) {
			$roles = $wp_roles->get_names();
			$def['value'] = strtolower($def['value']);
			if ( $def['value'] == "all" ) $selected = array("administrator");
			else $selected = explode(",", $def['value']);
			foreach ( $selected as $key => $item ) $selected[$key] = trim($item);
			$echo_str .= $dlp."\t\t".'<select id="wfu_attribute_'.$attr.'" multiple="multiple" size="'.count($roles).'" onchange="wfu_update_rolelist_value(\''.$attr.'\');"'.( strtolower($def['value']) == "all" ? ' disabled="disabled"' : '' ).'>';
			foreach ( $roles as $roleid => $rolename )
				$echo_str .= $dlp."\t\t\t".'<option value="'.$roleid.'"'.( in_array($roleid, $selected) ? ' selected="selected"' : '' ).'>'.$rolename.'</option>';
			$echo_str .= $dlp."\t\t".'</select>';
			$echo_str .= $dlp."\t\t".'<div class="wfu_rolelist_checkall"><input id="wfu_attribute_'.$attr.'_all" type="checkbox" onchange="wfu_update_rolelist_value(\''.$attr.'\');"'.( strtolower($def['value']) == "all" ? ' checked="checked"' : '' ).' /> Select all (including guests)</div>';
		}
		elseif ( $def['type'] == "dimensions" ) {
			$vals_arr = explode(",", $def['value']);
			$vals = array();
			foreach ( $vals_arr as $val_raw ) {
				list($val_id, $val) = explode(":", $val_raw);
				$vals[trim($val_id)] = trim($val);
			}
			$dims = array();
			foreach ( $components as $comp ) {
				if ( $comp['dimensions'] == null ) $dims[$comp['id']] = $comp['name'];
				else foreach ( $comp['dimensions'] as $dimraw ) {
					list($dim_id, $dim_name) = explode("/", $dimraw);
					$dims[$dim_id] = $dim_name;
				}
			}
			foreach ( $dims as $dim_id => $dim_name ) {
				$echo_str .= $dlp."\t\t".'<span style="display:inline-block; width:130px;">'.$dim_name.'</span><input id="wfu_attribute_'.$attr.'_'.$dim_id.'" type="text" name="wfu_dimension_elements_'.$attr.'" class="wfu_short_text" value="'.$vals[$dim_id].'" /><br />';
			}
		}
		elseif ( $def['type'] == "userfields" ) {
			$fields_arr = explode("/", $def['value']);
			$fields = array();
			foreach ( $fields_arr as $field_raw ) {
				$is_req = ( substr($field_raw, 0, 1) == "*" );
				if ( $is_req ) $field_raw = substr($field_raw, 1);
				if ( $field_raw != "" ) array_push($fields, array( "name" => $field_raw, "required" => $is_req ));
			}
			if ( count($fields) == 0 ) array_push($fields, array( "name" => "", "required" => false ));
			$echo_str .= $dlp."\t\t".'<div id="wfu_attribute_'.$attr.'" class="wfu_userdata_container">';
			foreach ( $fields as $field ) {
				$echo_str .= $dlp."\t\t\t".'<div class="wfu_userdata_line">';
				$echo_str .= $dlp."\t\t\t\t".'<input type="text" name="wfu_userfield_elements" value="'.$field['name'].'" />';
				$echo_str .= $dlp."\t\t\t\t".'<div class="wfu_userdata_action" onclick="wfu_userdata_add_field(this);"><img src="'.WFU_IMAGE_ADMIN_USERDATA_ADD.'" ></div>';
				$echo_str .= $dlp."\t\t\t\t".'<div class="wfu_userdata_action wfu_userdata_action_disabled" onclick="wfu_userdata_remove_field(this);"><img src="'.WFU_IMAGE_ADMIN_USERDATA_REMOVE.'" ></div>';
				$echo_str .= $dlp."\t\t\t\t".'<input type="checkbox"'.( $field['required'] ? 'checked="checked"' : '' ).' onchange="wfu_update_userfield_value({target:this});" />';
				$echo_str .= $dlp."\t\t\t\t".'<span>Required</span>';
				$echo_str .= $dlp."\t\t\t".'</div>';
			}
			$echo_str .= $dlp."\t\t".'</div>';
		}
		elseif ( $def['type'] == "color" ) {
			$echo_str .= $dlp."\t\t".'<input id="wfu_attribute_'.$attr.'" type="text" name="wfu_text_elements" class="wfu_color_field" value="'.$def['value'].'" />';
		}
		elseif ( $def['type'] == "color-triplet" ) {
			$triplet = explode(",", $def['value']);
			foreach ( $triplet as $key => $item ) $triplet[$key] = trim($item);
			if ( count($triplet) == 2 ) $triplet = array( $triplet[0], $triplet[1], "#000000");
			elseif ( count($triplet) == 1 ) $triplet = array( $triplet[0], "#FFFFFF", "#000000");
			elseif ( count($triplet) < 3 ) $triplet = array( "#000000", "#FFFFFF", "#000000");
			$echo_str .= $dlp."\t\t".'<div class="wfu_color_container"><label style="display:inline-block; width:120px; margin-top:-16px;">Text Color</label><input id="wfu_attribute_'.$attr.'_color" type="text" class="wfu_color_field" name="wfu_triplecolor_elements" value="'.$triplet[0].'" /></div>';
			$echo_str .= $dlp."\t\t".'<div class="wfu_color_container"><label style="display:inline-block; width:120px; margin-top:-16px;">Background Color</label><input id="wfu_attribute_'.$attr.'_bgcolor" type="text" class="wfu_color_field" name="wfu_triplecolor_elements" value="'.$triplet[1].'" /></div>';
			$echo_str .= $dlp."\t\t".'<div class="wfu_color_container"><label style="display:inline-block; width:120px; margin-top:-16px;">Border Color</label><input id="wfu_attribute_'.$attr.'_borcolor" type="text" class="wfu_color_field" name="wfu_triplecolor_elements" value="'.$triplet[2].'" /></div>';
		}
		else {
			$echo_str .= $dlp."\t\t".'<input id="wfu_attribute_'.$attr.'" type="text" name="wfu_text_elements" value="'.$def['value'].'" />';
			if ( $def['variables'] != null ) $echo_str .= wfu_insert_variables($def['variables'], 'wfu_variable wfu_variable_'.$attr);
		}
		$echo_str .= $dlp."\t".'</div></td>';
		if ( $def['parent'] == "" ) {
			$echo_str .= $dlp."\t".'<td style="position:relative; vertical-align:top; padding:0;"><div class="wfu_td_div">';
			$block_open = false;
		}
		else {
			$echo_str .= $dlp.'</tr>';
			$subblock_open = true;						
		}
	}
	if ( $subblock_open ) {
		$echo_str .= "\n\t\t\t\t\t\t".'</div>';
	}
	if ( $block_open ) {
		$echo_str .= "\n\t\t\t\t\t".'</div></td>';
		$echo_str .= "\n\t\t\t\t".'</tr>';
	}
	$echo_str .= "\n\t\t\t".'</tbody>';
	$echo_str .= "\n\t\t".'</table>';
	$handler = 'function() { wfu_Attach_Admin_Events(); }';
	$echo_str .= "\n\t".'<script type="text/javascript">if(window.addEventListener) { window.addEventListener("load", '.$handler.', false); } else if(window.attachEvent) { window.attachEvent("onload", '.$handler.'); } else { window["onload"] = '.$handler.'; }</script>';
	$echo_str .= "\n".'</div>';
//	$echo_str .= "\n\t".'<div style="margin-top:10px;">';
//	$echo_str .= "\n\t\t".'<label>Final shortcode text</label>';
//	$echo_str .= "\n\t".'</div>';

	echo $echo_str;
}

function wfu_insert_variables($variables, $class) {
	$ret = "";
	foreach ( $variables as $variable )
		if ( $variable == "%userdataXXX%" ) $ret .= $dlp."\t\t".'<select class="'.$class.'" name="wfu_userfield_select" title="'.constant("WFU_VARIABLE_TITLE_".strtoupper(str_replace("%", "", $variable))).'" onchange="wfu_insert_userfield_variable(this);"><option style="display:none;">%userdataXXX%</option></select>';
		elseif ( $variable != "%n%" ) $ret .= $dlp."\t\t".'<span class="'.$class.'" title="'.constant("WFU_VARIABLE_TITLE_".strtoupper(str_replace("%", "", $variable))).'" ondblclick="wfu_insert_variable(this);">'.$variable.'</span>';
	return $ret;
}

function wfu_update_settings() {
	if ( !current_user_can( 'manage_options' ) ) return;
	$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
	$new_plugin_options = array();

//	$enabled = ( isset($_POST['wfu_enabled']) ? ( $_POST['wfu_enabled'] == "on" ? 1 : 0 ) : 0 ); 
	if ( isset($_POST['wfu_basedir']) && isset($_POST['submit']) ) {
		if ( $_POST['submit'] == "Update" ) {
			$new_plugin_options['version'] = '1.0';
			$new_plugin_options['shortcode'] = $plugin_options['shortcode'];
			$new_plugin_options['basedir'] = $_POST['wfu_basedir'];
			$encoded_options = wfu_encode_plugin_options($new_plugin_options);
			update_option( "wordpress_file_upload_options", $encoded_options );
		}
	}

	return true;
}

function wfu_browse_files($basedir) {
	if ( !current_user_can( 'manage_options' ) ) return;
	$siteurl = site_url();
	//first decode basedir
	$basedir = wfu_plugin_decode_string($basedir);
	//then extract sort info from basedir
	$ret = wfu_extract_sortdata_from_path($basedir);
	$basedir = $ret['path'];
	$sort = $ret['sort'];
	if ( $sort == "" ) $sort = 'name';
	if ( substr($sort, 0, 1) == '-' ) $order = SORT_DESC;
	else $order = SORT_ASC;

	//adjust basedir to have a standard format
	if ( $basedir != "" ) {
		if ( substr($basedir, -1) != '/' ) $basedir .= '/';
		if ( !file_exists($basedir) ) $basedir = "";
	}
	//set basedit to default value if empty
	if ( $basedir == "" ) {
		$plugin_options = wfu_decode_plugin_options(get_option( "wordpress_file_upload_options" ));
		$basedir = $plugin_options['basedir'];
		$temp_params = array( 'uploadpath' => $basedir, 'accessmethod' => 'normal', 'ftpinfo' => '', 'useftpdomain' => 'false' );
		$basedir = wfu_upload_plugin_full_path($temp_params);
	}
	//find relative dir
	$reldir = str_replace(ABSPATH, "root/", $basedir);
	//save dir route to an array
	$parts = explode('/', $reldir);
	$route = array();
	$prev = "";
	foreach ( $parts as $part ) {
		$part = trim($part);
		if ( $part != "" ) {
			if ( $part == 'root' && $prev == "" ) $prev = ABSPATH;
			else $prev .= $part.'/';
			array_push($route, array( 'item' => $part, 'path' => $prev ));
		}
	}
	//calculate upper directory
	$updir = substr($basedir, 0, -1);
	$delim_pos = strrpos($updir, '/');
	if ( $delim_pos !== false ) $updir = substr($updir, 0, $delim_pos + 1);

	$echo_str = "\n".'<div class="wrap">';
	$echo_str .= "\n\t".'<div style="margin-top:20px;">';
	$echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=manage_settings" class="button" title="go back">Go to Settings</a>';
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n\t".'<h2 style="margin-bottom: 10px;">File Browser</h2>';
	$echo_str .= "\n\t".'<div>';
	$echo_str .= "\n\t\t".'<span><strong>Location:</strong> </span>';
	foreach ( $route as $item ) {
		$echo_str .= '<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=file_browser&dir='.wfu_plugin_encode_string($item['path']).'">'.$item['item'].'</a>';
		$echo_str .= '<span>/</span>';
	}
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=create_dir&dir='.wfu_plugin_encode_string($basedir.'[['.$sort.']]').'" class="button" title="create folder" style="margin-top:6px">Create folder</a>';
	$echo_str .= "\n\t".'<div style="margin-top:10px;">';
	$echo_str .= "\n\t\t".'<table class="widefat">';
	$echo_str .= "\n\t\t\t".'<thead>';
	$echo_str .= "\n\t\t\t\t".'<tr>';
	$echo_str .= "\n\t\t\t\t\t".'<th scope="col">';
	$enc_dir = wfu_plugin_encode_string($basedir.'[['.( substr($sort, -4) == 'name' ? ( $order == SORT_ASC ? '-name' : 'name' ) : 'name' ).']]');
	$echo_str .= "\n\t\t\t\t\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=file_browser&dir='.$enc_dir.'">Name'.( substr($sort, -4) == 'name' ? ( $order == SORT_ASC ? ' &uarr;' : ' &darr;' ) : '' ).'</a>';
	$echo_str .= "\n\t\t\t\t\t".'</th>';
	$echo_str .= "\n\t\t\t\t\t".'<th scope="col">';
	$enc_dir = wfu_plugin_encode_string($basedir.'[['.( substr($sort, -4) == 'size' ? ( $order == SORT_ASC ? '-size' : 'size' ) : 'size' ).']]');
	$echo_str .= "\n\t\t\t\t\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=file_browser&dir='.$enc_dir.'">Size'.( substr($sort, -4) == 'size' ? ( $order == SORT_ASC ? ' &uarr;' : ' &darr;' ) : '' ).'</a>';
	$echo_str .= "\n\t\t\t\t\t".'</th>';
	$echo_str .= "\n\t\t\t\t\t".'<th scope="col">';
	$enc_dir = wfu_plugin_encode_string($basedir.'[['.( substr($sort, -4) == 'date' ? ( $order == SORT_ASC ? '-date' : 'date' ) : 'date' ).']]');
	$echo_str .= "\n\t\t\t\t\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=file_browser&dir='.$enc_dir.'">Date'.( substr($sort, -4) == 'date' ? ( $order == SORT_ASC ? ' &uarr;' : ' &darr;' ) : '' ).'</a>';
	$echo_str .= "\n\t\t\t\t\t".'</th>';
	$echo_str .= "\n\t\t\t\t".'</tr>';
	$echo_str .= "\n\t\t\t".'</thead>';
	$echo_str .= "\n\t\t\t".'<tbody>';

	$dirlist = array();
	$filelist = array();
	if ( $handle = opendir($basedir) ) {
		$blacklist = array('.', '..');
		while ( false !== ($file = readdir($handle)) )
			if ( !in_array($file, $blacklist) ) {
				$filepath = $basedir.$file;
				$stat = stat($filepath);
				if ( is_dir($filepath) ) {
					array_push($dirlist, array( 'name' => $file, 'fullpath' => $filepath, 'mdate' => $stat['mtime'] ));
				}
				else {
					array_push($filelist, array( 'name' => $file, 'fullpath' => $filepath, 'size' => $stat['size'], 'mdate' => $stat['mtime'] ));
				}
			}
		closedir($handle);
	}
	$dirsort = ( substr($sort, -4) == 'date' ? 'mdate' : substr($sort, -4) );
	$filesort = $dirsort;
	$dirorder = $order;
	if ( $dirsort == 'size' ) { $dirsort = 'name'; $dirorder = SORT_ASC; }
	$dirlist = wfu_array_sort($dirlist, $dirsort, $dirorder);
	$filelist = wfu_array_sort($filelist, $filesort, $order);

	if ( $reldir != "root/" ) {
		$enc_dir = wfu_plugin_encode_string($updir);
		$echo_str .= "\n\t\t\t\t".'<tr onmouseover="for (i in document.getElementsByName(\'wfu_dir_actions\')){document.getElementsByName(\'wfu_dir_actions\').item(i).style.visibility=\'hidden\';}" onmouseout="for (i in document.getElementsByName(\'wfu_dir_actions\')){document.getElementsByName(\'wfu_dir_actions\').item(i).style.visibility=\'hidden\';}">';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;">';
		$echo_str .= "\n\t\t\t\t\t\t".'<a class="row-title" href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=file_browser&dir='.$enc_dir.'" title="go up">..</a>';
		$echo_str .= "\n\t\t\t\t\t".'</td>';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;"> </td>';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;"> </td>';
		$echo_str .= "\n\t\t\t\t".'</tr>';
	}
	$ii = 1;
	foreach ( $dirlist as $dir ) {
		$enc_dir = wfu_plugin_encode_string($dir['fullpath'].'[['.$sort.']]');
		$echo_str .= "\n\t\t\t\t".'<tr onmouseover="for (i in document.getElementsByName(\'wfu_dir_actions\')){document.getElementsByName(\'wfu_dir_actions\').item(i).style.visibility=\'hidden\';} document.getElementById(\'wfu_dir_actions_'.$ii.'\').style.visibility=\'visible\'" onmouseout="for (i in document.getElementsByName(\'wfu_dir_actions\')){document.getElementsByName(\'wfu_dir_actions\').item(i).style.visibility=\'hidden\';}">';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;">';
		$echo_str .= "\n\t\t\t\t\t\t".'<a class="row-title" href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=file_browser&dir='.$enc_dir.'" title="'.$dir['name'].'">'.$dir['name'].'</a>';
		$echo_str .= "\n\t\t\t\t\t\t".'<div id="wfu_dir_actions_'.$ii.'" name="wfu_dir_actions" style="visibility:hidden;">';
		$echo_str .= "\n\t\t\t\t\t\t\t".'<span>';
		$echo_str .= "\n\t\t\t\t\t\t\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=rename_dir&file='.$enc_dir.'" title="Rename this folder">Rename</a>';
		$echo_str .= "\n\t\t\t\t\t\t\t\t".' | ';
		$echo_str .= "\n\t\t\t\t\t\t\t".'</span>';
		$echo_str .= "\n\t\t\t\t\t\t\t".'<span>';
		$echo_str .= "\n\t\t\t\t\t\t\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=delete_dir&file='.$enc_dir.'" title="Delete this folder">Delete</a>';
		$echo_str .= "\n\t\t\t\t\t\t\t".'</span>';
		$echo_str .= "\n\t\t\t\t\t\t".'</div>';
		$echo_str .= "\n\t\t\t\t\t".'</td>';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;"> </td>';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;">'.date("d/m/Y H:i:s", $dir['mdate']).'</td>';
		$echo_str .= "\n\t\t\t\t".'</tr>';
		$ii ++;
	}
	foreach ( $filelist as $file ) {
		$enc_file = wfu_plugin_encode_string($file['fullpath'].'[['.$sort.']]');
		$echo_str .= "\n\t\t\t\t".'<tr onmouseover="for (i in document.getElementsByName(\'wfu_file_actions\')){document.getElementsByName(\'wfu_file_actions\').item(i).style.visibility=\'hidden\';} document.getElementById(\'wfu_file_actions_'.$ii.'\').style.visibility=\'visible\'" onmouseout="for (i in document.getElementsByName(\'wfu_file_actions\')){document.getElementsByName(\'wfu_file_actions\').item(i).style.visibility=\'hidden\';}">';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;">';
		$echo_str .= "\n\t\t\t\t\t\t".'<span>'.$file['name'].'</span>';
		$echo_str .= "\n\t\t\t\t\t\t".'<div id="wfu_file_actions_'.$ii.'" name="wfu_file_actions" style="visibility:hidden;">';
		$echo_str .= "\n\t\t\t\t\t\t\t".'<span>';
		$echo_str .= "\n\t\t\t\t\t\t\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=rename_file&file='.$enc_file.'" title="Rename this file">Rename</a>';
		$echo_str .= "\n\t\t\t\t\t\t\t\t".' | ';
		$echo_str .= "\n\t\t\t\t\t\t\t".'</span>';
		$echo_str .= "\n\t\t\t\t\t\t\t".'<span>';
		$echo_str .= "\n\t\t\t\t\t\t\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&action=delete_file&file='.$enc_file.'" title="Delete this file">Delete</a>';
		$echo_str .= "\n\t\t\t\t\t\t\t".'</span>';
		$echo_str .= "\n\t\t\t\t\t\t".'</div>';
		$echo_str .= "\n\t\t\t\t\t".'</td>';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;">'.$file['size'].'</td>';
		$echo_str .= "\n\t\t\t\t\t".'<td style="padding: 5px 5px 5px 10px;">'.date("d/m/Y H:i:s", $file['mdate']).'</td>';
		$echo_str .= "\n\t\t\t\t".'</tr>';
		$ii ++;
	}
	$echo_str .= "\n\t\t\t".'</tbody>';
	$echo_str .= "\n\t\t".'</table>';
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n".'</div>';

	return $echo_str;
}

function wfu_rename_file_prompt($file, $type, $error) {
	if ( !current_user_can( 'manage_options' ) ) return;
	$siteurl = site_url();
	$dec_file = wfu_plugin_decode_string($file);
	//first extract sort info from dec_file
	$ret = wfu_extract_sortdata_from_path($dec_file);
	$dec_file = $ret['path'];
	if ( $type == 'dir' && substr($dec_file, -1) == '/' ) $dec_file = substr($dec_file, 0, -1);
	$parts = pathinfo($dec_file);
	$newname = $parts['basename'];
	$enc_dir = wfu_plugin_encode_string($parts['dirname'].'[['.$ret['sort'].']]');

	$echo_str = "\n".'<div class="wrap">';
	if ( $error ) {
		$newname = $_SESSION['wfu_rename_file']['newname'];
		$echo_str .= "\n\t".'<div class="error">';
		$echo_str .= "\n\t\t".'<p>'.$_SESSION['wfu_rename_file_error'].'</p>';
		$echo_str .= "\n\t".'</div>';
	}
	$echo_str .= "\n\t".'<div style="margin-top:20px;">';
	$echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=file_browser&dir='.$enc_dir.'" class="button" title="go back">Go back</a>';
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n\t".'<h2 style="margin-bottom: 10px;">Rename '.( $type == 'dir' ? 'Folder' : 'File' ).'</h2>';
	$echo_str .= "\n\t".'<form enctype="multipart/form-data" name="renamefile" id="renamefile" method="post" action="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload" class="validate">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="action" value="rename'.( $type == 'dir' ? 'dir' : 'file' ).'">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="dir" value="'.$enc_dir.'">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="file" value="'.$file.'">';
	if ( $type == 'dir' ) $echo_str .= "\n\t\t".'<label>Enter new name for folder <strong>'.$dec_file.'</strong></label><br/>';
	else $echo_str .= "\n\t\t".'<label>Enter new filename for file <strong>'.$dec_file.'</strong></label><br/>';
	$echo_str .= "\n\t\t".'<input name="wfu_newname" id="wfu_newname" type="text" value="'.$newname.'" style="width:50%;" />';
	$echo_str .= "\n\t\t".'<p class="submit">';
	$echo_str .= "\n\t\t\t".'<input type="submit" class="button-primary" name="submit" value="Rename">';
	$echo_str .= "\n\t\t\t".'<input type="submit" class="button-primary" name="submit" value="Cancel">';
	$echo_str .= "\n\t\t".'</p>';
	$echo_str .= "\n\t".'</form>';
	$echo_str .= "\n".'</div>';
	return $echo_str;
}

function wfu_rename_file($file, $type) {
	if ( !current_user_can( 'manage_options' ) ) return;
	$dec_file = wfu_plugin_decode_string($file);
	$dec_file = wfu_flatten_path($dec_file);
	if ( $type == 'dir' && substr($dec_file, -1) == '/' ) $dec_file = substr($dec_file, 0, -1);
	if ( !file_exists($dec_file) ) return wfu_browse_files();
	$parts = pathinfo($dec_file);
	$error = "";
	if ( isset($_POST['wfu_newname'])  && isset($_POST['submit']) ) {
		if ( $_POST['submit'] == "Rename" && $_POST['wfu_newname'] != $parts['basename'] ) {
			$new_file = $parts['dirname'].'/'.$_POST['wfu_newname'];
			if ( $_POST['wfu_newname'] == "" ) $error = 'Error: New '.( $type == 'dir' ? 'folder ' : 'file' ).'name cannot be empty!';
			elseif ( preg_match("/[^A-Za-z0-9.#\-$]/", $_POST['wfu_newname']) ) $error = 'Error: name contains invalid characters! Please correct.';
			elseif ( file_exists($new_file) ) $error = 'Error: The '.( $type == 'dir' ? 'folder' : 'file' ).' <strong>'.$_POST['wfu_newname'].'</strong> already exists! Please choose another one.';
			elseif ( rename($dec_file, $new_file) == false ) $error = 'Error: Rename of '.( $type == 'dir' ? 'folder' : 'file' ).' <strong>'.$parts['basename'].'</strong> failed!';
		}
	}
	if ( $error != "" ) {
		$_SESSION['wfu_rename_file_error'] = $error;
		$_SESSION['wfu_rename_file']['newname'] = $_POST['wfu_newname'];
	}
	return ( $error == "" );
}

function wfu_delete_file_prompt($file, $type) {
	if ( !current_user_can( 'manage_options' ) ) return;
	$siteurl = site_url();
	$dec_file = wfu_plugin_decode_string($file);
	//first extract sort info from dec_file
	$ret = wfu_extract_sortdata_from_path($dec_file);
	$dec_file = $ret['path'];
	if ( $type == 'dir' && substr($dec_file, -1) == '/' ) $dec_file = substr($dec_file, 0, -1);
	$parts = pathinfo($dec_file);
	$enc_dir = wfu_plugin_encode_string($parts['dirname'].'[['.$ret['sort'].']]');

	$echo_str = "\n".'<div class="wrap">';
	$echo_str .= "\n\t".'<div style="margin-top:20px;">';
	$echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=file_browser&dir='.$enc_dir.'" class="button" title="go back">Go back</a>';
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n\t".'<h2 style="margin-bottom: 10px;">Delete '.( $type == 'dir' ? 'Folder' : 'File' ).'</h2>';
	$echo_str .= "\n\t".'<form enctype="multipart/form-data" name="deletefile" id="deletefile" method="post" action="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload" class="validate">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="action" value="delete'.( $type == 'dir' ? 'dir' : 'file' ).'">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="dir" value="'.$enc_dir.'">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="file" value="'.$file.'">';
	$echo_str .= "\n\t\t".'<label>Are you sure that you want to delete '.( $type == 'dir' ? 'folder' : 'file' ).' <strong>'.$parts['basename'].'</strong>?</label><br/>';
	$echo_str .= "\n\t\t".'<p class="submit">';
	$echo_str .= "\n\t\t\t".'<input type="submit" class="button-primary" name="submit" value="Delete">';
	$echo_str .= "\n\t\t\t".'<input type="submit" class="button-primary" name="submit" value="Cancel">';
	$echo_str .= "\n\t\t".'</p>';
	$echo_str .= "\n\t".'</form>';
	$echo_str .= "\n".'</div>';
	return $echo_str;
}

function wfu_delete_file($file, $type) {
	if ( !current_user_can( 'manage_options' ) ) return;
	$dec_file = wfu_plugin_decode_string($file);
	$dec_file = wfu_flatten_path($dec_file);
	if ( $type == 'dir' && substr($dec_file, -1) == '/' ) $dec_file = substr($dec_file, 0, -1);
	if ( isset($_POST['submit']) ) {
		if ( $_POST['submit'] == "Delete" ) {
			if ( $type == 'dir' && $dec_file != "" ) wfu_delTree($dec_file);
			else unlink($dec_file);
		}
	}
	return true;
}

function wfu_create_dir_prompt($dir, $error) {
	if ( !current_user_can( 'manage_options' ) ) return;
	$siteurl = site_url();
	$dec_dir = wfu_plugin_decode_string($dir);
	//first extract sort info from dec_dir
	$ret = wfu_extract_sortdata_from_path($dec_dir);
	$dec_dir = $ret['path'];
	if ( substr($dec_dir, -1) != '/' ) $dec_dir .= '/';
	$newname = '';

	$echo_str = "\n".'<div class="wrap">';
	if ( $error ) {
		$newname = $_SESSION['wfu_create_dir']['newname'];
		$echo_str .= "\n\t".'<div class="error">';
		$echo_str .= "\n\t\t".'<p>'.$_SESSION['wfu_create_dir_error'].'</p>';
		$echo_str .= "\n\t".'</div>';
	}
	$echo_str .= "\n\t".'<div style="margin-top:20px;">';
	$echo_str .= "\n\t\t".'<a href="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload&amp;action=file_browser&dir='.$dir.'" class="button" title="go back">Go back</a>';
	$echo_str .= "\n\t".'</div>';
	$echo_str .= "\n\t".'<h2 style="margin-bottom: 10px;">Create Folder</h2>';
	$echo_str .= "\n\t".'<form enctype="multipart/form-data" name="createdir" id="createdir" method="post" action="'.$siteurl.'/wp-admin/options-general.php?page=wordpress_file_upload" class="validate">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="action" value="createdir">';
	$echo_str .= "\n\t\t".'<input type="hidden" name="dir" value="'.$dir.'">';
	$echo_str .= "\n\t\t".'<label>Enter the name of the new folder inside <strong>'.$dec_dir.'</strong></label><br/>';
	$echo_str .= "\n\t\t".'<input name="wfu_newname" id="wfu_newname" type="text" value="'.$newname.'" style="width:50%;" />';
	$echo_str .= "\n\t\t".'<p class="submit">';
	$echo_str .= "\n\t\t\t".'<input type="submit" class="button-primary" name="submit" value="Create">';
	$echo_str .= "\n\t\t\t".'<input type="submit" class="button-primary" name="submit" value="Cancel">';
	$echo_str .= "\n\t\t".'</p>';
	$echo_str .= "\n\t".'</form>';
	$echo_str .= "\n".'</div>';
	return $echo_str;
}

function wfu_create_dir($dir) {
	if ( !current_user_can( 'manage_options' ) ) return;
	$dec_dir = wfu_plugin_decode_string($dir);
	$dec_dir = wfu_flatten_path($dec_dir);
	if ( substr($dec_dir, -1) != '/' ) $dec_dir .= '/';
	if ( !file_exists($dec_dir) ) return wfu_browse_files();
	$error = "";
	if ( isset($_POST['wfu_newname'])  && isset($_POST['submit']) ) {
		if ( $_POST['submit'] == "Create" ) {
			$new_dir = $dec_dir.$_POST['wfu_newname'];
			if ( $_POST['wfu_newname'] == "" ) $error = 'Error: New folder name cannot be empty!';
			elseif ( preg_match("/[^A-Za-z0-9.#\-$]/", $_POST['wfu_newname']) ) $error = 'Error: name contains invalid characters! Please correct.';
			elseif ( file_exists($new_dir) ) $error = 'Error: The folder <strong>'.$_POST['wfu_newname'].'</strong> already exists! Please choose another one.';
			elseif ( mkdir($new_dir) == false ) $error = 'Error: Creation of folder <strong>'.$_POST['wfu_newname'].'</strong> failed!';
		}
	}
	if ( $error != "" ) {
		$_SESSION['wfu_create_dir_error'] = $error;
		$_SESSION['wfu_create_dir']['newname'] = $_POST['wfu_newname'];
	}
	return ( $error == "" );
}

?>
