<?php
/*
mesaging.inc.php - Funktionen fuer das Messaging
Copyright (C) 2002 Cornelis Kater <ckater@gwdg.de>

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

require_once $ABSOLUTE_PATH_STUDIP.$RELATIVE_PATH_CHAT."/ChatServer.class.php"; //wird f�r Nachrichten im chat ben�tigt
require_once ("$ABSOLUTE_PATH_STUDIP/functions.php");
require_once ("$ABSOLUTE_PATH_STUDIP/contact.inc.php");

class messaging {
	var $db;							//Datenbankanbindung
	var $sig_string;					//String, der Signaturen vom eigentlichen Text abgrenut


//Konstruktor
function messaging () {
	
	$this->sig_string="\n \n -- \n";
		
	$this->db = new DB_Seminar;
}

//alle Nachrichten loeschen
function delete_all_sms  ($user_id, $delete_unread) { 
	global $LastLogin, $user;
	$db=new DB_Seminar;
	
	if (!$user_id)
		$user_id = $user->id;
	
	$db->query ("SELECT username FROM auth_user_md5 WHERE user_id = '".$user_id."' ");
	$db->next_record();
	$tmp_sms_username=$db->f("username");
	
	if (!$delete_unread)
		$db->query("DELETE FROM globalmessages WHERE user_id_rec = '$tmp_sms_username' ");
	else
		$db->query("DELETE FROM globalmessages WHERE mkdate < ".$LastLogin ." AND user_id_rec = '$tmp_sms_username' ");

	return $db->affected_rows();
	}

//Nachricht loeschen
function delete_sms ($message_id) {
	global $user;
	$db=new DB_Seminar;

	$db->query ("SELECT username FROM auth_user_md5 WHERE user_id = '".$user->id."' ");
	$db->next_record();
	$tmp_sms_username=$db->f("username");
		
	$db->query("DELETE FROM globalmessages WHERE message_id = '$message_id' AND user_id_rec = '$tmp_sms_username' ");
	if ($db->affected_rows())
		return TRUE;
	else
		return FALSE;
	}
 
//Geschriebene Nachricht einfuegen
function insert_sms ($rec_uname, $message, $user_id='') {
	global $_fullname_sql,$user, $my_messaging_settings, $CHAT_ENABLE;

	if (!$this->sig_string)
		$this->sig_string="\n \n -- \n";

	$db=new DB_Seminar;
	$db2=new DB_Seminar;
	$db3=new DB_Seminar;

	if (!$user_id)
		$user_id = $user->id;

	if (!empty($message)) {
		if ($user_id != "____%system%____") {
			$db->query ("SELECT username," . $_fullname_sql['full'] ." AS fullname FROM auth_user_md5 a LEFT JOIN user_info USING (user_id) WHERE a.user_id = '".$user_id."' ");
			$db->next_record();
			$snd_uname=$db->f("username");
		} else
			$snd_uname="____%system%____";			

		$db2->query ("SELECT user_id FROM auth_user_md5 WHERE username = '".$rec_uname."' ");
		$db2->next_record();

		if ($db2->num_rows()){
			$m_id=md5(uniqid("voyeurism"));
			if ($user_id != "____%system%____")  {
				if ($my_messaging_settings["sms_sig"])
					$message.=$this->sig_string.$my_messaging_settings["sms_sig"];
			} else
				$message.=$this->sig_string."Diese Nachricht wurde automatisch vom System generiert. Sie k�nnen darauf nicht antworten.";
			$db3->query("INSERT INTO globalmessages SET message_id='$m_id', user_id_rec='$rec_uname', user_id_snd='$snd_uname', mkdate='".time()."', message='$message' ");
		
			//Benachrichtigung in alle Chatr�ume schicken
			if ($CHAT_ENABLE) {
				$chatServer =& ChatServer::GetInstance($GLOBALS['CHAT_SERVER_NAME']);
				$chatMsg = "Du hast eine SMS von <b>".$db->f("fullname")." (".$db->f("username").")</b> erhalten!<br></i>";
				$chatMsg .= formatReady(stripslashes($message))."<i>";
				foreach($chatServer->chatDetail as $chatid => $wert)
					if ($wert['users'][$db2->f("user_id")])
						$chatServer->addMsg("system:".$db2->f("user_id"),$chatid,$chatMsg);
			}
			return $db3->affected_rows();
		} else 
			return false;
	} else
		return -1;
}

//Chateinladung absetzen
function insert_chatinv ($rec_uname, $user_id='') {
	global $user,$_fullname_sql;
	$db=new DB_Seminar;
	$db2=new DB_Seminar;
	$db3=new DB_Seminar;

	if (!$user_id)
		$user_id = $user->id;

	$db->query ("SELECT username," . $_fullname_sql['full'] ." AS fullname FROM auth_user_md5 a LEFT JOIN user_info USING (user_id) WHERE a.user_id = '".$user_id."' ");
	$db->next_record();

	$db2->query ("SELECT user_id FROM auth_user_md5 WHERE username = '".$rec_uname."' ");
	$db2->next_record();

	$m_id=md5(uniqid("voyeurism"));
	$db3->query ("INSERT INTO globalmessages SET message_id='$m_id', user_id_rec='$rec_uname', user_id_snd='".$db->f("username")."', mkdate='".time()."', message='chat_with_me' ");

	//Benachrichtigung in alle Chatr�ume schicken, noch nicht so sinnvoll :)
	if ($CHAT_ENABLE) {
		$chatServer =& ChatServer::GetInstance($GLOBALS['CHAT_SERVER_NAME']);
		$chatMsg="Du wurdest von <b>".$db->f("fullname")." (".$db->f("username").")</b> in den Chat eingeladen !";
		foreach($chatServer->chatDetail as $chatid => $wert)
			if ($wert['users'][$db2->f("user_id")])
				$chatServer->addMsg("system:".$db2->f("user_id"),$chatid,$chatMsg);
	}
	
	if ($db3->affected_rows())
		return TRUE;
	else
		return FALSE;
}

function delete_chatinv($username){
    $this->db->query("DELETE FROM globalmessages WHERE user_id_rec='$username' AND message LIKE '%chat_with_me%'");
    return $this->db->affected_rows();
}


//Buddy aus der Buddyliste loeschen        
function delete_buddy ($username) {
	RemoveBuddy($username);
	}

//Buddy zur Buddyliste hinzufuegen
function add_buddy ($username) {
		AddNewContact (get_userid($username));
		AddBuddy($username);
	}
}
?>
