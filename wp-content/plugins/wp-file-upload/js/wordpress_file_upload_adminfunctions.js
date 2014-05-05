var DraggedItem = null;
var ShortcodeNextSave = 0;
var ShortcodeTimeOut = null;
var ShortcodeString = "";

jQuery(document).ready(function($){
	$('.wfu_color_field').wpColorPicker({
		change: function(event, ui) {
			event.target.value = ui.color.toString();
			if (event.target.name == "wfu_text_elements") wfu_update_text_value(event);
			else if (event.target.name == "wfu_triplecolor_elements") wfu_update_triplecolor_value(event);
		}
	});
});

function wfu_admin_activate_tab(key) {
	var tabs = document.getElementById("wfu_tab_container");
	var tab, tabkey;
	for (var i = 0; i < tabs.childNodes.length; i++) {
		tab = tabs.childNodes[i];
		if (tab.nodeType === 1) {
			tabkey = tab.id.substr(8);
			if (tab.className.indexOf("nav-tab-active") > -1) {
				tab.className = "nav-tab";
				document.getElementById("wfu_container_" + tabkey).style.display = "none";
			}
		}
	}
	document.getElementById("wfu_tab_" + key).className = "nav-tab nav-tab-active";
	document.getElementById("wfu_container_" + key).style.display = "block";
}

function wfu_admin_onoff_clicked(key) {
	var onoff = document.getElementById("wfu_attribute_" + key);
	var container = document.getElementById("wfu_wrapper");
	var shadows = document.getElementsByClassName("wfu_shadow_" + key, "div", container);
	var shadows_inv = document.getElementsByClassName("wfu_shadow_" + key + "_inv", "div", container);
	var status = (onoff.className.substr(onoff.className.length - 2) == "on");
	status = !status;
	if (status) {
		document.getElementById("wfu_attribute_value_" + key).value = "true";
		onoff.className = "wfu_onoff_container_on";
		for (var i = 0; i < shadows.length; i++) shadows[i].style.display = "none";
		for (var i = 0; i < shadows_inv.length; i++) shadows_inv[i].style.display = "block";
	}
	else {
		document.getElementById("wfu_attribute_value_" + key).value = "false";
		onoff.className = "wfu_onoff_container_off";
		for (var i = 0; i < shadows.length; i++) shadows[i].style.display = "block";
		for (var i = 0; i < shadows_inv.length; i++) shadows_inv[i].style.display = "none";
	}
	wfu_generate_shortcode();
	if (key == "userdata") wfu_update_userfield_variables();
}

function wfu_admin_radio_clicked(key) {
	var radios = document.getElementsByName("wfu_radioattribute_" + key);
	var container = document.getElementById("wfu_wrapper");
	var shadows = document.getElementsByClassName("wfu_shadow_" + key, "div", container);
	var shadows_inv = document.getElementsByClassName("wfu_shadow_" + key + "_inv", "div", container);
	var val = "";
	for (i = 0; i < radios.length; i++)
		if (radios[i].checked) val = radios[i].value;
	var status = (val.substr(0, 1) == "*");
	if (status) {
		val = val.substr(1);
		for (var i = 0; i < shadows.length; i++) shadows[i].style.display = "none";
		for (var i = 0; i < shadows_inv.length; i++) shadows_inv[i].style.display = "block";
	}
	else {
		for (var i = 0; i < shadows.length; i++) shadows[i].style.display = "block";
		for (var i = 0; i < shadows_inv.length; i++) shadows_inv[i].style.display = "none";
	}
	document.getElementById("wfu_attribute_value_" + key).value = val;
	wfu_generate_shortcode();
}

function wfu_addEventHandler(obj, evt, handler) {
	if(obj.addEventListener) {
		// W3C method
		obj.addEventListener(evt, handler, false);
	}
	else if(obj.attachEvent) {
		// IE method.
		obj.attachEvent('on'+evt, handler);
	}
	else {
		// Old school method.
		obj['on'+evt] = handler;
	}
}

function wfu_attach_separator_dragdrop_events() {
	var container = document.getElementById('wfu_placements_container');
	var item;
	for (var i = 0; i < container.childNodes.length; i++) {
		item = container.childNodes[i];
		if (item.className == "wfu_component_separator_hor" || item.className == "wfu_component_separator_ver") {
			wfu_addEventHandler(item, 'dragenter', wfu_separator_dragenter);
			wfu_addEventHandler(item, 'dragover', wfu_default_dragover);
			wfu_addEventHandler(item, 'dragleave', wfu_separator_dragleave);
			wfu_addEventHandler(item, 'drop', wfu_separator_drop);
		}
	}
}

function wfu_Attach_Admin_DragDrop_Events() {
	if (window.FileReader) {
		var container = document.getElementById('wfu_placements_container');
		var available_container = document.getElementById('wfu_componentlist_container');
		var item;
		for (var i = 0; i < container.childNodes.length; i++) {
			item = container.childNodes[i];
			if (item.className == "wfu_component_box") {
				wfu_addEventHandler(item, 'dragstart', wfu_component_dragstart);
				wfu_addEventHandler(item, 'dragend', wfu_component_dragend);
			}
		}
		for (var i = 0; i < available_container.childNodes.length; i++) {
			item = available_container.childNodes[i];
			if (item.className == "wfu_component_box_container") {
				for (var ii = 0; ii < item.childNodes.length; ii++) {
					if (item.childNodes[ii].className == "wfu_component_box wfu_inbase") {
						wfu_addEventHandler(item.childNodes[ii], 'dragstart', wfu_component_dragstart);
						wfu_addEventHandler(item.childNodes[ii], 'dragend', wfu_component_dragend);
					}
				}
			}
		}
		item = document.getElementById('wfu_componentlist_dragdrop');
		wfu_addEventHandler(item, 'dragenter', wfu_componentlist_dragenter);
		wfu_addEventHandler(item, 'dragover', wfu_default_dragover);
		wfu_addEventHandler(item, 'dragleave', wfu_componentlist_dragleave);
		wfu_addEventHandler(item, 'drop', wfu_componentlist_drop);
		wfu_attach_separator_dragdrop_events();
	}	
}

function wfu_componentlist_dragenter(e) {
	e = e || window.event;
	if (e.preventDefault) { e.preventDefault(); }
	if (!DraggedItem) return false;
	var item = document.getElementById('wfu_componentlist_dragdrop');
	if (item.className.indexOf("wfu_componentlist_dragdrop_dragover") == -1)
		item.className += " wfu_componentlist_dragdrop_dragover";
	return false;
}

function wfu_componentlist_dragleave(e) {
	e = e || window.event;
	if (e.preventDefault) { e.preventDefault(); }
	if (!DraggedItem) return false;
	var item = document.getElementById('wfu_componentlist_dragdrop');
	item.className = item.className.replace(" wfu_componentlist_dragdrop_dragover", "");
	return false;
}

function wfu_componentlist_drop(e) {
	e = e || window.event;
	if (e.preventDefault) { e.preventDefault(); }
	var component = e.dataTransfer.getData("Component");
	if (!component) return false;
	//move dragged component to base
	var item = document.getElementById('wfu_component_box_' + component);
	item.className = "wfu_component_box wfu_inbase";
	item.style.display = "block";
	document.getElementById('wfu_component_box_container_' + component).appendChild(item);
	//recreate placements panel
	var placements = wfu_admin_recreate_placements_text(null, "");
	wfu_admin_recreate_placements_panel(placements);
	document.getElementById("wfu_attribute_value_placements").value = placements;
	wfu_generate_shortcode();
	return false;
}

function wfu_separator_dragenter(e) {
	e = e || window.event;
	if (e.preventDefault) { e.preventDefault(); }
	if (!DraggedItem) return false;
	if (e.target.className == "wfu_component_separator_hor") {
		var bar = document.getElementById('wfu_component_bar_hor');
		bar.style.top = e.target.offsetTop + "px";
		bar.style.display = "block";
	}
	else if (e.target.className == "wfu_component_separator_ver") {
		var bar = document.getElementById('wfu_component_bar_ver');
		bar.style.top = e.target.offsetTop + "px";
		bar.style.left = e.target.offsetLeft + "px";
		bar.style.display = "block";
	}
	return false;
}

function wfu_default_dragover(e) {
	e = e || window.event;
	if (e.preventDefault) { e.preventDefault(); }
	return false;
}

function wfu_separator_dragleave(e) {
	e = e || window.event;
	if (e.preventDefault) { e.preventDefault(); }
	if (!DraggedItem) return false;
	if (e.target.className == "wfu_component_separator_hor") {
		var bar = document.getElementById('wfu_component_bar_hor');
		bar.style.display = "none";
	}
	else if (e.target.className == "wfu_component_separator_ver") {
		var bar = document.getElementById('wfu_component_bar_ver');
		bar.style.display = "none";
	}
	return false;
}

function wfu_separator_drop(e) {
	e = e || window.event;
	if (e.preventDefault) { e.preventDefault(); }
	var component = e.dataTransfer.getData("Component");
	if (!component) return false;
	//first move dragged component to base otherwise we may lose it during recreation of placements panel
	var item = document.getElementById('wfu_component_box_' + component);
	item.style.display = "none";
	item.className = "wfu_component_box wfu_inbase";
	document.getElementById('wfu_component_box_container_' + component).appendChild(item);
	//recreate placements panel
	var placements = wfu_admin_recreate_placements_text(e.target, component);
	wfu_admin_recreate_placements_panel(placements);
	document.getElementById("wfu_attribute_value_placements").value = placements;
	wfu_generate_shortcode();
	return false;
}

function wfu_component_dragstart(e) {
	e = e || window.event;
	e.dataTransfer.setData("Component", e.target.id.replace("wfu_component_box_", ""));
	if (e.target.className.indexOf("wfu_component_box_dragged") == -1) {
		e.target.className += " wfu_component_box_dragged";
		DraggedItem = e.target;
	}
	e.target.style.zIndex = 3;
	var item = document.getElementById('wfu_componentlist_dragdrop');
	item.className = "wfu_componentlist_dragdrop wfu_componentlist_dragdrop_dragover";
	item.style.display = "block";
	return false;
}

function wfu_component_dragend(e) {
	e = e || window.event;
	DraggedItem = null;
	e.target.style.zIndex = 1;
	var item = document.getElementById('wfu_componentlist_dragdrop');
	item.style.display = "none";
	item.className = "wfu_componentlist_dragdrop";
	e.target.className = e.target.className.replace(" wfu_component_box_dragged", "");
	document.getElementById('wfu_component_bar_ver').style.display = "none";
	document.getElementById('wfu_component_bar_hor').style.display = "none";
	return false;
}

function wfu_admin_recreate_placements_text(place, new_component) {
	function add_item(component) {
		if (placements != "") placements += delim;
		placements += component;
		delim = "";
	}

	var container = document.getElementById('wfu_placements_container');
	var delim = "";
	var placements = "";
	var component = "";
	for (var i = 0; i < container.childNodes.length; i++) {
		item = container.childNodes[i];
		if (item.className == "wfu_component_separator_ver") {
			if (delim == "" ) delim = "+";
			if (item == place) { add_item(new_component); delim = "+"; }
		}
		else if (item.className == "wfu_component_separator_hor") {
			delim = "/";
			if (item == place) { add_item(new_component); delim = "/"; } 
		}
		else if (item.className == "wfu_component_box") add_item(item.id.replace("wfu_component_box_", ""));
	}
	return placements;
}

function wfu_admin_recreate_placements_panel(placements_text) {
	var container = document.getElementById('wfu_placements_container');
	var item, placements, sections;
	var itemname = "";
	for (var i = 0; i < container.childNodes.length; i++) {
		item = container.childNodes[i];
		if (item.className == "wfu_component_box") {
			itemname = item.id.replace("wfu_component_box_", "");
			item.style.display = "inline-block";
			item.className = "wfu_component_box wfu_inbase";
			document.getElementById('wfu_component_box_container_' + itemname).appendChild(item);
		}
	}
	container.innerHTML = "";
	placements = placements_text.split("/");
	for (var i = 0; i < placements.length; i++) {
		item = document.createElement("DIV");
		item.className = "wfu_component_separator_hor";
		item.setAttribute("draggable", true);
		container.appendChild(item);
		item = document.createElement("DIV");
		item.className = "wfu_component_separator_ver";
		item.setAttribute("draggable", true);
		container.appendChild(item);
		sections = placements[i].split("+");
		for (var ii = 0; ii < sections.length; ii++) {
			item = document.getElementById('wfu_component_box_' + sections[ii]);
			if (item) {
				container.appendChild(item);
				item.className = "wfu_component_box";
				item.style.display = "inline-block";
				item = document.createElement("DIV");
				item.className = "wfu_component_separator_ver";
				item.setAttribute("draggable", true);
				container.appendChild(item);
			}
		}
	}
	item = document.createElement("DIV");
	item.className = "wfu_component_separator_hor";
	item.setAttribute("draggable", true);
	container.appendChild(item);
	item = document.createElement("DIV");
	item.id = "wfu_component_bar_hor";
	item.className = "wfu_component_bar_hor";
	container.appendChild(item);
	item = document.createElement("DIV");
	item.id = "wfu_component_bar_ver";
	item.className = "wfu_component_bar_ver";
	container.appendChild(item);
	wfu_attach_separator_dragdrop_events();
}

function wfu_userdata_edit_field(line, label, required) {
	var item;
	for (var i = 0; i < line.childNodes.length; i ++) {
		item = line.childNodes[i];
		if (item.tagName == "INPUT") {
			if (item.type == "text") {
				item.value = label;
				wfu_attach_element_handlers(item, wfu_update_userfield_value);
			}
			else if (item.type == "checkbox") {
				item.checked = required;
			}
		}
		else if (item.tagName == "DIV") item.className = "wfu_userdata_action";
	}
}

function wfu_userdata_add_field(obj) {
	var line = obj.parentNode;
	var newline = line.cloneNode(true);
	wfu_userdata_edit_field(newline, "", false);
	line.parentNode.insertBefore(newline, line.nextSibling);
}

function wfu_userdata_remove_field(obj) {
	var line = obj.parentNode;
	var container = line.parentNode;
	var first = null;
	for (var i = 0; i < container.childNodes.length; i++)
		if (container.childNodes[i].nodeType === 1) {
			first = container.childNodes[i];
			break;
		}
	if (line != first) {
		line.parentNode.removeChild(line);
		for (var i = 0; i < first.childNodes.length; i++)
			if (first.childNodes[i].nodeType === 1) {
				wfu_update_userfield_value({target:first.childNodes[i]});
				break;
			}
	}
}

function wfu_generate_shortcode() {
	var defaults = document.getElementById("wfu_attribute_defaults");
	var values = document.getElementById("wfu_attribute_values");
	var item;
	var attribute = "";
	var value = "";
	var shortcode_full = "[wordpress_file_upload";
	var shortcode = "";
	for (var i = 0; i < defaults.childNodes.length; i++) {
		item = defaults.childNodes[i];
		if (item.nodeType === 1) {
			attribute = item.id.replace("wfu_attribute_default_", "");
			value = document.getElementById("wfu_attribute_value_" + attribute).value;
			if (item.value != value)
				shortcode += " " + attribute + "=\"" + value + "\"";
		}
	}
	shortcode_full += shortcode + "]";

	document.getElementById("wfu_shortcode").value = shortcode_full;
	ShortcodeString = shortcode.substr(1);

	wfu_schedule_save_shortcode();
}

function wfu_update_text_value(e) {
	e = e || window.event;
	var item = e.target;
	var attribute = item.id.replace("wfu_attribute_", "");
	var val = item.value;
	//if it is a multiline element, then replace line breaks with %n%
	if (item.tagName == "TEXTAREA") {
		val = val.replace(/(\r\n|\n|\r)/gm,"%n%");
	}
	if (val !== item.oldVal) {
		item.oldVal = val;
		document.getElementById("wfu_attribute_value_" + attribute).value = val;
		wfu_generate_shortcode();
	}
}

function wfu_update_triplecolor_value(e) {
	e = e || window.event;
	var item = e.target;
	var attribute = item.id.replace("wfu_attribute_", "");
	attribute = attribute.replace("_color", "");
	attribute = attribute.replace("_bgcolor", "");
	attribute = attribute.replace("_borcolor", "");	
	item = document.getElementById("wfu_attribute_" + attribute + "_color");
	var val = item.value + "," +
		document.getElementById("wfu_attribute_" + attribute + "_bgcolor").value + "," +
		document.getElementById("wfu_attribute_" + attribute + "_borcolor").value;
	if (val !== item.oldVal) {
		item.oldVal = val;
		document.getElementById("wfu_attribute_value_" + attribute).value = val;
		wfu_generate_shortcode();
	}
}

function wfu_update_dimension_value(e) {
	e = e || window.event;
	var item = e.target;
	var attribute = item.name.replace("wfu_dimension_elements_", "");
	var group = document.getElementsByName(item.name);
	item = group[0];
	var val = "";
	var dimname = "";
	for (var i = 0; i < group.length; i++) {
		dimname = group[i].id.replace("wfu_attribute_" + attribute + "_", "");
		if (val != "" && group[i].value != "") val += ", ";
		if (group[i].value != "") val += dimname + ":" + group[i].value;
	}
	if (val !== item.oldVal) {
		item.oldVal = val;
		document.getElementById("wfu_attribute_value_" + attribute).value = val;
		wfu_generate_shortcode();
	}
}

function wfu_update_ptext_value(e) {
	e = e || window.event;
	var item = e.target;
	var attribute = item.id.replace("wfu_attribute_", "");
	attribute = attribute.substr(2);
	var singular = document.getElementById("wfu_attribute_s_" + attribute).value;
	var plural = document.getElementById("wfu_attribute_p_" + attribute).value;
	var val = singular + "/" + plural;
	if (val !== item.oldVal) {
		item.oldVal = val;
		document.getElementById("wfu_attribute_value_" + attribute).value = val;
	}
	wfu_generate_shortcode();
}

function wfu_update_rolelist_value(attribute) {
	var value = "";
	var rolelist = document.getElementById("wfu_attribute_" + attribute);
	var checkall = document.getElementById("wfu_attribute_" + attribute + "_all");
	if (checkall.checked) {
		rolelist.disabled = true;
		value = "all";
	}
	else {
		rolelist.disabled = false;
		var options = rolelist.options;
		for (var i = 0; i < options.length; i++)
			if (options[i].selected) {
				if (value != "") value += ",";
				value += options[i].value;
			}
	}
	document.getElementById("wfu_attribute_value_" + attribute).value = value;
	wfu_generate_shortcode();
}

function wfu_update_userfield_value(e) {
	e = e || window.event;
	var item = e.target;
	var line = item.parentNode;
	var container = line.parentNode;
	var fieldval = "";
	var fieldreq = false;
	var val = "";
	for (var i = 0; i < container.childNodes.length; i++) {
		line = container.childNodes[i];
		if (line.tagName === "DIV") {
			for (var j = 0; j < line.childNodes.length; j++)
				if (line.childNodes[j].tagName == "INPUT") {
					if (line.childNodes[j].type == "text") {
						fieldval = line.childNodes[j].value;
						if (i == 0) item = line.childNodes[j];
					}
					else if (line.childNodes[j].type == "checkbox")
						fieldreq = line.childNodes[j].checked;
				}
			if (val != "" && fieldval != "") val += "/";
			if (fieldval != "" && fieldreq) val += "*";
			if (fieldval != "") val += fieldval;
		}
	}
	if (val !== item.oldVal) {
		item.oldVal = val;
		document.getElementById("wfu_attribute_value_userdatalabel").value = val;
		wfu_generate_shortcode();
		wfu_update_userfield_variables();
	}
}

function wfu_update_userfield_variables() {
	var userdata = document.getElementById("wfu_attribute_value_userdatalabel").value;
	var container = document.getElementById("wfu_wrapper");
	var shadows = document.getElementsByClassName("wfu_shadow_userdata", "div", container);
	var selects = document.getElementsByName("wfu_userfield_select");
	for (var i = 0; i < selects.length; i++) selects[i].style.display = "none";
	if (shadows.length == 0) return;
	if (shadows[0].style.display == "block") return;

	var options_str = '<option style="display:none;">%userdataXXX%</option>';
	var userfields = userdata.split("/");
	var field = "";
	for (var i = 1; i <= userfields.length; i++) {
		field = userfields[i - 1];
		if (field[0] == "*") field = field.substr(1);
		options_str += '<option value="%userdata' + i + '%">' + i + ': ' + field + '</option>';
	}
	for (var i = 0; i < selects.length; i++) {
		selects[i].innerHTML = options_str;
		selects[i].style.display = "inline-block";
	}
}

function wfu_attach_element_handlers(item, handler) {
	var elem_events = ['DOMAttrModified', 'textInput', 'input', 'change', 'keypress', 'paste', 'focus', 'propertychange'];
	for (var i = 0; i < elem_events.length; i++)
		wfu_addEventHandler(item, elem_events[i], handler);
}

function wfu_Attach_Admin_Events() {
	wfu_generate_shortcode();
	wfu_update_userfield_variables();
	wfu_Attach_Admin_DragDrop_Events();
	var text_elements = document.getElementsByName("wfu_text_elements");
	for (var i = 0; i < text_elements.length; i++) wfu_attach_element_handlers(text_elements[i], wfu_update_text_value);
	var ptext_elements = document.getElementsByName("wfu_ptext_elements");
	for (var i = 0; i < ptext_elements.length; i++) wfu_attach_element_handlers(ptext_elements[i], wfu_update_ptext_value);
	var triplecolor_elements = document.getElementsByName("wfu_triplecolor_elements");
	for (var i = 0; i < triplecolor_elements.length; i++) wfu_attach_element_handlers(triplecolor_elements[i], wfu_update_triplecolor_value);
	var dimension_elements = document.getElementsByName("wfu_dimension_elements_widths");
	for (var i = 0; i < dimension_elements.length; i++) wfu_attach_element_handlers(dimension_elements[i], wfu_update_dimension_value);
	dimension_elements = document.getElementsByName("wfu_dimension_elements_heights");
	for (var i = 0; i < dimension_elements.length; i++) wfu_attach_element_handlers(dimension_elements[i], wfu_update_dimension_value);
	var userfield_elements = document.getElementsByName("wfu_userfield_elements");
	for (var i = 0; i < userfield_elements.length; i++) wfu_attach_element_handlers(userfield_elements[i], wfu_update_userfield_value);
}

function wfu_insert_variable(obj) {
	var attr = obj.className.replace("wfu_variable wfu_variable_", "");
	var inp = document.getElementById("wfu_attribute_" + attr);
	var pos = inp.selectionStart;
	var prevval = inp.value;
	inp.value = prevval.substr(0, pos) + obj.innerHTML + prevval.substr(pos);
	wfu_update_text_value({target:inp});
}

function wfu_insert_userfield_variable(obj) {
	var attr = obj.className.replace("wfu_variable wfu_variable_", "");
	var inp = document.getElementById("wfu_attribute_" + attr);
	var pos = inp.selectionStart;
	var prevval = inp.value;
	inp.value = prevval.substr(0, pos) + obj.value + prevval.substr(pos);
	obj.value = "%userdataXXX%";
	wfu_update_text_value({target:inp});
}

//wfu_GetHttpRequestObject: function that returns XMLHttpRequest object for various browsers
function wfu_GetHttpRequestObject() {
	var xhr = null;
	try {
		xhr = new XMLHttpRequest(); 
	}
	catch(e) { 
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e2) {
			try {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e) {}
		}
	}
	if (xhr == null && window.createRequest) {
		try {
			xmlhttp = window.createRequest();
		}
		catch (e) {}
	}
	return xhr;
}

//wfu_plugin_encode_string: function that encodes a decoded string
function wfu_plugin_encode_string(str) {
	var i = 0;
	var newstr = "";
	var num;
	var hex = "";
	for (i = 0; i < str.length; i++) {
		num = str.charCodeAt(i);
		if (num >= 2048) num = (((num & 16773120) | 917504) << 4) + (((num & 4032) | 8192) << 2) + ((num & 63) | 128);
		else if (num >= 128) num = (((num & 65472) | 12288) << 2) + ((num & 63) | 128);
		hex = num.toString(16);
		if (hex.length == 1 || hex.length == 3 || hex.length == 5) hex = "0" + hex; 
		newstr += hex;
	}
	return newstr;
}

function wfu_schedule_save_shortcode() {
	var d = new Date();
	var dt = ShortcodeNextSave - d.getTime();
	if (ShortcodeTimeOut != null) {
		clearTimeout(ShortcodeTimeOut);
		ShortcodeTimeOut = null;
	}
	if (dt <= 0) wfu_save_shortcode();
	else ShortcodeTimeOut = setTimeout(function() {wfu_save_shortcode();}, dt);
}

function wfu_save_shortcode() {
	var xhr = wfu_GetHttpRequestObject();
	if (xhr == null) return;

	//send request using AJAX
	var url = AdminParams.wfu_ajax_url;
	params = new Array(2);
	params[0] = new Array(2);
	params[0][0] = 'action';
	params[0][1] = 'wfu_ajax_action_save_shortcode';
	params[1] = new Array(2);
	params[1][0] = 'shortcode';
	params[1][1] = wfu_plugin_encode_string(ShortcodeString);

	var parameters = '';
	for (var i = 0; i < params.length; i++) {
		parameters += (i > 0 ? "&" : "") + params[i][0] + "=" + encodeURI(params[i][1]);
	}

	var d = new Date();
	ShortcodeNextSave = d.getTime() + 5000;

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.setRequestHeader("Content-length", parameters.length);
	xhr.setRequestHeader("Connection", "close");
	xhr.onreadystatechange = function() {
		if ( xhr.readyState == 4 ) {
			if ( xhr.status == 200 ) {
				if (xhr.responseText == "save_shortcode_success") {
					document.getElementById("wfu_save_label").innerHTML = "saved";
					document.getElementById("wfu_save_label").className = "wfu_save_label";
					document.getElementById("wfu_save_label").style.opacity = 1;
					wfu_fadeout_element(300);
					ShortcodeNextSave = d.getTime() + 1000;
					if (ShortcodeTimeOut != null) wfu_schedule_save_shortcode();
				}
				else {
					document.getElementById("wfu_save_label").innerHTML = "not saved";
					document.getElementById("wfu_save_label").className = "wfu_save_label_fail";
					document.getElementById("wfu_save_label").style.opacity = 1;
					wfu_fadeout_element(300);
				}
			}
		}
	};
	xhr.send(parameters);
}

function wfu_adjust_opacity(opacity) {
	document.getElementById("wfu_save_label").style.opacity = opacity;
}

function wfu_fadeout_element(interval) {
	var reps = 20.0;
	var op = 0.0;
	for (var i = 0; i < reps; i++) {
		op = 1.0 - i / reps;
		setTimeout('wfu_adjust_opacity("' + op.toString() + '")', i * interval / reps);
	}

	setTimeout('wfu_adjust_opacity("0.0")', i * interval / reps);
}

function wfu_apply_value(attribute, type, value) {
	if (type == "onoff") {
		document.getElementById("wfu_attribute_" + attribute).className = "wfu_onoff_container_" + (value != "true" ? "on" : "off");
		wfu_admin_onoff_clicked(attribute);
	}
	else if (type == "text" || type == "ltext" || type == "integer" || type == "float" || type == "mtext" || type == "color" ) {
		var item = document.getElementById("wfu_attribute_" + attribute);
		if (item.tagName == "TEXTAREA") value = value.replace(/\%n\%/gm,"\n");
		if (type == "color") {
			var rgb = colourNameToHex(value);
			if (!rgb) rgb = value;
			jQuery('#wfu_attribute_' + attribute).wpColorPicker('color', rgb);
		}
		item.value = value;
		wfu_update_text_value({target:item});
	}
	else if (type == "placements") {
		wfu_admin_recreate_placements_panel(value);
		document.getElementById("wfu_attribute_value_placements").value = value;
		wfu_generate_shortcode();
	}
	else if (type == "radio") {
		var radios = document.getElementsByName("wfu_radioattribute_" + attribute);
		for (var i = 0; i < radios.length; i++)
			radios[i].checked = (radios[i].value == value || ("*" + radios[i].value) == value);
		wfu_admin_radio_clicked(attribute);
	}
	else if (type == "ptext" ) {
		var parts = value.split("/");
		var singular = parts.length < 1 ? "" : parts[0];
		var plural = parts.length < 2 ? singular : parts[1];
		var item1 = document.getElementById("wfu_attribute_s_" + attribute);
		item1.value = singular;
		var item2 = document.getElementById("wfu_attribute_p_" + attribute);
		item2.value = plural;
		wfu_update_ptext_value({target:item1});
		wfu_update_ptext_value({target:item2});
	}
	else if (type == "rolelist" ) {
		value = value.toLowerCase();
		if (value == "all") document.getElementById("wfu_attribute_" + attribute + "_all").checked = true;
		else {
			document.getElementById("wfu_attribute_" + attribute + "_all").checked = false;
			var roles = value.split(",");
			for (var i = 0; i < roles.length; i++) roles[i] = roles[i].trim();
			var item = document.getElementById("wfu_attribute_" + attribute);
			for (var i = 0; i < item.options.length; i++)
				item.options[i].selected = (roles.indexOf(item.options[i].value) > -1);
		}
		wfu_update_rolelist_value(attribute);
	}
	else if (type == "dimensions" ) {
		var dims = value.split(",");
		var details, nam, val, item;
		var group = document.getElementsByName("wfu_dimension_elements_" + attribute);
		for (var i = 0; i < group.length; i++) group[i].value = "";
		for (var i = 0; i < dims.length; i++) {
			details = dims[i].split(":", 2);
			nam = details.length < 1 ? "" : details[0];
			val = details.length < 2 ? nam : details[1];
			item = document.getElementById("wfu_attribute_" + attribute + "_" + nam.trim());
			if (item) item.value = val.trim();
		}
		item = group[0];
		wfu_update_dimension_value({target:item});
	}
	else if (type == "userfields") {
		var fields_arr = value.split("/");
		var is_req;
		var fields = Array();
		for (var i = 0; i < fields_arr.length; i++) {
			is_req = (fields_arr[i].substr(0, 1) == "*");
			if (is_req) fields_arr[i] = fields_arr[i].substr(1);
			if (fields_arr[i] != "") fields.push({name:fields_arr[i], required:is_req});
		}
		var container = document.getElementById("wfu_attribute_" + attribute);
		var first = null;
		var remove_array = Array();
		for (var i = 0; i < container.childNodes.length; i++)
			if (container.childNodes[i].nodeType === 1) {
				if (first == null) first = container.childNodes[i];
				else remove_array.push(container.childNodes[i]);
			}
		for (var i = 0; i < remove_array.length; i++) container.removeChild(remove_array[i]);
		wfu_userdata_edit_field(first, "", false);
		
		var newline;
		var prevline = first;
		for (var i = 0; i < fields.length; i++) {
			if (i == 0) wfu_userdata_edit_field(first, fields[i].name, fields[i].required);
			else {
				newline = prevline.cloneNode(true);
				wfu_userdata_edit_field(newline, fields[i].name, fields[i].required);
				container.insertBefore(newline, prevline.nextSibling);
				prevline = newline;
			}
		}
		var item;
		for (var i = 0; i < first.childNodes.length; i++) {
			item = first.childNodes[i];
			if (item.tagName == "INPUT") break;
		}
		wfu_update_userfield_value({target:item});
	}
	else if (type == "color-triplet") {
		var colors = value.split(",");
		for (var i = 0; i < colors.length; i++) colors[i] = colors[i].trim();
		if (colors.length == 2) colors = [colors[0], colors[1], "#000000"];
		else if (colors.length == 1) colors = [colors[0], "#FFFFFF", "#000000"];
		else if (colors.length < 3) colors = ["#000000", "#FFFFFF", "#000000"];
		var rgb = colourNameToHex(colors[0]);
		if (!rgb) rgb = colors[0];
		jQuery('#wfu_attribute_' + attribute + "_color").wpColorPicker('color', rgb);
		var item = document.getElementById("wfu_attribute_" + attribute + "_color");
		item.value = colors[0];
		rgb = colourNameToHex(colors[1]);
		if (!rgb) rgb = colors[1];
		jQuery('#wfu_attribute_' + attribute + "_bgcolor").wpColorPicker('color', rgb);
		document.getElementById("wfu_attribute_" + attribute + "_bgcolor").value = colors[1];
		rgb = colourNameToHex(colors[2]);
		if (!rgb) rgb = colors[2];
		jQuery('#wfu_attribute_' + attribute + "_borcolor").wpColorPicker('color', rgb);
		document.getElementById("wfu_attribute_" + attribute + "_borcolor").value = colors[2];
		wfu_update_triplecolor_value({target:item});
	}
}

function colourNameToHex(colour)
{
	var colours = {"aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff",
		"beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887",
		"cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff",
		"darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f",
		"darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1",
		"darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff",
		"firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff",
		"gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","green":"#008000","greenyellow":"#adff2f",
		"honeydew":"#f0fff0","hotpink":"#ff69b4",
		"indianred ":"#cd5c5c","indigo ":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c",
		"lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2",
		"lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de",
		"lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6",
		"magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee",
		"mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5",
		"navajowhite":"#ffdead","navy":"#000080",
		"oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6",
		"palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080",
		"red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1",
		"saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4",
		"tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0",
		"violet":"#ee82ee",
		"wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5",
		"yellow":"#ffff00","yellowgreen":"#9acd32"
	};

	if (typeof colours[colour.toLowerCase()] != 'undefined')
	return colours[colour.toLowerCase()];

	return false;
}
