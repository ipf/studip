<?
/**
* Export-mainfile. Calls the submodules.
* 
* 
*
* @author		Arne Schroeder <schroeder@data.quest.de>
* @version		$Id$
* @access		public
* @modulegroup	export_modules
* @module		export
* @package		Export
*/
/**
* workaround for PHPDoc
*
* Use this if module contains no elements to document !
* @const PHPDOC_DUMMY
*/
define("PHPDOC_DUMMY",true);
// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// export.php
//
// Copyright (c) 2002 Arne Schroeder <schroeder@data-quest.de> 
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

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", user => "Seminar_User"));
$perm->check("tutor");

include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

//$i_page = "meine_seminare.php";
$EXPORT_ENABLE = TRUE;
$PATH_EXPORT = "export";
// -- here you have to put initialisations for the current page

require_once ($ABSOLUTE_PATH_STUDIP."/visual.inc.php");
require_once ($ABSOLUTE_PATH_STUDIP."/functions.php");
require_once ($ABSOLUTE_PATH_STUDIP."/msg.inc.php");

require_once ("$ABSOLUTE_PATH_STUDIP$PATH_EXPORT" . "/export_config.inc.php");

if ($EXPORT_ENABLE)
{
	// Zurueckbutton benutzt?
	if (isset($back))
	{
		if ($o_mode == "choose")
		{
			if ($page == 4) 
			{
				if ($skip_page_3)
					$page = 1;
				else
					$page = 2;
			}
			elseif ($page>1) 
				$page = $page-2;
			else
			{
				unset($xml_file_id);
				unset($page);
				$o_mode= "start";
			}
		}
	}
	if (($o_mode != "passthough") AND ($o_mode != "direct"))
	{
		?> <script LANGUAGE="JavaScript">
		function export_start()
		{
			msg_window=window.open("","messagewindow","height=250,width=200,left=20,top=20,scrollbars=no,resizable=no,toolbar=no");
			msg_window.document.write("<html><head><title>Daten-Export</title></head>");
			msg_window.document.write("<body bgcolor='#ffffff'><center><p><img src='pictures/alienupload.gif' width='165' height='125'></p>");
			msg_window.document.write("<p><font face='arial, helvetica, sans-serif'><b>&nbsp;Die Daten werden exportiert.<br>&nbsp;Bitte haben sie etwas Geduld!<br /></font></p></body></html>");
		}
		function export_end()
		{
			msg_window.close();
		}
		
		</script>
		<body onUnLoad="export_end()">
		<?
	}

	if ((!isset($range_id) AND !isset($xml_file_id) AND !isset($o_mode) AND !isset($ex_type)) OR ($o_mode == "start"))
	{
		include($ABSOLUTE_PATH_STUDIP ."" . $PATH_EXPORT . "/export_start.inc.php");
		$start_done = true;
	}

	if (($page==2) AND $XSLT_ENABLE AND $skip_page_3) 
		$page=3;
	
	//Exportmodul einbinden
	if (($range_id != "") AND (!isset($xml_file_id)))
	{
		include($ABSOLUTE_PATH_STUDIP ."" . $PATH_EXPORT . "/export_xml.inc.php");
		if ($export_error_num < 1)
			$xml_output_done = true;
	}
	
	if (($xml_file_id != "") AND ($page != 3) AND ($o_mode == "choose") AND ($export_error_num < 1))
	{
		include($ABSOLUTE_PATH_STUDIP ."" . $PATH_EXPORT . "/export_choose_xslt.inc.php");
		if ($export_error_num < 1)
			$xslt_choose_done = true;
	}
	
	if ( ($choose != "") AND ($format != "") AND ($XSLT_ENABLE) AND ($export_error_num==0) AND
		( ($o_mode == "processor") OR ($o_mode == "passthrough") OR ($page == 3) ) )
	{
		include($ABSOLUTE_PATH_STUDIP ."" . $PATH_EXPORT . "/export_run_xslt.inc.php");
		if ($export_error_num < 1)
			$xslt_process_done = true;
	}
	
	if (($export_error_num < 1) AND ($xslt_process_done) AND ($format == "fo"))
		include($ABSOLUTE_PATH_STUDIP ."" . $PATH_EXPORT . "/export_run_fop.inc.php");
	if (($export_error_num < 1) AND (!$start_done) AND ((!$xml_output_done) OR ($o_mode != "file")) AND (!$xslt_choose_done) AND (!$xslt_process_done))
	{
		$export_pagename = "Exportmodul - Fehler!";
		$export_error = _("Fehlerhafter Seitenaufruf");
		$infobox = array(			
		array ("kategorie"  => "Information:",
			"eintrag" => array	(	
							array (	"icon" => "pictures/ausruf_small.gif",
									"text"  => _("Die Parameter, mit denen diese Seite aufgerufen wurde, sind fehlerhaft.")
								 )
							)
			)
		);
	}
	
	include($ABSOLUTE_PATH_STUDIP ."" . $PATH_EXPORT . "/export_view.inc.php");
}
else 
{
	// Start of Output
	include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
	include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head
	parse_window ("error�" . _("Das Exportmodul ist nicht eingebunden. Damit Daten im XML-Format exportiert werden k&ouml;nnen, muss das Exportmodul in den Systemeinstellungen freigeschaltet werden. Wenden Sie sich bitte an den Administrator."), "�",
				_("Exportmodul nicht eingebunden"));
}
page_close();
?>
