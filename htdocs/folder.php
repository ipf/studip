<?php
/*
folder.php - Anzeige und Verwaltung des Ordnersystems
Copyright (C) 2001 Ralf Stockmann <rstockm@gwdg.de>, Cornelis Kater <ckater@gwdg.de>

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
	
include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

// -- here you have to put initialisations for the current page
require_once("$ABSOLUTE_PATH_STUDIP/msg.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/datei.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/visual.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/config.inc.php");
require_once("$ABSOLUTE_PATH_STUDIP/functions.php");

$sess->register("folder_system_data");

// Start of Output
include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
//JS Routinen einbinden, wenn benoetigt. Wird in der Funktion gecheckt, ob noetig...
JS_for_upload();
//we need this <body> tag, sad but true :)
echo "\n<body onUnLoad=\"upload_end()\">"; 

//Switch fuerr die Ansichten
if ($cmd=="tree") {
	$folder_system_data='';
	$folder_system_data["cmd"]="tree";
	}
if ($cmd=="all") {
	$folder_system_data='';
	$folder_system_data["cmd"]="all";
	}

include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head

IF ($SessSemName[1] =="")
	{
	parse_window ("error�Sie haben kein Objekt gew&auml;hlt. <br /><font size=-1 color=black>Dieser Teil des Systems kann nur genutzt werden, wenn Sie vorher ein Objekt gew&auml;hlt haben.<br /><br /> Dieser Fehler tritt auch auf, wenn Ihre Session abgelaufen ist. Wenn sie sich l�nger als $AUTH_LIFETIME Minuten nicht im System bewegt haben, werden Sie automatisch abgemeldet. Bitte nutzen Sie in diesem Fall den untenstehenden Link, um zur�ck zur Anmeldung zu gelangen. </font>", "�",
				"Keine Objekt gew&auml;hlt", 
				"<a href=\"index.php\"><b>&nbsp;Hier</b></a> geht es wieder zur Anmeldung beziehungsweise Startseite.<br />&nbsp;");
	die;
	}
ELSE
	{
	include "links_openobject.inc.php";
	}

$db=new DB_Seminar;
$db2=new DB_Seminar;

//Wenn nicht Rechte und Operation uebermittelt: Ist das mein Dokument?
if ((!$rechte) && strpos($open, "_")) {
	$db->query("SELECT user_id FROM dokumente WHERE dokument_id = '".substr($open, 0, strpos($open, "_"))."'");
	$db->next_record();
	if (($db->f("user_id") == $user->id) && ($db->f("user_id") != "nobody"))
		$owner=TRUE;
	}

if (($rechte) || ($owner)) {
	//wurde Code fuer Anlegen von Ordnern ubermittelt (=id+"_n_"), wird entsprechende Funktion aufgerufen
	if (strpos($open, "_n_")) {
		$change=create_folder('Neuer Ordner', '', substr($open, (strpos($open, "_n_"))-32, (strpos($open, "_n_"))));
		$open=$change;
		}

	//wurde Code fuer Anlegen von Ordnern der obersten Ebene ubermittelt (=id+"_a_"), wird entsprechende Funktion aufgerufen
	if (strpos($open, "_a_")) {
		if (substr($open, (strpos($open, "_a_"))-32, (strpos($open, "_a_"))) == $SessionSeminar) {
			$titel="Allgemeiner Dateiordner";
			$description="Ablage f�r allgemeine Ordner und Dokumente der ".$SessSemName["art_generic"];
			}
		
		$db->query("SELECT date, date_typ, content FROM termine WHERE termin_id='".substr($open, (strpos($open, "_a_"))-32, (strpos($open, "_a_")))."'");
		if ($db->next_record()) {
			//Titel basteln
			$titel=$TERMIN_TYP[$db->f("date_typ")]["name"].": ".substr($db->f("content"), 0, 35);
			if (strlen($db->f("content")) >=35)
				$titel.="...";
			$titel.=" am ".date("d.m.Y ", $db->f("date"));
			$description="Ablage f�r Ordner und Dokumente zu diesem Termin";
			}
			
		$change=create_folder($titel, $description, substr($open, (strpos($open, "_a_"))-32, (strpos($open, "_a_"))));
		}

	//wurde Code fuer Loeschen von Ordnern ubermittelt (=id+"_d_"), wird entsprechende Funktion aufgerufen
	if (strpos($open, "_d_")) {
		delete_folder(substr($open, (strpos($open, "_d_"))-32, (strpos($open, "_d_"))));
		}
	
	//wurde Code fuer Loeschen von Dateien ubermittelt (=id+"_fd_"), wird erstmal nachgefragt
	if (strpos($open, "_fd_")) {
		$db->query("SELECT filename, Vorname, Nachname, username FROM dokumente LEFT JOIN auth_user_md5 USING (user_id) WHERE dokument_id ='".substr($open, (strpos($open, "_fd_"))-32, (strpos($open, "_fd_")))."'");
		$db->next_record();
		$msg="info�Wollen sie die Datei <b>".htmlentities(stripslashes($db->f("filename")))."</b> von <a href=\"about.php?username=".$db->f("username")."\">".$db->f("Vorname")." ".$db->f("Nachname")."</a> wirklich l&ouml;schen?<br>";
		$msg.="<b><a href=\"$PHP_SELF?open=".substr($open, (strpos($open, "_fd_"))-32, (strpos($open, "_fd_")))."_rm_\"><img src=\"pictures/buttons/ja2-button.gif\" border=0></a>&nbsp;&nbsp; <a href=\"$PHP_SELF\"><img src=\"pictures/buttons/nein-button.gif\" border=0></a>�";
		}

	//Loeschen von Datein im wirklich-ernst Mode
	if (strpos($open, "_rm_")) {
		if (delete_document(substr($open, (strpos($open, "_rm_"))-32, (strpos($open, "_rm_")))))
			$msg.="msg�Die Datei wurde gel&ouml;scht�";
		else
			$msg.="error�Die Datei konnte nicht gel&ouml;scht werden�";
		} 

	//wurde Code fuer Aendern des Namens und der Beschreibung von Ordnern oder Dokumenten ubermittelt (=id+"_c_"), wird entsprechende Funktion aufgerufen
	if (strpos($open, "_c_")) {
		$change=substr($open, (strpos($open, "_c_"))-32, (strpos($open, "_c_")));
		}

	//wurde Code fuer Speichern von Aenderungen uebermittelt (=id+"_sc_"), wird entsprechende Funktion aufgerufen
	if ((strpos($open, "_sc_")) && (!$Abbrechen)) {
		edit_item (substr($open, (strpos($open, "_sc_"))-32, (strpos($open, "_sc_"))), $type, $change_name, $change_description);
		}

	//wurde Code fuer Verschieben-Vorwaehlen uebermittelt (=id+"_m_"), wird entsprechende Funktion aufgerufen
	if ((strpos($open, "_m_")) && (!$Abbrechen)) {
		$folder_system_data["move"]=substr($open, (strpos($open, "_m_"))-32, (strpos($open, "_m_")));
		}
	}


//Upload, Check auf Konsistenz mit Seminar-Schreibberechtigung
if (($SemUserStatus == "autor") || ($rechte)) {
	//wurde Code fuer Hochladen uebermittelt (=id+"_n_"), wird entsprechende Funktion aufgerufen
	if ((strpos($open, "_u_")) && (!$Abbrechen)) {
		$folder_system_data["upload"]=substr($open, (strpos($open, "_u_"))-32, (strpos($open, "_u_")));
		}	
	
	//wurde eine Datei hochgeladen? 
	if (($cmd=="upload") && (!$abbrechen)) {
		upload_item ($folder_system_data["upload"], TRUE, FALSE);
		$folder_system_data["upload"]='';
		unset($cmd);
		}
	if ($abbrechen)  {
		$folder_system_data["upload"]='';
		unset($cmd);
		}
	}
	
//wurde Code fuer Starten der Verschiebung uebermittelt (=id+"_md_"), wird entsprechende Funktion aufgerufen (hier kein Rechtecheck noetig, da Dok_id aus Sess_Variable.
if ((strpos($open, "_md_")) && (!$Abbrechen)) {
	move_item ($folder_system_data["move"], substr($open, (strpos($open, "_md_"))-32, (strpos($open, "_md_"))));
	$folder_system_data["move"]='';
	}

//wurde ein weiteres Objekt aufgeklappt?
if ($folder_system_data["open"]) {
	if ((!strstr($folder_system_data["open"], $open)) &&  (!strpos($open, "_"))) {
		$folder_system_data["open"].=$open;
		}
	}
else
	$folder_system_data["open"]=$open;

//wurde ein Objekt zugeklappt?
if ($close) {
	$pos=strpos($folder_system_data["open"], $close);
	if ($pos)
		$folder_system_data["open"]=substr($folder_system_data["open"], 0, $pos).substr($folder_system_data["open"], $pos+32, strlen($folder_system_data["open"])); 
		
	else
		$folder_system_data["open"]=substr($folder_system_data["open"], 32, strlen($folder_system_data["open"])); 	
	}

// Hauptteil

 if (!isset($range_id)) $range_id = $SessionSeminar ;

?>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr><td class="topic" colspan="2"><b>&nbsp;<img src="pictures/icon-disc.gif" align=absmiddle>&nbsp; <? echo htmlReady($SessSemName["art"]) . ": ", htmlReady($SessSemName[0]) .  " - Dateien"; ?></b></td></tr>

		<tr>
			<td class="blank" colspan=2>&nbsp;
				<?
				if ($msg) parse_msg($msg);
				?>
			</td>
		</tr>
<tr>
<td class="blank" colspan="2" width="100%">

<?
	//Ordner die fehlen, anlegen: Allgemeiner, wenn nicht da, Ordner zu Terminen, die keinen Ordner haben
	if (($rechte) && ($folder_system_data["cmd"]=="tree")) {
		$db2->query("SELECT name FROM folder WHERE range_id='$range_id'");
		if (!$db2->affected_rows())
			$select="<option value=\"".$range_id."_a_\">Allgemeiner Dateiordner</option>";
		
		$db2->query("SELECT termine.date, folder.name, termin_id, date_typ FROM termine LEFT JOIN folder ON (termin_id = folder.range_id) WHERE termine.range_id='$range_id' ORDER BY name, termine.date");
		while (($db2->next_record()) && (!$db2->f("name"))) {
			$select.="<option value=\"".$db2->f("termin_id")."_a_\">Dateiordner zum Termin am ".date("d.m.Y ", $db2->f("date"))."[".$TERMIN_TYP[$db2->f("date_typ")]["name"]."]</option>";
			}

		if ($select) {
			?>
			<blockquote>
			<p valign="middle">
			<form action="<? echo $PHP_SELF?>" method="POST">
				<input type="image" name="anlegen" value="Neuer Ordner" align="absmiddle" src="pictures/buttons/neuerordner-button.gif" border=0 />&nbsp;
				<select name="open">
					<? echo $select ?>				
				</select>
			</form>
			</p>
			</blockquote>
			<?
			}
		}
		
	if ($folder_system_data["cmd"]=="all") {
		?>
		<blockquote>
		<? printf ("Hier sehen Sie alle Dateien, die zu dieser %s eingestellt wurden. Wenn Sie eine neue Datei einstellen m&ouml;chten, w&auml;hlen Sie bitte die Ordneransicht und &ouml;ffnen den Ordner, in den Sie die Datei einstellen wollen.", $SessSemName["art_generic"]); ?>
		</blockquote>
		<?
		}
		
	//Alle Termine der Veranstaltung holen
	$db->query("SELECT termin_id FROM termine WHERE range_id='$range_id' ORDER BY date");
	
	//Bei Veraenderung Form beginnen
	if ($change) {
		echo "<form method=\"post\" action=\"$PHP_SELF\">";
		echo "<br />";	
		}
	
	//Treeview
	if ($folder_system_data["cmd"]=="tree") {
		//Seminar...
		display_folder_system($range_id, 0,$folder_system_data["open"], '', $change, $folder_system_data["move"], $folder_system_data["upload"], FALSE);
		while ($db->next_record()) {
			//und einzelne Termine	
			display_folder_system($db->f("termin_id"), 0,$folder_system_data["open"], '', $change, $folder_system_data["move"], $folder_system_data["upload"], FALSE);
			}
		}
	
	//Alle / Listview
	else {
		?><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><?
		//Seminar...
		display_folder_system($range_id, 0,$folder_system_data["open"], '', $change, $folder_system_data["move"], $folder_system_data["upload"], TRUE);		
		while ($db->next_record()) {
			//und einzelne Termine	
			display_folder_system($db->f("termin_id"), 0,$folder_system_data["open"], '', $change, $folder_system_data["move"], $folder_system_data["upload"], TRUE);
			}
		?><td class="blank" width="*">&nbsp;</td></tr></table><?
		}
	
	//und Form wieder schliessen
	if ($change)
		echo "</form>";				
?>
<br>
</td>
</tr>
</table>
<br>
<?

  // Save data back to database.
  page_close()
?>
</body>
</html>