<?
/*
sms_send.php - Verwaltung von systeminternen Kurznachrichten
Copyright (C) 2002 Cornelis Kater <ckater@gwdg.de>, Nils K. Windisch <info@nkwindisch.de>

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

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$perm->check("user");
	
include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

// -- here you have to put initialisations for the current page
require_once ("$ABSOLUTE_PATH_STUDIP/functions.php");
require_once ("$ABSOLUTE_PATH_STUDIP/msg.inc.php");
require_once ("$ABSOLUTE_PATH_STUDIP/visual.inc.php");
require_once ("$ABSOLUTE_PATH_STUDIP/messagingSettings.inc.php");
require_once ("$ABSOLUTE_PATH_STUDIP/messaging.inc.php");
require_once ("$ABSOLUTE_PATH_STUDIP/statusgruppe.inc.php");
require_once ("$ABSOLUTE_PATH_STUDIP/reiter.inc.php");
if ($GLOBALS['CHAT_ENABLE']){
	include_once $ABSOLUTE_PATH_STUDIP.$RELATIVE_PATH_CHAT."/chat_func_inc.php"; 
	$chatServer =& ChatServer::GetInstance($GLOBALS['CHAT_SERVER_NAME']);
	$chatServer->caching = true;
	$admin_chats = $chatServer->getAdminChats($auth->auth['uid']);
}

$sess->register("sms_data");
$msging=new messaging;

$db=new DB_Seminar;

check_messaging_default();

# FUNCTIONS
###########################################################

// checkt ob alle adressbuchmitglieder in der empaengerliste stehen
function CheckAllAdded($adresses_array, $rec_array) {
	$x = sizeof($adresses_array);
	if (!empty($rec_array)) {
		foreach ($rec_array as $a) {
			if (in_array($a, $adresses_array)) {
				$x = ($x-1);
			}
		}
	}
	if ($x != "0") {
		return FALSE;
	} else {
		return TRUE;
	}
}

# ACTION
###########################################################

// check if active chat avaiable
if (($cmd == "write_chatinv") && (!is_array($admin_chats)))
	$cmd='';

// send message
if ($cmd_insert_x) {
	
	if (!empty($sms_data["p_rec"])) {
		$count = "";
		$time = date("U");
		$tmp_message_id = md5(uniqid("321losgehtes"));
		foreach ($sms_data["p_rec"] as $a) {
			if ($chat_id) {
				$count = ($count+$msging->insert_chatinv($message, $a, $chat_id));
			} else {
				$count = ($count+$msging->insert_message($message, $a, FALSE, $time, $tmp_message_id, FALSE, $signature));
			}
		}
	}

	if ($count) {
		$msg = "msg�";
		if ($count == "1")	 {
			$msg .= sprintf(_("Ihre Nachricht an %s wurde verschickt!"), get_fullname_from_uname($sms_data["p_rec"][0]))."<br />";
		}
		if ($count >= "2") {
			$msg .= sprintf(_("Ihre Nachricht wurde an %s Empf&auml;nger verschickt!"), $count)."<br />";
		}
		unset($signature);
		unset($message);
		$sms_data["sig"] = $my_messaging_settings["addsignature"];
	}
	if ($count < 0) {
		$msg = "error�" . _("Ihre Nachricht konnte nicht gesendet werden. Die Nachricht enth&auml;lt keinen Text.");
	} else if ((!$count) && (!$group_count)) {
		$msg = "error�" . _("Ihre Nachricht konnte nicht gesendet werden.");
	}
		
	$sms_msg = rawurlencode ($msg);

	if ($sms_source_page) {
		if ($sms_source_page == "about.php") {
			$header_info = "Location: ".$sms_source_page."?username=".$sms_data["p_rec"][0]."&sms_msg=".$sms_msg;
		} else {
			$header_info = "Location: ".$sms_source_page."?sms_msg=".$sms_msg;
		}
		header ($header_info);
		die;
	}
	unset($sms_data["p_rec"]);
}

// falls antwort
if ($rec_uname) {
	$sms_data["p_rec"] = array($rec_uname);
	$sms_data["sig"] = $my_messaging_settings["addsignature"];
}

// if send message at adressbook-group
if ($group_id) {
	$query = sprintf("SELECT statusgruppe_user.user_id, username FROM statusgruppe_user LEFT JOIN auth_user_md5 USING (user_id) WHERE statusgruppe_id = '%s' ", $group_id);
	$db->query($query);
	while ($db->next_record()) {
		$add_group_members[] = $db->f("username");
	}
	$sms_data["p_rec"] = "";
	$sms_data["p_rec"] = array_add_value($add_group_members, $sms_data["p_rec"]);
	$sms_data["sig"] = $my_messaging_settings["addsignature"];
}

// attach signature
if (!isset($sms_data["sig"])) {
	$sms_data["sig"] = $my_messaging_settings["addsignature"];
} else if ($add_sig_button_x) {
	$sms_data["sig"] = "1";
} else if ($rmv_sig_button_x) {
	$sms_data["sig"] = "0";
}

// add a reciever from adress-members
if ($add_receiver_button_x && !empty($add_receiver)) {
	$sms_data["p_rec"] = array_add_value($add_receiver, $sms_data["p_rec"]);
}

// add all reciever from adress-members
if ($add_allreceiver_button_x) {
	$query_for_adresses = "SELECT contact.user_id, username, ".$_fullname_sql['full_rev']." AS fullname FROM contact LEFT JOIN auth_user_md5 USING(user_id) LEFT JOIN user_info USING (user_id) WHERE owner_id = '".$user->id."' ORDER BY Nachname ASC";
	$db->query($query_for_adresses);
	while ($db->next_record()) {
		if (empty($sms_data["p_rec"])) {
			$add_rec[] = $db->f("username");
		} else {	
			if (!in_array($db->f("username"), $sms_data["p_rec"])) {
				$add_rec[] = $db->f("username");
			}
		}
	}
	$sms_data["p_rec"] = array_add_value($add_rec, $sms_data["p_rec"]);
	unset($add_rec);
}

// add receiver from freesearch
if ($add_freesearch_x && !empty($freesearch)) {
	$sms_data["p_rec"] = array_add_value($freesearch, $sms_data["p_rec"]);
}

// remove all from receiverlist
if ($del_allreceiver_button_x) {
	unset($sms_data["p_rec"]);
}

// aus empfaengerliste loeschen
if ($del_receiver_button_x && !empty($del_receiver)) {
	foreach ($del_receiver as $a) {
		$sms_data["p_rec"] = array_delete_value($sms_data["p_rec"], $a);
	}
}

function show_precform() {
	global $PHP_SELF, $sms_data, $user, $recuname, $my_messaging_settings;
	if ($my_messaging_settings["send_view"] == "1") {
		$tmp_01 = sizeof($sms_data["p_rec"]);
		if (sizeof($sms_data["p_rec"]) >= "12") {
			$tmp_01 = "12";
		}
	} else {
		$tmp_01 = "5";
	}
	$tmp =  "";
	if (sizeof($sms_data["p_rec"]) == "0") { 
		$tmp .= "<font size=\"-1\">"._("Bitte w&auml;hlen Sie mindestens einen Empf&auml;nger aus.");
		if (get_username($user->id) == $rec_uname) {
			$tmp .= "<br>"._("Nachrichten k&ouml;nnen nicht an sich selbst gesandt werden.");
		}
		$tmp .= "</font>";
	} else {
		$tmp .= "<select size=\"$tmp_01\" name=\"del_receiver[]\" multiple style=\"width: 250\">";
		if ($sms_data["p_rec"]) {
			foreach ($sms_data["p_rec"] as $a) {
				$tmp .= "<option value=\"$a\">".get_fullname_from_uname($a)."</option>";
			}
		}
		$tmp .= "</select><br>";	
		$tmp .= "<input type=\"image\" name=\"del_receiver_button\" src=\"./pictures/trash.gif\" ".tooltip(_("l�scht alle ausgew�hlten Empf�ngerInnen"))." border=\"0\">";
		#$tmp .= " <font size=\"-1\"><a href=\"".$PHP_SELF."?del_receiver_button_x=1\">"._("ausgew&auml;hlte l&ouml;schen")."</a></font><br>";
		$tmp .= " <font size=\"-1\">"._("ausgew&auml;hlte l&ouml;schen")."</font><br>";
		$tmp .= "<input type=\"image\" name=\"del_allreceiver_button\" src=\"./pictures/trash.gif\" ".tooltip(_("Empf&auml;ngerliste leeren"))." border=\"0\">";
		#$tmp .= " <font size=\"-1\"><a href=\"".$PHP_SELF."?del_allreceiver_button_x=1\">"._("Empf&auml;ngerliste leeren")."</a></font>";
		$tmp .= " <font size=\"-1\">"._("Empf&auml;ngerliste leeren")."</font>";
	}
	return $tmp;
}

function show_addrform() {
	global $PHP_SELF, $sms_data, $user, $recuname, $db, $_fullname_sql, $adresses_array, $search_exp, $my_messaging_settings;
	if ($my_messaging_settings["send_view"] == "1") {
		$picture = "move_up.gif";
	} else {
		$picture = "move_left.gif";
	}
	// list of adresses
	$query_for_adresses = "SELECT contact.user_id, username, ".$_fullname_sql['full_rev']." AS fullname FROM contact LEFT JOIN auth_user_md5 USING(user_id) LEFT JOIN user_info USING (user_id) WHERE owner_id = '".$user->id."' ORDER BY Nachname ASC";
	$db->query($query_for_adresses);
	while ($db->next_record()) {
		$adresses_array[] = $db->f("username");
	}
	$tmp = "<b><font size=\"-1\">"._("Adressbuch-Liste:")."</font></b><br>";
	if (empty($adresses_array)) { // user with no adress-members at all
		$tmp .= sprintf("<font size=\"-1\">"._("Sie haben noch keine Personen in ihrem Adressbuch. %s Klicken sie %s hier %s um dorthin zu gelangen.")."</font>", "<br>", "<a href=\"contact.php\">", "</a>");
	} else if (!empty($adresses_array)) { // test if all adresses are added?
		if (CheckAllAdded($adresses_array, $sms_data["p_rec"]) == TRUE) { // all adresses already added
			$tmp .= sprintf("<font size=\"-1\">"._("Bereits alle Personen des Adressbuchs hinzugef&uuml;gt!")."</font>");
		} else { // show adresses-select
			$tmp_count = "0";
			$db->query($query_for_adresses);
			while ($db->next_record()) {
				if (empty($sms_data["p_rec"])) {
					$tmp_02 .= "<option value=\"".$db->f("username")."\">".htmlReady(my_substr($db->f("fullname"),0,35))."</option>";
					$tmp_count = ($tmp_count+1);
				} else {
					if (!in_array($db->f("username"), $sms_data["p_rec"])) {
						$tmp_02 .= "<option value=\"".$db->f("username")."\">".htmlReady(my_substr($db->f("fullname"),0,35))."</option>";
						$tmp_count = ($tmp_count+1);
					}
				}
			}
			
			if ($my_messaging_settings["send_view"] == "1") {
				$tmp_01 = $tmp_count;
				if ($tmp_count >= "12") {
					$tmp_01 = "12";
				}
			} else {
				$tmp_01 = "3";
			}
			$tmp .= "<select size=\"".$tmp_01."\" name=\"add_receiver[]\" multiple style=\"width: 250\">";
			$tmp .= $tmp_02;
			$tmp .= "</select><br>";
			$tmp .= "<input type=\"image\" name=\"add_receiver_button\" src=\"./pictures/".$picture."\" border=\"0\" ".tooltip(_("f�gt alle ausgew�htlen Personen der Empf�ngerInnenliste hinzu")).">";
			#$tmp .= "&nbsp;<font size=\"-1\"><a href=\"".$PHP_SELF."?add_allreceiver_button_x=1\">"._("ausgew&auml;hlte hinzuf�gen")."</a>";
			$tmp .= "&nbsp;<font size=\"-1\">"._("ausgew&auml;hlte hinzuf�gen")."";
			$tmp .= "&nbsp;<br><input type=\"image\" name=\"add_allreceiver_button\" src=\"./pictures/".$picture."\" border=\"0\" ".tooltip(_("f�gt alle Personen der Empf�ngerInnenliste hinzu")).">";
			#$tmp .= "&nbsp;<font size=\"-1\"><a href=\"".$PHP_SELF."?add_allreceiver_button_x=1\">"._("alle hinzuf&uuml;gen")."</a></font>";
			$tmp .= "&nbsp;<font size=\"-1\">"._("alle hinzuf&uuml;gen")."</font>";
		}
	}
	// free search
	$tmp .= "<br><br><font size=\"-1\"><b>"._("Freie Suche:")."</b></font><br>";
	if ($search_exp != "" && strlen($search_exp) >= "3") {
		$search_exp = str_replace("%", "\%", $search_exp);
		$search_exp = str_replace("_", "\_", $search_exp);	
		$query = "SELECT username, ".$_fullname_sql['full_rev']." AS fullname, perms FROM auth_user_md5 LEFT JOIN user_info USING(user_id) WHERE (username LIKE '%$search_exp%' OR Vorname LIKE '%$search_exp%' OR Nachname LIKE '%$search_exp%') ORDER BY Nachname ASC";
		$db->query($query); //
		if (!$db->num_rows()) {
			$tmp .= "&nbsp;<input type=\"image\" name=\"reset_freesearch\" src=\"./pictures/rewind.gif\" border=\"0\" value=\""._("Suche zur&uuml;cksetzen")."\" ".tooltip(_("setzt die Suche zur�ck")).">";
			$tmp .= "&nbsp;<font size=\"-1\">"._("keine Treffer")."</font>";
		} else {
			$tmp .= "<input type=\"image\" name=\"add_freesearch\" ".tooltip(_("zu Empf�ngerliste hinzuf�gen"))." value=\""._("zu Empf&auml;ngerliste hinzuf&uuml;gen")."\" src=\"./pictures/".$picture."\" border=\"0\">&nbsp;";
			$tmp .= "<select size=\"1\" width=\"80\" name=\"freesearch[]\">";
			while ($db->next_record()) {
				if (empty($sms_data["p_rec"])) {
					$tmp .= "<option value=\"".$db->f("username")."\">".htmlReady(my_substr($db->f("fullname"),0,35))." (".$db->f("username").") - ".$db->f("perms")."</option>";
				} else {
					if (!in_array($db->f("username"), $sms_data["p_rec"])) {
						$tmp .= "<option value=\"".$db->f("username")."\">".htmlReady(my_substr($db->f("fullname"),0,35))." (".$db->f("username").") - ".$db->f("perms")."</option>";
					}
				}
			}
			$tmp .= "</select>";
			$tmp .= "<input type=\"image\" name=\"reset_freesearch\" src=\"./pictures/rewind.gif\" border=\"0\" value=\""._("Suche zur&uuml;cksetzen")."\" ".tooltip(_("setzt die Suche zur�ck")).">";
		}
	} else {
		$tmp .= "<input type=\"text\" name=\"search_exp\" size=\"30\">";
		$tmp .= "<input type=\"image\" name=\"\" src=\"./pictures/suchen.gif\" border=\"0\">";
	}
	return $tmp;
}

function show_msgform() {
	global $PHP_SELF, $sms_data, $user, $quote, $tmp_sms_content, $rec_uname, $message;
	
	$tmp = "<div align=\"center\"><textarea name=\"message\" style=\"width: 99%\" cols=80 rows=10 wrap=\"virtual\">\n";
	if ($quote) {
		$tmp .= quotes_encode($tmp_sms_content, get_fullname_from_uname($rec_uname));
	}
	if ($message) {
		$tmp .= stripslashes($message);
	}
	$tmp .= "</textarea>\n<br><br>";	
	// send/ break-button
	if (sizeof($sms_data["p_rec"]) > "0") { 
		$tmp .= "<input type=\"image\" ".makeButton("abschicken", "src")." name=\"cmd_insert\" border=0 align=\"absmiddle\">";
	}
	$tmp .= "&nbsp;<a href=\"sms_box.php\">".makeButton("abbrechen", "img")."</a>&nbsp;";
	$tmp .= "<input type=\"image\" ".makeButton("vorschau", "src")." name=\"cmd\" border=0 align=\"absmiddle\">";
	$tmp .= "<br><br>";	
	$tmp .= "</div>";	
	return $tmp;
}

function show_previewform() {
	global $sms_data, $message, $signature, $my_messaging_settings;
	$tmp = "<input type=\"image\" name=\"refresh_message\" src=\"./pictures/rewind3.gif\" border=\"0\" ".tooltip(_("f�gt der aktuellen Nachricht eine Signatur an.")).">&nbsp;"._("Vorschau erneuern.")."<br><br>";

		$tmp .= quotes_decode(formatReady(stripslashes($message)));
		if ($sms_data["sig"] == "1") {
			$tmp .= "<br><br>--<br>";
			if ($signature) {
				$tmp .= quotes_decode(formatReady(stripslashes($signature)));
			} else {
				$tmp .= quotes_decode(formatReady(stripslashes($my_messaging_settings["sms_sig"])));
			}
		}
	$tmp = "<font size=\"-1\">".$tmp."</font>";
	return $tmp;
}

function show_sigform() {
	global $sms_data, $signature, $my_messaging_settings;
	if ($sms_data["sig"] == "1") {
			$tmp =  "<font size=\"-1\">";
			$tmp .= _("Dieser Nachricht wird eine Signatur angeh�ngt");
			$tmp .= "<br><input type=\"image\" name=\"rmv_sig_button\" src=\"./pictures/rmv_sig.gif\" border=\"0\" ".tooltip(_("entfernt die Signatur von der aktuellen Nachricht.")).">&nbsp;"._("Signatur entfernen.");
			$tmp .= "</font><br>";
			$tmp .= "<textarea name=\"signature\" style=\"width: 250px\" cols=20 rows=7 wrap=\"virtual\">\n";
			if (!$signature) {
				$tmp .= htmlready($my_messaging_settings["sms_sig"]);
			} else {
				$tmp .= htmlready($signature);
			}
			$tmp .= "</textarea>\n";
	} else {
		$tmp =  "<font size=\"-1\">";
		$tmp .=  _("Dieser Nachricht wird keine Signatur angeh�ngt");
			$tmp .= "<br><input type=\"image\" name=\"add_sig_button\" src=\"./pictures/add_sig.gif\" border=\"0\" ".tooltip(_("f�gt der aktuellen Nachricht eine Signatur an.")).">&nbsp;"._("Signatur anh�ngen.");
		$tmp .= "</font>";
	}
	$tmp = "<font size=\"-1\">".$tmp."</font>";
	return $tmp;

}

function show_chatselector() {
	global $_REQUEST, $admin_chats, $cmd;
	if ($cmd == "write_chatinv") {
		echo "<td class=\"steel1\" width=\"100%\" valign=\"left\"><div align=\"left\">";
		echo "<font size=\"-1\"><b>"._("Chatraum ausw&auml;hlen:")."</b>&nbsp;&nbsp;</font>";
		echo "<select name=\"chat_id\" style=\"vertical-align:middle;font-size:9pt;\">";
		foreach($admin_chats as $chat_id => $chat_name){
			echo "<option value=\"$chat_id\"";
			if ($_REQUEST['selected_chat_id'] == $chat_id){
				echo " selected ";
			}
			echo ">".htmlReady($chat_name)."</option>";
		}
		echo "</select>";
		echo "</div><img src=\"pictures/blank.gif\" height=\"6\" border=\"0\">";
		echo "</td></tr>";	
	}
}

# OUTPUT
###########################################################

// includes
include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head
include ("$ABSOLUTE_PATH_STUDIP/links_sms.inc.php"); // include reitersystem
check_messaging_default();

if (($change_view) || ($delete_user) || ($view=="Messaging")) {
	change_messaging_view();
	echo "</td></tr></table>";
	page_close();
	die;
} 

$txt['001'] = _("aktuelle Empf&auml;ngerInnen"); 
$txt['002'] = _("m&ouml;gliche Empf&auml;ngerInnen");
$txt['003'] = _("Signatur");
$txt['004'] = _("Vorschau");
$txt['005'] = (($cmd=="write_chatinv") ? _("Chateinladung") : _("Nachricht"));

if ($send_view) {
	if ($send_view == "2") {
		unset($my_messaging_settings["send_view"]);
	} else if ($send_view == "1") {
		$my_messaging_settings["send_view"] = $send_view;
	}
}

?>


<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td class="topic" colspan="2"><img src="pictures/nachricht1.gif" border="0" align="texttop"><b>&nbsp;<?=_("Systeminterne Nachricht schreiben")?></b></td>
</tr>
<tr>
	<td class="blank" colspan="2">&nbsp;</td>
</tr>
<tr>	
	<td class="blank" valign="top" align="center"> <?
	if ($sms_msg) {
		print ("<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"99%\"><tr><td valign=\"top\">");
		parse_msg (rawurldecode($sms_msg));
		print ("</td></tr></table>");
	}

	echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"sms_source_page\" value=\"".$sms_source_page."\">";
	echo "<input type=\"hidden\" name=\"cmd\" value=\"".$cmd."\">";

	if ($quote) {
		$db->query ("SELECT message FROM message WHERE message_id = '$quote' ");
		$db->next_record();
		if (strpos($db->f("message"),$msging->sig_string)) {
			$tmp_sms_content = substr($db->f("message"), 0, strpos($db->f("message"),$msging->sig_string));
		} else {
			$tmp_sms_content = $db->f("message");
		}
	}
	
	if ($my_messaging_settings["send_view"] == "1") { ?>

		<table cellpadding="0" cellspacing="0" border="0" height="10" width="99%">
			<tr>
				<td colspan="2" valign="top" width="30%" height="10" class="blank" style="border-right: dotted 1px"> 
					
					<table cellpadding="5" cellspacing="0" border="0" height="10" width="100%">
						<tr>
							<td valign="top" class="steelgraudunkel">
								<?
								echo "<font size=\"-1\" color=\"#FFFFFF\"><b>".$txt['001']."</b></font>"; ?>
							</td>
						</tr>
						<tr>
							<td valign="top" class="steelgraulight">
								<?
								echo show_precform();							
								?>
							</td>
						</tr>
						<tr>
							<td valign="top" class="steelgraudunkel">
								<font size="-1" color="#FFFFFF"><b><?=$txt['002']?></b></font>
							</td>
						</tr>
						<tr>
							<td valign="top" class="steelgraulight">
								<?
								echo show_addrform();							
								?>
							</td>
						</tr>
					</table>

				</td>
				<td colspan="2" valign="top" width="70%" class="blank"> 
					
					<table cellpadding="5" cellspacing="0" border="0" height="10" width="100%">
						<?=show_chatselector()?>
						<tr>
							<td valign="top" class="steelgraudunkel">
								<?
								echo "<font size=\"-1\" color=\"#FFFFFF\"><b>".$txt['005']."</b></font>"; ?>
							</td>
						</tr>
						<tr>
							<td valign="top" class="steelgraulight">
								<?=show_msgform()?>
							</td>
						</tr>
						<tr>
							<td valign="top" class="steelgraudunkel">
								<font size="-1" color="#FFFFFF"><b><?=$txt['003']?></b></font>
							</td>
						</tr>
						<tr>
							<td valign="top" class="printcontent">
								<?=show_sigform()?>
							</td>
						</tr>						
						<tr>
							<td valign="top" class="steelgraudunkel">
								<?
								echo "<font size=\"-1\" color=\"#FFFFFF\"><b>".$txt['004']."</b></font>"; ?>
							</td>
						</tr>
						<tr>
							<td valign="top" class="printcontent">
								<?=show_previewform()?>
							</td>
						</tr>
					</table>
				
				</td>
			</tr>
		</table> <? 

	} else { ?>

		<table cellpadding="5" cellspacing="0" border="0" height="10" width="99%">
			<tr>
				<td colspan="2" valign="top" width="30%" height="10" class="steelgraudunkel">
					<font size="-1" color="#FFFFFF"><b><?=$txt['001']?></b></font>
				</td>
				<td colspan="2" valign="top" width="70%" class="steelgraudunkel"> 
					<font size="-1" color="#FFFFFF"><b><?=$txt['002']?></b></font> 
				</td>
			</tr>
		</table>
		<table cellpadding="5" cellspacing="0" border="0" width="99%">
			<tr>
				<td colspan="2" valign="top" width="30%" class="steelgraulight">
					<?=show_precform()?>
					</td>
					<td class="printcontent" align="left" valign="top" width="70%">
					<?=show_addrform()?><br><br>
				</td>
			</tr>
		</table>
		<table cellpadding="5" cellspacing="0" border="0" width="99%">
			<tr>
				<td colspan="2" valign="top" width="80%" class="steelgraudunkel">
					<font size="-1" color="#FFFFFF"><b><?=$txt['005']?></b></font>
				</td>
			</tr>
		</table>
		<table border="0" cellpadding="5" cellspacing="0" width="99%" align="center">
			<?=show_chatselector()?>
			<tr>
				<td class="steelgraulight" width="80%" valign="center">
					<?=show_msgform()?>
				</td>
			</tr>
		</table>
		<table border="0" cellpadding="5" cellspacing="0" width="99%" align="center">
			<tr>
				<td class="steelgraudunkel"  width="30%" valign="top">
					<font size="-1" color="#FFFFFF"><b><?=$txt['003']?></b></font>
				</td>
				<td class="steelgraudunkel"  width="70%" valign="top">
					<font size="-1" color="#FFFFFF"><b><?=$txt['004']?></b></font>
				</td>
			</tr>
			<tr>
				<td class="steelgraulight"  width="20%" valign="top">
					<?=show_sigform()?>
				</td>
				<td class="printcontent" width="20%" valign="top">
					<?=show_previewform()?>
					
				</td>
			</tr>
		</table> <?
	}

	if (!$my_messaging_settings["send_view"]) {
		$tmp_link_01 = "1";
		$tmp_link_02 = _("Experten-Ansicht");
	} else if ($my_messaging_settings["send_view"] == "1") {
		$tmp_link_01 = "2";
		$tmp_link_02 = _("Standard-Ansicht");
	}
	$switch_sendview = sprintf(_("W�hlen Sie hier zwischen Experten- und Standard-Ansicht."))."<br><a href=\"".$PHP_SELF."?send_view=".$tmp_link_01."\">".$tmp_link_02."</a>";
	
	echo"</form>\n";
	print "</td><td class=\"blank\" width=\"270\" align=\"right\" valign=\"top\">";
	$infobox = array(
		array("kategorie" => _("Ansicht:"),"eintrag" => array(
			array("icon" => "pictures/blank.gif", "text" => $switch_sendview)
		)),
		array("kategorie" => _("Empf&auml;nger hinzuf&uuml;gen:"),"eintrag" => array(
			array("icon" => "pictures/nutzeronline.gif", "text" => sprintf(_("Nutzen Sie die Adressbuch-Liste oder freie Suche um Empf&auml;ngerInnen hinzuf&uuml;gen.")))
		)),
		array("kategorie" => _("Smilies & Textformatierung:"),"eintrag" => array(
			array("icon" => "pictures/smile/asmile.gif", "text" => sprintf(_("%s Liste mit allen Smilies %s Hilfe zu Smilies %s Hilfe zur Textformatierung %s"), "<a href=\"show_smiley.php\" target=\"_blank\">", "</a><br><a href=\"help/index.php?help_page=ix_forum7.htm\" target=\"_blank\">", "</a><br><a href=\"help/index.php?help_page=ix_forum6.htm\" target=\"_blank\">", "</a>"))
		))
	);
	print_infobox($infobox,"pictures/sms3.jpg"); ?>

	</td>
</tr>
<tr>
	<td class="blank" colspan="2">&nbsp;
	</td>
</tr>
</table> <?

// Save data back to database.
page_close() ?>

</body>
</html>
