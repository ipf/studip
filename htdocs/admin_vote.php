<?php
// +--------------------------------------------------------------------------+
// This file is part of Stud.IP
// admin_vote.php
//
// Show the admin pages
//
// +--------------------------------------------------------------------------+
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or any later version.
// +--------------------------------------------------------------------------+
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// +--------------------------------------------------------------------------+


/**
 * admin_vote.php
 *
 *
 * @author	Michael Cohrs <michael@cohrs.de>
 * @version	10. Juni 2003
 * @access	public
 * @package	vote
 */

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth",
		"perm" => "Seminar_Perm", "user" => "Seminar_User"));
//$perm->check("admin");

if ($page == "edit")
	include($ABSOLUTE_PATH_STUDIP . "/vote/vote_edit.inc.php");
else
	include($ABSOLUTE_PATH_STUDIP . "/vote/vote_overview.inc.php");

//else {
// Start of Output
//include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
//include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head
//require_once ($ABSOLUTE_PATH_STUDIP."msg.inc.php");
//parse_window ("error" . _("Die Verwaltung externer Seiten ist nicht eingebunden. Bitte aktivieren Sie diese in den Systemeinstellungen, oder wenden Sie sich an den Systemadministrator."), "",
//	 		  _("Modul \"externe Seiten\" nicht eingebunden"));


page_close();

?>

