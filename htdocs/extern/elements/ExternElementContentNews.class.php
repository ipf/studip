<?
/**
* ExternElementContentNews.class.php
* 
* 
* 
*
* @author		Peter Thienel <pthienel@web.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version	$Id$
* @access		public
* @modulegroup	extern
* @module		ExternElement
* @package	studip_extern
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// ExternElementContentNews.class.php
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

require_once($GLOBALS["ABSOLUTE_PATH_STUDIP"].$GLOBALS["RELATIVE_PATH_EXTERN"]."/lib/ExternElement.class.php");

class ExternElementContentNews extends ExternElement {

	var $attributes = array("fonttopic_size", "fonttopic_face", "fonttopic_color",
			"fonttopic_class", "fonttopic_style","divtopic_align", "divtopic_class",
			"divtopic_style", "fontbody_size", "fontbody_face", "fontbody_color",
			"fontbody_class", "fontbody_style", "divbody_align", "divbody_class", "divbody_style");

	/**
	* Constructor
	*
	* @param array config
	*/
	function ExternElementContentNews ($config = "") {
		if ($config)
			$this->config = $config;
		
		$this->name = "ContentNews";
		$this->real_name = _("&Uuml;berschrift und Beschreibung der News");
		$this->description = _("Eigenschaften der Überschrift und der Beschreibung einer News.");
	}
	
	function toStringEdit ($post_vars = "", $faulty_values = "",
			$edit_form = "", $anker = "") {
		$out = "";
		$table = "";
		if ($edit_form == "")
			$edit_form =& new ExternEditModule($this->config, $post_vars, $faulty_values, $anker);
		
		$edit_form->setElementName($this->getName());
		$element_headline = $edit_form->editElementHeadline($this->real_name,
				$this->config->getName(), $this->config->getId(), TRUE, $anker);
		
		$attributes = array("fonttopic_size", "fonttopic_face", "fonttopic_color",
			"fonttopic_class", "fonttopic_style","divtopic_align", "divtopic_class",
			"divtopic_style", "fontbody_size", "fontbody_face", "fontbody_color",
			"fontbody_class", "fontbody_style", "divbody_align", "divbody_class", "divbody_style");
		$headlines = array("fonttopic" => _("Schriftformatierung News-Titel"),
				"divtopic" => "Ausrichtung News-Titel", "fontbody" => "Schriftformatierung News-Beschreibung",
				"divbody" => "Ausrichtung News-Beschreibung");
		$content_table = $edit_form->getEditFormContent($attributes, $headlines);
		$content_table .= $edit_form->editBlankContent();
		
		$headline = $edit_form->editHeadline(_("Weitere Angaben"));
		
		$submit = $edit_form->editSubmit($this->config->getName(),
				$this->config->getId(), $this->getName());
		$out = $edit_form->editContent($content_table, $submit);
		$out .= $edit_form->editBlank();
		
		return $element_headline . $out;
		
		return $out;
	}
	
}

?>
