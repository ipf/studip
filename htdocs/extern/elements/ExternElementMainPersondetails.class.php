<?
/**
* ExternElementMainPersondetails.class.php
* 
*  
* 
*
* @author		Peter Thienel <pthienel@web.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version	$Id$
* @access		public
* @modulegroup	extern
* @module		ExternElementMainPersondetails
* @package	studip_extern
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// ExternElementMainPersondetails.class.php
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


require_once($GLOBALS["ABSOLUTE_PATH_STUDIP"].$GLOBALS["RELATIVE_PATH_EXTERN"]."/lib/ExternElementMain.class.php");

class ExternElementMainPersonDetails extends ExternElementMain {

	var $attributes = array("name", "order", "visible", "aliases", "width", "showcontact",
			"showimage", "wholesite", "nameformat", "dateformat", "language",
			"studiplink", "urlcss", "title");
	var $edit_function = "editMainSettings";
	
	/**
	* Constructor
	*
	*/
	function ExternElementMainPersonDetails () {
		$this->real_name = _("Grundeinstellungen");
		$this->description = _("In den Grundeinstellungen k&ouml;nnen Sie allgemeine Daten des Moduls &auml;ndern.");
	}
	
	/**
	* 
	*/
	function getDefaultConfig () {
		
		$config = array(
			"name" => "",
			"order" => "|0|1|2|3|4|5|6|7",
			"visible" => "|1|1|1|1|1|1|1|1",
			"aliases" => "||"._("Lebenslauf")."|"._("Schwerpunkte")."|"._("Lehrveranstaltungen")."|"
					._("Aktuell")."|"._("Termine")."|"._("Publikationen")."|",
			"showcontact" => "1",
			"showimage" => "right",
			"wholesite" => "0",
			"nameformat" => "no_title",
			"dateformat" => "%d. %b. %Y",
			"language" => "de_DE",
			"studiplink" => "top",
			"urlcss" => "",
			"title" => _("MitarbeiterInnen"),
		);
		
		return $config;
	}
	
	/**
	* 
	*/
	function toStringEdit ($post_vars = "", $faulty_values = "",
			$edit_form = "", $anker = "") {
		
		$out = "";
		$table = "";
		if ($edit_form == "")
			$edit_form =& new ExternEditModule($this->config, $post_vars, $faulty_values, $anker);
		
		$edit_form->setElementName($this->getName());
		$element_headline = $edit_form->editElementHeadline($this->real_name,
				$this->config->getName(), $this->config->getId(), TRUE, $anker);
		
		$headline = $edit_form->editHeadline(_("Name der Konfiguration"));
		$table = $edit_form->editName("name");
		
		$content_table = $edit_form->editContentTable($headline, $table);
		$content_table .= $edit_form->editBlankContent();
		
		$headline = $edit_form->editHeadline(_("Allgemeine Angaben zum Tabellenaufbau"));
		
		$edit_function = $this->edit_function;
		$table = $edit_form->$edit_function($this->field_names["content"],
				array("aliases" => array(0,7)), array("sort", "width", "widthpp"));
		
		$content_table .= $edit_form->editContentTable($headline, $table);
		$content_table .= $edit_form->editBlankContent();
		
		$headline = $edit_form->editHeadline(_("Weitere Angaben"));
		
		$title = _("Kontaktdaten anzeigen:");
		$info = _("Anw�hlen, wenn die Kontaktdaten (Anschrift, Email, Telefon usw.) angezeigt werden sollen.");
		$values = "1";
		$names = "";
		$table = $edit_form->editCheckboxGeneric("showcontact", $title, $info, $values, $names);
		
		$title = _("Bild anzeigen:");
		$info = _("W�hlen Sie, ob ein vom Nutzer in Stud.IP eingestelltes Bild links oder rechts neben den Kontaktdaten angezeigt werden soll.");
		$value = array("left", "right", "0");
		$names = array(_("links"), _("rechts"), _("nicht anzeigen"));
		$table .= $edit_form->editRadioGeneric("showimage", $title, $info, $value, $names);
		
		$title = _("Namensformat:");
		$info = _("W�hlen Sie, wie Personennamen formatiert werden sollen.");
		$values = array("no_title_short", "no_title", "no_title_rev", "full", "full_rev");
		$names = array(_("Meyer, P."), _("Peter Meyer"), _("Meyer Peter"),
				_("Dr. Peter Meyer"), _("Meyer, Peter, Dr."));
		$table .= $edit_form->editOptionGeneric("nameformat", $title, $info, $values, $names);
		
		$title = _("Datumsformat:");
		$info = _("W�hlen Sie, wie Datumsangaben formatiert werden sollen.");
		$values = array("%d. %b. %Y", "%d.%m.%Y", "%d.%m.%y", "%d. %B %Y", "%m/%d/%y");
		$names = array(_("25. Nov. 2003"), _("25.11.2003"), _("25.11.03"),
				_("25. November 2003"), _("11/25/03"));
		$table .= $edit_form->editOptionGeneric("dateformat", $title, $info, $values, $names);
		
		$title = _("Sprache:");
		$info = _("W�hlen Sie eine Sprache f�r die Datumsangaben aus.");
		$values = array("de_DE", "en_GB");
		$names = array(_("Deutsch"), _("Englisch"));
		$table .= $edit_form->editOptionGeneric("language", $title, $info, $values, $names);
		
		$title = _("Stud.IP-Link:");
		$info = _("Ausgabe eines Links, der direkt zum Stud.IP-Administrationsbereich verweist.");
		$value = array("top", "bottom", "0");
		$names = array(_("oberhalb"), _("unterhalb der Tabelle"), _("ausblenden"));
		$table .= $edit_form->editRadioGeneric("studiplink", $title, $info, $value, $names);
		
		$title = _("HTML-Header/Footer:");
		$info = _("Anw�hlen, wenn die Seite als komplette HTML-Seite ausgegeben werden soll, z.B. bei direkter Verlinkung oder in einem Frameset.");
		$values = "1";
		$names = "";
		$table .= $edit_form->editCheckboxGeneric("wholesite", $title, $info, $values, $names);
		
		$title = _("Stylesheet-Datei:");
		$info = _("Geben Sie hier die URL Ihrer Stylesheet-Datei an.");
		$table .= $edit_form->editTextfieldGeneric("urlcss", $title, $info, 50, 200);
		
		$title = _("Seitentitel:");
		$info = _("Geben Sie hier den Titel der Seite ein. Der Titel wird bei der Anzeige im Web-Browser in der Titelzeile des Anzeigefensters angezeigt.");
		$table .= $edit_form->editTextfieldGeneric("title", $title, $info, 50, 200);
		
		$content_table .= $edit_form->editContentTable($headline, $table);
		$content_table .= $edit_form->editBlankContent();
		
		$submit = $edit_form->editSubmit($this->config->getName(),
				$this->config->getId(), $this->getName());
		$out = $edit_form->editContent($content_table, $submit);
		$out .= $edit_form->editBlank();
		
		return $element_headline . $out;
	}
	
	function checkValue ($attribute, $value) {
		if ($attribute == "showcontact") {
			if (!isset($GLOBALS["HTTP_POST_VARS"]["Main_showcontact"])) {
				$GLOBALS["HTTP_POST_VARS"]["Main_showcontact"] = 0;
				return FALSE;
			}
				
			return !($value == "1" || $value == "0");
		}
		return FALSE;
	}
	
}

?>
