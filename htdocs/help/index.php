<?php
/*
help/index.php - Zentrale Steuerungsdatei f�r die Hilfe in Stud.IP
Copyright (C) 2001 Stefan Suchi <suchi@gmx.de>

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

	page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Default_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
?>

<html>
<head>

<title>Stud.IP - Hilfe</title>
<?
if (!isset($druck))
	print("<link rel=\"stylesheet\" href=\"help_style.css\" type=\"text/css\">");
else
	print("<link rel=\"stylesheet\" href=\"../style_print.css\" type=\"text/css\">");
?>
</head>
<body bgcolor=white>

<?php
//includes
require_once ("../config.inc.php");
require_once ("../seminar_open.php");

// -- hier wird die Struktur geladen --
require_once("structure.inc.php");


// kommen wir gerade rein in die Hilfe?
if (isset($referrer_page) && !isset($help_page))
	include("switcher.inc.php");

// -- hier wird die Navigationleiste ausgegeben --
include("kartei.inc.php");


if (isset($help_page)) { // ok, eine normale Hilfeseite ausgeben

	if (!isset($druck)) { // die Hilfe-Seite in eine Tabelle packen
		print("\n<table cellspacing=0 cellpadding=10 border=0 width=\"100%\"><tr><td class=\"blank\">");
		include("pages/$help_page");
		print("\n</td></tr></table>");
	} else {	// die Hilfeseite nackt ausgeben
		include("pages/$help_page");

		//Studipinfozeile
		echo "<table width=100% border=0 cellpadding=2 cellspacing=0>";
		echo "<tr><td colspan=2><hr></td></tr>";
		echo "<tr><td><i><font size=-1>Stand: ".date("d.m.y",time()).", ".date("G:i", time())." Uhr.</font></i></td><td align=\"right\"><font size=-2><img src=\"../pictures/logo2b.gif\"><br />&copy; ".date("Y", time())." v.$SOFTWARE_VERSION&nbsp; &nbsp; </font></td></tr>";
		echo  "</table>\n";
	}	


} else { // das Inhaltsverzeichnis ausgeben

	if (!isset($druck)) { // das Inhaltsverzeichnis als links in eine Tabelle packen

		print("\n<table cellspacing=0 cellpadding=10 border=0 width=\"100%\"><tr><td class=\"blank\">");
		print("<ol>");
		for ($i = 0; $i < count($pages); $i++) {
			if ($pages[$i]["perm"] == "" || $perm->have_perm($pages[$i]["perm"])) {
			printf("<li><a href=\"%s?help_page=%s\"><b>%s</b></a><br><i>%s</i><ul>\n",$PHP_SELF, $pages[$i]["kategorien"][0]["page"], $pages[$i]["name"], $pages[$i]["text"]);
				for ($j = 0; $j < count($pages[$i]["kategorien"]); $j++) {
					printf("<li><a href=\"%s?help_page=%s\">%s</a><br><i>%s</i></li>\n",$PHP_SELF, $pages[$i]["kategorien"][$j]["page"], $pages[$i]["kategorien"][$j]["name"], $pages[$i]["kategorien"][$j]["text"]);
				}
			print("</ul></li><br>");
			}
		}
		print("\n</ol></td></tr></table>");


	} else { // die komplette Hilfe in druckbarer Form ausgeben

		// erstmal die Gliederung
		print("<div align=\"center\"><font size=\"+2\"><b>Inhaltsverzeichnis</b></font></div><br><br>\n");
		for ($i = 0; $i < count($pages); $i++) {
			if ($pages[$i]["perm"] == "" || $perm->have_perm($pages[$i]["perm"])) {
			printf("\n<a href=\"#%d\"><font size=\"+1\"><b>%d. %s</b></font></a><br>\n<i>%s</i><br>\n", $i+1, $i+1,$pages[$i]["name"], $pages[$i]["text"]);
			print("<blockquote>");
				for ($j = 0; $j < count($pages[$i]["kategorien"]); $j++) {
					printf("<br><a href=\"#%d.%d\"><b>%d.%d %s</b></a><br>\n<i>%s</i><br>\n", $i+1, $j+1, $i+1, $j+1, $pages[$i]["kategorien"][$j]["name"], $pages[$i]["kategorien"][$j]["text"]);
					$temp_page = $pages[$i]["kategorien"][$j]["page"];
				}
			print("</blockquote><br>");
			}
		}
		
		// und dann komplett
		for ($i = 0; $i < count($pages); $i++) {
			if ($pages[$i]["perm"] == "" || $perm->have_perm($pages[$i]["perm"])) {
				print("<br><hr><br>\n");
				printf("\n<a name=\"%d\"><div align=\"center\"><br><br><font size=\"+2\"><b>%d. %s</b></font><br>\n<i>%s</i><br></div>\n", $i+1, $i+1, $pages[$i]["name"], $pages[$i]["text"]);
				for ($j = 0; $j < count($pages[$i]["kategorien"]); $j++) {
					printf("<a name=\"%d.%d\"><div align=\"center\"><br><br><font size=\"+1\"><b>%d.%d %s</b></font><br>\n<i>%s</i><br><br></div>\n", $i+1, $j+1, $i+1, $j+1, $pages[$i]["kategorien"][$j]["name"], $pages[$i]["kategorien"][$j]["text"]);
					$temp_page = $pages[$i]["kategorien"][$j]["page"];
					include("pages/$temp_page");
				}
				print("<br>");
			}
		//Studipinfozeile
		echo "<table width=100% border=0 cellpadding=2 cellspacing=0>";
		echo "<tr><td colspan=2><hr></td></tr>";
		echo "<tr><td><i><font size=-1>Stand: ".date("d.m.y",time()).", ".date("G:i", time())." Uhr.</font></i></td><td align=\"right\"><font size=-2><img src=\"../pictures/logo2b.gif\"><br />&copy; ".date("Y", time())." v.$SOFTWARE_VERSION&nbsp; &nbsp; </font></td></tr>";
		echo  "</table>\n";
		}	
	}

}

// Save data back to database.
page_close()
?>
</body>
</html>