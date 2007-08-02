<?
/**
* admin_aux.php - Zusatzangaben-Administration von Stud.IP.
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

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", user => "Seminar_User"));
$auth->login_if($auth->auth["uid"] == "nobody");
$perm->check("dozent");
if ($aux_rule_x && $aux_rule_y) {
	$list=TRUE;
	$new_session=TRUE;
}
include ("lib/seminar_open.php"); // initialise Stud.IP-Session

//var_dump($_REQUEST);

// -- here you have to put initialisations for the current page
require_once("lib/dates.inc.php"); // Funktionen zum Loeschen von Terminen
require_once("lib/datei.inc.php"); // Funktionen zum Loeschen von Dokumenten
require_once("lib/functions.php");
require_once("lib/visual.inc.php");
require_once("lib/classes/Table.class.php");
require_once("lib/classes/ZebraTable.class.php");
require_once("lib/classes/AuxLockRules.class.php");

// Start of Output
include ("lib/include/html_head.inc.php"); // Output of html head
include ("lib/include/header.php"); // Output of Stud.IP head

// most of the logic happens in links_admin
//
include ("lib/include/links_admin.inc.php"); //Linkleiste fuer admins

if (isset($SessSemName[1]) && (!$make_aux)) {
	$db7 = new DB_Seminar;
	$db7->query("SELECT aux_lock_rule, Name, Veranstaltungsnummer FROM seminare WHERE Seminar_id='".$SessSemName[1]."'");
	$db7->next_record();
	$aux_sem[$SessSemName[1]]=$db7->f("aux_lock_rule");
	$selected = 1;
	//echo $db7->f("aux_lock_rule");
}

// Get a database connection
$db = new DB_Seminar();
$rules = AuxLockRules::getAllLockRules();
//echo "<body>";
$containerTable=new ContainerTable();
echo $containerTable->headerRow("<b>&nbsp;" . _("Zusatzangaben ausw�hlen") . "</b>");
echo $containerTable->openRow();
echo $containerTable->openCell(array("colspan"=>"2"));

$contentTable=new ContentTable();
echo $contentTable->openCell();
$zt=new ZebraTable(array("width"=>"100%", "padding"=>"5"));
echo $zt->openHeaderRow();
echo $zt->cell("<b>"._("Nr.")."</b>",array("width"=>"5%"));
echo $zt->cell("<b>"._("Name")."</b>",array("width"=>"75%"));
echo $zt->cell("<b>"._("Template")."</b>",array("width"=>"20%"));
echo $zt->closeRow();

// a Seminar is selected!
if (isset($SessSemName[1]) && isset($selected)) {
	$form	 = 	"<form name=\"\" action=\"".$PHP_SELF."\">";
	$form	.=	"<input type=\"hidden\" name=\"make_aux\" value=1>";
	$form .=	"<select name=aux_sem[".$SessSemName[1]."]>";
	$form .= "<option value=\"null\">-- ". _("keine Zusatzangaben"). " --</option>";
	foreach ($rules as $id => $rule) {
		$form .= '<option value="'.$id.'"';
		if ($id == $db7->f("aux_lock_rule")) {
			$form .= " selected ";
		}
		$form .= ">".$rule["name"]."</option>";
	}
	$form	.=	"</select>";
	$form 	.=	"<input type=\"hidden\" name=\"aux_all\" value=\"-1\">";
	$form	.=	"<input type=\"IMAGE\" ".makeButton("zuweisen", "src")." border=0 align=\"absmiddle\" />";
	$form 	.=	"</form>";
	echo $zt->row(array($db7->f("Veranstaltungsnummer"), $db7->f("Name"), $form));

}

if (is_array($aux_sem) && (!$selected)) {
	foreach ($aux_sem as $key => $val) {
		$sql = "SELECT Veranstaltungsnummer, Name, aux_lock_rule FROM seminare WHERE seminar_id='".$key."'";
		$db->query($sql);
		if ($db->next_record()) {
				$rule = AuxLockRules::getLockRuleById($val);
				echo $zt->row(array($db->f("Veranstaltungsnummer"), $db->f("Name"), $rule["name"]));
				if ($make_aux) {
					if ($val == 'null') {
						$sql = "UPDATE seminare SET aux_lock_rule = NULL WHERE Seminar_id='".$key."'";
					} else {
						$sql = "UPDATE seminare SET aux_lock_rule='".$val."' WHERE Seminar_id='".$key."'";
					}
					$db->query($sql);
				}
		}
		else {
			echo $zt->row(array("&nbsp;", $db->f("Name"), "<font color=red>". _("�nderung fehlgeschlagen") . "</font>"));
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

