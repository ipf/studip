<?
/*
contact.php - 0.8
Bindet Adressbuch ein.
Copyright (C) 2003 Ralf Stockmann <rstockm@uni-goettingen.de>

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

// Default_Auth
page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$perm->check("user");

	if ($forum["jshover"]==1 AND $auth->auth["jscript"]) { // JS an und erwuenscht?
		echo "<script language=\"JavaScript\">";
		echo "var ol_textfont = \"Arial\"";
		echo "</script>";
		echo "<DIV ID=\"overDiv\" STYLE=\"position:absolute; visibility:hidden; z-index:1000;\"></DIV>";
		echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"overlib.js\"></SCRIPT>";
	}

include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

// -- here you have to put initialisations for the current page

require_once ("$ABSOLUTE_PATH_STUDIP/functions.php");
require_once ("$ABSOLUTE_PATH_STUDIP/statusgruppe.inc.php");
require_once ("$ABSOLUTE_PATH_STUDIP/contact.inc.php");
require_once ("$ABSOLUTE_PATH_STUDIP/visual.inc.php");

$cssSw = new cssClassSwitcher;									// Klasse f�r Zebra-Design
$cssSw->enableHover();

include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head


echo "\n" . $cssSw->GetHoverJSFunction() . "\n";
$cssSw->switchClass();

// include "links_openobject.inc.php";

$sess->register("contact");

if (!$contact["filter"])
	$contact["filter"]="all";
if ($view) {
	$contact["view"]=$view;
}
if (!$contact["view"])
	$contact["view"]="alpha";

if ($filter) {
	$contact["filter"]=$filter;
}
$filter = $contact["filter"];

if ($filter == "all")
	$filter="";
if ($contact["view"]=="alpha" && strlen($filter) > 3)
	$filter="";
if ($contact["view"]=="gruppen" && strlen($filter) < 4)
	$filter="";

include ("$ABSOLUTE_PATH_STUDIP/links_sms.inc.php");

// Aktionen //////////////////////////////////////

// adding a contact via search

if ($Freesearch) {
	$open = AddNewContact(get_userid($Freesearch));
}

// deletel a contact

if ($cmd == "delete") {
	DeleteContact ($contact_id);
}

// remove from buddylist

if ($cmd == "changebuddy") {
	changebuddy($contact_id);
	if (!$open) {
		$open = $contact_id;
	}
}

// delete a single userinfo

if ($deluserinfo) {
	DeleteUserinfo ($deluserinfo);
}

if ($move) {
	MoveUserinfo ($move);
}

// add a new userinfo

if ($owninfolabel AND ($owninfocontent[0]!=_("Inhalt"))){
	AddNewUserinfo ($edit_id, $owninfolabel[0], $owninfocontent[0]);
}

if ($existingowninfolabel) {
	for ($i=0; $i<sizeof($existingowninfolabel); $i++) {
		UpdateUserinfo ($existingowninfolabel[$i], $existingowninfocontent[$i], $userinfo_id[$i]);
	}
}



?>
<table width = "100%" cellspacing="0" border="0" cellpadding="0"><tr>
	<td class="topic" colspan="2" width = "100%"><img src="pictures/nutzer.gif" border="0" align="texttop"><b>&nbsp; <?echo _("Mein Adressbuch");?> <font size="2">(<?=(($size_of_book = GetSizeofBook()) == 1) ? _("1 Eintrag") : sprintf(_("%d Eintr&auml;ge"),$size_of_book);?>)</font></b>
	</td>
</tr><tr><td class="blank" align="left" valign="absmiddle">

	<form action="<? echo $PHP_SELF ?>?cmd=search#anker" method="POST"><?

if ($open != "all" && $size_of_book>0) {
	echo "&nbsp; <a href=\"$PHP_SELF?open=all&filter=$filter\"><img src=\"pictures/forumgraurunt.gif\" border=\"0\">&nbsp; <font size=\"2\">"._("Alle aufklappen")."</font></a></td>";
} elseif ($size_of_book>0) {
	echo "&nbsp; <a href=\"$PHP_SELF?filter=$filter\"><img src=\"pictures/forumgraurauf.gif\" border=\"0\">&nbsp; <font size=\"2\">"._("Alle zuklappen")."</font></a></td>";
}

echo "<td class=\"blank\" align=\"right\">";

if ($search_exp) {
	$search_exp = trim($search_exp);
	if (SearchResults($search_exp)) {
		printf ("<input type=\"IMAGE\" name=\"addsearch\" src=\"pictures/move_down.gif\" border=\"0\" value=\"" . _("In Adressbuch eintragen") . "\" %s>&nbsp;  ", tooltip(_("In Adressbuch eintragen")));
		echo SearchResults($search_exp);
	} else {
		echo "&nbsp; <font size=\"2\">"._("keine Treffer zum Suchbegriff:")."</font><b>&nbsp; $search_exp&nbsp; </b>";
	}
	printf ("<a href=\"$PHP_SELF\"><img src= \"./pictures/rewind.gif\" border=\"0\" value=\"" . _("neue Suche") . "\" %s>", tooltip(_("neue Suche")));
} else {
	echo "<font size=\"2\" color=\"#555555\">". _("Person zum Eintrag in das Adressbuch suchen:")."</font>&nbsp; <input type=\"text\" name=\"search_exp\" value=\"\">";
	printf ("<input type=\"IMAGE\" name=\"search\" src= \"./pictures/suchen.gif\" border=\"0\" value=\"" . _("Personen suchen") . "\" %s>&nbsp;  ", tooltip(_("Person suchen")));
} 
echo "</form></td></tr>";

if ($sms_msg)	{
	parse_msg (rawurldecode($sms_msg));
	}
?>
</table>
<?


echo "<table align=\"center\" class=\"grey\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td align=\"middle\" class=\"lightgrey\">";


if (($contact["view"])=="alpha") {
	echo "<table align=\"center\" width=\"70%\"><tr>";
	if (!$filter) {
		$cssSw->switchClass();
	}
	echo "<td width=\"5%\" align=\"center\" valign=\"center\" ".$cssSw->getHover()." class=\"".$cssSw->getClass()."\" "
		. tooltip(($size_of_book == 1) ? _("1 Eintrag") : sprintf(_("%d Eintr�ge"),$size_of_book),false)
		."><a href=\"$PHP_SELF?filter=all\">a-z</a>"
		."</td>";
	if (!$filter) {
		$cssSw->switchClass();
	}
	$size_of_book_by_letter = GetSizeOfBookByLetter();
	for ($i=97;$i<123;$i++) {
		if ($filter==chr($i)) {
			$cssSw->switchClass();
		}
		if ($size_of_book_by_letter[chr($i)]==0) {
			$character = "<font color=\"#999999\">".chr($i)."</font>";
		} elseif($filter==chr($i)) {
			$character = "<font color=\"#FF0000\">".chr($i)."</font>";
		} else {
			$character = chr($i);
		}
		echo "<td width=\"3%\"  align=\"center\" valign=\"center\" ".$cssSw->getHover()." class=\"".$cssSw->getClass()."\""
		. tooltip(($size_of_book_by_letter[chr($i)] == 1) ? _("1 Eintrag") : (($size_of_book_by_letter[chr($i)] > 1 ) ? sprintf(_("%d Eintr�ge"),$size_of_book_by_letter[chr($i)]) : _("keine Eintr�ge")),false)
		."><a href=\"$PHP_SELF?filter=".chr($i)."\" "
		. ">".$character."</a>"
		."</td>";
		if ($filter==chr($i)) {
			$cssSw->switchClass();
		}
	}
	echo "</tr></table>";
}

if (($contact["view"])=="gruppen") {
	echo "<table align=\"center\" ><tr>";
	if (!$filter) {
		$cssSw->switchClass();
	}
	echo "<td ".$cssSw->getHover()." class=\"".$cssSw->getClass()."\">&nbsp; "
		."<a href=\"$PHP_SELF?filter=all\"><font size=\"2\">" . _("Alle Gruppen") . "</font></a>"
		."&nbsp; <a href=\"contact_export.php?groupid=all\"><img src=\"pictures/vcardexport.gif\" border=\"0\" ".tooltip(_("Alle Eintr�ge als vCard exportieren"))."></a>&nbsp; </td>";
	if (!$filter) {
		$cssSw->switchClass();
	}
	$owner_id = $user->id;
	$db=new DB_Seminar;
	$db->query ("SELECT name, statusgruppe_id FROM statusgruppen WHERE range_id = '$owner_id' ORDER BY position ASC");	
	while ($db->next_record()) {
		if ($filter==$db->f("statusgruppe_id")) {
			$cssSw->switchClass();
			$color = "color=\"#FF0000\"";
			$maillink = "&nbsp; <a href=\"sms_send.php?sms_source_page=contact.php&group_id=$filter\"><img src=\"pictures/nachrichtsmall.gif\" valign=\"bottom\" border=\"0\"".tooltip(_("Nachricht an alle Personen dieser Gruppe schicken"))."></a>";
			$maillink .= "&nbsp; <a href=\"contact_export.php?groupid=".$db->f("statusgruppe_id")."\"><img src=\"pictures/vcardexport.gif\" border=\"0\" ".tooltip(_("Diese Gruppe als vCard exportieren"))."></a>";			
		} else {
			$color = "";
			$maillink ="";
		}
		echo "<td ".$cssSw->getHover()." class=\"".$cssSw->getClass()."\">&nbsp; "
		."<a href=\"$PHP_SELF?filter=".$db->f("statusgruppe_id")."\"><font size=\"2\" $color>".htmlready($db->f("name"))."</font></a>$maillink"
		."&nbsp; </td>";
		if ($filter==$db->f("statusgruppe_id")) {
			$cssSw->switchClass();
		}
	}
	echo "</tr></table>";
}



// Anzeige Treffer
if ($edit_id) {
	PrintEditContact($edit_id);
} else {
	PrintAllContact($filter);
}



		
		
if (!$edit_id) {

	if ($size_of_book>0)
		$hints .= "&nbsp; |&nbsp; <img src= \"./pictures/nachrichtsmall.gif\">&nbsp; "._("Nachricht an Kontakt");
	if ($open && $size_of_book>0)
		$hints .= "&nbsp; |&nbsp; <img src= \"./pictures/forumgraurauf.gif\">&nbsp; "._("Kontakt zuklappen");
	if ((!$open) && $size_of_book>0)
		$hints .= "&nbsp; |&nbsp; <img src= \"./pictures/forumgraurunt.gif\">&nbsp; "._("Kontakt aufklappen");
	if ($open && $size_of_book>0) {
		$hints .= "&nbsp; |&nbsp; <img src= \"./pictures/nutzer.gif\">&nbsp; "._("Buddystatus");
		$hints .= "&nbsp; |&nbsp; <img src= \"./pictures/einst.gif\">&nbsp; "._("Eigene Rubriken");
		$hints .= "&nbsp; |&nbsp; <img src= \"./pictures/vcardexport.gif\">&nbsp; "._("Als vCard exportieren");
		$hints .= "&nbsp; |&nbsp; <img src= \"./pictures/trash.gif\">&nbsp; "._("Kontakt l�schen");
	}
	echo 	"<br><font size=\"2\" color=\"#555555\">"._("Bedienung:").$hints;
}
echo "<br>&nbsp; </td></tr></table>";
page_close()

 ?>
</body>
</html>
