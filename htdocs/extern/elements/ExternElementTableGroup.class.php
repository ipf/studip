<?
/**
* ExternElementTableGroup.class.php
* 
* 
* 
*
* @author		Peter Thienel <pthienel@web.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version	$Id$
* @access		public
* @modulegroup	extern
* @module		ExternElementTableGroup
* @package	studip_extern
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// ExternElement.class.php
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

class ExternElementTableGroup extends ExternElement {

	var $attributes = array("td_align", "td_valign", "td_bgcolor", "td_bgcolor_2",
			"td_class", "td_style", "tr_height", "tr_class", "tr_style", "font_face",
			"font_size", "font_color", "font_class", "font_style");

	/**
	* Constructor
	*
	* @param array config
	*/
	function ExternElementTableGroup ($config = "") {
		if ($config)
			$this->config = $config;
		
		$this->name = "TableGroup";
		$this->real_name = _("Gruppen&uuml;berschriften");
		$this->description = _("Gruppenüberschriften sind Tabellenzeilen, die eine neue Gruppe einleiten.");
	}

}

?>
