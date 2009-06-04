<?
# Lifter001: TEST
# Lifter002: TODO
/*
admin_statusgruppe.php - Statusgruppen-Verwaltung von Stud.IP.
Copyright (C) 2002 Ralf Stockmann <rstockm@gwdg.de>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", 'user' => "Seminar_User"));
	$auth->login_if($auth->auth["uid"] == "nobody");


$hash_secret = "dslkjjhetbjs";
include ('lib/seminar_open.php'); // initialise Stud.IP-Session

require_once ('lib/contact.inc.php');
require_once ('config.inc.php');
require_once ('lib/visual.inc.php');
require_once 'lib/functions.php';
require_once ('lib/statusgruppe.inc.php');
require_once ('lib/user_visible.inc.php');
#include ("calendar/calendar_links.inc.php");

$CURRENT_PAGE = _("Kontaktgruppen");

include ('lib/include/html_head.inc.php'); // Output of html head
include ('lib/include/header.php');   // Output of Stud.IP head
include('lib/include/links_sms.inc.php');

$cssSw = new cssClassSwitcher;									// Klasse f�r Zebra-Design
$cssSw->enableHover();

echo "\n" . $cssSw->GetHoverJSFunction() . "\n";
$cssSw->switchClass();

// Beginn Funktionsteil

// Hilfsfunktionen

function MovePersonStatusgruppe ($range_id, $AktualMembers="", $InstitutMembers="", $Freesearch="", $workgroup_mode=FALSE)
{
		while (list($key, $val) = each ($_POST)) {
			$statusgruppe_id = substr($key, 0, -2);
		}
		$db=new DB_Seminar;
		$db2=new DB_Seminar;
		$mkdate = time();
		if ($AktualMembers != "") {
			for ($i  = 0; $i < sizeof($AktualMembers); $i++) {
				$user_id = get_userid($AktualMembers[$i]);
				InsertPersonStatusgruppe ($user_id, $statusgruppe_id);
			}
		}
		if ($Freesearch != "") {
			for ($i  = 0; $i < sizeof($Freesearch); $i++) {
				$user_id = get_userid($Freesearch[$i]);
				$writedone = InsertPersonStatusgruppe ($user_id, $statusgruppe_id);
				if ($writedone==TRUE) {
					AddNewContact ($user_id);
				}
			}
		}
}


// Funktionen zur reinen Augabe von Statusgruppendaten


function PrintAktualStatusgruppen ($range_id, $view, $edit_id="")
{
	global $PHP_SELF, $_fullname_sql;
	$db=new DB_Seminar;
	$db2=new DB_Seminar;
	$db->query ("SELECT name, statusgruppe_id, size FROM statusgruppen WHERE range_id = '$range_id' ORDER BY position ASC");
	$AnzahlStatusgruppen = $db->num_rows();
	$i = 0;
	while ($db->next_record()) {
		$statusgruppe_id = $db->f("statusgruppe_id");
		$size = $db->f("size");
		echo "\n<table width=\"95%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\">";
		echo "\n\t<tr>";
		echo "\n\t\t<td width=\"5%\">";
		printf ("            	  <input type=\"IMAGE\" name=\"%s\" src=\"".$GLOBALS['ASSETS_URL']."images/move.gif\" border=\"0\" %s>&nbsp; </td>", $statusgruppe_id, tooltip(_("Markierte Personen dieser Gruppe zuordnen")));
		printf ("	          <td width=\"85%%\" class=\"%s\">&nbsp; %s </td><td class=\"%s\" width=\"5%%\"><a href=\"%s\"><img src=\"".$GLOBALS['ASSETS_URL']."images/einst.gif\" border=\"0\" %s></a></td>", ($edit_id == $statusgruppe_id?"topicwrite":"topic"), htmlReady($db->f("name")), ($edit_id == $statusgruppe_id?"topicwrite":"topic"), URLHelper::getLink($PHP_SELF."?edit_id=".$statusgruppe_id."&range_id=".$range_id."&view=".$view), tooltip(_("Gruppenname oder -gr��e anpassen")) );
		printf ("	          <td width=\"5%%\"><a href=\"%s\"><img src=\"".$GLOBALS['ASSETS_URL']."images/trash_att.gif\" width=\"11\" height=\"17\" border=\"0\" %s></a></td>", URLHelper::getLink($PHP_SELF."?cmd=verify_remove_statusgruppe&statusgruppe_id=".$statusgruppe_id."&range_id=".$range_id."&view=".$view."&name=".$db->f("name")), tooltip(_("Gruppe mit Personenzuordnung entfernen")));
		echo 	"\n\t</tr>";

		$db2->query ("SELECT statusgruppe_user.user_id, " . $_fullname_sql['full'] . " AS fullname , username FROM statusgruppe_user LEFT JOIN auth_user_md5 USING(user_id) LEFT JOIN user_info USING (user_id) WHERE statusgruppe_id = '$statusgruppe_id'");
		$k = 1;
		while ($db2->next_record()) {
			if (get_visibility_by_id($db2->f("user_id"))) {
				if ($k > $size) {
					$farbe = "#AAAAAA";
				} else {
					$farbe = "#000000";
				}
				if ($k % 2) {
					$class="steel1";
				} else {
					$class="steelgraulight";
				}
				printf ("\n\t<tr>\n\t\t<td><font color=\"%s\">$k</font></td>", $farbe);
				printf ("<td class=\"%s\" colspan=\"2\"><font size=\"2\">%s</font></td>",$class, htmlReady($db2->f("fullname")));
				printf ("<td><a href=\"%s\"><img src=\"".$GLOBALS['ASSETS_URL']."images/trash.gif\" width=\"11\" height=\"17\" border=\"0\" %s></a></td>", URLHelper::getLink($PHP_SELF.'?cmd=remove_person&statusgruppe_id='.$statusgruppe_id.'&username='.$db2->f("username").'&range_id='.$range_id.'&view='.$view), tooltip(_("Person aus der Gruppe entfernen")));
				echo "\n\t</tr>";
				$k++;
			}
		}
		while ($k <= $db->f("size")) {
			echo "\n\t<tr>\n\t\t<td><font color=\"#FF4444\">$k</font></td>";
			printf ("<td class=\"blank\" colspan=\"3\">&nbsp; </td>");
			echo "\n\t</tr>";
			$k++;
		}
		$i++;
		echo "</table>";
		if ($i < $AnzahlStatusgruppen) {
			printf ("<p align=\"center\"><a href=\"%s\"><img src=\"".$GLOBALS['ASSETS_URL']."images/move_up.gif\"  vspace=\"1\" width=\"13\" height=\"11\" border=\"0\"  %s><img src=\"".$GLOBALS['ASSETS_URL']."images/move_down.gif\" vspace=\"1\" width=\"13\" height=\"11\" border=\"0\" %s></a><br>&nbsp;", URLHelper::getLink($PHP_SELF.'?cmd=swap&statusgruppe_id='.$statusgruppe_id.'&range_id='.$range_id.'&view='.$view), tooltip(_("Gruppenreihenfolge tauschen")), tooltip(_("Gruppenreihenfolge tauschen")));
		}
	}
}

function PrintSearchResults ($search_exp, $range_id)
{
	global $SessSemName, $_fullname_sql;
	$db=new DB_Seminar;
	$query = "SELECT DISTINCT auth_user_md5.user_id, " . $_fullname_sql['full_rev'] ." AS fullname, username, perms ".
	"FROM auth_user_md5 LEFT JOIN user_info USING (user_id) LEFT JOIN user_inst ON user_inst.user_id=auth_user_md5.user_id AND Institut_id = '$inst_id' ".
	"WHERE perms !='root' AND perms !='admin' AND perms !='user' AND (user_inst.inst_perms = 'user' OR user_inst.inst_perms IS NULL) ".
	"AND (Vorname LIKE '%$search_exp%' OR Nachname LIKE '%$search_exp%' OR username LIKE '%$search_exp%') ORDER BY Nachname ";
	$db->query($query); // results all users which are not in the seminar
	if (!$db->num_rows()) {
		echo "&nbsp; " . _("keine Treffer") . "&nbsp; ";
	} else {
		$c = 0;
		$tmp = "&nbsp; <select name=\"Freesearch[]\" size=\"4\" >";
		while ($db->next_record()) {
			if (get_visibility_by_id($db->f("user_id"))) {
				$c++;
				$tmp .= sprintf ("<option value=\"%s\">%s - %s\n", $db->f("username"), htmlReady(my_substr($db->f("fullname"),0,35)." (".$db->f("username").")"), $db->f("perms"));
			}
		}
		$tmp .= "</select>";
		if ($c > 0) {
			echo $tmp;
		} else {
			echo "&nbsp; " . _("keine Treffer") . "&nbsp; ";
		}
	}
}

function PrintAktualContacts ($range_id)
{
	global $_fullname_sql;
	$bereitszugeordnet = GetAllSelected($range_id);
	echo "<font size=\"-1\">&nbsp; " . _("Personen im Adressbuch") . "</font><br>";
	$query = "SELECT contact.user_id, username, " . $_fullname_sql['full_rev'] ." AS fullname, perms FROM contact LEFT JOIN auth_user_md5 USING(user_id) LEFT JOIN user_info USING (user_id)  WHERE owner_id = '$range_id' ORDER BY Nachname ASC";
	echo "&nbsp; <select size=\"10\" name=\"AktualMembers[]\" multiple>";
	$db=new DB_Seminar;
	$db->query ($query);
	while ($db->next_record()) {
		if (get_visibility_by_id($db->f("user_id"))) {
			if (in_array($db->f("user_id"), $bereitszugeordnet)) {
				$tmpcolor = "#777777";
			} else {
				$tmpcolor = "#000000";
			}
			printf ("<option style=\"color:%s;\" value=\"%s\">%s - %s\n", $tmpcolor, $db->f("username"), htmlReady(my_substr($db->f("fullname"),0,35)." (".$db->f("username").")"), $db->f("perms"));
		}
	}
	echo "</select>";
}


// Ende Funktionen

// fehlende Werte holen

	// alles ist userbezogen:

	$range_id = $user->id;;

// Abfrage der Formulare und Aktionen

	// neue Statusgruppe hinzufuegen

	if (($cmd=="add_new_statusgruppe") && ($new_statusgruppe_name != "")) {
		if (Statusgruppe::countByName($new_statusgruppe_name, $range_id) > 0) {
			$msgs[] = 'info�' . sprintf(_("Die Gruppe %s wurde hinzugef�gt, es gibt jedoch bereits ein Gruppe mit demselben Namen!"), '<b>'. htmlReady($new_statusgruppe_name) .'</b>');
		} else {
			$msgs[] = 'msg�' . sprintf(_("Die Gruppe %s wurde hinzugef�gt!"), '<b>'. htmlReady($new_statusgruppe_name) .'</b>');
		}

		AddNewStatusgruppe ($new_statusgruppe_name, $range_id, $new_statusgruppe_size);
	}

	// bestehende Statusgruppe editieren

	if (($cmd=="edit_existing_statusgruppe") && ($new_statusgruppe_name != "")) {
		EditStatusgruppe ($new_statusgruppe_name, $new_statusgruppe_size, $update_id);
	}

	// zuordnen von Personen zu einer Statusgruppe
	if ($cmd=="move_person" && ($AktualMembers !="" || $InstitutMembers !="---" || $Freesearch !=""))  {
		MovePersonStatusgruppe ($range_id, $AktualMembers, $InstitutMembers, $Freesearch, $workgroup_mode);
	}

	// Entfernen von Personen aus einer Statusgruppe

	if ($cmd=="remove_person") {
		RemovePersonStatusgruppe ($username, $statusgruppe_id);
	}

	// Entfernen von Statusgruppen

	if ($cmd=="verify_remove_statusgruppe") {
		$msg = sprintf(_('M�chten Sie wirklich die Kategorie **%s** l�schen?'), $name); 
		echo createQuestion($msg, array('cmd' => 'remove_statusgruppe', "statusgruppe_id" => $statusgruppe_id, 'range_id' => $range_id)); 
	}

    if ($cmd=="remove_statusgruppe") {
		
		DeleteStatusgruppe ($statusgruppe_id);
	}
	// Aendern der Position

	if ($cmd=="swap") {
		SwapStatusgruppe ($statusgruppe_id);
	}


// Ende Abfrage Formulare



// Beginn Darstellungsteil

// Anfang Edit-Bereich
?>
<table class="blank" width="100%" border="0" cellspacing="0">
<?
if (is_array($msgs)) {
	foreach ($msgs as $msg) {
		parse_msg($msg);
	}
}
?>
  <tr>
    <td align="right" width="50%" class="blank"></td>
    <td align="right" width="50%" class="blank" nowrap="nowrap">
<?
	if ($cmd!="edit_statusgruppe") { // normale Anzeige
?>
		<form action="<? echo URLHelper::getLink('?cmd=add_new_statusgruppe') ?>" method="post">
		<?
	  	  echo"<input type=\"HIDDEN\" name=\"range_id\" value=\"$range_id\">";
  	      	  echo"<input type=\"HIDDEN\" name=\"view\" value=\"$view\">";
	  	?>
	        <font size="2"><?=_("Adressbuchgruppe anlegen:")?> </font>
	        <input type="text" name="new_statusgruppe_name" style="vertical-align:middle" value="<?=_("Gruppenname")?>">
	        &nbsp; &nbsp; &nbsp; <b><?=_("Einf&uuml;gen")?></b>&nbsp;
	        <?
	    	printf ("<input type=\"IMAGE\" name=\"add_new_statusgruppe\" src=\"".$GLOBALS['ASSETS_URL']."images/move_down.gif\" border=\"0\" value=\" %s \" %s>&nbsp;  &nbsp; &nbsp; ", _("neue Statusgruppe"), tooltip(_("neue Gruppe anlegen")));
	    	?>
	      </form>
<?
	} else { // editieren einer bestehenden Statusgruppe
?>
		<form action="<? echo URLHelper::getLink('?cmd=edit_existing_statusgruppe') ?>" method="POST">
		<?
		$db = new DB_Seminar("SELECT name, size FROM statusgruppen WHERE statusgruppe_id = '$edit_id'");
		if ($db->next_record()) {
			$gruppe_name = $db->f("name");
		}
	  	  echo"<input type=\"HIDDEN\" name=\"range_id\" value=\"$range_id\">";
  	  	  echo"<input type=\"HIDDEN\" name=\"update_id\" value=\"$edit_id\">";
	    	  echo"<input type=\"HIDDEN\" name=\"view\" value=\"$view\">";
	  	?>
	        <font size="2"><?=_("neuer Gruppenname:")?> </font>
	        <input type="text" name="new_statusgruppe_name" style="vertical-align:middle" value="<? echo htmlReady($gruppe_name);?>">
	        &nbsp; &nbsp; &nbsp; <b><?=_("&Auml;ndern")?></b>&nbsp;
	        <?
	    	printf ("<input type=\"IMAGE\" name=\"add_new_statusgruppe\" src=\"".$GLOBALS['ASSETS_URL']."images/move_down.gif\" border=\"0\" value=\" %s \" %s>&nbsp;  &nbsp; &nbsp; ", _("Gruppe anpassen"), tooltip(_("Gruppe anpassen")));
	    	?>
	      </form>
<?
	}
?>

      <br></td>
  </tr>
</table><?
// Ende Edit-Bereich

// Anfang Personenbereich
$db = new DB_Seminar();
$db->query ("SELECT name, statusgruppe_id, size FROM statusgruppen WHERE range_id = '$range_id' ORDER BY position ASC");
if ($db->num_rows()>0) {   // haben wir schon Gruppen? dann Anzeige
	?>
<form action="<? echo URLHelper::getLink('?cmd=move_person') ?>" method="post">
<table width="100%" border="0" cellspacing="0">
	<tr>
		<td class="steel1" valign="top" width="50%">
		<br>
<? 		echo"<input type=\"HIDDEN\" name=\"range_id\" value=\"$range_id\">\n";
	  	echo"<input type=\"HIDDEN\" name=\"view\" value=\"$view\">\n";
	if ($db->num_rows() > 0)
	{
		$nogroups = 1;
		PrintAktualContacts ($range_id);

		?>
       	<br><br>
		<?
		if ($search_exp) {

			$search_exp = str_replace("%","\%",$search_exp);
			$search_exp = str_replace("_","\_",$search_exp);
			if (strlen(trim($search_exp))<3) {
				echo "&nbsp; <font size=\"-1\">"._("Ihr Suchbegriff muss mindestens 3 Zeichen umfassen!");
				echo "<br><br><font size=\"-1\">&nbsp; " . _("freie Personensuche (wird in Adressbuch �bernommen)") . "</font><br>";
				echo "&nbsp; <input type=\"text\" name=\"search_exp\" value=\"\">";
				printf ("<input type=\"IMAGE\" name=\"search\" src= \"".$GLOBALS['ASSETS_URL']."images/suchen.gif\" border=\"0\" value=\" %s \" %s>&nbsp;  ", _("Person suchen"), tooltip(_("Person suchen")));
			} else {
				PrintSearchResults($search_exp, $range_id);
				printf ("<input type=\"IMAGE\" name=\"search\" src= \"".$GLOBALS['ASSETS_URL']."images/rewind.gif\" border=\"0\" value=\" %s \" %s>&nbsp;  ", _("neue Suche"), tooltip(_("neue Suche")));
			}
		} else {
			echo "<font size=\"-1\">&nbsp; " . _("freie Personensuche (wird in Adressbuch &uuml;bernommen)") . "</font><br>";
			echo "&nbsp; <input type=\"text\" name=\"search_exp\" value=\"\">";
			printf ("<input type=\"IMAGE\" name=\"search\" src= \"".$GLOBALS['ASSETS_URL']."images/suchen.gif\" border=\"0\" value=\" %s \" %s>&nbsp;  ", _("Person suchen"), tooltip(_("Person suchen")));
		}
	} ?>
		<br><br>
    	</td>
    	<td class="blank" width="50%" align="center" valign="top">
<? // Ende Personen-Bereich

	// Anfang Gruppenuebersicht
	PrintAktualStatusgruppen ($range_id, $view, $edit_id);
?>
		<br>
		</td>
	</tr>
</table>
</form>
<?
} else { // es sind noch keine Gruppen angelegt, daher Infotext
?>
<table class="blank" width="100%" border="0" cellspacing="0">
    <?

	if (get_config("EXTERNAL_HELP")) {
		$help_url=format_help_url("Basis.VeranstaltungenVerwaltenGruppen");
	} else {
		$help_url="help/index.php?help_page=admin_statusgruppe.php";
	}
  	parse_msg("info�"
		. _("Es sind noch keine Gruppen oder Funktionen angelegt worden.") . "<br>"
		. _("Um f&uuml;r diesen Bereich Gruppen oder Funktionen anzulegen, nutzen Sie bitte die obere Zeile!")
  	. "<br><br>"
		. _("Wenn Sie Gruppen angelegt haben, k&ouml;nnen Sie diesen Personen zuordnen. Jeder Gruppe k&ouml;nnen beliebig viele Personen zugeordnet werden. Jede Person kann beliebig vielen Gruppen zugeordnet werden.")
  	. "<br><br>"
		. sprintf(_("Lesen Sie weitere Bedienungshinweise in der %sHilfe%s nach!"),"<a href=\"".$help_url."\">", "</a>")
  	. "�");
    ?>
</table>
<?php
}
// Ende Gruppenuebersicht

	include ('lib/include/html_end.inc.php');
	page_close();
?> 
