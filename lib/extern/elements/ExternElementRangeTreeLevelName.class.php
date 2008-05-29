<?
# Lifter002: TODO
/**
* ExternElementTreeLevelName.class.php
* 
* 
* 
*
* @author		Peter Thienel <pthienel@web.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version	$Id$
* @access		public
* @modulegroup	extern
* @module		ExternElementTreeLevelName
* @package	studip_extern
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// ExternElementTreeLevelName.class.php
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

require_once($GLOBALS["RELATIVE_PATH_EXTERN"]."/lib/ExternElement.class.php");

class ExternElementRangeTreeLevelName extends ExternElement {

	var $attributes = array("aliases", "table_bgcolor",
				"table_cellpadding", "table_cellspacing", "table_class",
				"table_style", "td_height", "td_align", "td_valign", "td_bgcolor",
				"td_class", "td_style", "font_face", "font_size", "font_color",
				"font_class", "font_style");

	/**
	* Constructor
	*
	* @param array config
	*/
	function ExternElementRangeTreeLevelName ($config = "") {
		if ($config)
			$this->config = $config;
		
		$this->name = "RangeTreeLevelName";
		$this->real_name = _("Name der Ebene");
		$this->description = _("Formatierung des Ebenennamens in einer Baum-Navigation.");
	}
	
	function getDefaultConfig () {
		global $INST_TYPE;
		$config = parent::getDefaultConfig();
		foreach ($INST_TYPE as $type)
			$names .= "|{$type['name']}:";
		$config["aliases"] = $names;
		
		return $config;
	}
	
	function toStringEdit ($post_vars = "", $faulty_values = "",
			$edit_form = "", $anker = "") {
		
		global $INST_TYPE;
		
		if ($faulty_values == '')
			$faulty_values = array();	
		$out = '';
		$tag_headline = '';
		$table = '';
		if ($edit_form == '')
			$edit_form =& new ExternEditHtml($this->config, $post_vars, $faulty_values, $anker);
		
		$edit_form->setElementName($this->getName());
		$element_headline = $this->getEditFormHeadline($edit_form);
		
		$headline = $edit_form->editHeadline(_("Bezeichnungen"));
		$info = _("Geben Sie eine alternative Bezeichnung ein.");
		foreach ($INST_TYPE as $type)
			$names[] = $type["name"];
		$content = $edit_form->editTextfieldGeneric("aliases", $names, $info, 35, 200);
		
		$content_table = $edit_form->editContentTable($headline, $content);
		$content_table .= $edit_form->editBlankContent();
		
		$out = $content_table . $edit_form->getEditFormContent($this->attributes);
		
		$submit = $edit_form->editSubmit($this->config->getName(),
				$this->config->getId(), $this->getName());
		$out = $edit_form->editContent($out, $submit);
		$out .= $edit_form->editBlank();
		
		return  $element_headline . $out;
	}
	
}

?>
