<?php
/*
institut_main.php - Die Eingangsseite fuer ein Institut
Copyright (C) 200 Stefan Suchi <suchi@gmx.de>, Ralf Stockmann <rstockm@gwdg.de>, Cornelis Kater <ckater@gwdg.de>

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
$auth->login_if($again && ($auth->auth["uid"] == "nobody"));

include "seminar_open.php"; //hier werden die sessions initialisiert
require_once "dates.inc.php"; //Funktionen zur Anzeige der Terminstruktur
require_once "datei.inc.php";
require_once "config.inc.php";
require_once "visual.inc.php"; 

// hier muessen Seiten-Initialisierungen passieren
	if (isset($auswahl) && $auswahl!="") {
		// dieses Ibstitut wurde gerade eben betreten
		$SessionSeminar="$auswahl";
		$db=new DB_Seminar;
		$db->query ("SELECT Name, Institut_id, type FROM Institute WHERE Institut_id='$auswahl'");
		while ($db->next_record()) {
			$SessSemName[0] = $db->f("Name");
			$SessSemName[1] = $db->f("Institut_id");
			$SessSemName["art_generic"]="Einrichtung";
			$SessSemName["art"]=$INST_TYPE[$db->f("type")]["name"];
			if (!$SessSemName["art"])
				$SessSemName["art"]=$SessSemName["art_generic"];
			$SessSemName["class"]="inst";
			$nr = $db->f("Institut_id");
			$loginfilelast["$nr"] = $loginfilenow["$nr"];
			$loginfilenow["$nr"] = time();
		}
	}
	else {
		$auswahl=$SessSemName[1];
	}

	// gibt es eine Anweisung zur Umleitung?
	if(isset($redirect_to) && $redirect_to != "") {
		$take_it = 0;

		for ($i = 0; $i < count($i_query); $i++) { // alle Parameter durchwandern
			$parts = explode('=',$i_query[$i]);
			if ($parts[0] == "redirect_to") {
				// aha, wir haben die erste interessante Angabe gefunden
				$new_query = $parts[1];
				$take_it ++;
			}
			else if ($take_it) {
				// alle weiteren Parameter mit einsammeln
				if ($take_it == 1) { // hier kommt der erste
					$new_query .= '?';
				} else { // hier kommen alle weiteren
					$new_query .= '&';
				}
				$new_query .= $i_query[$i];
				$take_it ++;
			}

		}
		header("Location: $new_query");
		unset($redirect_to);
		page_close();
		die;
	}

?>

<html>
<head>
<!--
// here i include my personal meta-tags; one of those might be useful:
// <META HTTP-EQUIV="REFRESH" CONTENT="<?php print $auth->lifetime*60;?>; URL=logout.php">
-->
  <title>Stud.IP</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body bgcolor="#ffffff">


<?php

	include "header.php";   //hier wird der "Kopf" nachgeladen

?>
<body>
<?
IF ($SessSemName[1] =="")
	{
	parse_window ("error�Sie haben kein Objekt gew&auml;hlt. <br /><font size=-1 color=black>Dieser Teil des Systems kann nur genutzt werden, wenn Sie vorher ein Objekt gew&auml;hlt haben.<br /><br /> Dieser Fehler tritt auch auf, wenn Ihre Session abgelaufen ist. Wenn sie sich l�nger als $AUTH_LIFETIME Minuten nicht im System bewegt haben, werden Sie automatisch abgemeldet. Bitte nutzen Sie in diesem Fall den untenstehenden Link, um zur�ck zur Anmeldung zu gelangen. </font>", "�",
				"Kein Objekt gew&auml;hlt", 
				"<a href=\"index.php\"><b>&nbsp;Hier</b></a> geht es wieder zur Anmeldung beziehungsweise Startseite.<br />&nbsp;");
	die;
} else {
	include "links1.php";
	include "show_news.php";
  	
  	$sess->register("institut_main_data");
  	
  	//Auf und Zuklappen News
  	if ($nopen)
        	$institut_main_data["nopen"]=$nopen;
        
        if ($nclose)
        	$institut_main_data["nopen"]='';
        
	?>

	<table width="100%" border=0 cellpadding=0 cellspacing=0>
	<tr><td class="topic" colspan=2><b>&nbsp;<? echo $SessSemName["art"],": ",htmlReady($SessSemName[0]); ?>
	</b></td></tr>
	<tr><td class="blank">
	<br><blockquote><?
	$db->query ("SELECT *, Fakultaeten.Name AS fakultaet_name  FROM Institute LEFT JOIN Fakultaeten using (Fakultaets_id) WHERE Institut_id='$auswahl'");
	$db->next_record();

	if ($db->f("Strasse")) {
		echo "<b>Strasse: </b>"; echo htmlReady($db->f("Strasse")); echo"<br>";
	}
		
	if ($db->f("Plz")) {
		echo "<b>Plz: </b>"; echo htmlReady($db->f("Strasse")); echo"<br>";
	}

	if ($db->f("url")) {
		echo "<b>Homepage: </b>"; echo formatReady($db->f("url")); echo"<br>";
	}

	if ($db->f("telefon")) {
		echo "<b>Tel.: </b>"; echo htmlReady($db->f("telefon")); echo"<br>";
	}

	if ($db->f("fax")) {
		echo "<b>Fax: </b>"; echo htmlReady($db->f("fax")); echo"<br>";
	}

	if ($db->f("email")) {
		echo "<b>Email: </b>"; echo htmlReady($db->f("email")); echo"<br>";
	}

	if ($db->f("fakultaet_name")) {
		echo "<b>Fakultaet: </b>"; echo htmlReady($db->f("fakultaet_name")); echo"<br>";
	}
		
	?>
	</blockquote><br><br>
	</td>

	<td class="blank" align = right><img src="pictures/board2.jpg" border="0"></td>
	</tr></table><br>


	<?php

	// Anzeige von News
	
	
	($rechte) ? $show_admin=TRUE : $show_admin=FALSE;
	if (show_news($auswahl,$show_admin, 0, $institut_main_data["nopen"], "100%", $loginfilelast[$SessSemName[1]]))
		echo"<br>";

}
?>
</body>
</html>
<?php
  // Save data back to database.
  page_close()
 ?>
<!-- $Id$ -->