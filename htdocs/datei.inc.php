<?php
/*
datei.inc.php - basale Routinen zur Dateiverwaltung, dienen zum Aufbau des Ordnersystems
Copyright (C) 2001 Stefan Suchi <suchi@gmx.de>, Cornelis Kater <ckater@gwdg.de>

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

function doc_count ($parent_id) {
	$db=new DB_Seminar;
	$db2=new DB_Seminar;
	
	$db2->query ("SELECT count(*) as count FROM dokumente WHERE range_id='$parent_id'");
	$db2->next_record();
	$count = $db2->f("count");
		
	$db->query ("SELECT folder_id FROM folder WHERE range_id='$parent_id'");
	 
	while ($db->next_record()) {
		$count += doc_count ($db->f("folder_id"));
	} 
	
	return $count;
}


function doc_challenge ($parent_id, $doc_ids=array()) {
	$db=new DB_Seminar;
	$db2=new DB_Seminar;	
	
	$db->query ("SELECT folder_id FROM folder WHERE range_id='$parent_id'");
	 
	while ($db->next_record()) {
		$doc_ids=doc_challenge ($db->f("folder_id"), $doc_ids);

		$db2->query ("SELECT dokument_id FROM dokumente WHERE range_id='".$db->f("folder_id")."'");

		while ($db2->next_record()) {	 		 	
			$doc_ids[]=$db2->f("dokument_id");
			}
		} 
	return $doc_ids;
	}

function move_item ($item_id, $new_parent) {

	$db=new DB_Seminar;
	
	if ($item_id != $new_parent) {
		$db->query ("UPDATE dokumente SET range_id='$new_parent' WHERE dokument_id = '$item_id'");
		if (!$db->affected_rows());
			$db->query ("UPDATE folder SET range_id='$new_parent' WHERE folder_id = '$item_id'");
	}	
		
	if ($db->affected_rows());		
		return TRUE;
	}
	
function edit_item ($item_id, $type, $name, $description) {

	$db=new DB_Seminar;
	
	if ($type)
		$db->query("UPDATE folder SET name='$name', description='$description'  WHERE folder_id ='$item_id'");
	else
		$db->query("UPDATE dokumente SET description='$description'  WHERE dokument_id ='$item_id'");	
	
	if ($db->affected_rows())
		return TRUE;
	}

function create_folder ($name, $description, $parent_id) {
	global $user;
	
	$db=new DB_Seminar;
	$id=md5(uniqid("salmonellen"));
	
	$db->query("INSERT INTO folder SET name='$name', folder_id='$id', description='$description', range_id='$parent_id', user_id='".$user->id."', mkdate='".time()."', chdate='".time()."'");
	if ($db->affected_rows()) {
		return $id;
		}
	}
	
## Upload Funktionen ################################################################################

//Ausgabe des Formulars
function form() {
	global $PHP_SELF,$UPLOAD_TYPES,$range_id,$SessSemName,$SemUserStatus,$user;
	
	//Hier kommt wohl ein root oder admin, der nicht in der Smeinartabelle steht... also uebernehmen wir globale Rechte
	if (!$SemUserStatus) {
		$db=new DB_Seminar;
		$db->query("SELECT perms FROM auth_user_md5 WHERE user_id = '".$user->id."'");
		$db->next_record();
		$SemUserStatus=$db->f("perms");
		}
		
	//erlaubte Dategroesse aus Regelliste der Config.inc.php auslesen
	if ($UPLOAD_TYPES[$SessSemName["art_num"]]) {
		$max_filesize=$UPLOAD_TYPES[$SessSemName["art_num"]]["file_sizes"][$SemUserStatus];
		}
	else {
		$max_filesize=$UPLOAD_TYPES["default"]["file_sizes"][$SemUserStatus];
		}
	
	$c=1;
	
	$print="\n<br /><br />Sie haben diesen Ordner zum Upload ausgew&auml;hlt:<br /><br /><center><table width=\"90%\" style=\"{border-style: solid; border-color: #000000;  border-width: 1px;}\" border=0 cellpadding=2 cellspacing=3>";
	$print.="\n";
	$print.="\n<tr><td class=\"steel1\" width=\"20%\"><font size=-1><b>";
	
	//erlaubte Upload-Typen aus Regelliste der Config.inc.php auslesen
	if ($UPLOAD_TYPES[$SessSemName["art_num"]]) {
		if ($UPLOAD_TYPES[$SessSemName["art_num"]]["type"] == "allow") {
			$i=1;
			$print.= "Unzul&auml;ssige Dateitypen:</b><font></td><td class=\"steel1\" width=\"80%\"><font size=-1>";
			foreach ($UPLOAD_TYPES[$SessSemName["art_num"]]["file_types"] as $ft) {
				if ($i !=1)
					$print.= ", ";				
				$print.= strtoupper ($ft);
				$i++;
				}
			}
		else {
			$i=1;
			$print.= "Zul&auml;ssige Dateitypen:</b><font></td><td class=\"steel1\" width=\"80%\"><font size=-1>";
			foreach ($UPLOAD_TYPES[$SessSemName["art_num"]]["file_types"] as $ft) {
				if ($i !=1)
					$print.= ", ";				
				$print.= strtoupper ($ft);
				$i++;
				}		
			}
		}
	else {
		if ($UPLOAD_TYPES["default"]["type"] == "allow") {
			$i=1;
			$print.= "Unzul&auml;ssige Dateitypen:</b><font></td><td class=\"steel1\" width=\"80%\"><font size=-1>";
			foreach ($UPLOAD_TYPES["default"]["file_types"] as $ft) {
				if ($i !=1)
					$print.= ", ";				
				$print.= strtoupper ($ft);
				$i++;
				}
			}
		else {
			$i=1;
			$print.= "Zul&auml;ssige Dateitypen:</b></td><font><td class=\"steel1\" width=\"80%\"><font size=-1>";
			foreach ($UPLOAD_TYPES["default"]["file_types"] as $ft) {
				if ($i !=1)
					$print.= ", ";				
				$print.= strtoupper ($ft);
				$i++;
				}
			}
		}
	$print.="</font></td></tr>";
	$print.="\n<tr><td class=\"steel1\" width=\"20%\"><font size=-1><b>Maximale Gr&ouml;&szlig;e:</b></font></td><td class=\"steel1\" width=\"80%\"><font size=-1><b>".($max_filesize / 1048576)." </b>Megabyte</font></td></tr>";
	$print.= "\n<form enctype=\"multipart/form-data\" NAME=\"upload_form\" action=\"" . $PHP_SELF . "\" method=\"post\">";
	$print.= "<tr><td class=\"steelgraudunkel\" colspan=2><font size=-1>$c. Klicken Sie auf <b>'Durchsuchen...'</b>, um eine Datei auszuw&auml;hlen. </font></td></tr>";
	$c++;
	$print.= "\n<tr>";
	$print.= "\n<td class=\"steel1\" colspan=2 align=\"left\" valign=\"center\"><font size=-1>&nbsp;Dateipfad:&nbsp;</font><br />";
	$print.= "&nbsp;<INPUT NAME=\"the_file\" TYPE=\"file\"  style=\"width: 70%\" SIZE=\"30\">&nbsp;</td></td>";
	$print.= "\n</tr>";
	$print.= "<tr><td class=\"steelgraudunkel\" colspan=2><font size=-1>$c. Geben Sie eine kurze Bescheibung der Datei ein.</font></td></tr>";
	$c++;
	$print.= "\n<tr><td class=\"steel1\" colspan=2 align=\"left\" valign=\"center\"><font size=-1>&nbsp;Beschreibung:&nbsp;</font><br>";
	$print.= "\n&nbsp;<TEXTAREA NAME=\"description\"  style=\"width: 70%\" COLS=40 ROWS=3 WRAP=PHYSICAL></TEXTAREA>&nbsp;</td></tr>";
	if (!$range_id) {
		$print.= "\n<tr><td class=\"steelgraudunkel\" colspan=2><font size=-1>$c. W&auml;hlen Sie aus der Liste aus, ob die Datei direkt zum Seminar oder zu einem bestimmten Termin geh&ouml;rt.</font></td></tr>";
		$c++;
		$print.= "\n<tr><td class=\"steel1\" colspan=2 align=\"center\" valign=\"center\" width=\"20%\"><font size=-1>Seminar oder Termin:&nbsp;</font></td>";
		$print.= "\n<td class=\"steel1\" colspan=2 align=\"center\" valign=\"center\" width=\"80%\" height=\"75\"><SELECT NAME=\"range_id\">";
		}
			
/*	$db->query("SELECT termin_id,date,content FROM termine WHERE range_id='$SessSemName[1]' ORDER BY date");
	if ($db->num_rows())
		{
		while ($db->next_record())
			{
			print"\n<option ";
			if ($range_id==$db->f("termin_id")) print"selected ";
			print"value=\"".$db->f("termin_id")."\">Termin am ".date("d.M.Y",$db->f("date")).": ".substr($db->f("content"),0,20)."...</option>";
			}
		}
	print"\n</select>&nbsp;</td></tr>";*/
	$print.= "\n<tr><td class=\"steelgraudunkel\"colspan=2 ><font size=-1>$c. Klicken Sie auf <b>'absenden'</b>, um die Datei hochzuladen</font></td></tr>";
	$print.= "\n<tr><td class=\"steel1\" colspan=2 align=\"center\" valign=\"center\">";
	$print.= "\n<input type=\"image\" src=\"pictures/buttons/absenden-button.gif\" border=0 value=\"Senden\" onClick=\"return upload_start();\" name=\"create\">";
	$print.="&nbsp;<a href=\"$PHP_SELF?abbrechen=true\"><img src=\"./pictures/buttons/abbrechen-button.gif\" border=0></a></td></tr>";	
	$print.= "\n<input type=\"hidden\" name=\"cmd\" value=\"upload\">";	
	$print.= "\n<input type=\"hidden\" name=\"upload_seminar_id\" value=\"".$SessSemName[1]."\">";	
	$print.= "\n</form></table><br /></center>";
	
	return $print;
	}

//Diese Funktion dient zur Abfrage der Dateierweiterung
function getFileExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }

	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);

	return $ext;
}

//Check auf korrekten Upload
function validate_upload($the_file) {
	global $UPLOAD_TYPES,$the_file_size, $msg, $the_file_name, $SessSemName,$SemUserStatus, $user, $auth;

	//Hier kommt wohl ein root oder admin, der nicht in der Smeinartabelle steht... also uebernehmen wir globale Rechte
	if (!$SemUserStatus) {
		$db=new DB_Seminar;
		$db->query("SELECT perms FROM auth_user_md5 WHERE user_id = '".$user->id."'");
		$db->next_record();
		$SemUserStatus=$db->f("perms");
		}
	
	//erlaubte Dategroesse aus Regelliste der Config.inc.php auslesen
	if ($UPLOAD_TYPES[$SessSemName["art_num"]]) {
		$max_filesize=$UPLOAD_TYPES[$SessSemName["art_num"]]["file_sizes"][$SemUserStatus];
		}
	else {
		$max_filesize=$UPLOAD_TYPES["default"]["file_sizes"][$SemUserStatus];
		}
	
	$error = FALSE;
	if ($the_file == "none") { # haben wir eine Datei?
		$emsg.= "error�Sie haben keine Datei zum Hochladen ausgew&auml;hlt!�";
	} else { # pruefen, ob der Typ stimmt

		//Die Dateierweiterung von dem Original erfragen
		$pext = strtolower(getFileExtension($the_file_name));
		if ($pext == "doc")
			$doc=TRUE;
		
		//Erweiterung mit Regelliste in config.inc.php vergleichen
		if ($UPLOAD_TYPES[$SessSemName["art_num"]]) {
			if ($UPLOAD_TYPES[$SessSemName["art_num"]]["type"] == "allow") {
				$t=TRUE;
				$i=1;
				foreach ($UPLOAD_TYPES[$SessSemName["art_num"]]["file_types"] as $ft) {
					if ($pext == $ft)
						$t=FALSE;
					if ($i !=1)
						$exts.=",";
					$exts.=" ".strtoupper($ft);
					$i++;
					}
				if (!$t) {
					if ($i==2)
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen den Dateityp$exts nicht hochladen!�";
					else
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen die Dateitypen$exts nicht hochladen!�";
					if ($doc)
						if (!$auth->auth["jscript"])
							$emsg.= "info�<a target=\"_new\" href=\"help/index.php?referrer_page=datei.inc.php&doc=TRUE\">Hier</a> bekommen Sie Hilfe zum Upload von Word-Dokumenten.�";
					}
				}
			else {
				$t=FALSE;
				$i=1;
				foreach ($UPLOAD_TYPES[$SessSemName["art_num"]]["file_types"] as $ft) {
					if ($pext == $ft)
						$t=TRUE;
					if ($i !=1)
						$exts.=",";
					$exts.=" ".strtoupper($ft);
					$i++;
					}
				if (!$t) {
					if ($i==2)
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen nur den Dateityp$exts hochladen!�";
					else
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen nur die Dateitypen$exts hochladen!�";
					if ($doc)
						if (!$auth->auth["jscript"])
							$emsg.= "info�<a target=\"_new\" href=\"help/index.php?referrer_page=datei.inc.php&doc=TRUE\">Hier</a> bekommen Sie Hilfe zum Upload von Word-Dokumenten.�";
						else
							$emsg.= "info�<script language=\"Javascript\">{fenster=window.open(\"help/index.php?referrer_page=datei.inc.php&doc=TRUE\",\"help\",\"scrollbars=yes,width=620,height=400\");} </script>Hilfe zum Upload von Word-Dokumenten bekommen Sie in dem soeben ge&ouml;ffneten Hilfefenster!�";
					}
				}
			}
		else {
			if ($UPLOAD_TYPES["default"]["type"] == "allow") {
				$t=TRUE;
				$i=1;
				foreach ($UPLOAD_TYPES["default"]["file_types"] as $ft) {
					if ($pext == $ft)
						$t=FALSE;
					if ($i !=1)
						$exts.=",";
					$exts.=" ".strtoupper($ft);
					$i++;
					}
				if (!$t) {
					if ($i==2)
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen den Dateityp$exts nicht hochladen!�";
					else
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen  die Dateitypen$exts nicht hochladen!�";
					if ($doc)
						if (!$auth->auth["jscript"])
							$emsg.= "info�<a target=\"_new\" href=\"help/index.php?referrer_page=datei.inc.php&doc=TRUE\">Hier</a> bekommen Sie Hilfe zum Upload von Word-Dokumenten.�";
						else
							$emsg.= "info�<script language=\"Javascript\">{fenster=window.open(\"help/index.php?referrer_page=datei.inc.php&doc=TRUE\",\"help\",\"scrollbars=yes,width=620,height=400\");} </script>Hilfe zum Upload von Word-Dokumenten bekommen Sie in dem soeben ge&ouml;ffneten Hilfefenster!�";
					}
				}					      				
			else {
				$t=FALSE;
				$i=1;
				foreach ($UPLOAD_TYPES["default"]["file_types"] as $ft) {
					if ($pext == $ft)
						$t=TRUE;
					if ($i !=1)
						$exts.=",";
					$exts.=" ".strtoupper($ft);
					$i++;
					}
				if (!$t) {
					if ($i==2)
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen nur den Dateityp$exts hochladen!�";
					else
						$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Sie d&uuml;rfen nur die Dateitypen$exts hochladen!�";
					if ($doc)
						if (!$auth->auth["jscript"])
							$emsg.= "info�<a target=\"_new\" href=\"help/index.php?referrer_page=datei.inc.php&doc=TRUE\">Hier</a> bekommen Sie Hilfe zum Upload von Word-Dokumenten.�";
						else
							$emsg.= "info�<script language=\"Javascript\">{fenster=window.open(\"help/index.php?referrer_page=datei.inc.php&doc=TRUE\",\"help\",\"scrollbars=yes,width=620,height=400\");} </script>Hilfe zum Upload von Word-Dokumenten bekommen Sie in dem soeben ge&ouml;ffneten Hilfefenster!�";
					}
				}
			}
		
		//pruefen ob die Groesse stimmt.
		if ($the_file_size > $max_filesize) {
			$emsg.= "error�Die Datei konnte nicht &uuml;bertragen werden: Die maximale Gr&ouml;sse zum Upload (".($max_filesize /1048576)." Megabyte) wurde &uuml;berschritten!";
		}
	}
	if ($emsg) {
		$msg.=$emsg;
		return FALSE;
		}
	else
		return TRUE;
} 

//der eigentliche Upload
function upload($the_file) {
	global $UPLOAD_PATH, $dokument_id,$the_file_name, $msg;

	if (!validate_upload($the_file)) {
		return FALSE;
		} 
	else { # cool, es geht weiter

		//Dokument_id erzeugen
		$dokument_id=md5(uniqid(rand()));

		//Erzeugen des neuen Speicherpfads
		$newfile = "$UPLOAD_PATH/$dokument_id";

		//Kopieren und Fehlermeldung
		if (!@copy($the_file,$newfile)) {
			$msg.= "error�Datei&uuml;bertragung gescheitert!";
			return FALSE;
		} else {
			$msg="msg�Die Datei wurde erfolgreich auf den Server &uuml;bertragen!";
			return TRUE;
		}
	}
} 


//Erzeugen des Datenbankeintrags zur Datei
function insert_entry_db($range_id, $sem_id=0) {
	global $the_file_name, $the_file_size, $dokument_id, $description, $user, $upload_seminar_id;
	
	$date = time();						//Systemzeit
	$user_id = $user->id;				 // user_id erfragen...
	$range_id = trim($range_id);  // laestige white spaces loswerden
	$description = trim($description);  // laestige white spaces loswerden
	$db=new DB_Seminar;
	$query	 = "INSERT INTO dokumente "
			. " (dokument_id, description, mkdate, chdate, range_id, filename, user_id, seminar_id, filesize, autor_host) "
			. " values('$dokument_id','$description','$date', '$date', '$range_id','$the_file_name','$user_id','$upload_seminar_id', '$the_file_size', '".getenv("REMOTE_ADDR")."') ";
	$db->query($query);
	return;
}

function JS_for_upload() {

	global $UPLOAD_TYPES, $SessSemName;
	
	?>
	 <SCRIPT LANGUAGE="JavaScript">
	<!-- Begin

	function open_helptext() {
		fenster=window.open("help/index.php?referrer_page=datei.inc.php&doc=TRUE","help","scrollbars=yes,width=620,height=400");
	}

	var upload=false;

	function upload_end()
	{
	if (upload)
		{
		msg_window.close();
		}
	return;
	}

	function upload_start()
	{
	file_name=document.upload_form.the_file.value
	if (!file_name)
	     {
	     alert("Bitte w�hlen Sie eine Datei aus !");
	     document.upload_form.the_file.focus();
	     return false;
	     }

	if (file_name.charAt(file_name.length-1)=="\"") {
	 ende=file_name.length-1; }
	else  {
	 ende=file_name.length;  }
	
	ext=file_name.substring(file_name.lastIndexOf(".")+1,ende);
	ext=ext.toLowerCase();
		
	if (<?
	if ($UPLOAD_TYPES[$SessSemName["art_num"]]) {
		if ($UPLOAD_TYPES[$SessSemName["art_num"]]["type"] == "allow") {
			$i=1;
			foreach ($UPLOAD_TYPES[$SessSemName["art_num"]]["file_types"] as $ft) {
				if ($i !=1)
					echo " && ";				
				echo "ext == \"$ft\"";
				$i++;
				if ($ft=="doc")
					$deny_doc=TRUE;
				}
			}
		else {
			$i=1;
			$deny_doc=TRUE;
			foreach ($UPLOAD_TYPES[$SessSemName["art_num"]]["file_types"] as $ft) {
				if ($i !=1)
					echo " && ";				
				echo "ext != \"$ft\"";
				$i++;
				if ($ft=="doc")
					$deny_doc=FALSE;
				}
			}
		}
	else {
		if ($UPLOAD_TYPES["default"]["type"] == "allow") {
			$i=1;
			foreach ($UPLOAD_TYPES["default"]["file_types"] as $ft) {
				if ($i !=1)
					echo " && ";				
				echo "ext == \"$ft\"";
				$i++;
				if ($ft=="doc")
					$deny_doc=TRUE;
				}
			}
		else {
			$i=1;
			$deny_doc=TRUE;
			foreach ($UPLOAD_TYPES["default"]["file_types"] as $ft) {
				if ($i !=1)
					echo " && ";
				echo "ext != \"$ft\"";
				$i++;
				if ($ft=="doc")
					$deny_doc=FALSE;
				}
			}
		}
	?>)
	     {
	     alert("Dieser Dateityp ist nicht zugelassen!");
	     document.upload_form.the_file.focus();
	     <? if ($deny_doc) { ?>
		if (ext == "doc")
			open_helptext();
	     <? } ?>
	     
	     return false;
	     }

	if (file_name.lastIndexOf("/") > 0)
	     {
	     file_only=file_name.substring(file_name.lastIndexOf("/")+1,ende);
	     }
	if (file_name.lastIndexOf("\\") > 0)
	     {
	     file_only=file_name.substring(file_name.lastIndexOf("\\")+1,ende);
	     }

	msg_window=window.open("","messagewindow","height=250,width=200,left=20,top=20,scrollbars=no,resizable=no,toolbar=no");
	msg_window.document.write("<html><head><title>Datei Upload</title></head>");
	msg_window.document.write("<body bgcolor='#ffffff'><center><p><img src='pictures/alienupload.gif' width='165' height='125'></p>");
	msg_window.document.write("<p><font face='arial, helvetica, sans-serif'><b>&nbsp;"+file_only+"</b><br>&nbsp;wird hochgeladen.<br>&nbsp;Bitte haben sie etwas Geduld!<br /></font></p></body></html>");

	upload=true;

	return true;
	}

	// End -->
	</script>
	<?
	}


//Steuerungsfunktion 
function upload_item ($range_id, $create = FALSE, $echo = FALSE) {

	global $the_file;

	if ($create) {
		if (upload($the_file))
			insert_entry_db($range_id);
		return;
		} 
	 else {
		if ($echo) {
			echo form();
			return;
			}
		else
			return form();
		}
	}



## Ende der Upload Funktionen ################################################################################

function display_folder_system ($folder_id, $level, $open, $lines, $change, $move, $upload, $all) {


	global $SessionSeminar,$SessSemName,$loginfilelast,$loginfilenow, $rechte, $anfang, $PHP_SELF, $user, $SemSecLevelWrite, $SemUserStatus;

	if (!$anfang)
		$anfang = $folder_id;
	
	$db=new DB_Seminar;
	$db2=new DB_Seminar;
	$db3=new DB_Seminar;	
	
	$db->query("SELECT Vorname, Nachname, username, folder_id, range_id, folder.user_id, name, description, mkdate, chdate FROM folder LEFT JOIN auth_user_md5 USING (user_id)WHERE range_id = '$folder_id' ORDER BY mkdate");

	$lines[$level] = $db->affected_rows();

	while ($db->next_record()) {	
		if (!$all) {?><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><td class="blank" valign="top" heigth=21 nowrap><img src='pictures/forumleer.gif'><img src='pictures/forumleer.gif'><?}

		if ($level) { //Hier eine bezaubernde Routine um die Striche exakt wiederzugeben
			$striche = "";
				for ($i=0;$i<$level;$i++) {
					if ($i==($level-1)) {
						if ($lines[$i+1]>1) 
							$striche.= "<img src='pictures/forumstrich3.gif' border=0>"; 		//Kreuzung
						else
							$striche.= "<img src='pictures/forumstrich2.gif' border=0>"; 		//abknickend
						$lines[$i+1] -= 1;
						}
					else {
						if ($lines[$i+1]==0) 
							$striche .= "<img src='pictures/forumleer.gif' border=0>";		//Leerzelle
						else
							$striche .= "<img src='pictures/forumstrich.gif' border=0>";		//Strich
						}
					}
				if (!$all) echo $striche;				
			}
		if (!$all) echo "</td>";

		
		if (!$all) {
			$db2->query("SELECT * FROM folder WHERE range_id = '".$db->f("folder_id")."' ORDER BY mkdate");		
			$db3->query("SELECT Vorname, Nachname, username, dokumente.user_id, dokumente.* FROM dokumente  LEFT JOIN auth_user_md5 USING (user_id) WHERE range_id = '".$db->f("folder_id")."' ORDER BY mkdate DESC");
			}
		else
			$db3->query("SELECT Vorname, Nachname, username, dokumente.user_id, dokumente.* FROM dokumente  LEFT JOIN auth_user_md5 USING (user_id) WHERE seminar_id = '".$folder_id."' ORDER BY mkdate DESC");
			
		$letzter=$db2->num_rows(); 		// wenn $letzter = 0 ist gibt es keinen untergeordneten Ordner mehr
		$dok_letzter=$db3->num_rows(); // wenn $dok_letzter = 0 ist gibt es keine Dokumente in dem Ordner

		//Ordner aufgeklappt
		if ((strstr($open,$db->f("folder_id"))) || ($all)) { 
			$content='';
			
			//Icon auswaehlen
			if ($dok_letzter) //Dokumente und Dateien vorhanden
				$icon="<img src=\"pictures/cont_folder.gif\">";	
			else
				$icon="<img src=\"pictures/cont_folder2.gif\">";				
			
			if ($move)
				$icon="&nbsp;<a href=\"$PHP_SELF?open=".$db->f("folder_id")."_md_\"><img src=\"pictures/move.gif\" border=0 alt=\"Objekt in diesen Ordner verschieben\" /></a>".$icon;
			
			//Link erstellen
			$link=$PHP_SELF."?close=".$db->f("folder_id")."#anker";

			//Titelbereich erstellen
			$tmp_titel=htmlReady(mila($db->f("name")));
			if ($change == $db->f("folder_id")) { //Aenderungsmodus, Anker + Formular machen, Font tag direkt ausgeben (muss ausserhalb einer td stehen!
				$titel= "<input style=\"{font-size:8 pt; width: 100%;}\" type=\"text\" size=20 maxlength=255 name=\"change_name\" value=\"".htmlReady($db->f("name"))."\" />";
				}
			else {
				if ($dok_letzter > 1)
					$titel= $tmp_titel."&nbsp;&nbsp;($dok_letzter Dokumente)";
				elseif ($dok_letzter)
					$titel= $tmp_titel." </b>&nbsp;&nbsp;($dok_letzter Dokument)";
				else
					$titel= $tmp_titel;		
				}
			
			//Zusatzangaben erstellen
			$zusatz="<a href=\"about.php?username=".$db->f("username")."\"><font color=\"#333399\">".$db->f("Vorname")." ".$db->f("Nachname")."</font></a>&nbsp;".date("d.m.Y - H:i",$db->f("mkdate"))."";			

			
			if ($loginfilelast[$SessSemName[1]] < $db->f("chdate")) 
				$neuer_ordner = TRUE;
			else
				$neuer_ordner = FALSE;
			
			//Objekttitelzeile ausgeben
			if (!$all) printhead ("99%", 0, $link, "open", $neuer_ordner, $icon, $titel, $zusatz);
					
			//Striche erzeugen
			$striche = "<td class=\"blank\" nowrap background='pictures/forumleer.gif'><img src='pictures/forumleer.gif'><img src='pictures/forumleer.gif'></td>";
			for ($i=0;$i<$level;$i++) {
				if ($lines[$i+1]==0) 
					$striche .= "<td class=\"blank\" nowrap background='pictures/forumleer.gif'><img src='pictures/forumleer.gif'></td>";
				else 
					$striche .= "<td class=\"blank\" nowrap background='pictures/forumstrich.gif'><img src='pictures/forumleer2.gif'></td>";
					}
			$striche2='';
			if (($letzter > 0) || ($dok_letzter > 0))
				$striche2.= "<td class=\"blank\" nowrap background=\"pictures/forumstrichgrau.gif\"><img src=\"pictures/forumleer.gif\"></td>";
			else 
				$striche2.= "<td class=\"blank\" nowrap background=\"pictures/steel1.jpg\"><img src=\"pictures/forumleer.gif\"></td>";
			
			//Ankerlogik
			if (($change) || ($move) || ($upload)) {
				if (($change == $db->f("folder_id")) ||  ($move == $db->f("folder_id")) ||  ($upload == $db->f("folder_id")))
					echo "<a name='anker'></a>";
				}
			elseif ($db->f("folder_id") == substr($open, strlen($open) -32, strlen ($open)))
					echo "<a name='anker'></a>";
			
			//Contentbereich erstellen
			if ($change == $db->f("folder_id")) { //Aenderungsmodus, zweiter Teil
				$content.="<textarea name=\"change_description\" rows=3 cols=40>".htmlReady($db->f("description"))."</textarea><br /><br />";
				$content.="<input type=\"image\" src=\"./pictures/buttons/uebernehmen-button.gif\" border=0 value=\"&Auml;nderungen speichern\" />";
				$content.="&nbsp;<input type=\"image\" src=\"./pictures/buttons/abbrechen-button.gif\" border=0 value=\"Abbrechen\" />";
				$content.= "<input type=\"hidden\" name=\"open\" value=\"".$db->f("folder_id")."_sc_\" />";
				$content.="<input type=\"hidden\" name=\"type\" value=1 />";
				}
			elseif ($db->f("description"))
				$content= htmlReady($db->f("description"));
			else
				$content= "Keine Beschreibung vorhanden";
			
			if ($move == $db->f("folder_id")) 
				$content.="<br />Dieser Ordner wurde zum Verschieben markiert. Bitte w&auml;hlen sie das Einf&uuml;gen-Symbol <img src=\"pictures/move.gif\" border=0 alt=\"Klicken Sie dieses Symbol, um diesen Ordner in einen anderen Ordner einzuf&uuml;gen\">, um ihn in den gew&uuml;nschten Ordner zu verschieben.";
			
			if ($upload == $db->f("folder_id")) {
				$content.=upload_item ($upload,FALSE,FALSE);
				}

			$content.= "\n";
			$edit='';
					
			//Editbereich erstellen
			if (($change != $db->f("folder_id")) && ($upload != $db->f("folder_id"))) {
				if (($rechte) || ($SemUserStatus == "autor")) {
					$edit= "<a href=\"$PHP_SELF?open=".$db->f("folder_id")."_u_#anker\"><img src=\"./pictures/buttons/dateihochladen-button.gif\" border=0></a>";
					if ($rechte) {
						$edit.= " <a href=\"$PHP_SELF?open=".$db->f("folder_id")."_n_#anker\"><img src=\"./pictures/buttons/neuerordner-button.gif\" border=0></a>"; 
						if (($letzter == 0) && ($dok_letzter==0)) {
							$edit.= " <a href=\"$PHP_SELF?open=".$db->f("folder_id")."_d_\"><img src=\"./pictures/buttons/loeschen-button.gif\"  border=0></a>";
							}
						$edit.= " <a href=\"$PHP_SELF?open=".$db->f("folder_id")."_m_#anker\"><img src=\"./pictures/buttons/verschieben-button.gif\"  border=0></a>";	
						if (!$level==0)
							$edit.= " <a href=\"$PHP_SELF?open=".$db->f("folder_id")."_c_#anker\"><img src=\"./pictures/buttons/bearbeiten-button.gif\"  border=0></a>";
						}
					}
				}

			if (!$all) {?><td class="blank" width="*">&nbsp;</td></tr></table><table width="100%" cellpadding=0 cellspacing=0 border=0><tr><?}

			if (!$all) echo $striche.$striche2;			
			
			//Ordner-Content ausgeben
			if (!$all) printcontent ("99%", TRUE, $content, $edit);
			
			$s=0;
			
			//Hier wird der Ordnerinhalt (Dokumente) gelistet
			while ($db3->next_record()) { 			
				
				$s++;
				if (($dok_letzter == $s) && (!$letzter))
					$striche3="<td class=\"blank\" nowrap background='pictures/forumleer.gif'><img src='pictures/forumstrich2.gif'></td>"; //Knick
				else
					$striche3="<td class=\"blank\" nowrap background='pictures/forumleer.gif'><img src='pictures/forumstrich3.gif'></td>"; //Verzweigung
				
				//Icon auswaehlen
				if ((getFileExtension(strtolower($db3->f("filename"))) == "rtf") || (getFileExtension(strtolower($db3->f("filename"))) == "doc"))
					$icon="<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src='pictures/rtf-icon.gif' border=0></a>";
				elseif (getFileExtension(strtolower($db3->f("filename"))) == "xls")
					$icon="<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src='pictures/xls-icon.gif' border=0></a>";
				elseif (getFileExtension(strtolower($db3->f("filename"))) == "zip")
					$icon="<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src='pictures/zip-icon.gif' border=0></a>";
				elseif (getFileExtension(strtolower($db3->f("filename"))) == "ppt")
					$icon="<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src='pictures/ppt-icon.gif' border=0></a>";
				elseif (getFileExtension(strtolower($db3->f("filename"))) == "pdf")
					$icon="<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src='pictures/pdf-icon.gif' border=0></a>";
				elseif ((getFileExtension(strtolower($db3->f("filename"))) == "gif") || (getFileExtension(strtolower($db3->f("filename"))) == "jpg") ||  (getFileExtension(strtolower($db3->f("filename"))) == "jpe") ||  (getFileExtension(strtolower($db3->f("filename"))) == "jpeg") || (getFileExtension(strtolower($db3->f("filename"))) == "bmp"))
					$icon="<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src='pictures/pic-icon.gif' border=0></a>";
				else
					$icon="<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src='pictures/txt-icon.gif' border=0></a>";

				//Link erstellen
				if (strstr($open,$db3->f("dokument_id"))) 
					$link=$PHP_SELF."?close=".$db3->f("dokument_id")."#anker";
				else
					$link=$PHP_SELF."?open=".$db3->f("dokument_id")."#anker";
				
				//Titelbereich erstellen
				$tmp_titel=mila($db3->f("filename"));
				$titel= $tmp_titel."&nbsp;&nbsp;(".round ($db3->f("filesize") / 1024)." kB)";
				
				//Zusatzangaben erstellen
				$zusatz="<a href=\"about.php?username=".$db3->f("username")."\"><font color=\"#333399\">".$db3->f("Vorname")." ".$db3->f("Nachname")."</font></a>&nbsp;".date("d.m.Y - H:i",$db3->f("mkdate"));			

				?><td class="blank" width="*">&nbsp;</td></tr></table><table width="100%" cellpadding=0 cellspacing=0 border=0><tr><?
				
				if (!$all) echo $striche.$striche3;
				else {
					?><td class="blank" width="*">&nbsp;</td><?
					}
			
				//Neue Datei herausfinden
				if ($loginfilelast[$SessSemName[1]] < $db3->f("mkdate")) 
					$neue_datei = TRUE;
				else
					$neue_datei = FALSE;
				
				//Dokumenttitelzeile ausgeben
				if (strstr($open,$db3->f("dokument_id"))) 
					printhead ("90%", 0, $link, "open", $neue_datei, $icon, $titel, $zusatz);
				else
					printhead ("90%", 0, $link, "close", $neue_datei, $icon, $titel, $zusatz);				
				
				//Dokumentansicht aufgeklappt 
				if (strstr($open,$db3->f("dokument_id"))) {  
					$content='';

					if (($dok_letzter == $s) && (!$letzter))
						$striche4="<td class=\"blank\" nowrap background='pictures/forumleer.gif'><img src='pictures/forumleer2.gif'></td>";
					else
						$striche4="<td class=\"blank\" nowrap background='pictures/forumstrich.gif'><img src='pictures/forumleer2.gif'></td>";					
						
					//Ankerlogik
					if (($change) || ($move) || ($upload)) {
						if (($change == $db3->f("dokument_id")) ||  ($move == $db3->f("dokument_id")) ||  ($upload == $db3->f("dokument_id")))
							echo "<a name='anker'></a>";
						}
					elseif ($db3->f("dokument_id") == substr($open, strlen($open) -32, strlen ($open)))
						echo "<a name='anker'></a>";
								
					if ($change == $db3->f("dokument_id")) { 	//Aenderungsmodus, Formular aufbauen
						$content.= "<br /><textarea name=\"change_description\" rows=3 cols=40>".$db3->f("description")."</textarea><br />";
						$content.= "<input type=\"image\" src=\"./pictures/buttons/uebernehmen-button.gif\" border=0 value=\"&Auml;nderungen speichern\" />";
						$content.= "&nbsp;<input type=\"image\" src=\"./pictures/buttons/abbrechen-button.gif\" border=0 value=\"Abbrechen\" />";						
						$content.= "<input type=\"hidden\" name=\"open\" value=\"".$db3->f("dokument_id")."_sc_\" />";
						$content.= "<input type=\"hidden\" name=\"type\" value=0 />";
						}
					else {
						if ($db3->f("description"))
							$content= htmlReady($db3->f("description"));
						else
							$content= "Keine Beschreibung vorhanden";
						$content.=  "<br />Dateigr&ouml;&szlig;e: ".round ($db3->f("filesize") / 1024)." kB";	
						}
			
					if ($move == $db3->f("dokument_id"))
						$content.="<br />Diese Datei wurde zum Verschieben markiert. Bitte w&auml;hlen sie das Einf&uuml;gen-Symbol <img src=\"pictures/move.gif\" border=0 alt=\"Klicken Sie dieses Symbol, um diese Datei in einen anderen Ordner einzuf&uuml;gen\">, um sie in den gew&uuml;nschten Ordner zu verschieben.";
										
					$content.= "\n";	
										
					//Editbereich ertstellen
					$edit='';
					if (($change != $db3->f("dokument_id")) && ($upload != $db3->f("dokument_id"))) {
						if (($rechte) || ($db3->f("user_id")==$user->id)) {
							$edit= "&nbsp;<a href=\"$PHP_SELF?open=".$db3->f("dokument_id")."_fd_\"><img src=\"./pictures/buttons/loeschen-button.gif\" border=0></a>";
							$edit.= "&nbsp;<a href=\"$PHP_SELF?open=".$db3->f("dokument_id")."_m_#anker \"><img src=\"./pictures/buttons/verschieben-button.gif\" border=0></a>";	
							$edit.= "&nbsp;<a href=\"$PHP_SELF?open=".$db3->f("dokument_id")."_c_#anker \"><img src=\"./pictures/buttons/bearbeiten-button.gif\" border=0></a>";
							}
						$edit.= "&nbsp;<a href=\"sendfile.php?type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src=\"./pictures/buttons/herunterladen-button.gif\" border=0></a>";
						if (getFileExtension(strtolower($db3->f("filename"))) != "zip")
							$edit.= "&nbsp;<a href=\"sendfile.php?zip=TRUE&type=0&file_id=".$db3->f("dokument_id") ."&file_name=".rawurlencode($db3->f("filename"))."\"><img src=\"./pictures/buttons/alsziparchiv-button.gif\" border=0></a>";						
						}
	
					
					//Dokument-Content ausgeben
					?><td class="blank" width="*">&nbsp;</td></tr></table><table width="100%" cellpadding=0 cellspacing=0 border=0><tr><?				
					if (!$all) echo $striche.$striche4;
					else {
						?><td class="blank" width="*">&nbsp;</td><?
						}
					printcontent ("100%",TRUE, $content, $edit);
					}
				}
			
			if (!$all) echo "<td class=\"blank\">&nbsp;</td></tr></td></table>";
			}			
		
		//Ordner nicht aufgeklappt 
		else {

			//Icon auswaehlen
			if ($dok_letzter) //Dokumente und Dateien vorhanden
				$icon="<img src=\"pictures/cont_folder.gif\">";	
			else
				$icon="<img src=\"pictures/cont_folder2.gif\">";				

			
			if ($move)
				$icon="&nbsp;<a href=\"$PHP_SELF?open=".$db->f("folder_id")."_md_\"><img src=\"pictures/move.gif\" border=0 alt=\"Objekt in diesen Ordner verschieben\" /></a>".$icon;
			
			//Link erstellen
			$link=$PHP_SELF."?open=".$db->f("folder_id")."#anker";
			
			//Titelbereich erstellen
			$tmp_titel=htmlReady(mila($db->f("name")));
			if ($dok_letzter > 1)
				$titel= $tmp_titel."&nbsp;&nbsp;($dok_letzter Dokumente)";
			elseif ($dok_letzter)
				$titel= $tmp_titel." &nbsp;&nbsp;($dok_letzter Dokument)";
			else
				$titel= $tmp_titel;		

			//Zusatzangaben erstellen
			$zusatz="<a href=\"about.php?username=".$db->f("username")."\"><font color=\"#333399\">".$db->f("Vorname")." ".$db->f("Nachname")."</font></a>&nbsp;".date("d.m.Y - H:i",$db->f("mkdate"));
			
			
			if ($loginfilelast[$SessSemName[1]] < $db->f("chdate")) 
				$neuer_ordner = TRUE;
			else
				$neuer_ordner = FALSE;
				
			//Objekttitelzeile ausgeben
			if (!$all) printhead ("90%", 0, $link, "close", $neuer_ordner, $icon, $titel, $zusatz);
			if (!$all) echo "<td class=\"blank\">&nbsp;</td></tr></td></table>";
			}

		//Rekursiv mit Unterordnern weitermachen	
		if (!$all)
			display_folder_system ($db->f("folder_id"), $level+1, $open, $lines, $change, $move, $upload, $all);
		}
	}

/*
Die function delete_document l�scht ein hochgeladenes Dokument.
Der erste Parameter ist die dokument_id des zu l�schenden Dokuments.
Der R�ckgabewert der Funktion ist bei Erfolg TRUE.
FALSE bedeutet einen Fehler beim Loeschen des Dokumentes.
Ausgabe wird keine produziert.
Es erfolgt keine �berpr�fung der Berechtigung innerhalb der Funktion,
dies muss das aufrufende Script sicherstellen.
*/

function delete_document ($dokument_id) {
	global $UPLOAD_PATH, $msg; // brauchen wir fuer den Pfad zu den Dokumenten

	$db = new DB_Seminar;
	
	if (!unlink("$UPLOAD_PATH/$dokument_id"))
		return FALSE;
		
	// eintrag aus der Datenbank werfen
	$db->query("DELETE FROM dokumente WHERE dokument_id='$dokument_id'");
	
	if ($db->affected_rows())
		return TRUE;
	else 
		return FALSE;
	}


/*
Die function delete_folder l�scht einen kompletten Dateiordner.
Der Parameter ist die folder_id des zu l�schenden Ordners.
Der R�ckgabewert der Funktion ist bei Erfolg TRUE.
FALSE bedeutet einen Fehler beim Loeschen des Dokumentes.
Ausgabe wird keine produziert.
Es erfolgt keine �berpr�fung der Berechtigung innerhalb der Funktion,
dies muss das aufrufende Script sicherstellen.
*/

function delete_folder ($folder_id) {
	global $msg; 
	
	$db = new DB_Seminar;
	
	$db->query("SELECT dokument_id FROM dokumente WHERE range_id='$folder_id'");
	while ($db->next_record())
		if ($delete_document($db->f("dokument_id")))
			$deleted++;

	$db->query("DELETE FROM folder WHERE folder_id='$folder_id'");
	if ($db->affected_rows()) {
		if ($deleted)
			$msg.="info�Der Dateiordner und $deleted Dokumente wurden gel&ouml;scht�";
		else
			$msg.="info�Der Dateiordner wurde gel&ouml;scht�";
		return TRUE;
		}
	else {
		if ($deleted)
			$msg.="error�Probleme beim L&ouml;schen des Ordners. $deleted Dokumente wurden gel&ouml;scht�";
		else
			$msg.="error�Probleme beim L&ouml;schen des Ordners�";
		return FALSE;
		}
	}
	

//Rekursive Loeschfunktion, loescht erst jeweils enthaltene Dokumente und dann den entsprechenden Ordner
function recursiv_folder_delete ($parent_id) {
	
	$db=new DB_Seminar;
	$db2=new DB_Seminar;
		
	$doc_count = 0;
	
	$db->query ("SELECT folder_id FROM folder WHERE range_id='$parent_id'");
	 
	while ($db->next_record()) {
		$doc_count += recursiv_folder_delete($db->f("folder_id"));

		$db2->query ("SELECT dokument_id FROM dokumente WHERE range_id='".$db->f("folder_id")."'");

		while ($db2->next_record()) {	 		 	
			if (delete_document($db2->f("dokument_id")))
				$doc_count++;
			}
		 
		 $db2->query ("DELETE FROM folder WHERE folder_id ='".$db->f("folder_id")."'");
		} 
	return $doc_count;
	}



/*
Die function delete_range_of_documents l�scht alle Dateien eines Items.
Der Parameter ist die range_id der zu l�schenden Dateien.
Es koennen also mit einem Aufruf alle Dateien eines Seminares
(nur die direkten im allgemeinen Ordner), eines Institutes oder
eines Termines aus der Datenbank und von der Platte entfernt werden.
-- update: Seit Einfuehrung des Folder-Systems nur noch noetig zum
Loeschen aller Dateien eines Folders.
Der R�ckgabewert der Funktion ist die Anzahl der gel�schten Dateien.
Ausgabe wird keine produziert.
Es erfolgt keine �berpr�fung der Berechtigung innerhalb der Funktion,
dies muss das aufrufende Script sicherstellen.
*/

function delete_range_of_documents ($range_id) {

	$db = new DB_Seminar;
	$count = 0;

	## Dokumente finden...
	$query = "SELECT dokument_id, filename FROM dokumente WHERE range_id='$range_id'";
	$db->query($query);
	while ($db->next_record()) {       // ...und nacheinander...
		if (delete_document($db->f("dokument_id"),$db->f("filename")))  // ...loeschen
			$count ++;
	}

	return $count;
}

?>