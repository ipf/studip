<?
/**
* support.php
* 
* The startscript for the SupportDB module
*
* @author		Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version		$Id$
* @access		public
* @package		support
* @modulegroup		support_modules
* @module		support.php
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// support.php
// Startscript fuer SupportDB-Modul von Stud.IP
// Copyright (C) 2002 Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>, 
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

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$perm->check("user"); 

include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

if ($SUPPORT_ENABLE) {
	//Steuerung der Supportverwaltung einbinden
	include ("$RELATIVE_PATH_SUPPORT/support.inc.php");
} else {
	// Start of Output
	include ("$RELATIVE_PATH_SUPPORT/html_head.inc.php"); // Output of html head
	include ("$RELATIVE_PATH_SUPPORT/header.php");   // Output of Stud.IP head
	require_once ($ABSOLUTE_PATH_STUDIP."msg.inc.php");
	parse_window ("error�" . _("Das SupportDB-Modul ist nicht eingebunden. Bitte aktivieren Sie es in den Systemeinstellungen oder wenden Sie sich an die Systemadministratoren."), "�",
				_("SupportDB nicht eingebunden"));
}
?>
