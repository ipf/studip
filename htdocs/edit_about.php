<?php
/*
about_edit.php - �ndern der pers�nlichen Userseiten von Stud.IP
Copyright (C) 2000 Ralf Stockmann <rstockm@gwdg.de>, Stefan Suchi <suchi@gmx.de>, Niklas Nohlen <nnohlen@gwdg.de>,
Miro Freitag <mfreita@goe.net>, Andr� Noack <andre.noack@gmx.net>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Default_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$auth->login_if(!$logout && ($auth->auth["uid"] == "nobody"));

if ($usr_name)  $username=$usr_name; //wenn wir von den externen Seiten kommen, nehmen wir den Usernamen aus usr_name, falls dieser gesetzt ist, um die Anmeldeprozedur nicht zu verwirren....

require_once("$ABSOLUTE_PATH_STUDIP/config.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/kategorien.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/msg.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/messaging.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/visual.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/functions.php");
require_once("$ABSOLUTE_PATH_STUDIP/statusgruppe.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/language.inc.php");

include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

// Klassendefinition

class about extends messaging {

var $db;     //unsere Datenbankverbindung
var $auth_user = array();        // assoziatives Array, enth�lt die Userdaten aus der Tabelle auth_user_md5
var $user_info = array();        // assoziatives Array, enth�lt die Userdaten aus der Tabelle user_info
var $user_inst = array();        // assoziatives Array, enth�lt die Userdaten aus der Tabelle user_inst
var $user_studiengang = array(); // assoziatives Array, enth�lt die Userdaten aus der Tabelle user_studiengang
var $check="";    //Hilfsvariable f�r den Rechtecheck
var $special_user=FALSE;  // Hilfsvariable f�r bes. Institutsfunktionen
var $msg = ""; //enth�lt evtl Fehlermeldungen
var $max_file_size = 100; //max Gr��e der Bilddatei in KB
var $uploaddir = "./user"; //Uploadverzeichnis f�r Bilder
var $logout_user = FALSE; //Hilfsvariable, zeigt an, ob der User ausgeloggt werden mu�
var $priv_msg="";  //�nderungsnachricht bei Adminzugriff
var $default_url="http://www"; //default fuer private URL


function about($username,$msg) {  // Konstruktor, pr�ft die Rechte
	global $user,$perm,$auth;

	$this->db = new DB_Seminar;
	$this->get_auth_user($username);
	$this->msg = rawurldecode($msg); //Meldungen restaurieren

	if ($auth->auth["uname"] == $username AND $perm->have_perm("autor")) $this->check="user"; // der user selbst nat�rlich auch
	elseif ($auth->auth["perm"]=="admin") {     //bei admins schauen wir mal
		$this->db->query("SELECT a.user_id FROM user_inst AS a LEFT JOIN user_inst AS b USING (Institut_id) WHERE (b.inst_perms='admin' AND b.user_id='$user->id') AND (a.user_id='".$this->auth_user["user_id"]."' AND a.inst_perms IN ('dozent','tutor','autor'))");
		if ($this->db->num_rows()) 
			$this->check="admin";

		if ($perm->is_fak_admin()){
			$this->db->query("SELECT c.user_id FROM user_inst a LEFT JOIN Institute b ON(a.Institut_id=b.fakultaets_id)  LEFT JOIN user_inst c USING(Institut_id) WHERE a.user_id='$user->id' AND a.inst_perms='admin' AND c.user_id='".$this->auth_user["user_id"]."'");
			if ($this->db->next_record()) 
				$this->check="admin";
		}
	}
	elseif ($auth->auth["perm"]=="root")
		$this->check="admin";  //root darf mal wieder alles
	else
		$this->check="";

	if ($this->auth_user["username"]=="")
		$this->check="";    //hier ist wohl was falschgelaufen...

	return;
}


function get_auth_user($username) {
	$this->db->query("SELECT * FROM auth_user_md5 WHERE username = '$username'");  //ein paar userdaten brauchen wir schon mal
	if ($this->db->next_record()) {
		$fields = $this->db->metadata();
		for ($i=0; $i<count($fields); $i++) {
			$field_name = $fields[$i]["name"];
			$this->auth_user[$field_name] = $this->db->f("$field_name");
		}
	}
}

function get_user_details() {       // f�llt die arrays  mit Daten
	$this->db->query("SELECT * FROM user_info WHERE user_id = '".$this->auth_user["user_id"]."'");
	if ($this->db->next_record()) {
		$fields = $this->db->metadata();
		for ($i=0; $i<count($fields); $i++) {
			$field_name = $fields[$i]["name"];
			$this->user_info[$field_name] = $this->db->f("$field_name");
			if (!$this->user_info["Home"])
				$this->user_info["Home"]=$this->default_url;
		}
	}

	$this->db->query("SELECT user_studiengang.*,studiengaenge.name FROM user_studiengang LEFT JOIN studiengaenge USING (studiengang_id) WHERE user_id = '".$this->auth_user["user_id"]."' ORDER BY studiengang_id");
	while ($this->db->next_record()) {
		$this->user_studiengang[$this->db->f("studiengang_id")] = array ("name" => $this->db->f("name"));
	}


	$this->db->query("SELECT user_inst.*,Institute.Name FROM user_inst LEFT JOIN Institute USING (Institut_id) WHERE user_id = '".$this->auth_user["user_id"]."' ORDER BY Institut_id");
	while ($this->db->next_record()) {
		$this->user_inst[$this->db->f("Institut_id")] = array ("inst_perms" => $this->db->f("inst_perms"), "sprechzeiten" => $this->db->f("sprechzeiten"), "raum" => $this->db->f("raum"), "Telefon" => $this->db->f("Telefon"), "Fax" => $this->db->f("Fax"), "Name" => $this->db->f("Name"));
		if ($this->db->f("inst_perms")!="user")
			$this->special_user=TRUE;
	}

	return;
}

function imaging($img,$img_size,$img_name) {
	global $DJPEG_PATH, $CJPEG_PATH, $PNMSCALE_PATH, $GIFTOPNM_PATH;

	if ($img_size > ($this->max_file_size*1024)) { //Bilddatei ist zu gro�
		$this->msg = "error�" . sprintf(_("Die hochgeladene Bilddatei ist %s KB gro�!<br>Die maximale Dateigr��e betr�gt %s KB!"), round($img_size/1024), $this->max_file_size);
		return;
	}
	
	if (!$img_name) { //keine Datei ausgew�hlt!
		$this->msg = "error�" . _("Sie haben keine Datei zum hochladen ausgew�hlt!");
		return;
	}

	//Dateiendung bestimmen
	$dot = strrpos($img_name,".");
	if ($dot) {
		$l = strlen($img_name) - $dot;
		$ext = strtolower(substr($img_name,$dot+1,$l));
	}
	//passende Endung ?
	if ($ext != "jpg" && $ext != "gif" ) {
		$this->msg = "error�" . sprintf(_("Der Dateityp der Bilddatei ist falsch (%s)!<br>Es sind nur die Dateiendungen .gif und .jpg erlaubt!"), $ext);
		return;
	}

	//na dann kopieren wir mal...
	$newfile = $this->uploaddir . "/".$this->auth_user["user_id"].".jpg";
	if(!@copy($img,$newfile)) {
		$this->msg = "error�" . _("Fehler beim kopieren der Datei!");
		return;
	} else {
		$imgsize = GetImageSize($img);
		// Check picture size
		if (($imgsize[0] > 200) || ($imgsize[1] > 250)) {
			//Temporaere Datei
			$tmpimg = "/tmp/tmp.pnm";
			//Konvertierung nach PNM
			if ($ext == "jpg") {
				system($DJPEG_PATH ." $newfile >$tmpimg");
			}
			else if ($ext == "gif") {
				system($GIFTOPNM_PATH ." $newfile >$tmpimg");
			}
			system($PNMSCALE_PATH ." -xysize 200 250 $tmpimg | ". $CJPEG_PATH ." -smoo 10 -qual 60 >$newfile");
		}

		$this->msg = "msg�" . _("Die Bilddatei wurde erfolgreich hochgeladen! Eventuell sehen Sie das neue Bild erst nach einem Reload dieser Seite.");
		setTempLanguage($this->auth_user["user_id"]);
		$this->priv_msg = _("Eine neue Bilddatei wurde hochgeladen.\n");
		restoreLanguage();
	}
	return;
}


function studiengang_edit($studiengang_delete,$new_studiengang) {
	if (is_array($studiengang_delete)) {
		for ($i=0; $i < count($studiengang_delete); $i++) {
			$this->db->query("DELETE FROM user_studiengang WHERE user_id='".$this->auth_user["user_id"]."' AND studiengang_id='$studiengang_delete[$i]'");
			if (!$this->db->affected_rows())
				$this->msg = $this->msg."error�" . sprintf(_("Fehler beim L&ouml;schen in user_studiengang bei ID=%s"), $studiengang_delete[$i]) . "�";
		}
	}

	if ($new_studiengang) {
		$this->db->query("INSERT INTO user_studiengang (user_id,studiengang_id) VALUES ('".$this->auth_user["user_id"]."','$new_studiengang')");
		if (!$this->db->affected_rows())
			$this->msg = $this->msg."error�" . sprintf(_("Fehler beim Einf&uuml;gen in user_studiengang bei ID=%s"), $new_studiengang) . "�";
	}

	if ( ($studiengang_delete || $new_studiengang) && !$this->msg) {
		$this->msg = "msg�" . _("Die Zuordnung zu Studieng�ngen wurde ge&auml;ndert.");
		setTempLanguage($this->auth_user["user_id"]);
		$this->priv_msg= _("Die Zuordnung zu Studieng�ngen wurde ge�ndert!\n");
		restoreLanguage();
	}

	return;
}



function inst_edit($inst_delete,$new_inst) {
	if (is_array($inst_delete)) {
		for ($i=0; $i < count($inst_delete); $i++) {
			$this->db->query("DELETE FROM user_inst WHERE user_id='".$this->auth_user["user_id"]."' AND Institut_id='$inst_delete[$i]'");
			if (!$this->db->affected_rows())
				$this->msg = $this->msg . "error�" . sprintf(_("Fehler beim L&ouml;schen in user_inst bei ID=%s"), $inst_delete[$i]) . "�";
		}
	}

	if ($new_inst) {
		$this->db->query("INSERT INTO user_inst (user_id,Institut_id,inst_perms) VALUES ('".$this->auth_user["user_id"]."','$new_inst','user')");
		if (!$this->db->affected_rows())
			$this->msg = $this->msg . "error�" . sprintf(_("Fehler beim Einf&uuml;gen in user_inst bei ID=%s"), $new_inst) . "�";
	}

	if ( ($inst_delete || $new_inst) && !$this->msg) {
		$this->msg = "msg�" . _("Die Zuordnung zu Einrichtungen wurde ge&auml;ndert.");
		setTempLanguage($this->auth_user["user_id"]);
		$this->priv_msg= _("Die Zuordnung zu Einrichtungen wurde ge�ndert!\n");
		restoreLanguage();
	}

	return;
}

function special_edit($raum,$sprech,$tel,$fax,$name) {
	if (is_array($raum)) {
		while (list($inst_id,$detail) = each ($raum)) {
			$this->db->query("UPDATE user_inst SET raum='$detail', sprechzeiten='$sprech[$inst_id]', Telefon='$tel[$inst_id]', Fax='$fax[$inst_id]' WHERE Institut_id='$inst_id' AND user_id='".$this->auth_user["user_id"]."'");
			if ($this->db->affected_rows()) {
				$this->msg = $this->msg . "msg�" . sprintf(_("Ihre Daten an der Einrichtung %s wurden ge&auml;ndert"), $name[$inst_id]) . "�";
				setTempLanguage($this->auth_user["user_id"]);
				$this->priv_msg = $this->priv_msg . sprintf(_("Ihre Daten an der Einrichtung %s wurden ge�ndert.\n"), $name[$inst_id]);
				restoreLanguage();
			}
		}
	}
	return;
}

function edit_leben($lebenslauf,$schwerp,$publi,$view) {
	//check ob die blobs ver�ndert wurden...
	$this->db->query("SELECT  lebenslauf, schwerp, publi FROM user_info WHERE user_id='".$this->auth_user["user_id"]."'");
	$this->db->next_record();
	if ($lebenslauf!=$this->db->f("lebenslauf") || $schwerp!=$this->db->f("schwerp") || $publi!=$this->db->f("publi")) {
		$this->db->query("UPDATE user_info SET lebenslauf='$lebenslauf', schwerp='$schwerp', publi='$publi', chdate='".time()."' WHERE user_id='".$this->auth_user["user_id"]."'");
		$this->msg = $this->msg . "msg�" . _("Daten an Lebenslauf u.a. ge&auml;ndert") . "�";
		setTempLanguage($this->auth_user["user_id"]);
		$this->priv_msg = _("Daten an Lebenslauf u.a. wurden ge�ndert.\n");
		restoreLanguage();
	}
}


function edit_pers($password,$check_pass,$response,$new_username,$vorname,$nachname,$email,$telefon,$anschrift,$home,$hobby,$geschlecht,$title_front,$title_front_chooser,$title_rear,$title_rear_chooser,$view) {
	global $UNI_NAME_CLEAN; 

	//erstmal die "unwichtigen" Daten
	if ($home==$this->default_url)
		$home='';
	if($title_front == "")
		$title_front = $title_front_chooser;
	if($title_rear == "")
		$title_rear = $title_rear_chooser;

	$this->db->query("UPDATE user_info SET privatnr='$telefon', privadr='$anschrift', Home='$home', hobby='$hobby', geschlecht='$geschlecht',
					title_front='$title_front',title_rear='$title_rear',chdate='".time()."' WHERE user_id='".$this->auth_user["user_id"]."'");
	if ($this->db->affected_rows()) {
		$this->msg = $this->msg . "msg�" . _("Ihre pers&ouml;nlichen Daten wurden ge&auml;ndert.") . "�";
		setTempLanguage($this->auth_user["user_id"]);
		$this->priv_msg = _("Ihre pers�nlichen Daten wurden ge�ndert.\n");
		restoreLanguage();
	}

	$new_username = trim($new_username);
	$vorname = trim($vorname);
	$nachname = trim($nachname);
	$email = trim($email);

	//nur n�tig wenn der user selbst seine daten �ndert
	if ($this->check == "user") {
		//erstmal die Syntax checken $validator wird in der local.inc.php benutzt, sollte also funzen
		$validator=new email_validation_class; ## Klasse zum Ueberpruefen der Eingaben
		$validator->timeout=10;

		if (($response && $response!=md5("*****")) || $password!="*****") {      //Passwort ver�ndert ?
		// auf doppelte Vergabe wird weiter unten getestet.
			if (!isset($response) || $response=="") { // wir haben kein verschluesseltes Passwort
	 			if (!$validator->ValidatePassword($password)) {
	 				$this->msg=$this->msg . "error�" . _("Das Pa�wort ist zu kurz!") . "�";
	 				return false;
	 			}
				if ($check_pass != $password) {
	 				$this->msg=$this->msg . "error�" . _("Die Wiederholung des Pa�wortes ist falsch! Bitte geben sie das exakte Pa�wort ein!") . "�";
	 				return false;
	 			}
				$newpass=md5($password);             // also k�nnen wir das unverschluesselte Passwort testen
			} else
				$newpass=$response;

			$this->db->query("UPDATE auth_user_md5 SET password='$newpass' WHERE user_id='".$this->auth_user["user_id"]."'");
			$this->msg=$this->msg . "msg�" . _("Ihr Passwort wurde ge&auml;ndert!") . "�";
		}

		if ($vorname!=$this->auth_user["Vorname"] || $nachname!=$this->auth_user["Nachname"]) { //Namen ver�ndert ?
			if (!$validator->ValidateName($vorname)) {
				$this->msg=$this->msg . "error�" . _("Der Vorname fehlt, oder ist unsinnig!") . "�";
	 			return false;
	 		}   // Vorname nicht korrekt oder fehlend
			if (!$validator->ValidateName($nachname)) {
				$this->msg=$this->msg . "error�" . _("Der Nachname fehlt, oder ist unsinnig!") . "�";
	 			return false;      
			}   // Nachname nicht korrekt oder fehlend
			$this->db->query("UPDATE auth_user_md5 SET Vorname='$vorname', Nachname='$nachname' WHERE user_id='".$this->auth_user["user_id"]."'");
			$this->msg=$this->msg . "msg�" . _("Ihr Name wurde ge&auml;ndert!") . "�";
		}

		if ($this->auth_user["username"] != $new_username) {
			if (!$validator->ValidateUsername($new_username)) {
	 			$this->msg=$this->msg . "error�" . _("Der gew�hlte Username ist zu kurz!") . "�";
		  	return false;
			}
	 		$this->db->query("SELECT username,Vorname,Nachname FROM auth_user_md5 WHERE username='$new_username'") ;
			if ($this->db->num_rows()) {
	 			$this->msg=$this->msg . "error�" . sprintf(_("Der Username wird bereits von einem anderen User (%s %s) verwendet. Bitte w�hlen sie einen Anderen!"), $this->db->f("Vorname"), $this->db->f("Nachname")) . "�";
				return false;
	 		}
			$this->db->query("UPDATE auth_user_md5 SET username='$new_username' WHERE user_id='".$this->auth_user["user_id"]."'");
			$this->msg=$this->msg . "msg�" . _("Ihr Username wurde ge&auml;ndert!") . "�";
			//Hotfix, sms auf neuen usernamen umbiegen
			$this->db->query("UPDATE globalmessages SET user_id_rec='$new_username' WHERE user_id_rec='".$this->auth_user["username"]."'");
			$this->db->query("UPDATE globalmessages SET user_id_snd='$new_username' WHERE user_id_snd='".$this->auth_user["username"]."'");
			$this->logout_user = TRUE;
		}

		if ($this->auth_user["Email"] != $email) {  //email wurde ge�ndert!
			$smtp=new studip_smtp_class;       ## Einstellungen fuer das Verschicken der Mails
			$REMOTE_ADDR=getenv("REMOTE_ADDR");
			$Zeit=date("H:i:s, d.m.Y",time());

	 		if (!$validator->ValidateEmailAddress($email)) {
				$this->msg=$this->msg . "error�" . _("Die E-Mail Addresse fehlt, oder ist falsch geschrieben!") . "�";
	 			return false;        // E-Mail syntaktisch nicht korrekt oder fehlend
	 		}

	 		if (!$validator->ValidateEmailHost($email)) {     // Mailserver nicht erreichbar, ablehnen
				$this->msg=$this->msg . "error�" . _("Der Mailserver ist nicht erreichbar. Bitte �berpr�fen Sie, ob Sie E-Mails mit der angegebenen Addresse verschicken k�nnen!") . "�";
				return false;
			} else {       // Server ereichbar
	 			if (!$validator->ValidateEmailBox($email)) {    // aber user unbekannt. Mail an abuse@localhost!
					$from="wwwrun@".$smtp->localhost;
					$to="abuse@".$smtp->localhost;
					$smtp->SendMessage(
					$from, array($to),
					array("From: $from", "To: $to", "Subject: edit_about"),
					"Emailbox unbekannt\n\nUser: ".$this->auth_user["username"]."\nEmail: $email\n\nIP: $REMOTE_ADDR\nZeit: $Zeit\n");
					$this->msg=$this->msg . "error�" . _("Die angegebene E-Mail Addresse ist nicht erreichbar. Bitte �berpr�fen Sie Ihre Angaben!") . "�";
					return false;
	 			}
			}

	  	$this->db->query("SELECT Email,Vorname,Nachname FROM auth_user_md5 WHERE Email='$email'") ;
	  	if ($this->db->next_record()) {
				$this->msg=$this->msg . "error�" . sprintf(_("Die angegebene E-Mail Addresse wird bereits von einem anderen User (%s %s) verwendet. Sie m�ssen eine andere E-Mail Addresse angeben!"), $this->db->f("Vorname"), $this->db->f("Nachname")) . "�";
				return false;
			}

			//email ist ok, user bekommt neues Passwort an diese Addresse

			$newpass=$this->generate_password(6);
			$hashpass=md5($newpass);
			// Mail abschicken...
			$to=$email;
			$url = "http://" . $smtp->localhost . $CANONICAL_RELATIVE_PATH_STUDIP;
			$mailbody="Dies ist eine Informationsmail des Systems\n"
			."\"Studienbegleitender Internetsupport Pr�senzlehre\"\n"
			."- $UNI_NAME_CLEAN -\n\n"
			."Ihr Passwort wurde um $Zeit neu gesetzt,\n"
			."da Sie Ihre Email Addresse ver�ndert haben!\n"
			."Die aktuellen Angaben lauten:\n\n"
			."Benutzername: $new_username\n"
			."Passwort: $newpass\n"
			."Status: ".$this->auth_user["perms"]."\n"
			."Vorname: $vorname\n"
			."Nachname: $nachname\n"
			."Email-Adresse: $email\n\n"
			."Das Passwort ist nur Ihnen bekannt. Bitte geben Sie es an niemanden\n"
			."weiter (auch nicht an einen Administrator), damit nicht Dritte in Ihrem\n"
			."Namen Nachrichten in das System einstellen k�nnen!\n\n"
			."Hier kommen Sie direkt ins System:\n"
			."$url\n\n";

			$smtp->SendMessage(
			$smtp->env_from, array($to),
			array("From: $smtp->from", "Reply-To: $smtp->abuse", "To: $to", "Subject: Passwort-�nderung Stud.IP"),
			$mailbody);

	 		$this->db->query("UPDATE auth_user_md5 SET Email='$email', password='$hashpass' WHERE user_id='".$this->auth_user["user_id"]."'");
	 		$this->msg=$this->msg . "msg�" . _("Ihre Email Addresse wurde ge�ndert!") . "�info�" . _("ACHTUNG!<br>Aus Sicherheitsgr�nden wurde auch ihr Pa�wort ge�ndert, es wurde an die neu angegebene Email Addresse geschickt!") . "�";
	 		$this->logout_user = TRUE;
		}
	}
	return;
}


function select_studiengang() {  //Hilfsfunktion, erzeugt eine Auswahlbox mit noch ausw�hlbaren Studieng�ngen

	echo "<select name=\"new_studiengang\" width=30><option selected></option>";
	$this->db->query("SELECT a.studiengang_id,a.name FROM studiengaenge AS a LEFT JOIN user_studiengang AS b ON (b.user_id='".$this->auth_user["user_id"]."' AND a.studiengang_id=b.studiengang_id) WHERE b.studiengang_id IS NULL ORDER BY a.name");

	while ($this->db->next_record()) {
		echo "<option value=\"".$this->db->f("studiengang_id")."\">".htmlReady(my_substr($this->db->f("name"),0,50))."</option>";
	}
	echo "</select>";

	return;
}


function select_inst() {  //Hilfsfunktion, erzeugt eine Auswahlbox mit noch ausw�hlbaren Instituten

	echo "<select name=\"new_inst\" width=30><option selected></option>";
	$this->db->query("SELECT a.Institut_id,a.Name FROM Institute AS a LEFT JOIN user_inst AS b ON (b.user_id='".$this->auth_user["user_id"]."' AND a.Institut_id=b.Institut_id) WHERE b.Institut_id IS NULL ORDER BY a.Name");

	while ($this->db->next_record()) {
		echo "<option value=\"".$this->db->f("Institut_id")."\">".htmlReady(my_substr($this->db->f("Name"),0,50))."</option>";
	}
	echo "</select>";

	return;
}


function generate_password($length) {      //Hilfsfunktion, erzeugt neues Passwort

	mt_srand((double)microtime()*1000000);
	for ($i=1;$i<=$length;$i++) {
		$temp = mt_rand() % 36;
		if ($temp < 10)
			$temp += 48;   // 0 = chr(48), 9 = chr(57)
		else
			$temp += 87;   // a = chr(97), z = chr(122)
		$pass .= chr($temp);
	}
	return $pass;
}



//Displays Errosmessages (kritischer Abbruch, Symbol "X")

function my_error($msg) {
?>
 <tr>
	<td class="blank" colspan=2>
	 <table border=0 align="left" cellspacing=0 cellpadding=2>
	<tr>
	 <td class="blank" align="center" width=50><img src="pictures/x.gif"></td>
	 <td class="blank" align="left" width="*"><font color=#FF2020><?php print $msg ?></font></td>
	</tr>
	 </table>
	</td>
 </tr>
 <tr>
	<td class="blank" colspan=2>&nbsp;</td>
 </tr>
<?
}


//Displays  Successmessages (Information &uuml;ber erfolgreiche Aktion, Symbol Haken)

function my_msg($msg) {
?>
 <tr>
	<td class="blank" colspan=2>
	 <table border=0 align="left" cellspacing=0 cellpadding=2>
	<tr>
	 <td class="blank" align="center" width=50><img src="pictures/ok.gif"></td>
	 <td class="blank" align="left" width="*"><font color=#008000><?php print $msg ?></font></td>
	</tr>
	 </table>
	</td>
 </tr>
 <tr>
	<td class="blank" colspan=2>&nbsp;</td>
 </tr>
<?
}

//Displays  Informationmessages  (Hinweisnachrichten, Symbol Ausrufungszeichen)

function my_info($msg) {
?>
 <tr>
	<td class="blank" colspan=2>
	 <table border=0 align="left" cellspacing=0 cellpadding=2>
	<tr>
	 <td class="blank" align="center" width=50><img src="pictures/ausruf.gif"></td>
	 <td class="blank" align="left" width="*"><font color=#008000><?php print $msg ?></font></td>
	</tr>
	 </table>
	</td>
 </tr>
 <tr>
	<td class="blank" colspan=2>&nbsp;</td>
 </tr>
<?
}

function parse_msg($long_msg,$separator="�") {

	$msg = explode ($separator,$long_msg);
	for ($i=0; $i < count($msg); $i=$i+2) {
		switch ($msg[$i]) {
			case "error" : $this->my_error($msg[$i+1]); break;
			case "info" : $this->my_info($msg[$i+1]); break;
			case "msg" : $this->my_msg($msg[$i+1]); break;
		}
	}
	return;
}


} // ende Klassendefinition





// hier gehts los
if (!$username) $username = $auth->auth["uname"];

$my_about = new about($username,$msg);
$cssSw = new cssClassSwitcher;

if ($logout)  // wir wurden gerade ausgeloggt...
	{
	
	// Start of Output
	include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
	include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head
	?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr><td class="topic" colspan=2><b>&nbsp;Daten ge&auml;ndert!</b></th></tr>
	<?

	$my_about->parse_msg($my_about->msg);
	$temp_string = "<br><font color=\"black\">"
		. sprintf(_("Um eine korrekte Authentifizierung mit ihren neuen Daten
		sicherzustellen, wurden sie automatisch ausgeloggt.<br>
	  Wenn sie ihre Email Addresse ge�ndert haben, m�ssen sie das ihnen an
		diese Addresse zugesandte Pa�wort verwenden!<br><br>
	  Ihr aktueller Username ist: <b>%s</b><br>"), $username)
		. "---> <a href=\"index.php?again=yes\">" . _("Login") . "</a> <---</font>";
	$my_about->my_info($temp_string);


	echo "</table></html>";
	page_close();
	die;
	}

//No Permission to change userdata
if (!$my_about->check)
 {
	// -- here you have to put initialisations for the current page
	// Start of Output
	include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
	include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head
	parse_window ("error�" . sprintf("Zugriff verweigert.<br /><font size=-1 color=black>Wahrscheinlich ist Ihre Session abgelaufen. Wenn sie sich l�nger als %s Minuten nicht im System bewegt haben, werden Sie automatisch abgemeldet. Bitte nutzen Sie in diesem Fall den untenstehenden Link, um zur�ck zur Anmeldung zu gelangen.<br /> <br /> Eine andere Ursache kann der Versuch des Zugriffs auf Userdaten, die Sie nicht bearbeiten d&uuml;rfen, sein. Nutzen Sie den untenstehenden Link, um zur�ck auf die Startseite zu gelangen.</font>", $AUTH_LIFETIME), "�",
	_("Zugriff auf Userdaten verweigert"), 
	_("<a href=\"index.php\"><b>&nbsp;Hier</b></a> geht es wieder zur Anmeldung beziehungsweise Startseite.<br />&nbsp;"));
	
	?>
	</body>
	</html>  
	<?
	page_close();
	die;
	}

//ein Bild wurde hochgeladen
if ($cmd=="copy")
 {
	$my_about->imaging($imgfile,$imgfile_size,$imgfile_name);
	}

//Ver�nderungen an Studieng�ngen
if ($cmd=="studiengang_edit")
 {
	$my_about->studiengang_edit($studiengang_delete,$new_studiengang);
	}

//Ver�nderungen an Instituten f�r Studies
if ($cmd=="inst_edit")
 {
	$my_about->inst_edit($inst_delete,$new_inst);
	}

//Ver�nderungen an Raum, Sprechzeit, etc
if ($cmd=="special_edit")
 {
	$my_about->special_edit($raum,$sprech,$tel,$fax,$name);
	}

//Ver�nderungen der pers. Daten
if ($cmd=="edit_pers")
 {

	$my_about->edit_pers($password,$check_pass,$response,$new_username,$vorname,$nachname,$email,$telefon,$anschrift,$home,$hobby,$geschlecht,$title_front,$title_front_chooser,$title_rear,$title_rear_chooser,$view);

	if (($my_about->auth_user["username"] != $new_username) && $my_about->logout_user == TRUE) $my_about->get_auth_user($new_username);   //username wurde ge�ndert!
	 else $my_about->get_auth_user($username);
	$username = $my_about->auth_user["username"];
	}

if ($cmd=="edit_leben")  {
	$my_about->edit_leben($lebenslauf,$schwerp,$publi,$view);
	$my_about->get_auth_user($username);
	}

// general settings from mystudip: language
if ($cmd=="change_general") {
	$my_about->db->query("UPDATE user_info SET preferred_language = '$forced_language' WHERE user_id='" . $my_about->auth_user["user_id"] ."'");
	$_language = $forced_language;
}

if ($my_about->logout_user)
 {
	$sess->delete();  // User logout vorbereiten
	$auth->logout();

 $timeout=(time()-300);
	 $sqldate = date("YmdHis", $timeout);
	$query = "UPDATE active_sessions SET changed = '$sqldate' WHERE sid = '$user->id'";
	$my_about->db->query($query);
	$msg = rawurlencode($my_about->msg);
	header("Location: $PHP_SELF?username=$username&msg=$msg&logout=1&view=$view"); //Seite neu aufrufen, damit user nobody wird...
	page_close();
	die;
	}

if ($cmd) {
	if (($my_about->check != "user") && ($my_about->priv_msg != "")) {
		$m_id=md5(uniqid("smswahn"));
		setTempLanguage($my_about->auth_user["user_id"]);
		$priv_msg = _("Ihre pers�nliche Seite wurde von einem Administrator ver�ndert.\n Folgende Ver�nderungen wurden vorgenommen:\n \n").$my_about->priv_msg;
		restoreLanguage();
		$my_about->insert_sms($my_about->auth_user["username"], $priv_msg);
	}
	$msg = rawurlencode($my_about->msg);
	header("Location: $PHP_SELF?username=$username&msg=$msg&view=$view");  //Seite neu aufrufen, um Parameter loszuwerden
	page_close();
	die;
}



// Start of Output
include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head

if ($auth->auth["jscript"]) { // nur wenn JS aktiv
?>
<script type="text/javascript" language="javascript" src="md5.js"></script>

<script type="text/javascript" language="javascript">
<!--

function oeffne()
{
	fenster=window.open('get_auto.php','','scrollbars=no,width=400,height=150','resizable=no');
	fenster.focus();
}
function checkusername(){
 var re_username = /^([a-zA-Z0-9_@-]*)$/;
 var checked = true;
 if (document.pers.new_username.value.length<4) {
	alert("Der Benutzername ist zu kurz \n- er sollte mindestens 4 Zeichen lang sein.");
	 document.pers.new_username.focus();
	checked = false;
	}
 if (re_username.test(document.pers.new_username.value)==false) {
	alert("Der Benutzername enth�lt unzul�ssige Zeichen\n- er darf keine Sonderzeichen oder Leerzeichen enthalten.");
	 document.pers.new_username.focus();
	checked = false;
	}
 return checked;
}

function checkpassword(){
 var checked = true;
 if (document.pers.password.value.length<4) {
	alert("Das Passwort ist zu kurz \n- es sollte mindestens 4 Zeichen lang sein.");
	 document.pers.password.focus();
	checked = false;
	}
 if (document.pers.password.value != document.pers.check_pass.value)
	{
	alert("Bei der Wiederholung des Pa�wortes ist ein Fehler aufgetreten! Bitte geben sie das exakte Pa�wort ein!");
	document.pers.check_pass.focus();
	checked = false;
	}

 return checked;
}

function checkvorname(){
 var re_vorname = /^([a-zA-Z���][^0-9"�'`\/\\\(\)\[\]]+)$/;
 var checked = true;
 if (re_vorname.test(document.pers.vorname.value)==false) {
	alert("Bitte geben Sie Ihren tats�chlichen Vornamen an.");
	 document.pers.vorname.focus();
	checked = false;
	}
 return checked;
}

function checknachname(){
 var re_nachname = /^([a-zA-Z���][^0-9"�'`\/\\\(\)\[\]]+)$/;
 var checked = true;
 if (re_nachname.test(document.pers.nachname.value)==false) {
	alert("Bitte geben Sie Ihren tats�chlichen Nachnamen an.");
	 document.pers.nachname.focus();
	checked = false;
	}
 return checked;
}

function checkemail(){
 var re_email = /^([_a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@([_a-zA-Z0-9-]+\.)+([a-zA-Z]{2,4})$/;
 var email = document.pers.email.value;
 var checked = true;
 if ((re_email.test(email))==false || email.length==0) {
	alert("Die E-Mail Adresse ist nicht korrekt!");
	 document.pers.email.focus();
	checked = false;
	}
 return checked;
}

function checkdata(){
 // kompletter Check aller Felder vor dem Abschicken
 var checked = true;
 if (!checkusername())
	checked = false;
 if (!checkpassword())
	checked = false;
 if (!checkvorname())
	checked = false;
 if (!checknachname())
	checked = false;
 if (!checkemail())
	checked = false;
 if (checked) {
	 document.pers.method = "post";
	 document.pers.action = "<?php print ("$PHP_SELF?cmd=edit_pers&username=$username&view=$view") ?>";
	 document.pers.response.value = MD5(document.pers.password.value);
	 document.pers.password.value = "*****";
	 document.pers.check_pass.value = "*****";
 }
 return checked;
}
// -->
</SCRIPT>

<?

} // Ende nur wenn JS aktiv

include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head


if (!$cmd)
 {
 // darfst du �ndern?? evtl erst ab autor ?
	$perm->check("user");
	$my_about->get_user_details();
	$username = $my_about->auth_user["username"];
	//maximale spaltenzahl berechnen
	 if ($auth->auth["jscript"]) $max_col = round($auth->auth["xres"] / 10 );
	 else $max_col =  64 ; //default f�r 640x480

// Reitersystem
include ("$ABSOLUTE_PATH_STUDIP/links_about.inc.php");  

//Kopfzeile bei allen eigenen Modulen ausgeben
if ($view!="Forum" AND $view!="calendar" AND $view!="Stundenplan" AND $view!="Messaging" AND $view!= "allgemein") {
	echo "<table class=\"blank\" cellspacing=0 cellpadding=0 border=0 width=\"100%\">";
		
	if ($username!=$auth->auth["uname"]) 
		echo "<tr><td class=\"topicwrite\" colspan=2><img src='pictures/einst.gif' border=0 align=texttop><b>&nbsp;";
	else 
		echo "<tr><td class=\"topic\" colspan=2><img src='pictures/einst.gif' border=0 align=texttop><b>&nbsp;";
	switch ($view) {
		case ("Bild") :
			echo _("Hochladen des pers&ouml;nlichen Bildes");
		break;
		case ("Daten") :
			echo _("Benutzerdaten bearbeiten");
		break;
		case ("Karriere") :
			if ($perm->have_perm ("tutor"))
				echo _("Studienkarriere und Einrichtungen bearbeiten");
			else
				echo _("Studienkarriere bearbeiten");
		break;
		case ("Lebenslauf") :
			if ($perm->have_perm ("dozent"))
				echo _("Lebenslauf, Arbeitsschwerpunkte und Publikationen bearbeiten");
			else
				echo _("Lebenslauf bearbeiten");
		break;
		case ("Sonstiges") :
			echo _("Eigene Kategorien bearbeiten");
		break;
		case ("Login") :
			echo _("Autologin einrichten");
		break;
	}
	
	if ($username!=$auth->auth["uname"]) { 
		echo "&nbsp; &nbsp; <font size=-1>";
		printf(_("Daten von: %s %s (%s), Status: %s"), $my_about->auth_user["Vorname"], $my_about->auth_user["Nachname"], $username, $my_about->auth_user["perms"]);
		echo "</font>";
	}

	echo "</b></td></tr>\n";
	echo "<tr><td class=\"blank\" colspan=\"2\">&nbsp;</td></tr>\n</table>\n<table class=\"blank\" cellspacing=0 cellpadding=2 border=0 width=\"100%\">";
}

// evtl Fehlermeldung ausgeben
if ($my_about->msg) {
	$my_about->parse_msg($my_about->msg);
}

if ($view=="Bild") {
	// hier wird das Bild ausgegeben
	$cssSw->switchClass();
	echo "<tr><td colspan=2 class=\"blank\"><blockquote><br />" . _("Auf dieser Seite k&ouml;nnen Sie ein pers&ouml;nliches Bild f&uuml;r Ihre Homepage hochladen.") . "<br><br><br></td></tr>";
	echo"<tr><td width=\"30%\" class=\"".$cssSw->getClass()."\" align=\"center\">";
	echo "<font size=-1><b>" . _("Aktuell angezeigtes Bild:") . "<br /><br /></b></font>";
	
	if (!file_exists("./user/".$my_about->auth_user["user_id"].".jpg")) {
		echo "<img src=\"./user/nobody.jpg\" width=\"200\" height=\"250\" alt=\"" . _("kein pers&ouml;nliches Bild vorhanden") . "\" ><br />&nbsp; ";
	} else {
		echo "<img border=\"1\" src=\"./user/".$my_about->auth_user["user_id"].".jpg\" alt=\"". $my_about->auth_user["Vorname"]." ".$my_about->auth_user["Nachname"]."\"><br />&nbsp; ";
	}
			
	echo "</td><td class=\"".$cssSw->getClass()."\" width=\"70%\" align=\"left\" valign=\"top\"><blockquote>";
	echo "<form enctype=\"multipart/form-data\" action=\"$PHP_SELF?cmd=copy&username=$username&view=Bild\" method=\"POST\">";
	echo "<br />" . _("Upload eines Bildes:") . "<br><br>" . _("1. W�hlen sie mit <b>Durchsuchen</b> eine Bilddatei von ihrer Festplatte aus.") . "<br><br>";
	echo "&nbsp;&nbsp;<input name=\"imgfile\" type=\"file\" style=\"width: 80%\" cols=".round($max_col*0.7*0.8)."><br><br>";
	echo _("2. Klicken sie auf <b>absenden</b>, um das Bild hochzuladen.") . "<br><br>";
	echo "&nbsp;&nbsp;<input type=\"IMAGE\" " . makeButton("absenden", "src") . " border=0 value=\"" . _("absenden") . "\"><br><br>";
	printf ("<b>ACHTUNG!</b><br>Die Bilddatei darf max. %s KB gro� sein, es sind nur Dateien mit den Endungen <b>.jpg</b> oder <b>.gif</b> erlaubt!", $my_about->max_file_size);
	echo "</blockquote></td></tr>";
}

if ($view=="Daten") {
	$cssSw->switchClass();
	//pers�nliche Daten...
	echo "<tr><td align=\"left\" valign=\"top\" class=\"blank\"><blockquote><br>" . _("Hier k�nnen sie Ihre Benutzerdaten ver�ndern.");
	echo "<br><font size=-1>" . sprintf(_("Alle mit einem Sternchen %s markierten Felder m&uuml;ssen ausgef&uuml;llt werden."), "</font><font color=\"red\" size=+1><b>*</b></font><font size=-1>") . "</font><br><br>";
	echo "<br></td></tr>\n<tr><td class=blank><table align=\"center\" width=99% class=blank border=0 cellpadding=2 cellspacing=0>";
	//Keine JavaScript �berpr�fung bei adminzugriff
	if ($my_about->check=="user" AND $auth->auth["jscript"]) {
		echo "<tr><form action=\"$PHP_SELF?cmd=edit_pers&username=$username&view=$view\" method=\"POST\" name=\"pers\" onsubmit=\"return checkdata()\">";
	} else {
		echo "<tr><form action=\"$PHP_SELF?cmd=edit_pers&username=$username&view=$view\" method=\"POST\" name=\"pers\">";
	}
	 
	if ($my_about->check=="user") {
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Username:") . " </td><td class=\"".$cssSw->getClass()."\" colspan=2 width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" size=\"".round($max_col*0.25)."\" name=\"new_username\" value=\"".$my_about->auth_user["username"]."\">&nbsp; <font color=\"red\" size=+2>*</font></td></tr>\n";
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Pa�wort:") . " </td><td class=\"".$cssSw->getClass()."\" nowrap width=\"20%\" align=\"left\"><font size=-1>&nbsp; " . _("neues Passwort:") . "</font><br />&nbsp; <input type=\"password\" size=\"".round($max_col*0.25)."\" name=\"password\" value=\"*****\"><input type=\"HIDDEN\" name=\"response\" value=\"\">&nbsp; <font color=\"red\" size=+2>*</font>&nbsp; </td><td class=\"".$cssSw->getClass()."\" width=\"60%\" nowrap align=\"left\"><font size=-1>&nbsp; " . _("Passwort-Wiederholung:") . "</font><br />&nbsp; <input type=\"password\" size=\"".round($max_col*0.25)."\" name=\"check_pass\" value=\"*****\">&nbsp; <font color=\"red\" size=+2>*</font></td></tr>\n";
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Name:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><font size=-1>&nbsp; " . _("Vorname:") . "</font><br />&nbsp; <input type=\"text\" size=\"".round($max_col*0.25)."\" name=\"vorname\" value=\"".$my_about->auth_user["Vorname"]."\">&nbsp; <font color=\"red\" size=+2>*</font></td><td class=\"".$cssSw->getClass()."\" nowrap width=\"60%\" align=\"left\"><font size=-1>&nbsp; " . _("Nachname:") . "</font><br />&nbsp; <input type=\"text\" size=\"".round($max_col*0.25)."\" name=\"nachname\" value=\"".$my_about->auth_user["Nachname"]."\">&nbsp; <font color=\"red\" size=+2>*</font></td></tr>\n";
	  $cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Email:") . " </td><td class=\"".$cssSw->getClass()."\" colspan=2 width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" size=\"".round($max_col*0.25)."\" name=\"email\" value=\"".$my_about->auth_user["Email"]."\">&nbsp; <font color=\"red\" size=+2>*</font></td></tr>\n";
	} else {
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Username:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"30%\" align=\"left\">&nbsp; ".$my_about->auth_user["username"]."</td><td width=\"50%\" rowspan=4 align=\"center\"><b><font color=\"red\">" . _("Adminzugriff hier nicht m�glich!") . "</font></b></td></tr>\n";
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Pa�wort:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"30%\" align=\"left\">&nbsp; *****</td></tr>\n";
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Name:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"30%\" align=\"left\">&nbsp; ".$my_about->auth_user["Vorname"]." ".$my_about->auth_user["Nachname"]."</td></tr>\n";
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Email:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"30%\" align=\"left\">&nbsp; ".$my_about->auth_user["Email"]."</td></tr>\n";
	}
	$cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Titel:") . " </td>
			<td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\">&nbsp;";
	echo "\n<select name=\"title_front_chooser\" onChange=\"document.pers.title_front.value=document.pers.title_front_chooser.options[document.pers.title_front_chooser.selectedIndex].text;\">";
	for($i = 0; $i < count($TITLE_FRONT_TEMPLATE); ++$i) {
		echo "\n<option";
		if ($TITLE_FRONT_TEMPLATE[$i] == $my_about->user_info['title_front']) {
			echo " selected ";
		}
		echo ">$TITLE_FRONT_TEMPLATE[$i]</option>";
	}	
	echo "</select></td><td class=\"".$cssSw->getClass()."\" width=\"60%\" align=\"left\">&nbsp;&nbsp;";
	echo "<input type=\"text\" size=\"".round($max_col*0.25)."\" name=\"title_front\" value=\"".$my_about->user_info['title_front']."\"></td></tr>\n";
	$cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\" nowrap><blockquote><b>" . _("Titel nachgest.:") . " </td>
			<td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\">&nbsp;";
	echo "\n<select name=\"title_rear_chooser\" onChange=\"document.pers.title_rear.value=document.pers.title_rear_chooser.options[document.pers.title_rear_chooser.selectedIndex].text;\">";
	for($i = 0; $i < count($TITLE_REAR_TEMPLATE); ++$i) {
		echo "\n<option";
		if($TITLE_REAR_TEMPLATE[$i] == $my_about->user_info['title_rear']) {
			echo " selected ";
		}
		echo ">$TITLE_REAR_TEMPLATE[$i]</option>";
	}	
	echo "</select></td><td class=\"".$cssSw->getClass()."\" width=\"60%\" align=\"left\">&nbsp;&nbsp;";
	echo "<input type=\"text\" size=\"".round($max_col*0.25)."\" name=\"title_rear\" value=\"".$my_about->user_info['title_rear']."\"></td></tr>\n";
	
	$cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Geschlecht:") . " </td><td class=\"".$cssSw->getClass()."\" colspan=2 nowrap width=\"80%\" align=\"left\"><font size=-1>&nbsp; " . _("m&auml;nnlich") . "&nbsp; <input type=\"RADIO\" name=\"geschlecht\" value=0 ";
	if (!$my_about->user_info["geschlecht"]) {
		echo "checked";
	}
	echo " />&nbsp; " . _("weiblich") . "&nbsp; <input type=\"RADIO\" name=\"geschlecht\" value=1 ";
	if ($my_about->user_info["geschlecht"]) {
		echo "checked";
	}
	echo " /></font></td></tr>";

	$cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"100%\" colspan=3 align=\"center\"><b>" . _("Optionale Angaben") . "</b></td></tr>\n";
	 $cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Telefon:") . " </td><td class=\"".$cssSw->getClass()."\" colspan=2 width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" size=\"".round($max_col*0.25)."\" name=\"telefon\" value=\"".htmlReady($my_about->user_info["privatnr"])."\"></td></tr>\n";
	 $cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Anschrift:") . " </td><td class=\"".$cssSw->getClass()."\" colspan=2 width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" size=\"".round($max_col*0.5)."\" name=\"anschrift\" value=\"".htmlReady($my_about->user_info["privadr"])."\"></td></tr>\n";
	 $cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Homepage:") . " </td><td class=\"".$cssSw->getClass()."\" colspan=2 width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" size=\"".round($max_col*0.5)."\" name=\"home\" value=\"".htmlReady($my_about->user_info["Home"])."\"></td></tr>\n";
	 $cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\"><blockquote><b>" . _("Hobbies:") . " </td><td class=\"".$cssSw->getClass()."\" colspan=2 width=\"80%\" align=\"left\">&nbsp; <textarea  name=\"hobby\"  style=\"width: 50%\" cols=".round($max_col*0.5)." rows=4 maxlength=250 wrap=virtual >".htmlReady($my_about->user_info["hobby"])."</textarea></td></tr>\n";
	 $cssSw->switchClass();
	echo "<tr><td class=\"".$cssSw->getClass()."\">&nbsp; </td><td class=\"".$cssSw->getClass()."\" colspan=2>&nbsp; <input type=\"IMAGE\" " . makeButton("uebernehmen", "src") . " border=0 value=\"" . _("�nderungen �bernehmen") . "\"></td></tr>\n</table>\n</td>";
}

	

if ($view=="Karriere") {
	
	if ($perm->have_perm("root") AND $username==$auth->auth["uname"]) {
		echo "<tr><td align=\"left\" valign=\"top\" class=\"blank\"><blockquote><br><br>" . _("Als Root haben Sie bereits genug Karriere gemacht ;-)") . "<br><br>";
	} else { 
		if (($perm->have_perm("tutor")) && (!$perm->have_perm("dozent")))
			echo "<tr><td align=\"left\" valign=\"top\" class=\"blank\"><blockquote><br>" . _("Hier k�nnen Sie Angaben &uuml;ber ihre Studienkarriere und Daten an Einrichtungen, an denen Sie arbeiten, machen.");
		elseif ($perm->have_perm("dozent"))
			echo "<tr><td align=\"left\" valign=\"top\" class=\"blank\"><blockquote><br>" . _("Hier k�nnen Sie Angaben &uuml;ber Daten an Einrichtungen, in den Sie arbeiten, machen.");	
		else
			echo "<tr><td align=\"left\" valign=\"top\" class=\"blank\"><blockquote><br>" . _("Hier k�nnen Sie Angaben &uuml;ber ihre Studienkarriere machen.");	
	
		echo "<br />&nbsp; </td></tr>";

		//Ver�ndern von Raum, Sprechzeiten etc
		if ($my_about->special_user) {
	 		reset ($my_about->user_inst);
	 		echo "<a name=\"inst_data\"></a>";   
	 		echo "<tr><td class=\"blank\">";
	 		echo "<b>&nbsp; " . _("Ich arbeite an folgenden Einrichtungen:") . "</b>";
	 		echo "<table cellspacing=0 cellpadding=2 border=0 align=\"center\" width=\"99%\" border=\"0\">";
	 		echo "<form action=\"$PHP_SELF?cmd=special_edit&username=$username&view=$view\" method=\"POST\">";

	 		while (list ($inst_id,$details) = each ($my_about->user_inst)) {
				$cssSw->resetClass();
				$cssSw->switchClass();    
				if ($details["inst_perms"]!= "user") {
	 				echo "<tr><td class=\"blank\">&nbsp; </td></tr>";
	 				echo "<tr><td class=\"".$cssSw->getClass()."\" colspan=\"2\" align=\"left\">&nbsp; <b>".htmlReady($details["Name"])."</b>";
					//statusgruppen
					if ($gruppen = GetStatusgruppen($inst_id, $my_about->auth_user["user_id"])) {
						echo ",&nbsp;Funktion(en): " . htmlReady(join(", ", array_values($gruppen)));
					}
					echo "<input type=\"HIDDEN\" name=\"name[$inst_id]\" value=\"".htmlReady($details["Name"])."\"></td></tr>";
	 				$cssSw->switchClass();
	 				echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\">" . _("Raum:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" style=\"width: 30%\" size=\"".round($max_col*0.25*0.6)."\"   name=\"raum[$inst_id]\" value=\"".htmlReady($details["raum"])."\"></td></tr>";
	 				$cssSw->switchClass();
	 				echo "<td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\">" . _("Sprechzeit:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" style=\"width: 30%\" size=\"".round($max_col*0.25*0.6)."\"   name=\"sprech[$inst_id]\" value=\"".htmlReady($details["sprechzeiten"])."\"></td></tr>";
	 				$cssSw->switchClass();
	 				echo "<td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\">" . _("Telefon:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" style=\"width: 30%\" size=\"".round($max_col*0.25*0.6)."\"   name=\"tel[$inst_id]\" value=\"".htmlReady($details["Telefon"])."\"></td></tr>";
	 				$cssSw->switchClass();
	 				echo "<td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"left\">" . _("Fax:") . " </td><td class=\"".$cssSw->getClass()."\" width=\"80%\" align=\"left\">&nbsp; <input type=\"text\" style=\"width: 30%\" size=\"".round($max_col*0.25*0.6)."\"   name=\"fax[$inst_id]\" value=\"".htmlReady($details["Fax"])."\"></td></tr>";
				}
			}
	 		$cssSw->switchClass();
	 		echo "<tr><td class=\"".$cssSw->getClass()."\">&nbsp; </td><td class=\"".$cssSw->getClass()."\">&nbsp; <input type=\"IMAGE\" " . makeButton("uebernehmen", "src") . " value=\"" . _("�nderungen �bernehmen") . "\"></td></table><br />&nbsp; </form></td></tr>";
		}
	}

	//Studieng�nge die ich belegt habe
	if ($my_about->auth_user["perms"]=="autor" || $my_about->auth_user["perms"]=="tutor") { // nur f�r Autoren und Tutoren
		$cssSw->resetClass();
		$cssSw->switchClass();
		echo "<tr><td class=\"blank\">";
		echo "<b>&nbsp; " . _("Ich bin in folgenden Studieng�ngen immatrikuliert:") . "</b>";
		echo "<table width= \"99%\" align=\"center\" border=0 cellpadding=2 cellspacing=0>\n";
		echo "<form action=\"$PHP_SELF?cmd=studiengang_edit&username=$username&view=$view#studiengaenge\" method=\"POST\">";
		echo "<tr><td width=\"30%\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
		reset ($my_about->user_studiengang);
		$flag=FALSE;

		$i=0;
		while (list ($studiengang_id,$details) = each ($my_about->user_studiengang)) {
			if (!$i) {
				echo "<tr><td class=\"steelgraudunkel\" width=\"80%\">" . _("Studiengang") . "</td><td class=\"steelgraudunkel\" width=\"30%\">" . _("austragen") . "</ts></tr>";
			}
			$cssSw->switchClass();
			echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"80%\">".htmlReady($details["name"])."</td><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"center\"><input type=\"CHECKBOX\" name=\"studiengang_delete[]\" value=\"$studiengang_id\"></td><tr>";
			$i++;
			$flag=TRUE;
		}

		if (!$flag) {
			echo "<tr><td class=\"".$cssSw->getClass()."\" colspan=\"2\"><br /><font size=-1><b>" . _("Sie haben sich noch keinem Studiengang zugeordnet.") . "</b><br /><br />" . _("Tragen Sie bitte hier die Angaben aus Ihrem Studierendenausweis ein!") . "</font></td><tr>";
		}
		$cssSw->resetClass();
		$cssSw->switchClass();
		echo "</table></td><td class=\"".$cssSw->getClass()."\" width=\"70%\" align=\"left\" valign=\"top\"><blockquote><br />" . _("W�hlen Sie die Studieng�nge auf Ihrem Studierendenausweis aus der folgenden Liste aus:") . "<br>";
		echo "<br><div align=\"center\">";
		echo "<a name=\"studiengaenge\"></a>";   
		$my_about->select_studiengang();
		echo "</div><br></b>" . _("Wenn Sie einen Studiengang wieder ausgetragen m�chten, markieren Sie die entsprechenden Felder in der linken Tabelle.") . "<br>";
		echo _("Mit einem Klick auf <b>&Uuml;bernehmen</b> werden die gew�hlten �nderungen durchgef�hrt.") . "<br /><br /> ";
		echo "<input type=\"IMAGE\" " . makeButton("uebernehmen", "src") . " value=\"" . _("�nderungen �bernehmen") . "\"></blockquote></td></tr>";
		echo "</form>";
	}
	echo "</td></tr></table>";


	//Institute, an denen studiert wird
	if ($my_about->auth_user["perms"]=="autor" || $my_about->auth_user["perms"]=="tutor") {
		$cssSw->resetClass();
		$cssSw->switchClass();
		echo "<tr><td class=\"blank\">";
		echo "<br><b>&nbsp; " . _("Ich studiere an folgenden Einrichtungen:") . "</b>";
		echo "<table width= \"99%\" align=\"center\" border=0 cellpadding=2 cellspacing=0>\n";
		echo "<form action=\"$PHP_SELF?cmd=inst_edit&username=$username&view=$view#einrichtungen\" method=\"POST\">";
		echo "<tr><td width=\"30%\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
		reset ($my_about->user_inst);
		$flag=FALSE;
		$i=0;
		while (list ($inst_id,$details) = each ($my_about->user_inst)) {
			if ($details["inst_perms"] == "user") {
	 			if (!$i) {
					echo "<tr><td class=\"steelgraudunkel\" width=\"80%\">" . _("Einrichtung") . "</td><td class=\"steelgraudunkel\" width=\"30%\">" . _("austragen") . "</ts></tr>";
				}
				$cssSw->switchClass();    
				echo "<tr><td class=\"".$cssSw->getClass()."\" width=\"80%\">".htmlReady($details["Name"])."</td><td class=\"".$cssSw->getClass()."\" width=\"20%\" align=\"center\"><input type=\"CHECKBOX\" name=\"inst_delete[]\" value=\"$inst_id\"></td><tr>";
	 			$i++;
	 			$flag=TRUE;
	 		}
		}
		if (!$flag) {
			echo "<tr><td class=\"".$cssSw->getClass()."\" colspan=\"2\"><br /><font size=-1><b>" . _("Sie haben sich noch keinen Einrichtungen zugeordnet.") . "</b><br /><br />" . _("Wenn Sie auf ihrer Homepage die Einrichtungen, an denen Sie studieren, auflisten wollen, k&ouml;nnen Sie diese Einrichtungen hier entragen.") . "</font></td><tr>";
		}
		$cssSw->resetClass();
		$cssSw->switchClass();    
		echo "</table></td><td class=\"".$cssSw->getClass()."\" width=\"70%\" align=\"left\" valign=\"top\"><blockquote><br />" . _("Um sich als Student einer Einrichtung zuzuordnen, w�hlen sie die entsprechende Einrichtung aus der folgenden Liste aus:") . "<br>";
		echo "<br><div align=\"center\">";
		echo "<a name=\"einrichtungen\"></a>";   
		$my_about->select_inst();
		echo "</div><br></b>" . _("Wenn sie aus Einrichtungen wieder ausgetragen werden m�chten, markieren sie die entsprechenden Felder in der linken Tabelle.") . "<br>";
		echo _("Mit einem Klick auf <b>&Uuml;bernehmen</b> werden die gew�hlten �nderungen durchgef�hrt.") . "<br /><br /> ";
		echo "<input type=\"IMAGE\" " . makeButton("uebernehmen", "src") . " value=\"" . _("�nderungen �bernehmen") . "\"></blockquote></td></tr>";
		echo "</form>";
	}
	echo "</td></tr></table>";
}   

if ($view=="Lebenslauf") {
	$cssSw->switchClass();
	if ($my_about->auth_user["perms"] == "dozent") {
		echo "<tr><td align=\"left\" valign=\"top\" class=\"blank\"><blockquote><br>" . _("Hier k�nnen sie Ihren Lebenslauf, Publikationen und Arbeitschwerpunkte bearbeiten.");
	} else {
		echo "<tr><td align=\"left\" valign=\"top\" class=\"blank\"><blockquote><br>" . _("Hier k�nnen sie Ihren Lebenslauf bearbeiten.");
	}  
	echo "<br>&nbsp; </td></tr>\n<tr><td class=blank><table align=\"center\" width=\"99%\" align=\"center\" border=0 cellpadding=2 cellspacing=0>";
	echo "<tr><form action=\"$PHP_SELF?cmd=edit_leben&username=$username&view=$view\" method=\"POST\" name=\"pers\">";
	echo "<td class=\"".$cssSw->getClass()."\" colspan=\"2\" align=\"left\" valign=\"top\"><b><blockquote>" . _("Lebenslauf:") . "</b><br>";
	echo "<textarea  name=\"lebenslauf\" style=\" width: 80%\" cols=".round($max_col/1.3)." rows=7 wrap=virtual>".htmlReady($my_about->user_info["lebenslauf"])."</textarea><a name=\"lebenslauf\"></a></td></tr>\n";
	if ($my_about->auth_user["perms"] == "dozent") {
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" colspan=\"2\" align=\"left\" valign=\"top\"><b><blockquote>" . _("Schwerpunkte:") . "</b><br>";
		echo "<textarea  name=\"schwerp\" style=\" width: 80%\" cols=".round($max_col/1.3)." rows=7 wrap=virtual>".htmlReady($my_about->user_info["schwerp"])."</textarea><a name=\"schwerpunkte\"></a></td></tr>\n";
		$cssSw->switchClass();
		echo "<tr><td class=\"".$cssSw->getClass()."\" colspan=\"2\" align=\"left\" valign=\"top\"><b><blockquote>" . _("Publikationen:") . "</b><br>";
		echo "<textarea  name=\"publi\" style=\" width: 80%\" cols=".round($max_col/1.3)." rows=7 wrap=virtual>".htmlReady($my_about->user_info["publi"])."</textarea><a name=\"publikationen\"></a></td></tr>\n";
	}
	echo "<tr><td class=\"steel1\" colspan=2><blockquote><br><input type=\"IMAGE\" " . makeButton("uebernehmen", "src") . " value=\"" . _("�nderungen �bernehmen") . "\"><br></blockquote></td></tr>\n</table>\n</td>";
}

if ($view=="Sonstiges") {
	if ($freie=="create_freie") create_freie();
	if ($freie=="delete_freie") delete_freie($freie_id);
	if ($freie=="update_freie") update_freie();
	if ($freie=="order_freie") order_freie($cat_id,$direction,$username);
	print_freie($username);
}

// Ab hier die Views der MyStudip-Sektion
if($view == "allgemein") {
	require_once("mystudip.inc.php");
	change_general_view();
}

if($view == "Forum") {
	require_once("forumsettings.inc.php");
}

if ($view == "Stundenplan") {
	require_once ("ms_stundenplan.inc.php");
	check_schedule_default();
	change_schedule_view();
}
	
if($view == "calendar") {
	require_once("$RELATIVE_PATH_CALENDAR/calendar_settings.inc.php");
}

if ($view == "Messaging") {
	require_once ("messagingSettings.inc.php");
	check_messaging_default();
	change_messaging_view();
}

if ($view=="Login") {
	if ($my_about->check=="user" && !$perm->have_perm("admin")) {
		echo "<tr><td colspan=2 class=blank><blockquote>";
		echo "<br><br>" . _("Um die automatische Anmeldung zu nutzen m�ssen sie ihre pers�nliche Login Datei auf ihren Rechner kopieren. Mit dem folgenden Link �ffnet sich ein Fenster, indem sie ihr Pa�wort eingeben m�ssen.") . " ";
		echo _("Dann wird die Datei erstellt und zu ihrem Rechner geschickt.") . "<br><br>";
		echo "<b><center><a href=\"javascript:oeffne();\">" . _("Autologin Datei erzeugen") . "</a></b></center>";
		echo "<br><br>" . _("<b>ACHTUNG!</b> Die automatische Anmeldung stellt eine gro�e Sicherheitsl�cke dar. Jeder, der Zugriff auf ihren Rechner hat, kann sich damit unter ihrem Namen in Stud.IP einloggen!");
		echo "</blockquote></td></tr>";
	} else {
		echo "<blockquote><br><br>" . _("Als Administrator d&uuml;rfen Sie dieses Feature nicht nutzen - tragen Sie Verantwortung!");
		echo "</blockquote></td></tr>";
	}
}	

////////////////

	echo "\n</table></td></tr></form>";
	echo "\n</table>";
	echo "</body>";
	echo "</html>";
}
page_close();


?>
