<?php
/**
* admin_metadates.php
* 
* edit the settings for generic dates from a Veranstaltung
* 
*
* @author		Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version		$Id$
* @access		public
* @module		admin_metadates.php
* @modulegroup	admin
* @package		studip_core
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// admin_metadates.php
// Terminmetadatenverwaltung von Stud.IP
// Copyright (C) 2002 Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>
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

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$perm->check("tutor");
	
if ($abbrechen_x)
	header ("Location: ".$term_metadata["source_page"]."?ebene=sem&range_id=".$term_metadata["sem_id"]);

$_language_domain = "studip_core";
include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

// -- here you have to put initialisations for the current page

// Start of Output
include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head
include ("$ABSOLUTE_PATH_STUDIP/links_admin.inc.php");

require_once("$ABSOLUTE_PATH_STUDIP/msg.inc.php");//ja auch die...
require_once("$ABSOLUTE_PATH_STUDIP/config.inc.php");//ja,ja auch die...
require_once("$ABSOLUTE_PATH_STUDIP/functions.php");//ja,ja,ja auch die...
require_once("$ABSOLUTE_PATH_STUDIP/visual.inc.php");//ja,ja,ja,ja auch die...

$db=new DB_Seminar;
$db2=new DB_Seminar;
$cssSw=new cssClassSwitcher;
$sess->register ("term_metadata");

/**
* This function creates a snapshot for all the values the admin_metadates script uses
*
* The function serializes all the data which is used on this page. So you can
* compare an old and a new state of the whole set. It is used to inform the user,
* that the data isn't saved yet.
*
* @param		string	all the data in serilized form
*
*/
function get_snapshot() {
	global $term_metadata;
	return	serialize($term_metadata["turnus_data"]).
			serialize($term_metadata["sem_start_time"]).
			serialize($term_metadata["sem_duration_time"]).
			serialize($term_metadata["sem_start_termin"]).
			serialize($term_metadata["sem_end_termin"]).
			serialize($term_metadata["sem_vor_termin"]).
			serialize($term_metadata["sem_vor_end_termin"]).
			serialize($term_metadata["start_woche"]).
			serialize($term_metadata["art"]);
}

//wenn wir frisch reinkommen, werden die alten Metadaten eingelesen
if (($seminar_id) && (!$uebernehmen_x) && (!$add_turnus_field_x) &&(!$delete_turnus_field) && !($open_ureg_x) && !($open_reg_x) && !($enter_start_termin_x) && !($nenter_start_termin_x)) {
	$db->query("SELECT metadata_dates, art, Name, start_time, duration_time, status FROM seminare WHERE Seminar_id = '$seminar_id'");
	$db->next_record();
	$term_metadata=unserialize($db->f("metadata_dates"));
	$term_metadata["source_page"]=$source_page;
	$term_metadata["sem_status"]=$db->f("status");
	$term_metadata["sem_name"]=$db->f("Name");	
	$term_metadata["sem_start_time"]=$db->f("start_time");	
	$term_metadata["sem_duration_time"]=$db->f("duration_time");	
	$term_metadata["sem_id"]=$seminar_id;
	if (!$term_metadata["sem_start_termin"]) $term_metadata["sem_start_termin"] =-1;
	if (!$term_metadata["sem_end_termin"]) $term_metadata["sem_end_termin"] =-1;
	if (!$term_metadata["sem_vor_termin"]) $term_metadata["sem_vor_termin"] =-1;
	if (!$term_metadata["sem_vor_end_termin"]) $term_metadata["sem_vor_end_termin"] =-1;
	$term_metadata["original"]=get_snapshot();
	}
else {

//Sicherheitscheck ob &uuml;berhaupt was zum Bearbeiten gewaehlt ist.
if (!$term_metadata["sem_id"]) {
	echo "</tr></td></table>";
	die;
}

if (($turnus_refresh) || ($term_refresh))
	{
	if (($sem_duration_time == 0) || ($sem_duration_time == -1))
		$term_metadata["sem_duration_time"]=$sem_duration_time;
	else
		$term_metadata["sem_duration_time"]=$sem_duration_time - $sem_start_time;	
	$term_metadata["sem_start_time"]=$sem_start_time;
	}

if ($turnus_refresh)
	{
	if ($term_metadata["start_woche"] !=-1)
		$term_metadata["start_woche"]=$term_start_woche;
	$term_metadata["turnus"]=$term_turnus;	


	//Arrays fuer Turnus loeschen
	$term_metadata["turnus_data"]='';

	//Alle eingegebenen Turnus-Daten in Sessionvariable uebernehmen
	for ($i=0; $i<$term_metadata["turnus_count"]; $i++)
		{
		$term_metadata["turnus_data"][$i]["day"]=$turnus_day[$i]; 
		$term_metadata["turnus_data"][$i]["start_stunde"]=$turnus_start_stunde[$i];
		$term_metadata["turnus_data"][$i]["start_minute"]=$turnus_start_minute[$i]; 
		$term_metadata["turnus_data"][$i]["end_stunde"]=$turnus_end_stunde[$i]; 
		$term_metadata["turnus_data"][$i]["end_minute"]=$turnus_end_minute[$i]; 
		//diese Umwandlung muessen hier passieren, damit Werte mit fuehrender Null nicht als String abgelegt werden und so spaeter Verwirrung stiften
		settype($term_metadata["turnus_data"][$i]["start_stunde"], "integer");
		settype($term_metadata["turnus_data"][$i]["start_minute"], "integer");  
		settype($term_metadata["turnus_data"][$i]["end_stunde"], "integer");		
		settype($term_metadata["turnus_data"][$i]["end_minute"], "integer");
		}
	}
	
if (($turnus_refresh) || ($term_metadates["start_woche"] ==-1))
	{
	//Datum fuer ersten Termin umwandeln. Checken muessen wir es auch leider direkt hier, da wir es sonst nicht umwandeln duerfen
	if (($jahr>0) && ($jahr<100))
		 $jahr=$jahr+2000;

	if ($monat == _("mm")) $monat=0;
	if ($tag == _("tt")) $tag=0;
	if ($jahr == _("jjjj")) $jahr=0;	

	if (!checkdate($monat, $tag, $jahr) && ($monat) && ($tag) && ($jahr))
		{
		$errormsg=$errormsg."error�"._("Bitte geben Sie ein g&uuml;ltiges Datum ein!")."�";
		$check=FALSE;			
		}
	else
		$check=TRUE;
	if ($check)
 		$term_metadata["start_termin"] = mktime(0,0,0,$monat,$tag,$jahr);
	else
		$term_metadata["start_termin"] = -1;
	}

//Felder fuer Standardtermine hinzufuegen/l&ouml;schen
if ($add_turnus_field_x)
	{
	$term_metadata["turnus_count"]++;
	}

if ($delete_turnus_field)
	{
	for ($i=0; $i<$term_metadata["turnus_count"]; $i++)
		if ($i != ($delete_turnus_field-1))
			{
			$temp_term_turnus_date[]=$term_metadata["term_turnus_date"][$i];
			$tmp_term_turnus_start_stunde[]=$term_metadata["term_turnus_start_stunde"][$i];
			$tmp_term_turnus_start_minute[]=$term_metadata["term_turnus_start_minute"][$i]; 
			$tmp_term_turnus_end_stunde[]=$term_metadata["term_turnus_end_stunde"][$i]; 
			$tmp_term_turnus_end_minute[]=$term_metadata["term_turnus_end_minute"][$i]; 
			}
	$term_metadata["term_turnus_date"]=$temp_term_turnus_date;
	$term_metadata["term_turnus_start_stunde"]=$tmp_term_turnus_start_stunde;
	$term_metadata["term_turnus_start_minute"]=$tmp_term_turnus_start_minute;
	$term_metadata["term_turnus_end_stunde"]=$tmp_term_turnus_end_stunde;
	$term_metadata["term_turnus_end_minute"]=$tmp_term_turnus_end_minute;
	
	$term_metadata["turnus_count"]--;
	}
	
  
//Checks performen
if (($term_metadata["sem_duration_time"]<0) && ($term_metadata["sem_duration_time"] != -1))
	{ 
	$errormsg=$errormsg."error�"._("Das Endsemester darf nicht vor dem Startsemester liegen, bitte &auml;ndern Sie die entsprechenden Einstellungen!")."�";
	}
	
if ($term_metadata["art"]==0)
	{
	for ($i=0; $i<$term_metadata["turnus_count"]; $i++)
		if ((($term_metadata["turnus_data"][$i]["start_stunde"]) || ($term_metadata["turnus_data"][$i]["end_stunde"])))
			{
			if ((($term_metadata["turnus_data"][$i]["start_stunde"]) && (!$term_metadata["turnus_data"][$i]["end_stunde"])) || ((!$term_metadata["turnus_data"][$i]["start_stunde"]) && ($term_metadata["end_stunde"])))
					{
					if (!$just_informed)
						$errormsg=$errormsg."error�"._("Bitte f&uuml;llen Sie beide Felder f&uuml;r Start- und Endzeit der regul&auml;ren Termine aus!")."�";	
					$just_informed=TRUE;
					}
			if ((($term_metadata["turnus_data"][$i]["start_stunde"]>23) || ($term_metadata["turnus_data"][$i]["start_stunde"]<0))  ||  (($term_metadata["turnus_data"][$i]["start_minute"]>59) || ($term_metadata["turnus_data"][$i]["start_minute"]<0))  ||  (($term_metadata["turnus_data"][$i]["end_stunde"]>23) ||($term_metadata["turnus_data"][$i]["end_stunde"]<0))  || (($term_metadata["turnus_data"][$i]["end_minute"]>59) || ($term_metadata["turnus_data"][$i]["end_minute"]<0)))
					{
					if (!$just_informed3)
						$errormsg=$errormsg."error�"._("Sie haben eine ung&uuml;ltige Zeit eingegeben, bitte korrigieren sie dies!")."�";	
					$just_informed3=TRUE;
					}
			if (mktime($term_metadata["turnus_data"][$i]["start_stunde"], $term_metadata["turnus_data"][$i]["start_minute"], 0, 1, 1, 2001) > mktime($term_metadata["turnus_data"][$i]["end_stunde"], $term_metadata["turnus_data"][$i]["end_minute"], 0, 1, 1, 2001)) 
				if ((!$just_informed5) && (!$just_informed)) {
					$errormsg=$errormsg."error�"._("Die jeweilige Endzeitpunkt der regul&auml;ren Termine muss nach dem jeweiligen Startzeitpunkt liegen!")."�";
					$just_informed5=TRUE;				
				}
			}
			elseif(!$just_informed4) 
				if ((!$term_metadata["turnus_data"][$i]["start_stunde"]) && (!$term_metadata["turnus_data"][$i]["start_minute"]) && (!$term_metadata["turnus_data"][$i]["end_stunde"]) && (!$term_metadata["turnus_data"][$i]["end_minute"]))
					$empty_fields++;
				else
					{
					$errormsg=$errormsg."error�"._("Sie haben nicht alle Felder der regul&auml;ren Termine ausgef&uuml;llt, bitte korrigieren sie dies!")."�";
					$just_informed4=TRUE;
					}
	}

if (($term_metadata["start_termin"] == -1) && ($term_metadata["start_woche"] ==-1))
	$errormsg=$errormsg."error�"._("Bitte geben Sie einen ersten Termin an!")."�";
else
	if ((($stunde) && (!$end_stunde)) || ((!$stunde) && ($end_stunde)))
		$errormsg=$errormsg."error�"._("Bitte f&uuml;llen Sie beide Felder f&uuml;r Start- und Endzeit des ersten Termins aus!")."�";	
}

//Umschalter zwischen den Typen
if ($open_ureg_x)
	$term_metadata["art"]=0;
if ($open_reg_x)
	$term_metadata["art"]=1;
if ($enter_start_termin_x)
	$term_metadata["start_woche"]=-1;
if ($nenter_start_termin_x)
	$term_metadata["start_woche"]=0;

//Daten speichern
if (($uebernehmen_x) && (!$errormsg))
	{
	//Termin-Metadaten-Array erzeugen
	$metadata_termin["art"]=$term_metadata["art"];
	$metadata_termin["start_termin"]=$term_metadata["start_termin"];
	$metadata_termin["start_woche"]=$term_metadata["start_woche"];
	$metadata_termin["turnus"]=$term_metadata["turnus"];
	
	//indiziertes (=sortierbares) temporaeres Array erzeugen
	if ($term_metadata["art"] == 0)
		{
		for ($i=0; $i<$term_metadata["turnus_count"]; $i++)
			if (($term_metadata["turnus_data"][$i]["start_stunde"])  && ($term_metadata["turnus_data"][$i]["end_stunde"]))
				$tmp_metadata_termin["turnus_data"][]=array("idx"=>$term_metadata["turnus_data"][$i]["day"].$term_metadata["turnus_data"][$i]["start_stunde"].$term_metadata["turnus_data"][$i]["start_minute"], "day" => $term_metadata["turnus_data"][$i]["day"], "start_stunde" => $term_metadata["turnus_data"][$i]["start_stunde"], "start_minute" => $term_metadata["turnus_data"][$i]["start_minute"], "end_stunde" => $term_metadata["turnus_data"][$i]["end_stunde"], "end_minute" => $term_metadata["turnus_data"][$i]["end_minute"]);
	
		//sortieren
		if (is_array($tmp_metadata_termin["turnus_data"])) {
			sort ($tmp_metadata_termin["turnus_data"]);
		
			foreach ($tmp_metadata_termin["turnus_data"] as $tmp_array) {
				$metadata_termin["turnus_data"][]=$tmp_array;
			}
		}
	}
		
	//Termin-Metadaten-Array zusammenmatschen zum beseren speichern in der Datenbank
	$serialized_metadata=serialize ($metadata_termin);
	
	//speichern
	$db->query ("UPDATE seminare SET metadata_dates='$serialized_metadata', start_time='".$term_metadata["sem_start_time"]."', duration_time='".$term_metadata["sem_duration_time"]."' WHERE Seminar_id ='".$term_metadata["sem_id"]."'");
	if ($db->affected_rows()) {
		$errormsg.="msg�"._("Die allgemeinen Termindaten wurden aktualisiert")."�";
		$db->query ("UPDATE seminare SET chdate='".time()."' WHERE Seminar_id ='".$term_metadata["sem_id"]."'");
		//If resource-management activ, update the assigned reources
		if ($RESOURCES_ENABLE) {
		 	require_once ($RELATIVE_PATH_RESOURCES."/lib/VeranstaltungResourcesAssign.class.php");
		 	$veranstAssign = new VeranstaltungResourcesAssign($term_metadata["sem_id"]);
		 	$veranstAssign->updateAssign();
		}
	}
	
	//Save the current state as snapshot to compare with current data
	$term_metadata["original"]=get_snapshot();

	$metadata_saved=TRUE;
	}
 
 if (($errormsg) && (($open_reg_x) || ($open_ureg_x) || ($enter_start_termin_x) || ($nenter_start_termin_x) || ($add_turnus_field_x) || ($delete_turnus_field)))
 	$errormsg='';	
 
 if ((!$metadata_saved) || (!$term_metadata["source_page"]))
 	{
?>
	<table width="100%" border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td class="topic" colspan=2>&nbsp; <b>
		<?
		echo getHeaderLine($term_metadata["sem_id"])." -  "._("allgemeine Zeiten");
		?>
		</td>
	</tr>
	<?
	if (isset($errormsg)) {
	?>
	<tr> 
		<td class="blank" colspan=2><br />
		<?parse_msg($errormsg);?>
		</td>
	</tr>
	<? } ?>
 	<tr>
		<td class="blank" valign="top">
			<br />
			<blockquote>
			<?
			print "<b>"._("Zeiten der Veranstaltung bearbeiten")."</b><br /><br />";
			print _("Sie k&ouml;nnen hier die allgemeinen Zeiten bearbeiten.")." <br />";
			printf (_("Spezifische Termine zur Anzeige im Ablaufplan legen Sie unter dem Menupunkt %s Ablaufplan </a> fest."), "<a href=\"admin_dates.php?ebene=sem&range_id=".$term_metadata["sem_id"]."\">");
			?>
			</blockqoute>
		</td>
		<td class="blank" align="right">
			<img src="pictures/board2.jpg" border="0">
		</td>
	</tr>
	<tr>
	<td class="blank" colspan=2>
	<form method="POST" action="<? echo $PHP_SELF ?>">
		<table width="99%" border=0 cellpadding=2 cellspacing=0 align="center">
		<tr <? $cssSw->switchClass() ?>>
			<td class="<? echo $cssSw->getClass() ?>" align="center" colspan=3>		
				<input type="IMAGE" name="uebernehmen" <? echo makeButton ("uebernehmen", "src") ?> border=0 value="uebernehmen">
				<? if ($term_metadata["source_page"]) {
					?> &nbsp; <input type="IMAGE" name="abbrechen" <? echo makeButton ("abbrechen", "src") ?> border=0 value="abbrechen"> <?
					}
				?>
				<? if ($term_metadata["original"] != get_snapshot()) {
					?> <br /><font size=-1><? print _("Diese Daten sind noch nicht gespeichert.") ?></font><br /> <?
					}
				?>
			</td>
		</tr>
		<tr <? $cssSw->switchClass() ?> rowspan=2>
			<td class="<? echo $cssSw->getClass() ?>" width="4%" align="right" rowspan=2>
				&nbsp;
			</td>
			<td class="<? echo $cssSw->getClass() ?>"  colspan=2 align="left">
				<font size=-1><b>&nbsp;<?= _("Allgemeine Zeiten:") ?></b><br /></font>
				<font size=-1>&nbsp;<?= _("Sie k&ouml;nnen hier angeben, ob die Veranstaltung regelm&auml;&szlig;ig stattfindet oder die Termine unregelm&auml;&szlig;ig sind (etwa bei einer Blockveranstaltung).") ?><br /></font>
				<br />&nbsp;<input type="IMAGE" name="open_ureg" <? if (!$term_metadata["art"]) print makeButton ("regelmaessig2", "src");  else print makeButton ("regelmaessig", "src") ?> border=0 value="regelmaessig">&nbsp; 
				<input type="IMAGE" name="open_reg"  <? if (!$term_metadata["art"]) print makeButton ("unregelmaessig", "src");  else print makeButton ("unregelmaessig2", "src") ?> border=0 value="unregelmaessig">
			</td>
		</tr>
		<?
		if (!$term_metadata["art"]) {
		?>
					<tr>
						<td class="<? echo $cssSw->getClass() ?>" width="96%" colspan=2>
							<br /><font size=-1>&nbsp;<?=("Turnus:") ?> &nbsp; <select name="term_turnus"></font>
							<?
							if ($term_metadata["turnus"]==0)
								echo "<option selected value=0>"._("w&ouml;chentlich")."</option>";
							else
								echo "<option value=0>"._("w&ouml;chentlich")."</option>";
							if ($term_metadata["turnus"]==1)
								echo "<option selected value=1>"._("zweiw&ouml;chentlich")."</option>";
							else
								echo "<option value=1>"._("zweiw&ouml;chentlich")."</option>";
							?>
							</select>
							<br><br><font size=-1>&nbsp; <?=_("Die Veranstaltung findet immer zu diesen Zeiten statt:")?></font><br><br>
							<?
							if (!$term_metadata["turnus_count"])
								{
								if (sizeof($term_metadata["turnus_data"])>0) 
									{
									$term_metadata["turnus_count"]=sizeof($term_metadata["turnus_data"]);
									}
								else
									$term_metadata["turnus_count"]=2;
								}
								
							for ($i=0; $i<$term_metadata["turnus_count"]; $i++)
								{
								if ($i>0) echo "<br>";
								?>&nbsp; <select name="turnus_day[<?echo $i?>]">
								<?
								if ($term_metadata["turnus_data"][$i]["day"]==1)
									echo "<option selected value=1>"._("Montag")."</option>";
								else
									echo "<option value=1>"._("Montag")."</option>";
								if ($term_metadata["turnus_data"][$i]["day"]==2)
									echo "<option selected value=2>"._("Dienstag")."</option>";
								else
									echo "<option value=2>"._("Dienstag")."</option>";
								if ($term_metadata["turnus_data"][$i]["day"]==3)
									echo "<option selected value=3>"._("Mittwoch")."</option>";
								else
									echo "<option value=3>"._("Mittwoch")."</option>";
								if ($term_metadata["turnus_data"][$i]["day"]==4)
									echo "<option selected value=4>"._("Donnerstag")."</option>";
								else
									echo "<option value=4>"._("Donnerstag")."</option>";
								if ($term_metadata["turnus_data"][$i]["day"]==5)
									echo "<option selected value=5>"._("Freitag")."</option>";
								else
									echo "<option value=5>"._("Freitag</option>")."</option>";
								if ($term_metadata["turnus_data"][$i]["day"]==6)
									echo "<option selected value=6>"._("Samstag")."</option>";
								else
									echo "<option value=6>"._("Samstag")."</option>";
								if ($term_metadata["turnus_data"][$i]["day"]==7)
									echo "<option selected value=7>"._("Sonntag")."</option>";
								else
									echo "<option value=7>"._("Sonntag")."</option>";
									echo "</select>\n";
								?>
								&nbsp; <input type="text" name="turnus_start_stunde[<?echo $i?>]" size=2 maxlength=2 value="<? if ($term_metadata["turnus_data"][$i]["start_stunde"]) echo $term_metadata["turnus_data"][$i]["start_stunde"] ?>"> :
								<input type="text" name="turnus_start_minute[<?echo $i?>]" size=2 maxlength=2 value="<? if (($term_metadata["turnus_data"][$i]["start_minute"]) && ($term_metadata["turnus_data"][$i]["start_minute"] >0)) { if ($term_metadata["turnus_data"][$i]["start_minute"] < 10) echo "0", $term_metadata["turnus_data"][$i]["start_minute"]; else echo $term_metadata["turnus_data"][$i]["start_minute"];  } elseif ($term_metadata["turnus_data"][$i]["start_stunde"]) echo "00"; ?>"><?=_("Uhr bis")?>
								&nbsp; <input type="text" name="turnus_end_stunde[<?echo $i?>]" size=2 maxlength=2 value="<? if ($term_metadata["turnus_data"][$i]["end_stunde"]) echo $term_metadata["turnus_data"][$i]["end_stunde"] ?>"> :
								<input type="text" name="turnus_end_minute[<?echo $i?>]" size=2 maxlength=2 value="<? if (($term_metadata["turnus_data"][$i]["end_minute"]) && ($term_metadata["turnus_data"][$i]["end_minute"] >0)) { if ($term_metadata["turnus_data"][$i]["end_minute"] < 10) echo "0", $term_metadata["turnus_data"][$i]["end_minute"]; else echo $term_metadata["turnus_data"][$i]["end_minute"];  } elseif ($term_metadata["turnus_data"][$i]["end_stunde"]) echo "00"; ?>"><?=_("Uhr")?>
								<? if ($term_metadata["turnus_count"]>1) 
									{
									?>
									&nbsp; <a href="<? echo $PHP_SELF?>?delete_turnus_field=<?echo $i+1?>"><img border=0 src="./pictures/trash.gif" <? tooltip(_("Dieses Feld aus der Auswahl l&ouml;schen")) ?>></a>
									<?
									}
								}
								?>
								<input type="HIDDEN" name="turnus_refresh" value="TRUE">
								&nbsp; &nbsp; <input type="IMAGE" name="add_turnus_field" <?=makeButton("feldhinzufuegen", "src") ?> border=0 value="Feld hinzuf&uuml;gen"><br />
						</td>
					</tr>
		<?
			}
		else 
			{
		?>
					<tr >
						<td class="<? echo $cssSw->getClass() ?>" width="96%" colspan=2>
							&nbsp;<font size=-1><?=_("Bitte geben Sie die einzelnen Sitzungstermine unter dem Menupunkt Ablaufplan ein!")?></font><br><br>
							<input type="HIDDEN" name="term_refresh" value="TRUE">
						</td>
					</tr>
		<?
			}
	
		if (!$term_metadata["art"])
			{
		?>
					<tr <? $cssSw->switchClass() ?> rowspan=2>
						<td class="<? echo $cssSw->getClass() ?>" width="4%" align="right" rowspan=2>
							&nbsp;
					</td>
						<td class="<? echo $cssSw->getClass() ?>"  colspan=2 align="left">
							&nbsp;<font size=-1><b><?=_("Veranstaltungsbeginn")?></b></font><br /><br />
							<font size=-1>&nbsp;<?=_("Bei einer regelm&auml;&szlig;igen Veranstaltungen k&ouml;nnen sie den ersten Termin eingeben oder automatisch berechnen lassen.") ?></font><br />
							<br />&nbsp;<input type="IMAGE" name="nenter_start_termin" <? if (!$term_metadata["start_woche"]) print makeButton ("automatisch2", "src");  else print makeButton ("automatisch", "src") ?> border=0 value="automatisch">&nbsp; 
							<input type="IMAGE" name="enter_start_termin" <? if (!$term_metadata["start_woche"]) print makeButton ("eingeben", "src");  else print makeButton ("eingeben2", "src") ?> border=0 value="eingeben">
						</td>
					</tr>
		<?
			if ($term_metadata["start_woche"] !=-1)
				{
		?>
					<tr>
						<td class="<? echo $cssSw->getClass() ?>" width="96%" colspan=2>
							<br />&nbsp;<font size=-1><?=_("Veranstaltungsbeginn in der")?> <select name="term_start_woche">
							<?
							if ($term_metadata["start_woche"]==0)
								echo "<option selected value=0>"._("1. Semesterwoche")."</option>";
							else
								echo "<option value=0>"._("1. Semesterwoche")."</option>";
							if ($term_metadata["start_woche"]==1)
								echo "<option selected value=1>"._("2. Semesterwoche")."</option>";
							else
								echo "<option value=1>"._("2. Semesterwoche")."</option>";								
							?>
							</select></font>
						</td>
					</tr>
		<?
				}
			else
				{
		?>
					<tr>
						<td class="<? echo $cssSw->getClass() ?>" width="96%" colspan=2>
							<br /><font size=-1>&nbsp;<?=_("Bitte geben Sie hier den ersten Termin ein:")?></font><br>
							&nbsp;<input type="text" name="tag" size=2 maxlength=2 value="<? if ($term_metadata["start_termin"]<>-1) echo date("d",$term_metadata["start_termin"]); else echo _("tt") ?>">.
							<input type="text" name="monat" size=2 maxlength=2 value="<? if ($term_metadata["start_termin"]<>-1) echo date("m",$term_metadata["start_termin"]); else echo _("mm") ?>">.
							<input type="text" name="jahr" size=4 maxlength=4 value="<? if ($term_metadata["start_termin"]<>-1) echo date("Y",$term_metadata["start_termin"]); else echo _("jjjj") ?>">&nbsp; 
						</td>
					</tr>
		<?
			}
			}
		?>
					<tr <? $cssSw->switchClass() ?>>
						<td class="<? echo $cssSw->getClass() ?>" width="4%" rowspan=2>
							&nbsp;
						</td>
						<td class="<? echo $cssSw->getClass() ?>" width="96%" colspan=2>
							<b><font size=-1>&nbsp;<?=_("weitere Daten")?></font></b>
						</td>
					</tr>
					<tr>
						<td class="<? echo $cssSw->getClass() ?>" width="4%">
							&nbsp;<font size=-1><?=_("Semester")?></font>
							<?
							echo "&nbsp;<select name=\"sem_start_time\">";
							for ($i=1; $i<=sizeof($SEMESTER); $i++)
								{
								if ($term_metadata["sem_start_time"] ==$SEMESTER[$i]["beginn"])
									echo "<option value=".$SEMESTER[$i]["beginn"]." selected>", $SEMESTER[$i]["name"], "</option>";
								else
									echo "<option value=".$SEMESTER[$i]["beginn"].">", $SEMESTER[$i]["name"], "</option>";
								}
							echo "</select>";
							?>
						</td>
						<td class="<? echo $cssSw->getClass() ?>" width="96%" valign="bottom">
							&nbsp;<font size=-1><?=_("Dauer")?><br /></font>
							&nbsp;<select name="sem_duration_time">
							<?
								if ($term_metadata["sem_duration_time"] == 0)
									echo "<option value=0 selected>"._("1 Semester")."</option>";
								else
									echo "<option value=0>"._("1 Semester")."</option>";
								$i=1;
								for ($i; $i<=sizeof($SEMESTER); $i++)
									{
									if (($term_metadata["sem_start_time"] + $term_metadata["sem_duration_time"]) == $SEMESTER[$i]["beginn"])
										{
										if ((!$term_metadata["sem_duration_time"] == 0) && (!$term_metadata["sem_duration_time"] == 0))
											echo "<option value=",$SEMESTER[$i]["beginn"], " selected>"._("bis")." ", $SEMESTER[$i]["name"], "</option>";
										else
											echo "<option value=",$SEMESTER[$i]["beginn"], ">"._("bis")." ", $SEMESTER[$i]["name"], "</option>";
										}
									else
										echo "<option value=",$SEMESTER[$i]["beginn"], ">"._("bis")." ", $SEMESTER[$i]["name"], "</option>";
									}
								if ($term_metadata["sem_duration_time"] == -1)
									echo "<option value=-1 selected>"._("unbegrenzt")."</option>";
								else
									echo "<option value=-1>"._("unbegrenzt")."</option>";
							?>
							</select>
						</td>
					</tr>
		</td>
	</tr>
	<tr <? $cssSw->switchClass() ?>>
		<td class="<? echo $cssSw->getClass() ?>" align="center" colspan=3>		
			<input type="IMAGE" name="uebernehmen" <?=makeButton("uebernehmen", "src") ?> border=0 value="uebernehmen">
			<? if ($term_metadata["source_page"]) {
				?> &nbsp; <input type="IMAGE" name="abbrechen" <?=makeButton("abbrechen", "src") ?> border=0 value="abbrechen"> <?
				}
			?>
		</td>
	</tr>
	<tr>
		<td class="blank" colspan=3>
			&nbsp;
		</td>
	</tr>
	</form>
<?
	}
elseif ($term_metadata["source_page"])
	{
	if ($auth->auth["jscript"])
		{
		$result=rawurlencode ($errormsg);
		?>
		<script language="JavaScript">
			 location.href = "<? echo $term_metadata["source_page"]."?ebene=sem&range_id=".$term_metadata["sem_id"]."&result=".$result ?>"
		</script>
		<?
		}
	else		
		{
		?>	
		<table width="100%" border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td class="blank" colspan=2>&nbsp;
				</td>
			</tr>
			<tr>	
				<td class="topic" colspan=2>&nbsp; <b>Bearbeiten der allgemeinen Termindaten der Veranstaltung "<? echo $term_metadata["sem_name"] ?>"</b>
				</td>
			</tr>
			<tr>
				<td class="blank"colspan=2>&nbsp; <br>

		<?
			if (isset($errormsg)) 
				{
				parse_msg($errormsg);
				}
		?>
				</td>
			</tr>
			<tr>
				<td class="blank" colspan=2>
				&nbsp; zur&uuml;ck zur <a href="admin_dates.php?ebene=sem&range_id=<? echo $term_metadata["sem_id"] ?>">Verwaltung des Ablaufplans</a><br><br>
				</td>
			</tr>
		<?
		}
	}		
	page_close();
?>
	</table>
</td>
</tr>
</table>
</body>
</html>