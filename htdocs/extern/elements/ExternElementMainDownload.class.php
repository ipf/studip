<?
/**
* ExternElementMainDownload.class.php
* 
*  
* 
*
* @author		Peter Thienel <pthienel@web.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version	$Id$
* @access		public
* @modulegroup	extern
* @module		ExternElementMainDownload
* @package	studip_extern
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// ExternElementMainDownload.class.php
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

class ExternElementMainDownload extends ExternElementMain {

	var $attributes = array("name", "order", "visible", "aliases", "width", "width_pp", "sort",
			"wholesite", "lengthdesc", "nameformat", "urlcss", "title", "nodatatext", "dateformat",
			"datelanguage", "srilink", "config", "iconpic", "icontxt", "iconpdf", "iconppt", "iconxls",
			"iconrtf", "iconzip", "icondefault");
	var $edit_function = "editMainSettings";
	
	/**
	* Constructor
	*
	*/
	function ExternElementMainDownload () {
		$this->real_name = _("Grundeinstellungen");
		$this->description = _("In den Grundeinstellungen k&ouml;nnen Sie allgemeine Daten des Moduls &auml;ndern.");
	}
	
	/**
	* 
	*/
	function getDefaultConfig () {
		$config = array(
			"name" => "",
			"order" => "|0|1|2|3|4|5",
			"visible" => "|TRUE|TRUE|TRUE|TRUE|TRUE|TRUE",
			"aliases" => "||"._("Name"."|"._("Beschreibung")."|"._("Upload am")."|"
					._("Gr&ouml;&szlig;e")."|"._("Upload durch"),
			"width" => "|1%|20%|25%|15%|15%|24%",
			"sort" => "|0|0|0|1|0|0",
			"wholesite" => "",
			"lengthdesc" => "",
			"nameformat" => "no_title",
			"urlcss" => "",
			"title" => "",
			"nodatatext" => _("Keine Dateien vorhanden"),
			"dateformat" => "%d. %b. %Y",
			"datelanguage" => "de_DE",
			"config" => "",
			"srilink" => "",
			"iconpic" => "",
			"icontxt" => "",
			"iconpdf" => "",
			"iconppt" => "",
			"iconxls" => "",
			"iconrtf" => "",
			"iconzip" => "",
			"icondefault" => ""
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
		
		if ($faulty_values = "")
			$faulty_values = array();
		
		$headline = $edit_form->editHeadline(_("Name der Konfiguration"));
		$table = $edit_form->editName("name");
		$content_table = $edit_form->editContentTable($headline, $table);
		$content_table .= $edit_form->editBlankContent();
		
		$headline = $edit_form->editHeadline(_("Allgemeine Angaben zum Tabellenaufbau"));
		
		$edit_function = $this->edit_function;
		$table = $edit_form->$edit_function($this->field_names, array("sort" => array(0)));
		
		$content_table .= $edit_form->editContentTable($headline, $table);
		$content_table .= $edit_form->editBlankContent();
		
		$headline = $edit_form->editHeadline(_("Weitere Angaben"));
		
		$title = _("HTML-Header/Footer:");
		$info = _("Anw�hlen, wenn die Seite als komplette HTML-Seite ausgegeben werden soll, z.B. bei direkter Verlinkung oder in einem Frameset.");
		$values = "1";
		$names = "";
		$table = $edit_form->editCheckboxGeneric("wholesite", $title, $info, $values, $names);
		
		$title = _("Max. L&auml;nge der Beschreibung:");
		$info = _("Geben Sie an, wieviele Zeichen der Beschreibung der Datei ausgegeben werden sollen.");
		$table .= $edit_form->editTextfieldGeneric("lengthdesc", $title, $info, 3, 3);
		
		$title = _("Namensformat:");
		$info = _("W�hlen Sie, wie Personennamen formatiert werden sollen.");
		$values = array("no_title", "no_title_rev", "full", "full_rev");
		$names = array(_("Vorname Nachname"), _("Nachname Vorname"),
				_("Titel Vorname Nachname"), _("Nachname Vorname Titel"));
		$table .= $edit_form->editOptionGeneric("nameformat", $title, $info, $values, $names);
		
		$title = _("Datumsformat:");
		$info = _("W�hlen Sie, wie Datumsangaben formatiert werden sollen.");
		$values = array("%d. %b. %Y", "%d.%m.%Y", "%d.%m.%y", "%d. %B %Y", "%m/%d/%y");
		$names = array(_("25. Nov. 2003"), _("25.11.2003"), _("25.11.03"),
				_("25. November 2003"), _("11/25/03"));
		$table .= $edit_form->editOptionGeneric("dateformat", $title, $info, $values, $names);
		
		$title = _("Sprache Datum");
		$info = ("W�hlen Sie eine Sprache f�r die Datumsangaben aus.");
		$values = array("de_DE", "en_US");
		$names = array(_("Deutsch"), _("Englisch (US)"));
		$table .= $edit_form->editOptionGeneric("datelanguage", $title, $info, $values, $names);
		
		$title = _("Stylesheet-Datei:");
		$info = _("Geben Sie hier die URL Ihrer Stylesheet-Datei an.");
		$table .= $edit_form->editTextfieldGeneric("urlcss", $title, $info, 50, 200);
		
		$title = _("Seitentitel:");
		$info = _("Geben Sie hier den Titel der Seite ein. Der Titel wird bei der Anzeige im Web-Browser in der Titelzeile des Anzeigefensters angezeigt.");
		$table .= $edit_form->editTextfieldGeneric("title", $title, $info, 50, 200);
		
		$title = _("Keine Dateien:");
		$info = _("Dieser Text wird an Stelle der Tabelle ausgegeben, wenn keine Dateien zum Download verf�gbar sind.");
		$table .= $edit_form->editTextareaGeneric("nodatatext", $title, $info, 3, 50);
		
		$title = _("Konfiguration Mitarbeiterdetails:");
		$info = ("Der Link auf die Seite zur Anzeige der Mitarbeiterdaten wird die gew�hlte Konfiguration aufrufen. W�hlen Sie \"Standard\", um die von Ihnen gesetzte Standardkonfiguration zu benutzen. Ist f�r das Mitarbeitermodul noch keine Konfiguration erstellt worden, wird die Stud.IP-Default-Konfiguration verwendet.");
		if ($configs = get_all_configurations($this->config->range_id, 6)) {
			$values = array_keys($configs["Persondetails"]);
			unset($names);
			foreach ($configs["Persondetails"] as $config)
				$names[] = $config["name"];
		}
		else {
			$values = array();
			$names = array();
		}
		array_unshift($values, "");
		array_unshift($names, _("Standardkonfiguration"));
		$table .= $edit_form->editOptionGeneric("config", $title, $info, $values, $names);
		
		$title = _("SRI-Link:");
		$info = _("Wenn Sie die SRI-Schnittstelle benutzen, m�ssen Sie hier die vollst�ndige URL (mit http://) der Seite angeben, in der das Ausgabemodul f�r die Mitarbeiterdaten eingebunden wird. Lassen Sie dieses Feld unbedingt leer, falls Sie die SRI-Schnittstelle nicht nutzen.");
		$table .= $edit_form->editTextfieldGeneric("srilink", $title, $info, 50, 250);
		
		$content_table .= $edit_form->editContentTable($headline, $table);
		$content_table .= $edit_form->editBlankContent();
		
		$headline = $edit_form->editHeadline(_("Eigene Icons"));
		$icon_attributes = array("iconpic", "icontxt", "iconpdf", "iconppt",
				"iconxls", "iconrtf", "iconzip", "icondefault");
		$icon_titles = array(
				_("Bilder:"),
				_("Text:"),
				_("Adobe pdf:"),
				_("Powerpoint (ppt):"),
				_("Excel (xls):"),
				_("Rich Text (rtf):"),
				_("ZIP-Dateien:"),
				_("sonstige Dateien:")
		);
		$icon_infos = array(
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r Bild-Dateien dienen soll. Erlaubte Formate: jpg, png, gif. "),
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r Text-Dateien dienen soll. Erlaubte Formate: jpg, png, gif. "),
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r PDF-Dateien dienen soll. Erlaubte Formate: jpg, png, gif. "),
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r Powerpoint-Dateien dienen soll. Erlaubte Formate: jpg, png, gif. "),
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r Excel-Dateien dienen soll. Erlaubte Formate: jpg, png, gif. "),
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r RTF-Dateien dienen soll. Erlaubte Formate: jpg, png, gif. "),
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r komprimierte Dateien dienen soll. Erlaubte Formate: jpg, png, gif. "),
				_("Geben Sie die URL eines Bildes ein, dass als Icon f�r alle anderen Dateiformate dienen soll. ")
		);
		$info_add = _("Wenn sie keine URL angeben, wird ein Standard-Icon ausgegeben.");
		
		$table = "";
		for ($i = 0; $i < sizeof($icon_attributes); $i++) {
			$table .= $edit_form->editTextfieldGeneric($icon_attributes[$i],
					$icon_titles[$i], $icon_infos[$i] . $info_add, 50, 200);
		}
		
		$content_table .= $edit_form->editContentTable($headline, $table);
		$content_table .= $edit_form->editBlankContent();
		
		$submit = $edit_form->editSubmit($this->config->getName(),
				$this->config->getId(), $this->getName());
		$out = $edit_form->editContent($content_table, $submit);
		$out .= $edit_form->editBlank();
		
		return $element_headline . $out;
	}
	
}

?>
