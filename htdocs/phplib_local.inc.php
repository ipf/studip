<?
// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// phplib_local.inc.php
// This file contains several phplib classes extended for use with Stud.IP
// 
// Copyright (c) 2003 Andr� Noack <noack@data-quest.de> 
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
//$Id$


require_once("$ABSOLUTE_PATH_STUDIP/language.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/lib/classes/auth_plugins/StudipAuthAbstract.class.php");

//Compatibility for PHP Version < 4.1.0
// 3/18/2002 - Tim Gallagher<timg@sunflowerroad.com>
// if $_REQUEST isn't set, we will set it based on $HTTP_GET_VARS AND $HTTP_POST_VARS
// however, we should still global these variables in the functions to keep backward
// compatability from breaking.

if ( (! isset($_REQUEST)) && (! isset($_GET)) ) {
	// swap the foreach loops to change the order of variable registration
	// in other words you can change GET then POST to POST then GET
	// where the second set of variables overrides the first.

	foreach ($HTTP_GET_VARS as $key => $value) {
		$_GET[$key] = $value;
		$_REQUEST[$key] =& $_GET[$key];
	} // end foreach loop

	foreach ($HTTP_POST_VARS as $key => $value) {
		$_POST[$key] = $value;
		$_REQUEST[$key] =& $_POST[$key];
	} // end foreach loop
} // end if

//bugfix ?
reset($HTTP_POST_VARS);
reset($HTTP_GET_VARS);

if (strstr( PHP_OS,"WIN") && $CHAT_ENABLE == true) 						//Attention: file based chat for windows installations (slow)
	$CHAT_SERVER_NAME = "ChatFileServer";

//Besser hier globale Variablen definieren...
$_fullname_sql['full'] = "TRIM(CONCAT(title_front,' ',Vorname,' ',Nachname,IF(title_rear!='',CONCAT(', ',title_rear),'')))";
$_fullname_sql['full_rev'] = "TRIM(CONCAT(Nachname,', ',Vorname,IF(title_front!='',CONCAT(', ',title_front),''),IF(title_rear!='',CONCAT(', ',title_rear),'')))";
$_fullname_sql['no_title'] = "CONCAT(Vorname ,' ', Nachname)";
$_fullname_sql['no_title_rev'] = "CONCAT(Nachname ,', ', Vorname)";
$_fullname_sql['no_title_short'] = "CONCAT(Nachname,', ',UCASE(LEFT(TRIM(Vorname),1)),'.')";

//software version - please leave it as it as!
$SOFTWARE_VERSION="0.9.5beta";
	
/*classes for database access
----------------------------------------------------------------
please note: Stud.IP uses the class DB_Seminar*/

// default Stud.IP database class
class DB_Seminar extends DB_Sql {
	function DB_Seminar($query = false){
		$this->Host = $GLOBALS['DB_STUDIP_HOST'];
		$this->Database = $GLOBALS['DB_STUDIP_DATABASE'];
		$this->User = $GLOBALS['DB_STUDIP_USER'];
		$this->Password = $GLOBALS['DB_STUDIP_PASSWORD'];
		if ($query){
			$this->query($query);
		}
	}
}

//additional class, for your own purpose!
class DB_Institut extends DB_Sql {
	function DB_Institut($query = false){
		$this->Host = $GLOBALS['DB_INSTITUT_HOST'];
		$this->Database = $GLOBALS['DB_INSTITUT_DATABASE'];
		$this->User = $GLOBALS['DB_INSTITUT_USER'];
		$this->Password = $GLOBALS['DB_INSTITUT_PASSWORD'];
		if ($query){
			$this->query($query);
		}
	}
}

// Vollzugriff auf eine ILIAS-Installation
class DB_Ilias extends DB_Sql {
	function DB_Ilias($query = false){
		$this->Host = $GLOBALS['DB_ILIAS_HOST'];
		$this->Database = $GLOBALS['DB_ILIAS_DATABASE'];
		$this->User = $GLOBALS['DB_ILIAS_USER'];
		$this->Password = $GLOBALS['DB_ILIAS_PASSWORD'];
		if ($query){
			$this->query($query);
		}
	}
}


/*mail settings
----------------------------------------------------------------*/

class studip_smtp_class extends smtp_class {

	var $from = "";
	var $env_from = "";
	var $abuse = "";
	
	function studip_smtp_class() {
		$this->localhost = ($GLOBALS['MAIL_LOCALHOST'] == "") ? getenv("SERVER_NAME") : $GLOBALS['MAIL_LOCALHOST']; // name of the mail sending machine (the web server)
		$this->host_name = ($GLOBALS['MAIL_HOST_NAME'] == "") ? getenv("SERVER_NAME") : $GLOBALS['MAIL_HOST_NAME']; // which mailserver should we use? (must allow mail-relaying from this->localhost)
		$this->from="\"Stud.IP\" <wwwrun@".$this->localhost.">"; // From: Mailheader
		$this->env_from="wwwrun@".$this->localhost; // Envelope-From:
		$this->abuse="abuse@".$this->localhost; // Reply-To: Mailheader
	}
}


class Seminar_CT_Sql extends CT_Sql {
	var $database_class = "DB_Seminar";	  // Which database to connect...
	var $database_table = "active_sessions"; // and find our session data in this table.
}


class Seminar_Session extends Session {
	var $classname = "Seminar_Session";
	
	var $cookiename     = "Seminar_Session"; // defaults to classname
	var $magic	  = "sdfghjdfdf";      // ID seed
	var $mode	   = "cookie";	  // We propagate session IDs with cookies
	//var $fallback_mode  = "get";
	var $lifetime       = 0;		 // 0 = do session cookies, else minutes
	var $that_class     = "Seminar_CT_Sql"; // name of data storage container
	var $gc_probability = 5;
	var $allowcache = "no";
	
	
	//modifizierte function put_headers(),erm�glicht den Verzicht auf Headers seitens der PHPLib
	function put_headers(){
		if ($GLOBALS["dont_put_headers"]) return;
		//put_headers der SuperKlasse aufrufen
		Session::put_headers();
	}
	
	//erweiterter Garbage Collector
	function gc(){
		mt_srand((double)microtime()*1000000);
		if ((mt_rand()%100) < $this->gc_probability){
			//Alte News, oder News ohne range_id l�schen
			$db=new DB_Seminar("SELECT news.news_id FROM news where (date+expire)<UNIX_TIMESTAMP() ");
			while($db->next_record()) {
				$result[$db->Record[0]] = true;
			}
			$db->query("SELECT news_range.news_id FROM news_range LEFT JOIN news using(news_id) WHERE ISNULL(news.news_id)");
			while($db->next_record()) {
				$result[$db->Record[0]] = true;
			}
			$db->query("SELECT news.news_id FROM news LEFT OUTER JOIN news_range USING (news_id) WHERE range_id IS NULL");
			while($db->next_record()) {
				$result[$db->Record[0]] = true;
			}
			if (is_array($result)) {
				$kill_news = "('".join("','",array_keys($result))."')";
				$db->query("DELETE FROM news WHERE news_id IN $kill_news");
				$db->query("DELETE FROM news_range WHERE news_id IN $kill_news");
			}
			unset($result);
		}
		if ((mt_rand()%1000) < $this->gc_probability){
			//unsichtbare forenbeitr�ge die �lter als 2 Stunden sind l�schen
			$db = new DB_Seminar();
			$db->query("SELECT a.topic_id, count( b.topic_id ) AS kinder FROM px_topics a
						LEFT JOIN px_topics b ON ( a.topic_id = b.parent_id )
						WHERE a.chdate < UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL -2 HOUR))  AND a.chdate = a.mkdate - 1
						GROUP BY a.topic_id");
			while ($db->next_record()){
				if ($db->f("kinder") != 0){
					$result['with_kids'][] = $db->f("topic_id");
				} else {
					$result['no_kids'][] = $db->f("topic_id");
				}
			}
			//Beitr�ge ohne Antworten l�schen
			if (is_array($result['no_kids'])){
				$db->query("DELETE FROM px_topics WHERE topic_id IN('" . join("','",$result['no_kids']) . "')");
			}
			//Beitr�ge mit Antworten sichtbar machen
			if (is_array($result['with_kids'])){
				$db->query("UPDATE px_topics SET chdate=mkdate WHERE topic_id IN('" . join("','",$result['with_kids']) . "')");
			}
			unset($result);
		}
		//weiter mit gc() in der Super Klasse
		Session::gc();
	}
}

class Seminar_User extends User {
	var $classname = "Seminar_User";
	
	var $magic	  = "dsfgakdfld";     // ID seed
	var $that_class     = "Seminar_CT_Sql"; // data storage container
}


//
// Seminar_Challenge_Crypt_Auth: Keep passwords in md5 hashes rather
//			   than cleartext in database
// Author: Jim Zajkowski <jim@jimz.com>

class Seminar_Auth extends Auth {
	var $classname      = "Seminar_Auth";
	
	var $lifetime       =  60;
	
	var $magic	  = "Fdfglkdfsg";  // Challenge seed
	var $database_class = "DB_Seminar";
	var $database_table = "auth_user_md5";
	var $error_msg = "";

	//constructor
	function Seminar_Auth() {
		//load the lifetime from the settings
		global $AUTH_LIFETIME;

		if ($AUTH_LIFETIME)
			$this->lifetime = $AUTH_LIFETIME;
	}	
	
	function login_if($ok){
		if ($ok){
			Auth::login_if($ok);
			if (is_object($GLOBALS['user'])){
				$GLOBALS['user']->start($this->auth['uid']);
			}
		}
		return true;
	}
	
	function auth_preauth() {
		global $auto_user,$auto_response,$auto_id,$resolution;
		
		if (!$auto_user OR !$auto_response OR !$auto_id){
			return false;
		}
		$aktuell = time();
		$folder = dir("/tmp");
		while ($entry = $folder->read()){
			if (!strncmp($entry,"auto_key",8)){
				if ($aktuell-filemtime("/tmp/$entry") > 30){
					unlink("/tmp/$entry");
				}
			}
		}
		$folder->close;
		if (file_exists("/tmp/auto_key_$auto_id")){
			$fp = @fopen("/tmp/auto_key_$auto_id","r");
			$auto_challenge = fgets($fp,100);
			fclose($fp);
			unlink("/tmp/auto_key_$auto_id");
		} else {
			$this->error_msg= _("Fehler beim Auto-Login!") . "<br>";
			return false;
		}
		$this->auth["uname"] = $auto_user;  // This provides access for "loginform.ihtml"
		$this->auth["jscript"] = true;
		$expected_response = "";
		for ($i = 0;$i < strlen($auto_response)/2;$i++){
			$s = (256-(ord(substr($auto_challenge,$i,1))-hexdec(substr($auto_response,$i*2,2)))) % 256;
			$expected_response .= chr($s);
		}
		$check_auth = StudipAuthAbstract::CheckAuthentication($auto_user,$expected_response,$this->auth['jscript']);
		if ($check_auth['uid']){
			$uid = $check_auth['uid'];
			$this->db->query(sprintf("select username,perms,auth_plugin from %s where user_id = '%s'",$this->database_table,$uid));
			$this->db->next_record();
			if ($this->db->f("perms") == "root" || $this->db->f("perms") == "admin"){
				$this->error_msg= sprintf(_("Autologin ist mit dem Status: %s nicht m�glich!"), $this->auth["perm"]);
				return false;
			}
			$this->auth["perm"]  = $this->db->f("perms");
			$this->auth["uname"] = $this->db->f("username");
			$this->auth["auth_plugin"]  = $this->db->f("auth_plugin");
			$this->auth_set_user_settings($uid);
			return $uid;
		} else {
			$this->error_msg = $check_auth['error'];
			return false;
		}
	}
	
	function auth_loginform() {
		global $sess;
		global $challenge;
		global $ABSOLUTE_PATH_STUDIP;
		global $shortcut;
		global $order;
		
	  $challenge = StudipAuthAbstract::CheckMD5();
    if ($challenge){
        $challenge = md5(uniqid($this->magic));
        $sess->register("challenge");
    }
	
		include("$ABSOLUTE_PATH_STUDIP/crcloginform.ihtml");
	}
	
	function auth_validatelogin() {
		global $username, $password, $challenge, $response, $resolution;
		global $_language, $_language_path;
		
		// check for direct link  
		if (!isset($_language) || $_language == "") {
			$_language = get_accepted_languages();
		}		
		
		$_language_path = init_i18n($_language);
		
		
		$this->auth["uname"] = $username;	// This provides access for "loginform.ihtml"
		$this->auth["jscript"] = ($resolution != "");
		if ($this->auth['jscript'] && $challenge){
			$password = $response;
		}
		$check_auth = StudipAuthAbstract::CheckAuthentication($username,$password,$this->auth['jscript']);
		if ($check_auth['uid']){
			$uid = $check_auth['uid'];
			$this->db->query(sprintf("select username,perms,auth_plugin from %s where user_id = '%s'",$this->database_table,$uid));
			$this->db->next_record();
			$this->auth["perm"]  = $this->db->f("perms");
			$this->auth["uname"] = $this->db->f("username");
			$this->auth["auth_plugin"]  = $this->db->f("auth_plugin");
			$this->auth_set_user_settings($uid);
			return $uid;
		} else {
			$this->error_msg = $check_auth['error'];
			return false;
		}
	}
	
	function auth_set_user_settings($uid){
		global $resolution, $_language;
		$divided = explode("x",$resolution);
		$this->auth["xres"] = ($divided[0]) ? $divided[0] : 800; //default
		$this->auth["yres"] = ($divided[1]) ? $divided[1] : 600; //default
		// Change X-Resulotion on Multi-Screen Systems (as Matrox Graphic-Adapters are)
		if (($this ->auth["xres"] / $this ->auth["yres"]) > 1.5){
			$this->auth["xres"] = $this->auth["xres"] /2;
		}
		//restore user-specific language preference
		$db = new DB_Seminar("SELECT preferred_language FROM user_info WHERE user_id='$uid'");
		if ($db->next_record()) {
			if ($db->f("preferred_language")) {
				// we found a stored setting for preferred language
				$_language = $db->f("preferred_language");
			}
		}
	}
}

class Seminar_Default_Auth extends Seminar_Auth {
	var $classname = "Seminar_Default_Auth";
	
	var $nobody    = true;
	
	function Seminar_Default_Auth(){
		Seminar_Auth::Seminar_Auth();
	}
}


class Seminar_Register_Auth extends Seminar_Auth {
	var $classname = "Seminar_Register_Auth";
	var $magic     = "dsdfjhgretha";  // Challenge seed
	
	var $mode      = "reg";
	var $error_msg = ""; // Was l�uft falsch bei der Registrierung ?
	
	function auth_registerform() {
		global $sess;
		global $challenge,$ABSOLUTE_PATH_STUDIP;
		
		$challenge = md5(uniqid($this->magic));
		$sess->register("challenge");
		
		include("$ABSOLUTE_PATH_STUDIP/crcregister.ihtml");
	}
	
	function auth_doregister() {
		global $username, $password, $challenge, $response, $Vorname, $Nachname, $geschlecht, $Email,$title_front,$title_front_chooser,$title_rear,$title_rear_chooser,$ABSOLUTE_PATH_STUDIP, $CANONICAL_RELATIVE_PATH_STUDIP, $UNI_NAME_CLEAN, $DEFAULT_LANGUAGE;
		
		global $_language, $_language_path;
		
		// check for direct link to register2.php 
		if (!isset($_language) || $_language == "") {
			$_language = get_accepted_languages();
		}		
		
		$_language_path = init_i18n($_language);
		
		$this->auth["uname"]=$username;					// This provides access for "crcregister.ihtml"
		
		$validator=new email_validation_class;	// Klasse zum Ueberpruefen der Eingaben
		$validator->timeout=10;									// Wie lange warten wir auf eine Antwort des Mailservers?
		
		
		$username = trim($username);
		$Vorname = trim($Vorname);
		$Nachname = trim($Nachname);
		$Email = trim($Email);
		
		if (!$validator->ValidateUsername($username))
		{
			$this->error_msg=$this->error_msg. _("Der gew�hlte Username ist zu kurz!") . "<br>";
			return false;
		}														// username syntaktisch falsch oder zu kurz
		// auf doppelte Vergabe wird weiter unten getestet.
		
		if (!isset($response) || $response=="")	{	// wir haben kein verschluesseltes Passwort
			if (!$validator->ValidatePassword($password))
			{
				$this->error_msg=$this->error_msg. _("Das Passwort ist zu kurz!") . "<br>";
				return false;
			}													// also k�nnen wir das unverschluesselte Passwort testen
		}
		
		if (!$validator->ValidateName($Vorname))
		{
			$this->error_msg=$this->error_msg. _("Der Vorname fehlt oder ist unsinnig!") . "<br>";
			return false;
		}			   // Vorname nicht korrekt oder fehlend
		if (!$validator->ValidateName($Nachname))
		{
			$this->error_msg=$this->error_msg. _("Der Nachname fehlt oder ist unsinnig!") . "<br>";
			return false;			   // Nachname nicht korrekt oder fehlend
		}
		if (!$validator->ValidateEmailAddress($Email))
		{
			$this->error_msg=$this->error_msg. _("Die E-Mail-Adresse fehlt oder ist falsch geschrieben!") . "<br>";
			return false;
		}			   // E-Mail syntaktisch nicht korrekt oder fehlend
		
		$smtp=new studip_smtp_class;		     // Einstellungen fuer das Verschicken der Mails
		$REMOTE_ADDR=getenv("REMOTE_ADDR");
		$Zeit=date("H:i:s, d.m.Y",time());
		
		if (!$validator->ValidateEmailHost($Email)) {     // Mailserver nicht erreichbar, ablehnen
			$this->error_msg=$this->error_msg. _("Der Mailserver ist nicht erreichbar, bitte �berpr�fen Sie, ob Sie E-Mails mit der angegebenen Adresse verschicken und empfangen k�nnen!") . "<br>";
			return false;
		} else {					  // Server ereichbar
			if (!$validator->ValidateEmailBox($Email)) {    // aber user unbekannt. Mail an abuse@puk!
				$from="wwwrun@".$smtp->localhost;
				$to="abuse@".$smtp->localhost;
				$smtp->SendMessage(
				$from, array($to),
				array("From: $from", "To: $to", "Subject: Register"),
				"Emailbox unbekannt\n\nUser: $username\nEmail: $Email\n\nIP: $REMOTE_ADDR\nZeit: $Zeit\n");
				$this->error_msg=$this->error_msg. _("Die angegebene E-Mail-Adresse ist nicht erreichbar, bitte �berpr�fen Sie Ihre Angaben!") . "<br>";
				return false;
			} else {
				;					     // Alles paletti, jetzt kommen die Checks gegen die Datenbank...
			}
		}
		
		$this->db->query(sprintf("select user_id ".
		"from %s where username = '%s'",
		$this->database_table,
		addslashes($username)));
		
		while($this->db->next_record()) {
			//   error_log("username schon vorhanden", 0);
			$this->error_msg=$this->error_msg. _("Der gew�hlte Username ist bereits vorhanden!") . "<br>";
			return false;				   // username schon vorhanden
		}
		
		$this->db->query(sprintf("select user_id ".
		"from %s where Email = '%s'",
		$this->database_table,
		addslashes($Email)));

		while($this->db->next_record()) {
			//error_log("E-Mail schon vorhanden", 0);
			$this->error_msg=$this->error_msg. _("Die angegebene E-Mail-Adresse wird bereits von einem anderen User verwendet. Sie m�ssen eine andere E-Mail-Adresse angeben!") . "<br>";
			return false;				   // Email schon vorhanden
		}
		
		// alle Checks ok, Benutzer registrieren...
		// True when JS is disabled
		if ($response == "") {
			$newpass = md5($password);
		}
		// Response is set, JS is enabled
		else {
			$newpass = $response;
		}
		$uid = md5(uniqid($this->magic));
		$perm = "user";
		$this->db->query(sprintf("insert into %s (user_id, username, perms, password, Vorname, Nachname, Email) ".
		"values ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
		$this->database_table, $uid, addslashes($username), $perm, $newpass,
		addslashes($Vorname), addslashes($Nachname), addslashes($Email)));
		$this->auth["perm"] = $perm;
		
		if($title_front == "")
			$title_front = $title_front_chooser;
		
		if($title_rear == "")
			$title_rear = $title_rear_chooser;
		
		// Anlegen eines korespondierenden Eintrags in der user_info
		$this->db->query("INSERT INTO user_info SET user_id='$uid', mkdate='".time()."', geschlecht='$geschlecht', title_front='$title_front', title_rear='$title_rear'");
		
		// Abschicken der Bestaetigungsmail
		$to=$Email;
		$secret= md5("$uid:$this->magic");
		$url = "http://" . $smtp->localhost . $CANONICAL_RELATIVE_PATH_STUDIP . "email_validation.php?secret=" . $secret;

		// include language-specific subject and mailbody
		include_once("$ABSOLUTE_PATH_STUDIP"."locale/$_language_path/LC_MAILS/register_mail.inc.php");

		$smtp->SendMessage(
		$smtp->env_from, array($to),
		array("From: $smtp->from", "Reply-To: $smtp->abuse", "To: $to", "Subject: $subject"),
		$mailbody);
		
		return $uid;
	}
}



class Seminar_Perm extends Perm {
	var $classname = "Seminar_Perm";
	
	var $permissions = array(
	"user"       => 1,
	"autor"      => 3,
	"tutor"	     => 7,
	"dozent"     => 15,
	"admin"      => 31,
	"root"       => 63
	);
	var $studip_perms = array();
	var $fak_admins = array();
	
	function perm_invalid($does_have, $must_have) {
		global $perm, $auth, $sess;
		global $ABSOLUTE_PATH_STUDIP,$RELATIVE_PATH_CHAT;
		include($ABSOLUTE_PATH_STUDIP . "perminvalid.ihtml");
	}
	
	function get_studip_perm($range_id, $user_id = false) {
		global $auth;
		if (!$range_id){
			return false;
		}
		$db=new DB_Seminar;
		$status = false;
		if (!$user_id){
			$user_id = $auth->auth["uid"];
			$user_perm = $auth->auth["perm"];
		} else {
			$db->query("SELECT perms FROM auth_user_md5 WHERE user_id = '$user_id'");
			if (!$db->next_record()){
				return false;
			} else {
				$user_perm = $db->f(0);
			}
		}
		if ($user_perm == "root") {
			return "root";
		} elseif (isset($this->studip_perms[$range_id][$user_id])) {
			return $this->studip_perms[$range_id][$user_id];
		} elseif ($user_perm == "admin") {
			$db->query("SELECT seminare.Seminar_id FROM user_inst 
						LEFT JOIN seminare USING (Institut_id)
						WHERE inst_perms='admin' AND user_id='$user_id' AND seminare.Seminar_id='$range_id'");
			if ($db->num_rows()) {
				$status = "admin";
			} else {
				$db->query("SELECT Seminar_id FROM user_inst a LEFT JOIN Institute b ON(a.Institut_id=b.Institut_id AND b.Institut_id=b.fakultaets_id) 
							LEFT JOIN Institute c ON (b.Institut_id=c.fakultaets_id) LEFT JOIN seminare d USING(Institut_id) WHERE a.user_id='$user_id' AND a.inst_perms='admin' AND d.Seminar_id='$range_id'");
				if ($db->num_rows()) {
					$status = "admin";
				} else {
					$db->query("SELECT a.Institut_id FROM user_inst a LEFT JOIN Institute b ON(a.Institut_id=b.fakultaets_id) WHERE user_id='$user_id' AND a.inst_perms='admin'
								AND b.Institut_id='$range_id'");
					if ($db->num_rows()) {
						$status = "admin";
					}
				}
			}
		}
		
		if ($status) {
			$this->studip_perms[$range_id][$user_id] = $status;
			return $status;
		}
		
		$db->query("SELECT status FROM seminar_user WHERE user_id='$user_id' AND Seminar_id='$range_id'");
		if ($db->next_record()){
			$status=$db->f("status");
		} else {
			$db->query("SELECT inst_perms FROM user_inst WHERE user_id='$user_id' AND Institut_id='$range_id'");
			if ($db->next_record()){
				$status=$db->f("inst_perms");
			}
		}
		$this->studip_perms[$range_id][$user_id] = $status;
		return $status;
	}
	
	function have_studip_perm($perm,$range_id) {
		
		if (!$perm || !$range_id){
			return false;
		}
		$pageperm = split(",", $perm);
		$userperm = split(",", $this->get_studip_perm($range_id));
		
		list ($ok0, $pagebits) = $this->permsum($pageperm);
		list ($ok1, $userbits) = $this->permsum($userperm);
		
		$has_all = (($userbits & $pagebits) == $pagebits);
		
		if (!($has_all && $ok0 && $ok1) ) {
			return false;
		} else {
			return true;
		}
	}
	
	function is_fak_admin($user_id = ""){
		global $auth;
		$user_id = $auth->auth["uid"];
		$user_perm = $auth->auth["perm"];
		if ($user_perm == "root") {
			return true;
		}
		if (isset($this->fak_admins[$user_id])){
			return $this->fak_admins[$user_id];
		} else {
			$db = new DB_Seminar("SELECT a.Institut_id FROM user_inst a LEFT JOIN Institute b ON(a.Institut_id=b.Institut_id AND b.Institut_id=b.fakultaets_id)  
									WHERE a.user_id='$user_id' AND a.inst_perms='admin' AND NOT ISNULL(b.Institut_id)");
			if ($db->next_record()){
				$this->fak_admins[$user_id] = true;
				return true;
			} else {
				$this->fak_admins[$user_id] = false;
				return false;
			}
		}
	}
}
?>
