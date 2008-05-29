<?php
# Lifter002: 
// vim: noexpandtab
/*
 * admin_lock.php - Sichtbarkeits-Administration von Stud.IP.
 * Copyright (C) 2002 Cornelis Kater <ckater@gwdg.de>, data-quest <info@data-quest.de>, (C) 2003 Mark Sievers <mark_sievers2000@yahoo.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth",
                "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$auth->login_if($auth->auth["uid"] == "nobody");
$perm->check("admin");
if ($general_lock_x && $general_lock_y) {
	$list=TRUE;
	$new_session=TRUE;
	$message = 'info�' . _("Diese Daten sind noch nicht gespeichert.");
}

include ("lib/seminar_open.php"); // initialise Stud.IP-Session

// -- here you have to put initialisations for the current page
require_once("lib/dates.inc.php"); // Funktionen zum Loeschen von Terminen
require_once("lib/datei.inc.php"); // Funktionen zum Loeschen von Dokumenten
require_once("lib/functions.php");
require_once("lib/visual.inc.php");
require_once("lib/classes/Table.class.php");
require_once("lib/classes/ZebraTable.class.php");
require_once("lib/classes/LockRules.class.php");

// Start of Output
include ("lib/include/html_head.inc.php"); // Output of html head
include ("lib/include/header.php"); // Output of Stud.IP head

// most of the logic happens in links_admin
//
include ("lib/include/links_admin.inc.php"); //Linkleiste fuer admins

if (isset($SessSemName[1]) && (!$make_lock)) {
	$stmt = DBManager::get()->prepare(
	  "SELECT lock_rule, Name, Veranstaltungsnummer ".
	  "FROM seminare WHERE Seminar_id=?");
	$stmt->execute(array($SessSemName[1]));
	$seminar_row = $stmt->fetch();
	$lock_sem[$SessSemName[1]] = $seminar_row["lock_rule"];
	$selected = 1;
}
// # Get a database connection
$lock_rules = new LockRules();
$all_lock_rules = $lock_rules->getAllLockRules();

//echo "<body>";
$containerTable=new ContainerTable();
echo $containerTable->headerRow("<b>&nbsp;" . _("Sperren von Veranstaltungen") . "</b>");
echo $containerTable->openRow();
echo $containerTable->openCell(array("colspan"=>"2"));

$contentTable=new ContentTable();
echo $contentTable->openCell();
$zt=new ZebraTable(array("width"=>"100%", "padding"=>"5"));
echo $zt->openHeaderRow();
echo $zt->cell("<b>"._("Nr.")."</b>",array("width"=>"5%"));
echo $zt->cell("<b>"._("Name")."</b>",array("width"=>"75%"));
echo $zt->cell("<b>"._("Sperrebene")."</b>",array("width"=>"20%"));
echo $zt->closeRow();

// a Seminar is selected!
if (isset($SessSemName[1]) && isset($selected)) {
	$form	 = 	"<form name=\"\" action=\"".$PHP_SELF."\">";
	$form	.=	"<input type=\"hidden\" name=\"make_lock\" value=1>";
	$form 	.=	"<select name=lock_sem[".$SessSemName[1]."]>";
	for ($i=0;$i<count($all_lock_rules);$i++) {
		$form .= "<option value=".$all_lock_rules[$i]["lock_id"]."";
		if ($all_lock_rules[$i]["lock_id"]==$seminar_row["lock_rule"]) {
			$form .= " selected ";
		}
		$form .= ">".$all_lock_rules[$i]["name"]."</option>";
	}
	$form	.=	"</select>";
	$form 	.=	"<input type=\"hidden\" name=\"lock_all\" value=\"-1\">";
	$form	.=	"<input type=\"IMAGE\" ".makeButton("zuweisen", "src")." border=0 align=\"absmiddle\" />";
	$form 	.=	"</form>";
	echo $zt->row(array($seminar_row["Veranstaltungsnummer"],
	                    $seminar_row["Name"],
	                    $form));

}

if (is_array($lock_sem) && !$selected) {
	$db = DBManager::get();

	$stmt = $db->prepare("SELECT Veranstaltungsnummer, Name, lock_rule ".
	                     "FROM seminare WHERE seminar_id=?");

	$stmt_lock1 = $db->prepare("UPDATE seminare SET lock_rule=? ".
	                           "WHERE Seminar_id=?");

	$stmt_lock2 = $db->prepare("UPDATE seminare SET lock_rule=NULL ".
	                           "WHERE Seminar_id=?");

	while (list($key, $val) = each($lock_sem)) {

		$stmt->execute(array($key));
		if ($row = $stmt->fetch()) {

			$rule = $lock_rules->getLockRule($val);
			echo $zt->row(array($row["Veranstaltungsnummer"],
			                    $row["Name"],
			                    $rule["name"]));
			if ($make_lock) {
				if ($val != 'none') {
					$stmt_lock1->execute(array($val, $key));
				} else {
					$stmt_lock2->execute(array($key));
				}
			}
		}
		else {
			echo $zt->row(array("&nbsp;",
			                    $row["Name"],
			                    "<font color=red>".
			                    _("�nderung fehlgeschlagen").
			                    "</font>"));
		}
	}
}
echo $zt->close();
echo $contentTable->close();

echo $containerTable->blankRow();
echo $containerTable->close();
echo "</body>";
echo "</html>";
page_close();

