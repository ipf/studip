<?
/**
* extern_functions_templates.inc.php
* 
* 
* 
*
* @author		Peter Thienel <pthienel@web.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version	$Id$
* @access		public
* @modulegroup	extern
* @module		extern_functions_templates
* @package	studip_extern
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// extern_functions_templates.inc.php
// 
// Copyright (C) 2003 Peter Thienel <pthienel@web.de>,
// Suchi & Berg GmbH <info@data-quest.de>
// +---------------------------------------------------------------------------+
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or any later version.
// +---------------------------------------------------------------------------+
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// +---------------------------------------------------------------------------+


/**
*
*
*/
function table_header ($element) {
	$out = "<table" . $element->getAttributes("table") . ">\n";
	
	return $out;
}

/**
*
*
*/
function table_headrow ($element, $fields) {
	$font_attributes = $element->getAttributes("font");
		
	$out = "<tr" . $element->getAttributes("tr") . ">\n";
	foreach ($fields as $field) {
		if ($font_attributes)
			$field = "<font$font_attributes>$field</font>";
		$out .= "<td" . $element->getAttributes("td") . ">" . $field . "</td>\n";
	}
	$out .= "<tr>\n";
	
	return $out;
}

/**
*
*
*/
function table_row ($element, $fields) {
	$font_attributes = $element->getAttributes("font");
		
	$out = "<tr" . $element->getAttributes("tr") . ">\n";
	foreach ($fields as $field) {
		if ($font_attributes)
			$field = "<font$font_attributes>$field</font>";
		$out .= "<td" . $element->getAttributes("td") . ">" . $field . "</td>\n";
	}
	$out .= "</tr>\n";
	
	return $out;
}

/**
*
*
*/
function table_group ($element, $group_name) {
	$colspan = " colspan=\"";
	$colspan .= sizeof($element->config->getValue("main", "order")) . "\"";
	
	if ($font_attributes = $element->getAttributes("font"))
		$group_name = "<font$font_attributes>$group_name</font>";
	
	$out = "<tr" . $element->getAttributes("tr") . ">\n";
	$out .= "<td" . $element->getAttributes("td") . $colspan . ">";
	$out .= $group_name;
	$out .= "</td>\n</tr>\n";
	
	return $out;
}

/**
*
*
*/
function tablefooter () {
	$out = "</table>";
	
	return $out;
}

/**
*
*
*/
function html_header ($title = "", $css_file = "", $attr_body = "") {
	$out = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	$out .= "<html>\n<head>\n";
	$out .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">\n";
	$out .= "<meta name=\"copyright\" content=\"Stud.IP-Crew (crew@studip.de)\">\n";
	$out .= "<title>$title</title>\n";
	if ($css_file)
		$out .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$css_file\">\n";
	$out .= "</head>\n<body$attr_body>\n";
	
	return $out;
}

/**
*
*
*/
function html_footer () {

	return "</body>\n</html>";
}


?>
