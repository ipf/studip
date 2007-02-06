<?
/**
* config.inc.php
*
* Configuration file for studip. In this file you can change the options of many
* Stud.IP Settings. Please note: to setup the system, set the basic settings in the
* local.inc of the phpLib package first.
*
* @access		public
* @package		studip_core
* @modulegroup	library
* @module		config.inc.php
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// functions.php
// Stud.IP Kernfunktionen
// Copyright (C) 2002 Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>,
// Ralf Stockmann <rstockm@gwdg.de>, Andr� Noack Andr� Noack <andre.noack@gmx.net>
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

/*basic settings for Stud.IP
----------------------------------------------------------------
you find here the indivual settings for your installation.
please note the LOCAL.INC.PHP in the php-lib folder for the basic system settings!*/

global
  $CALENDAR_MAX_EVENTS,
  $export_ex_types,
  $export_icon,
  $export_o_modes,
  $ilias_status,
  $ilias_systemgroup,
  $INST_MODULES,
  $INST_STATUS_GROUPS,
  $INST_TYPE,
  $LIT_LIST_FORMAT_TEMPLATE,
  $NAME_FORMAT_DESC,
  $output_formats,
  $PERS_TERMIN_KAT,
  $record_of_study_templates,
  $SCM_PRESET,
  $SEM_CLASS,
  $SEM_STATUS_GROUPS,
  $SEM_TYPE,
  $SEM_TYPE_MISC_NAME,
  $skip_page_3,
  $SMILE_SHORT,
  $SYMBOL_SHORT,
  $TERMIN_TYP,
  $TIME_PRESETS,
  $TITLE_FRONT_TEMPLATE,
  $TITLE_REAR_TEMPLATE,
  $UNI_CONTACT,
  $UNI_INFO,
  $UNI_LOGIN_ADD,
  $UNI_LOGOUT_ADD,
  $UNI_URL,
  $UPLOAD_TYPES,
  $username_prefix,
  $xml_filename,
  $xslt_filename;

//Daten ueber die Uni
    // der Name wird in der local.inc festgelegt
$UNI_URL = "http://www.studip.de";
$UNI_LOGOUT_ADD=sprintf(_("Und hier geht's direkt zum %sMensaplan%s&nbsp;;-)"), "<a href=\"http://studentenwerk.stud.uni-goettingen.de/mensa/mensen/alle_heute.php\"><b>", "</b></a>");
$UNI_CONTACT = "studip-users@lists.sourceforge.net";
$UNI_INFO = "Kontakt:\nStud.IP Crew c/o data-quest Suchi & Berg GmbH\nFriedl�nder Weg 20a\n37085 G�ttingen\nTel. 0551-3819850\nFax 0551-3819853\nstudip@data-quest.de";


//Festlegen der zulaessigen Typen fuer Veranstaltungen
$SEM_TYPE_MISC_NAME="sonstige"; //dieser Name wird durch die allgemeine Bezechnung (=Veranstaltung ersetzt)
$SEM_TYPE[1]=array("name"=>_("Vorlesung"), "en"=>"Lecture", "class"=>1);
$SEM_TYPE[2]=array("name"=>_("Grundstudium"), "en"=>"Basic classes", "class"=>1);
$SEM_TYPE[3]=array("name"=>_("Hauptstudium"), "en"=>"Advanced classes", "class"=>1);
$SEM_TYPE[4]=array("name"=>_("Seminar"), "en"=>"Seminar", "class"=>1);
$SEM_TYPE[5]=array("name"=>_("Praxisveranstaltung"), "en"=>"Practical course", "class"=>1);
$SEM_TYPE[6]=array("name"=>_("Kolloquium"), "en"=>"Colloqia", "class"=>1);
$SEM_TYPE[7]=array("name"=>_("Forschungsgruppe"), "en"=>"Research group", "class"=>1);
$SEM_TYPE[8]=array("name"=>_("Arbeitsgruppe"), "en"=>"Workgroup", "class"=>5);
$SEM_TYPE[9]=array("name"=>_("sonstige"), "en"=>"Miscellaneous", "class"=>1);
$SEM_TYPE[10]=array("name"=>_("Forschungsgruppe"), "en"=>"Research group", "class"=>2);
$SEM_TYPE[11]=array("name"=>_("sonstige"), "en"=>"Miscellaneous", "class"=>2);
$SEM_TYPE[12]=array("name"=>_("Gremiumsveranstaltung"), "en"=>"Board meeting", "class"=>3);
$SEM_TYPE[13]=array("name"=>_("sonstige"), "en"=>"Miscellaneous", "class"=>3);
$SEM_TYPE[14]=array("name"=>_("Community-Forum"), "en"=>"Community forum", "class"=>4);
$SEM_TYPE[15]=array("name"=>_("sonstige"), "en"=>"Miscellaneous", "class"=>4);
$SEM_TYPE[16]=array("name"=>_("Praktikum"), "en"=>"Practical course", "class"=>1);
$SEM_TYPE[17]=array("name"=>_("Lehrveranstaltung nach PVO-Lehr I"), "en"=>"", "class"=>1);
$SEM_TYPE[18]=array("name"=>_("Anleitung zu selbst�ndigen wissenschaftlichen Arbeiten"), "en"=>"", "class"=>1);
$SEM_TYPE[19]=array("name"=>_("Sprachkurs"), "en"=>"Language Course", "class"=>1);
$SEM_TYPE[20]=array("name"=>_("Fachdidaktik"), "en"=>"Didactics", "class"=>1);
$SEM_TYPE[21]=array("name"=>_("�bung"), "en"=>"Exercise Course", "class"=>1);
$SEM_TYPE[22]=array("name"=>_("Proseminar"), "en"=>"Proseminar", "class"=>1);
$SEM_TYPE[23]=array("name"=>_("Oberseminar"), "en"=>"Oberseminar", "class"=>1);
$SEM_TYPE[24]=array("name"=>_("Arbeitsgemeinschaft"), "en"=>"Workgroup", "class"=>1);
//weitere Typen koennen hier angefuegt werden


//Festlegen der zulaessigen Klassen fuer Veranstaltungen. Jeder sem_type referenziert auf eine dieser Klassen
$SEM_CLASS[1]=array("name"=>_("Lehre"), 					 	//the name of the class
					"compact_mode"=>FALSE, 			//indicates, if all fields are used in the creation process or only the fields that are necessary for workgroups
					"workgroup_mode"=>FALSE, 			//indicates, if the workgroup mode is used (to use different declarations)
					"only_inst_user"=>TRUE,				//indicates, that olny staff from the Einrichtungen which own the Veranstaltung, are allowed for tutor and dozent
					"turnus_default"=>0	, 				//indicates, whether the turnus field is default set to "regulary" (0), "not regulary" (1) or "no dates" (-1) in the creation process
					"default_read_level"=>1, 				//the default read acces level. "without signed in" (0), "signed in" (1), "password" (2)
					"default_write_level" =>1, 				//the default write acces level. "without signed in" (0), "signed in" (1), "password" (2)
					"bereiche"=>TRUE,					//indicates, if bereiche should be used
					"show_browse"=>TRUE, 				//indicates, if the hierachy-system should be shown in the search-process
					"write_access_nobody"=>FALSE, 		//indicates, if write access level 0 is possible. If this is not possibly, don't set default_write_level to 0
					"topic_create_autor"=>TRUE,
					"visible"=>TRUE,
					//modules, select the active modules for this class
					"forum"=>TRUE,				//forum, this modul is stud_ip core; always avaiable
					"documents"=>TRUE,			//documents, this modul is stud_ip core; always avaiable
					"schedule"=>TRUE,
					"participants"=>TRUE,
					"literature"=>TRUE,
					"ilias_connect"=>TRUE,			//Ilias-connect, only, if the modul is global activated; see local.inc
					"chat"=>TRUE,				//chat, only, if the modul is global activated; see local.inc
					"support"=>FALSE,			//support, only, if the modul is global activated; see local.inc (this modul is not part of the main distribution)
					"scm"=>TRUE,
					//descriptions
					"description"=>_("Hier finden Sie alle in Stud.IP registrierten Lehrveranstaltungen"), 						//the description
					"create_description"=>_("Verwenden Sie diese Kategorie, um normale Lehrveranstaltungen anzulegen"));		//the description in the creation process

$SEM_CLASS[2]=array("name"=>_("Forschung"),
					"compact_mode"=>TRUE,
					"workgroup_mode"=>TRUE,
					"only_inst_user"=>TRUE,
					"turnus_default"=>-1,
					"default_read_level"=>2,
					"default_write_level" =>2,
					"bereiche"=>FALSE,
					"show_browse"=>TRUE,
					"write_access_nobody"=>FALSE,
					"visible"=>TRUE,
					"forum"=>TRUE,
					"topic_create_autor" => true,
					"documents"=>TRUE,
					"schedule"=>TRUE,
					"participants"=>TRUE,
					"literature"=>TRUE,
					"chat"=>TRUE,
					"description"=>_("Hier finden Sie virtuelle Veranstaltungen zum Thema Forschung an der Universit&auml;t"),
					"create_description"=>_("In dieser Kategorie k&ouml;nnen sie virtuelle Veranstaltungen f&uuml;r Forschungsprojekte anlegen."));

$SEM_CLASS[3]=array("name"=>_("Organisation"),
					"compact_mode"=>TRUE,
					"workgroup_mode"=>TRUE,
					"only_inst_user"=>FALSE,
					"turnus_default"=>-1,
					"default_read_level"=>2,
					"default_write_level" =>2,
					"bereiche"=>FALSE,
					"show_browse"=>TRUE,
					"write_access_nobody"=>TRUE,
					"visible"=>TRUE,
					"forum"=>TRUE,
					"topic_create_autor" => true,
					"documents"=>TRUE,
					"schedule"=>TRUE,
					"participants"=>TRUE,
					"literature"=>TRUE,
					"chat"=>TRUE,
					"description"=>_("Hier finden Sie virtuelle Veranstaltungen zu verschiedenen Gremien an der Universit&auml;t"),
					"create_description"=>_("Um virtuelle Veranstaltungen f&uuml;r Uni-Gremien anzulegen, verwenden Sie diese Kategorie"));

$SEM_CLASS[4]=array("name"=>_("Community"),
					"compact_mode"=>TRUE,
					"workgroup_mode"=>FALSE,
					"only_inst_user"=>FALSE,
					"turnus_default"=>-1,
					"default_read_level"=>0,
					"default_write_level" =>0,
					"bereiche"=>FALSE,
					"show_browse"=>FALSE,
					"write_access_nobody"=>TRUE,
					"visible"=>TRUE,
					"forum"=>TRUE,
					"documents"=>TRUE,
					"schedule"=>TRUE,
					"participants"=>TRUE,
					"chat"=>TRUE,
					"description"=>_("Hier finden Sie virtuelle Veranstaltungen zu unterschiedlichen Themen"),
					"create_description"=>_("Wenn Sie Veranstaltungen als Diskussiongruppen zu unterschiedlichen Themen anlegen m&ouml;chten, verwenden Sie diese Kategorie."));

$SEM_CLASS[5]=array("name"=>_("Arbeitsgruppen"),
					"compact_mode"=>FALSE,
					"workgroup_mode"=>FALSE,
					"only_inst_user"=>TRUE,
					"turnus_default"=>1,
					"default_read_level"=>1,
					"default_write_level" =>1,
					"bereiche"=>FALSE,
					"show_browse"=>FALSE,
					"topic_create_autor"=>TRUE,
					"write_access_nobody"=>FALSE,
					"visible"=>TRUE,
					"forum"=>TRUE,
					"documents"=>TRUE,
					"schedule"=>TRUE,
					"participants"=>TRUE,
					"literature"=>TRUE,
					"chat"=>TRUE,
					"description"=>sprintf(_("Hier finden Sie verschiedene Arbeitsgruppen an der %s"), $GLOBALS['UNI_NAME']),
					"create_description"=>_("Verwenden Sie diese Kategorie, um unterschiedliche Arbeitsgruppen anzulegen."));
//weitere Klassen koennen hier angefuegt werden. Bitte Struktur wie oben exakt uebernehmen.


//Festlegen der erlaubten oder verbotenen Dateitypen
$UPLOAD_TYPES=array( 	"default" =>												//Name bezeichnet den zugehoerigen SEM_TYPE, name "1" waere entsprechend die Definition der Dateiendungen fuer SEM_TYPE[1]; default wird verwendet, wenn es keine spezielle Definition fuer einen SEM_TYPE gibt
						array(	"type"=>"deny", 									//Type bezeichnet den grundsetzlichen Typ der Deklaration: deny verbietet alles ausser den angegebenen file_types, allow erlaubt alle ausser den angegebenen file_types
								"file_types" => array ("rtf", "xls", "ppt", "zip", "pdf", "txt"),	//verbotene bzw. erlaubte Dateitypen
								"file_sizes" => array (	"root" => 7 * 1048576,			//Erlaubte Groesse je nach Rechtestufe
													"admin" => 7 * 1048576,
													"dozent" => 7 * 1048576,
													"tutor" => 7 * 1048576,
													"autor" => 1.38 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							),
						"7" =>
						array(	"type"=>"allow",
								"file_types" => array ("exe"),
								"file_sizes" => array (	"root" =>	14 * 1048576,
													"admin" =>
14 * 1048576,
													"dozent" =>
14 * 1048576,
													"tutor" =>
14 * 1048576,
													"autor" => 7 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							),
						"8" =>
						array(	"type"=>"allow",
								"file_types" => array ("exe"),
								"file_sizes" => array (	"root" =>
14 * 1048576,
													"admin" =>
14 * 1048576,
													"dozent" =>
14 * 1048576,
													"tutor" =>
14 * 1048576,
													"autor" => 7 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							),
						"9" =>
						array(	"type"=>"allow",
								"file_types" => array ("exe"),
								"file_sizes" => array (	"root" =>
14 * 1048576,
													"admin" =>
14 * 1048576,
													"dozent" =>
14 * 1048576,
													"tutor" =>
14 * 1048576,
													"autor" => 7 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							),
						"10" =>
						array(	"type"=>"allow",
								"file_types" => array ("exe"),
								"file_sizes" => array (	"root" =>
14 * 1048576,
													"admin" =>
14 * 1048576,
													"dozent" =>
14 * 1048576,
													"tutor" =>
14 * 1048576,
													"autor" => 7 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							),
						"11" =>
						array(	"type"=>"allow",
								"file_types" => array ("exe"),
								"file_sizes" => array (	"root" => 100 * 1048576,
													"admin" => 100 * 1048576,
													"dozent" => 100 * 1048576,
													"tutor" => 100 * 1048576,
													"autor" => 100 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							),
						"12" =>
						array(	"type"=>"allow",
								"file_types" => array ("exe"),
								"file_sizes" => array (	"root" =>
14 * 1048576,
													"admin" =>
14 * 1048576,
													"dozent" =>
14 * 1048576,
													"tutor" =>
14 * 1048576,
													"autor" => 7 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							),
						"13" =>
						array(	"type"=>"allow",
								"file_types" => array ("exe"),
								"file_sizes" => array (	"root" =>
14 * 1048576,
													"admin" =>
14 * 1048576,
													"dozent" =>
14 * 1048576,
													"tutor" =>
14 * 1048576,
													"autor" => 7 * 1048576,
													"nobody" => 1.38 * 1048576
												)
							)
					);
//weitere Definitionen fuer spezielle Veranstaltungstypen koennen hier angefuegt werden. Bitte Struktur wie oben exakt uebernehmen.


//Festlegen von zulaessigen Bezeichnungen fuer Einrichtungen (=Institute)
$INST_TYPE[1]=array("name"=>_("Einrichtung"));
$INST_TYPE[2]=array("name"=>_("Zentrum"));
$INST_TYPE[3]=array("name"=>_("Lehrstuhl"));
$INST_TYPE[4]=array("name"=>_("Abteilung"));
$INST_TYPE[5]=array("name"=>_("Fachbereich"));
$INST_TYPE[6]=array("name"=>_("Seminar"));
$INST_TYPE[7]=array("name"=>_("Fakult�t"));
$INST_TYPE[8]=array("name"=>_("Arbeitsgruppe"));
//weitere Typen koennen hier angefuegt werden


//define the presets of statusgroups for Veranstaltungen (refers to the key of the $SEM_CLASS array)
$SEM_STATUS_GROUPS["default"] = array ("DozentInnen", "TutorInnen", "AutorInnen", "LeserInnen", "sonstige"); 	//the default. Don't delete this entry!
$SEM_STATUS_GROUPS["2"] = array ("Projektleitung", "Koordination", "Forschung", "Verwaltung", "sonstige");
$SEM_STATUS_GROUPS["3"] = array ("Organisatoren", "Mitglieder", "Ausschu&szlig;mitglieder", "sonstige");
$SEM_STATUS_GROUPS["4"] = array ("Moderatoren des Forums","Mitglieder", "sonstige");
$SEM_STATUS_GROUPS["5"] = array ("ArbeitsgruppenleiterIn", "Arbeitsgruppenmitglieder", "sonstige");
//you can add more specifig presets for the different classes


//define the presets of statusgroups for Einrichtungen (refers to the key of the $INST_TYPE array)
$INST_STATUS_GROUPS["default"] = array ("DirektorIn", "HochschullehrerIn", "Lehrbeauftragte", "Zweitmitglied", "wiss. Hilfskraft","wiss. MitarbeiterIn",
									"stud. Hilfskraft", "Frauenbeauftragte", "Internetbeauftragte(r)", "StudentIn", "techn. MitarbeiterIn", "Sekretariat / Verwaltung",
									"stud. VertreterIn");
//you can add more specifig presets for the different types


//preset names for scm (simple content module)
$SCM_PRESET[1] = array("name"=>_("Informationen"));		//the first entry is the default label for scms, it'll be used if the user give no information for another
$SCM_PRESET[2] = array("name"=>_("Literatur"));
$SCM_PRESET[3] = array("name"=>_("Links"));
$SCM_PRESET[4] = array("name"=>_("Verschiedenes"));
//you can add more presets here

//preset template for formatting of literature list entries
$LIT_LIST_FORMAT_TEMPLATE = "**{dc_creator}** |({dc_contributor})||\n"
						. "{dc_title}||\n"
						. "{dc_identifier}||\n"
						. "%%{published}%%||\n"
						. "{note}||\n"
						. "[{lit_plugin}]{external_link}|\n";

//define the used modules for instiutes
$INST_MODULES["default"] = array(
			"forum"=>TRUE,				//forum, this modul is stud_ip core; always avaiable
			"documents"=>TRUE,			//documents, this modul is stud_ip core; always avaiable
			"schedule"=>TRUE,
			"participants"=>TRUE,
			"literature"=>TRUE,
			"ilias_connect"=>TRUE,			//Ilias-connect, only, if the modul is global activated; see local.inc
			"chat"=>TRUE,				//chat, only, if the modul is global activated; see local.inc
			"support"=>FALSE,			//support, only, if the modul is global activated; see local.inc (this modul is not part of the main distribution)
			);
//you can add more specific presets for the different types


//Festlegen der Veranstaltungs Termin Typen
$TERMIN_TYP[1]=array("name"=>_("Sitzung"), "sitzung"=>1, "color"=>"#2D2C64"); 		//dieser Termin Typ wird immer als Seminarsitzung verwendet und im Ablaufplan entsprechend markiert. Der Titel kann veraendert werden, Eintraege aus dem Seminar Assistenten und Terminverwaltung fuer Seminar-Sitzungsterrmine bekommen jedoch immer diesen Typ
$TERMIN_TYP[2]=array("name"=>_("Vorbesprechung"), "sitzung"=>0, "color"=>"#5C2D64"); 	//dieser Termin Typ wird immer als Vorbesprechung verwendet. Der Titel kann veraendert werden, Eintraege aus dem Seminar Assistenten fuer Vorbesprechungen bekommen jedoch immer diesen Typ
$TERMIN_TYP[3]=array("name"=>_("Klausur"), "sitzung"=>0,  "color"=>"#526416");
$TERMIN_TYP[4]=array("name"=>_("Exkursion"), "sitzung"=>0, "color"=>"#505064");
$TERMIN_TYP[5]=array("name"=>_("anderer Termin"), "sitzung"=>0, "color"=>"#41643F");
$TERMIN_TYP[6]=array("name"=>_("Sondersitzung"), "sitzung"=>0, "color"=>"#64372C");
$TERMIN_TYP[7]=array("name"=>_("Vorlesung"), "sitzung"=>1, "color"=>"#627C95");
//weitere Typen koennen hier angefuegt werden


// Festlegen der Kategorien f�r pers�nlichen Terminkalender
$PERS_TERMIN_KAT[1]=array("name"=>_("Sonstiges"), "color"=>"#41643F");
$PERS_TERMIN_KAT[2]=array("name"=>_("Sitzung"), "color"=>"#2D2C64");
$PERS_TERMIN_KAT[3]=array("name"=>_("Vorbesprechung"), "color"=>"#5C2D64");
$PERS_TERMIN_KAT[4]=array("name"=>_("Klausur"), "color"=>"#526416");
$PERS_TERMIN_KAT[5]=array("name"=>_("Exkursion"), "color"=>"#505064");
$PERS_TERMIN_KAT[6]=array("name"=>_("Sondersitzung"), "color"=>"#64372C");
$PERS_TERMIN_KAT[7]=array("name"=>_("Pr�fung"), "color"=>"#64541E");
$PERS_TERMIN_KAT[8]=array("name"=>_("Telefonat"), "color"=>"#48642B");
$PERS_TERMIN_KAT[9]=array("name"=>_("Besprechung"), "color"=>"#957C29");
$PERS_TERMIN_KAT[10]=array("name"=>_("Verabredung"), "color"=>"#956D42");
$PERS_TERMIN_KAT[11]=array("name"=>_("Geburtstag"), "color"=>"#66954F");
$PERS_TERMIN_KAT[12]=array("name"=>_("Familie"), "color"=>"#2C5964");
$PERS_TERMIN_KAT[13]=array("name"=>_("Urlaub"), "color"=>"#951408");
$PERS_TERMIN_KAT[14]=array("name"=>_("Reise"), "color"=>"#18645C");
$PERS_TERMIN_KAT[15]=array("name"=>_("Vorlesung"), "color"=>"#627C95");
// weitere Kategorien k�nnen hier angef�gt werden

//standardtimes for date-begin and date-end
$TIME_PRESETS = array ( //starthour, startminute, endshour, endminute
		array ('07','45','09','15'), // 07:45 - 09:15
		array ('09','30','11','00'), // 09:30 - 11:00
		array ('11','15','12','45'), // 11:15 - 12:45
		array ('13','30','15','00'), // 13:30 - 15:00
		array ('15','15','16','45'), // 15:15 - 16:45
		array ('17','00','18','30'), // 17:00 - 18:30
		array ('18','45','20','15')  // 18:45 - 20:15
		);
//$TIME_PRESETS = false;

$CALENDAR_MAX_EVENTS = 1000;

//Vorgaben f�r die Titelauswahl
$TITLE_FRONT_TEMPLATE = array("","Prof.","Prof. Dr.","Dr.","PD Dr.","Dr. des.","Dr. med.","Dr. rer. nat.","Dr. forest.",
							"Dr. sc. agr.","Dipl.-Biol.","Dipl.-Chem.","Dipl.-Ing.","Dipl.-Sozw.","Dipl.-Geogr.",
							"Dipl.-Geol.","Dipl.-Geophys.","Dipl.-Ing. agr.","Dipl.-Kfm.","Dipl.-Math.","Dipl.-Phys.",
							"Dipl.-Psych.","M. Sc","B. Sc");
$TITLE_REAR_TEMPLATE = array("","M.A.","B.A.","M.S.","MBA","Ph.D.","Dipl.-Biol.","Dipl.-Chem.","Dipl.-Ing.","Dipl.-Sozw.","Dipl.-Geogr.",
							"Dipl.-Geol.","Dipl.-Geophys.","Dipl.-Ing. agr.","Dipl.-Kfm.","Dipl.-Math.","Dipl.-Phys.",
							"Dipl.-Psych.","M. Sc","B. Sc");

$NAME_FORMAT_DESC['full'] = _("Titel1 Vorname Nachname Titel2");
$NAME_FORMAT_DESC['full_rev'] = _("Nachname, Vorname, Titel1, Titel2");
$NAME_FORMAT_DESC['no_title'] = _("Vorname Nachname");
$NAME_FORMAT_DESC['no_title_rev'] = _("Nachname, Vorname");
$NAME_FORMAT_DESC['no_title_short'] = _("Nachname, V.");
$NAME_FORMAT_DESC['no_title_motto'] = _("Vorname Nachname, Motto");

//Shorts for Smiley
$SMILE_SHORT = array( //diese Kuerzel fuegen das angegebene Smiley ein (Dateiname + ".gif")
	":)"=>"smile" ,
	":-)"=>"asmile" ,
	":#:"=>"zwinker" ,
	":("=>"frown" ,
	":o"=>"redface" ,
	":D"=>"biggrin",
	";-)"=>"wink");

//Shorts for symbols
$SYMBOL_SHORT = array( //use this shorts to insert an symbols (filename + ".gif")
	"=)"=>"symbol03" ,
	"(="=>"symbol04" ,
	"(c)"=>"symbol05" ,
	"(r)"=>"symbol06" ,
	" tm "=>"symbol08");


/*configuration for additional modules
----------------------------------------------------------------
this options are only needed, if you are using the addional modules (please see in local.inc
which modules are activated). It's a good idea to leave them untouched...*/


// <<-- EXPORT-EINSTELLUNGEN
// Ausgabemodi f�r den Export
$export_o_modes = array("start","file","choose", "direct","processor","passthrough");
// Exportierbare Datenarten
$export_ex_types = array("veranstaltung", "person", "forschung");

$skip_page_3 = true;
// Name der erzeugten XML-Datei
$xml_filename = "data.xml";
// Name der erzeugten Ausgabe-Datei
$xslt_filename = "studip";

// Vorhandene Ausgabeformate
$output_formats = array(
	"html"		=>		"Hypertext (HTML)",
	"rtf"		=>		"Rich Text Format (RTF)",
	"txt"		=>		"Text (TXT)",
	"fo"		=>		"Adobe Postscript (PDF)",
	"xml"		=>		"Extensible Markup Language (XML)"
);

// Icons f�r die Ausgabeformate
$export_icon["xml"] = "xls-icon.gif";
$export_icon["xslt"] = "xls-icon.gif";
$export_icon["xsl"] = "xls-icon.gif";
$export_icon["rtf"] = "rtf-icon.gif";
$export_icon["fo"] = "pdf-icon.gif";
$export_icon["pdf"] = "pdf-icon.gif";
$export_icon["html"] = "txt-icon.gif";
$export_icon["htm"] = "txt-icon.gif";
$export_icon["txt"] = "txt-icon.gif";
// weitere Icons und Formate k�nnen hier angef�gt werden

// PDF-Vorlagen f�r den Veranstaltungsexport (Index von 1 bis X)
// title = Beschreibung der Vorlage
// template = PDF-Vorlage in '/export'
$record_of_study_templates[1] = array("title" => "Allgemeine Druckvorlage", "template" =>"general_template.pdf");
$record_of_study_templates[2] = array("title" => "Studienbuch", "template" => "recordofstudy_template.pdf");

// EXPORT -->>



// <<-- LERNMODULE
// Zeichenkette, die vor Ilias-Usernamen gesetzt wird:
// IM LAUFENDEN BETRIEB NICHT MEHR �NDERN!!!
$username_prefix = "studip_";

// Zuordnung von Stud.IP-Status zu ILIAS-Status
// DEFAULT: 1 = Gast, 2 = Superuser, 3 = StudentIn, 4 = MitarbeiterIn
$ilias_status = array(
"user" => "1",
"autor" => "3",
"tutor" => "3",
"dozent" => "4",
"admin" => "2",
"root" => "2",
);

// Zuordnung von Stud.IP-Status zu ILIAS-System-Gruppe
// DEFAULT: 1 = AdministratorIn, 2 = AutorIn, 3 = LernerIn, 4 = Gast
$ilias_systemgroup = array(
"user" => "4",
"autor" => "2",
"tutor" => "2",
"dozent" => "2",
"admin" => "1",
"root" => "1",
);
?>
