<?php
/*
meine_seminare.php - Anzeige der eigenen Seminare (anhaengig vom Status)
Copyright (C) 2000 Stefan Suchi <suchi@gmx.de>, Ralf Stockmann <rstockm@gwdg.de>

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

ob_start(); //Outputbuffering f�r maximal Performance

function get_my_sem_values(&$my_sem) {
	 global $user,$loginfilenow;
	 $db2 = new DB_seminar;
	 $my_semids="('".implode("','",array_keys($my_sem))."')";
// Postings
	 $db2->query ("SELECT Seminar_id,count(*) as count FROM px_topics WHERE Seminar_id IN ".$my_semids." GROUP BY Seminar_id");
	 while($db2->next_record()) {
	 $my_sem[$db2->f("Seminar_id")]["postings"]=$db2->f("count");
	 }
	 $db2->query ("SELECT a.Seminar_id,count(*) as count FROM px_topics a LEFT JOIN loginfilenow_".$user->id." b USING (Seminar_id) WHERE a.Seminar_id IN ".$my_semids." AND chdate > b.loginfilenow AND user_id !='$user->id' GROUP BY a.Seminar_id");
	 while($db2->next_record()) {
	 $my_sem[$db2->f("Seminar_id")]["neuepostings"]=$db2->f("count");
	 }

//dokumente
	 $db2->query ("SELECT seminar_id , count(*) as count FROM dokumente WHERE seminar_id IN ".$my_semids." GROUP BY seminar_id");
	 while($db2->next_record()) {
	 $my_sem[$db2->f("seminar_id")]["dokumente"]=$db2->f("count");
	 }
	 $db2->query ("SELECT a.seminar_id , count(*) as count  FROM dokumente a LEFT JOIN loginfilenow_".$user->id." b USING (seminar_id) WHERE a.seminar_id IN ".$my_semids." AND chdate > b.loginfilenow AND user_id !='$user->id' GROUP BY a.seminar_id");
	 while($db2->next_record()) {
	 $my_sem[$db2->f("seminar_id")]["neuedokumente"]=$db2->f("count");
	 }

//News
	 $db2->query ("SELECT range_id,count(*) as count  FROM news_range  LEFT JOIN news USING(news_id) WHERE range_id IN ".$my_semids." GROUP BY range_id");
	 while($db2->next_record()) {
	 $my_sem[$db2->f("range_id")]["news"]=$db2->f("count");
	 }
	 $db2->query ("SELECT range_id,count(*) as count  FROM news_range LEFT JOIN news  USING(news_id)  LEFT JOIN loginfilenow_".$user->id." b ON (b.Seminar_id=range_id) WHERE range_id IN ".$my_semids." AND date > b.loginfilenow AND user_id !='$user->id' GROUP BY range_id");
	 while($db2->next_record()) {
	 $my_sem[$db2->f("range_id")]["neuenews"]=$db2->f("count");
	 }
// Literatur?
	 $db2->query ("SELECT range_id,chdate,user_id FROM literatur WHERE range_id IN ".$my_semids);
	while($db2->next_record()) {
	  if ($db2->f("chdate")>$loginfilenow[$db2->f("range_id")] AND $db2->f("user_id")!=$user->id){
	    $my_sem[$db2->f("range_id")]["neueliteratur"]=TRUE;
	    $my_sem[$db2->f("range_id")]["literatur"]=TRUE;
	    }
	 else $my_sem[$db2->f("range_id")]["literatur"]=TRUE;
	 }
	 $db2->query ("SELECT range_id,count(*) as count FROM termine WHERE range_id IN ".$my_semids." GROUP BY range_id");
	 while($db2->next_record()) {
	 $my_sem[$db2->f("range_id")]["termine"]=$db2->f("count");
	 }
	 $db2->query ("SELECT range_id,count(*) as count  FROM termine LEFT JOIN loginfilenow_".$user->id." b ON (b.Seminar_id=range_id) WHERE range_id IN ".$my_semids." AND chdate > b.loginfilenow AND autor_id !='$user->id' GROUP BY range_id");
	  while($db2->next_record()) {
	 $my_sem[$db2->f("range_id")]["neuetermine"]=$db2->f("count");
	 }

	 return;
}


function print_seminar_content($semid,$my_sem_values) {
  // Postings
  IF ($my_sem_values["neuepostings"])  ECHO "<a href=\"seminar_main.php?auswahl=$semid&redirect_to=forum.php&view=neue\">&nbsp; <img src='pictures/icon-posting2.gif' border=0 alt='".$my_sem_values["postings"]." Postings, ".$my_sem_values["neuepostings"]." Neue' title='".$my_sem_values["postings"]." Postings, ".$my_sem_values["neuepostings"]." Neue'></a>";
  ELSEIF ($my_sem_values["postings"]) ECHO "<a href=\"seminar_main.php?auswahl=$semid&redirect_to=forum.php\">&nbsp; <img src='pictures/icon-posting.gif' border=0 alt='".$my_sem_values["postings"]." Postings' title='".$my_sem_values["postings"]." Postings'></a>";
  ELSE ECHO "&nbsp; <img src='pictures/icon-leer.gif' border=0>";
  //Dokumente
  IF ($my_sem_values["neuedokumente"]) ECHO "&nbsp; <a href=\"seminar_main.php?auswahl=$semid&redirect_to=folder.php&cmd=all\"><img src='pictures/icon-disc2.gif' border=0 alt='".$my_sem_values["dokumente"]." Dokumente, ".$my_sem_values["neuedokumente"]." neue' title='".$my_sem_values["dokumente"]." Dokumente, ".$my_sem_values["neuedokumente"]." neue'></a>";
  ELSEIF ($my_sem_values["dokumente"]) ECHO "&nbsp; <a href=\"seminar_main.php?auswahl=$semid&redirect_to=folder.php&cmd=tree\"><img src='pictures/icon-disc.gif' border=0 alt='".$my_sem_values["dokumente"]." Dokumente' title='".$my_sem_values["dokumente"]." Dokumente'></a>";
  ELSE ECHO "&nbsp; <img src='pictures/icon-leer.gif' border=0>";

  //News
  IF ($my_sem_values["neuenews"]) ECHO "&nbsp; <a href=\"seminar_main.php?auswahl=$semid\"><img src='pictures/icon-news2.gif' border=0 alt='".$my_sem_values["news"]." News, ".$my_sem_values["neuenews"]." neue' title='".$my_sem_values["news"]." News, ".$my_sem_values["neuenews"]." neue'></a>";
  ELSEIF ($my_sem_values["news"]) ECHO "&nbsp; <a href=\"seminar_main.php?auswahl=$semid\"><img src='pictures/icon-news.gif' border=0 alt='".$my_sem_values["news"]." News' title='".$my_sem_values["news"]." News'></a>";
  ELSE ECHO "&nbsp; <img src='pictures/icon-leer.gif' border=0>";

  //Literatur
IF ($my_sem_values["literatur"]) {
    ECHO "<a href=\"seminar_main.php?auswahl=$semid&redirect_to=literatur.php\">";
    if ($my_sem_values["neueliteratur"])
      ECHO "&nbsp; <img src=\"pictures/icon-lit2.gif\" border=0 alt='Zur Literatur und Linkliste (ge&auml;ndert)'></a>";
		else
		  ECHO "&nbsp; <img src=\"pictures/icon-lit.gif\" border=0 alt='Zur Literatur und Linkliste'></a>";
  }
  ELSE ECHO "&nbsp; <img src='pictures/icon-leer.gif' border=0>";

  // Termine
  IF ($my_sem_values["neuetermine"]) ECHO "&nbsp; <a href=\"seminar_main.php?auswahl=$semid&redirect_to=dates.php\"><img src='pictures/icon-uhr2.gif' border=0 alt='".$my_sem_values["termine"]." Termine, ".$my_sem_values["neuetermine"]." neue' title='".$my_sem_values["termine"]." Termine, ".$my_sem_values["neuetermine"]." neue'></a>";
  ELSEIF ($my_sem_values["termine"]) ECHO "&nbsp; <a href=\"seminar_main.php?auswahl=$semid&redirect_to=dates.php\"><img src='pictures/icon-uhr.gif' border=0 alt='".$my_sem_values["termine"]." Termine' title='".$my_sem_values["termine"]." Termine'></a>";
  ELSE ECHO "&nbsp; <img src='pictures/icon-leer.gif' border=0>";

  echo "&nbsp;&nbsp;";

} // Ende function print_seminar_content

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


<?

include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php");		 //hier werden die sessions initialisiert

// -- hier muessen Seiten-Initialisierungen passieren --
// -- wir sind jetzt definitiv in keinem Seminar, also... --

$SessSemName[0] = "";
$SessSemName[1] = "";
$links_admin_data =''; 	//Auch im Adminbereich gesetzte Veranstaltungen muessen geloescht werden.

include ("$ABSOLUTE_PATH_STUDIP/header.php");   			//hier wird der "Kopf" nachgeladen
require_once ("$ABSOLUTE_PATH_STUDIP/config.inc.php"); 		// Klarnamen fuer den Veranstaltungsstatus
require_once ("$ABSOLUTE_PATH_STUDIP/visual.inc.php"); 		// htmlReady fuer die Veranstaltungsnamen
require_once ("$ABSOLUTE_PATH_STUDIP/dates.inc.php"); 		// Semester-Namen fuer Admins
require_once ("$ABSOLUTE_PATH_STUDIP/admission.inc.php");	//Funktionen der Teilnehmerbegrenzung
$cssSw=new cssClassSwitcher;                                // Klasse f�r Zebra-Design
?>
<body>

<?
$db=new DB_Seminar;
//bei Bedarf aus seminar_user austragen
if ($cmd=="kill") {
	$db->query("DELETE FROM seminar_user WHERE user_id='$user->id' AND Seminar_id='$auswahl'");
	if ($db->affected_rows() == 0)  $meldung="error�Datenbankfehler!";
	else {
	  //Pruefen, ob es Nachruecker gibt
	  update_admission($auswahl);
	  
	  $db->query("SELECT Name FROM seminare WHERE Seminar_id = '$auswahl'");
	  $db->next_record();
	  $meldung="msg�Das Abonnement der Veranstaltung <b>".$db->f("Name")."</b> wurde aufgehoben. Sie sind nun nicht mehr als Teilnehmer dieser Veranstaltung im System registriert.";
	}
}

// Update der Gruppen

      if ($gruppesent=="1")
      {for ($gruppe; $key = key($gruppe); next($gruppe))
			$db->query ("UPDATE seminar_user SET gruppe = '$gruppe[$key]' WHERE Seminar_id = '$key' AND user_id = '$user->id'");
	}


//Anzeigemodul fuer eigene Seminare (nur wenn man angemeldet und nicht root oder admin ist!)
IF ($auth->is_authenticated() && $user->id != "nobody" && !$perm->have_perm("admin")){

     //Alle fuer das Losen anstehenden Veranstaltungen bearbeiten (wenn keine anstehen wird hier nahezu keine Performance verbraten!)
     check_admission();
     
     if (!isset($sortby)) $sortby="gruppe, Name";
     if ($sortby == "count")
     $sortby = "count DESC";
	$db->query ("SELECT seminare.Name, seminare.Seminar_id, seminar_user.status, seminar_user.gruppe, seminare.chdate FROM seminar_user LEFT JOIN seminare  USING (Seminar_id) WHERE seminar_user.user_id = '$user->id' GROUP BY Seminar_id ORDER BY $sortby");
	$num_my_sem=$db->num_rows();
     if (!$num_my_sem) $meldung="msg�Sie haben keine Veranstaltungen abonniert!�".$meldung;

     ?>
     <table width="100%" border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td class="topic" colspan="2"><img src="pictures/meinesem.gif" border="0" align="texttop">&nbsp;<b>Meine Veranstaltungen</></td>
	</tr>
	<tr>
		<td class="blank" width="100%" colspan="2">&nbsp;
			<?
			if ($meldung) parse_msg($meldung);
			?>
		</td>
	</tr>
     <?
     if ($num_my_sem){
     ?>
     <tr><td class="blank" colspan=2>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="blank">
	<tr valign="top" align="center">
		<th width="2%" colspan=2 nowrap align="center">&nbsp;<a href="gruppe.php"><img src="pictures/gruppe.gif" alt="Gruppe &auml;ndern" border="0"></a></th>
		<th width="64%" align="center"><a href="<? echo $PHP_SELF ?>?sortby=Name">Name</a></th>
		<th width="10%"><b>besucht</b></th>
		<th width="10%"><b>Inhalt</b></th>
		<th width="10%"><a href="<? echo $PHP_SELF ?>?sortby=status">Status</a></th>
		<th width="3%"><b>X</b></th>
	</tr>
	<?
	ob_end_flush(); //Buffer leeren, damit der Header zu sehen ist
	ob_start();
     while ($db->next_record())
		{
	  $my_sem[$db->f("Seminar_id")]=array(name=>$db->f("Name"),status=>$db->f("status"),gruppe=>$db->f("gruppe"),chdate=>$db->f("chdate"));
	  $value_list.="('".$db->f("Seminar_id")."',0".$loginfilenow[$db->f("Seminar_id")]."),";
     }
     $value_list=substr($value_list,0,-1);
     $db->query("CREATE  TEMPORARY TABLE IF NOT EXISTS loginfilenow_".$user->id." ( Seminar_id varchar(32) NOT NULL PRIMARY KEY, loginfilenow int(11) NOT NULL DEFAULT 0, INDEX(loginfilenow) ) TYPE=HEAP");
     $ins_query="REPLACE INTO loginfilenow_".$user->id." (Seminar_id,loginfilenow) VALUES ".$value_list;
     $db->query($ins_query);
     get_my_sem_values($my_sem);
     $db->query("DROP TABLE loginfilenow_".$user->id);

 $c=1;
  foreach ($my_sem as $semid=>$values){
	  if ($c % 2)
			$class="steel1";
		else
			$class="steelgraulight";
		$c++;

		$lastVisit = $loginfilenow[$semid];

		ECHO "<tr><td class=gruppe";
		ECHO $values["gruppe"];
		ECHO "><a href='gruppe.php'><img src='pictures/blank.gif' alt='Gruppe &auml;ndern' border=0 width=7 height=12></a></td>";
		ECHO "<td class=\"$class\">&nbsp; </td>";
		ECHO "<td class=\"$class\" ><a href=\"seminar_main.php?auswahl=$semid\">";
		if ($lastVisit <= $values["chdate"])
			print ("<font color=\"red\">");
		ECHO htmlReady($values["name"]);
		if ($lastVisit <= $values["chdate"])
			print ("</font>");
		print ("</a></td>");

		IF ($loginfilenow[$semid]==0)
			{
			echo "<td class=$class  align=\"center\">nicht besucht</td>";
			}
		ELSE
			 {
			 echo "<td class=\"$class\" align=\"center\">", date("d.m.Y", $loginfilenow[$semid]),"</td>";
			}

// Inhalt
		echo "<td class=\"$class\" align=\"left\" nowrap>";
		print_seminar_content($semid, $values);
		echo "</td>";

		echo "<td class=\"$class\"  align=\"center\" nowrap>". $values["status"]."&nbsp;</td>";
		if (($values["status"]=="dozent") || ($values["status"]=="tutor")) echo "<td class=\"$class\"  align=center>&nbsp;</td>";
			else printf("<td class=\"$class\"  align=center align=center><a href=\"$PHP_SELF?auswahl=%s&cmd=kill\"><img src=\"pictures/trash.gif\" alt=\"aus der Veranstaltung abmelden\" border=\"0\"></a></td>", $semid);
		 echo "</tr>\n";
		}
	echo "</table></td></tr>";

     }

?>
	<tr>
	<td class="blank" colspan=2>&nbsp;</td>
	</tr>
<?
// Anzeige der Wartelisten

      $db->query("SELECT admission_seminar_user.*, seminare.Name, seminare.admission_endtime FROM admission_seminar_user LEFT JOIN seminare USING(seminar_id) WHERE user_id = '$user->id'");
      IF ($db->num_rows()) {
      	?>
       	<tr>
	<td class="blank" colspan=2><b>Anmelde- und Wartelisteneintr&auml;ge:</b><br />&nbsp; </td>
	</tr>
      	<?
        ECHO "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\" class=\"blank\">";
        ECHO "<tr>";
            ECHO "<th width=\"2%\" nowrap colspan=2>&nbsp";
		    ECHO "<th width=\"64%\"><b>Name</b>";
		    ECHO "<th width=\"10%\"><b>Losdatum</b>";
   		    ECHO "<th width=\"10%\"><b>Position</b>";
   		    ECHO "<th width=\"10%\"><b>Art</b>";
       	    ECHO "<th width=\"3%\">X</tr>";
      }
      WHILE ($db->next_record()) {
            $cssSw->switchClass();
     		printf ("<tr><td bgcolor=\"%s\"><img src='pictures/blank.gif' alt='Position oder Wahrscheinlichkeit' border=0 width=7 height=12>&nbsp;</td>","#880000");
     		printf ("<td class=\"%s\">&nbsp;</td>",$cssSw->getClass());
     		printf ("<td class=\"%s\">",$cssSw->getClass());
		print "<a href=details.php?sem_id=".$db->f("seminar_id").">".$db->f("Name")."</a></td>";
		print  "<td width=\"10%%\" align=center class=".$cssSw->getClass().">".date("d.m.Y", $db->f("admission_endtime"))."</td>";
		printf ("<td align=\"center\" class=\"%s\">%s</td>",$cssSw->getClass(),"100%");
		IF ($db->f("status") == "claiming") 
			$art = "Anmeldeliste";
		ELSE $art = "Warteliste";
		printf ("<td align=\"center\" class=\"%s\">%s</td>",$cssSw->getClass(),$art);
            printf("<td width=\"3%%\" class=\"%s\" align=\"center\"><a href=\"$PHP_SELF?auswahl=%s&cmd=kill_admission\"><img src=\"pictures/trash.gif\" alt=\"aus der Veranstaltung abmelden\" border=\"0\"></a></td></tr>", $cssSw->getClass(),$db->f("seminar_id"));
      }
      
?>

	</table><table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="blank"><tr>
	<td class="blank" align = left width="90%"><blockquote>
<?
      $db->query("SELECT count(*) as count  FROM seminare");
	$db->next_record();
	echo "Um weitere Veranstaltungen in Ihre pers&ouml;nliche Auswahl aufzunehmen, nutzen Sie bitte die\n";
	echo "<a href=\"sem_portal.php?view=Alle\">Veranstaltungssuche</a><br>\n";
	echo "<br><font size=-1>Es sind noch ".($db->f("count")-$num_my_sem)." weitere Veranstaltungen vorhanden</font><br>\n";
	if ($perm->have_perm("dozent")) {
	    echo "<br>Um eine neue Veranstaltung anzulegen, benutzen Sie bitte den<br>\n";
	    echo "<a href=\"admin_seminare_assi.php?new_session=TRUE\">Veranstaltungs-Assistenten</a><br>\n";
	}
	echo"</blockquote></td>";
?>
	<td class="blank" align = right><img src="pictures/board1.jpg" width="266" height="173" border="0"></td>
	</tr>

<?

}


ELSEIF ($auth->auth["perm"]=="admin"){

       if (!isset($sortby)) $sortby="Institut, start_time, Name";
       if ($sortby == "teilnehmer")
       $sortby = "teilnehmer DESC";
       $db->query("SELECT Institute.Name AS Institut, seminare.*, COUNT(seminar_user.user_id) AS teilnehmer FROM user_inst LEFT JOIN Institute USING (Institut_id) LEFT JOIN seminare USING(Institut_id) LEFT OUTER JOIN seminar_user USING(Seminar_id) WHERE user_inst.inst_perms='admin' AND user_inst.user_id='$user->id' AND seminare.Institut_id is not NULL GROUP BY seminare.Seminar_id ORDER BY $sortby");
       $num_my_sem=$db->num_rows();
       if (!$num_my_sem) $meldung="msg�Sie haben keine Veranstaltungen!�".$meldung;
	 ?>
	<table width="100%" border=0 cellpadding=0 cellspacing=0>
	<tr>
		<td class="topic" colspan=2><img src="pictures/meinesem.gif" border="0" align="texttop">&nbsp;<b>Veranstaltungen an meinen Einrichtungen</></td>
	</tr>
	<tr>
		<td class="blank" width="100%" colspan=2>&nbsp;
			<?
			if ($meldung) parse_msg($meldung);
			?>
		</td>
	</tr>
     <?
     if ($num_my_sem) {
     ?>
	<tr><td class="blank" colspan=2>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class=blank>
	<tr valign"top" align="center">
		<th width="50%" colspan=2><a href="<? echo $PHP_SELF ?>?sortby=Name">Name</a></th>
		<th width="10%"><a href="<? echo $PHP_SELF ?>?sortby=status">Status</a></th>
		<th width="15%"><b>Dozent</b></th>
		<th width="10%"><b>Inhalt</b></th>
		<th width="10%"><a href="<? echo $PHP_SELF ?>?sortby=teilnehmer">Teilnehmer</a></th>
		<th width="5%"><b>&nbsp; </b></th>
	</tr>
	<?
  $db2=new DB_Seminar;

	while ($db->next_record()){
		$my_sem[$db->f("Seminar_id")]=array(institut=>$db->f("Institut"),teilnehmer=>$db->f("teilnehmer"),name=>$db->f("Name"),status=>$db->f("status"),chdate=>$db->f("chdate"),start_time=>$db->f("start_time"));
		$value_list.="('".$db->f("Seminar_id")."',0".$loginfilenow[$db->f("Seminar_id")]."),";
	}
	$value_list=substr($value_list,0,-1);
     $db->query("CREATE TEMPORARY TABLE IF NOT EXISTS  loginfilenow_".$user->id." ( Seminar_id varchar(32) NOT NULL PRIMARY KEY, loginfilenow int(11) NOT NULL DEFAULT 0 ) TYPE=HEAP");
     $ins_query="REPLACE INTO loginfilenow_".$user->id." (Seminar_id,loginfilenow) VALUES ".$value_list;
	$db->query($ins_query);
	get_my_sem_values(&$my_sem);
	$db->query("DROP TABLE loginfilenow_".$user->id);
     $c=1;
	foreach ($my_sem as $semid=>$values){
	  if ($c % 2)
			$class="steel1";
		else
			$class="steelgraulight";
		$c++;

		$lastVisit = $loginfilenow[$semid];

		echo "<td class=\"$class\">&nbsp;&nbsp;</td>";

		ECHO "<td class=\"$class\"><a href=\"seminar_main.php?auswahl=$semid\">";
		if ($lastVisit <= $values["chdate"])
			print ("<font color=\"red\">");
		ECHO htmlReady($values["name"]);
		echo " (" . get_sem_name($values["start_time"]) .")";
		if ($lastVisit <= $values["chdate"])
			print ("</font>");
		print ("</a></td>");

		ECHO "<td class=\"$class\" align=\"center\">&nbsp;" . $SEM_TYPE[$values["status"]]["name"] . "&nbsp;</td>";
// Dozenten
		$db2->query ("SELECT Vorname, Nachname, username FROM  seminar_user LEFT JOIN auth_user_md5  USING (user_id) WHERE Seminar_id='$semid' AND status='dozent'");
		$temp = "";
		while ($db2->next_record()) {
		    $temp .= "<a href=\"about.php?username=" . $db2->f("username") . "\">" . $db2->f("Nachname") . "</a>, ";
		}
		$temp = substr($temp, 0, -2);
		print ("<td class=\"$class\" align=\"center\">&nbsp;$temp</td>");

// Inhalt
		echo "<td class=\"$class\" align=\"left\" nowrap>";
		print_seminar_content($semid, $values);
		echo "</td>";

		echo "<td class=\"$class\" align=\"center\" nowrap>". $values["teilnehmer"]."&nbsp;</td>";
		printf("<td class=\"$class\" align=center align=center><a href=\"seminar_main.php?auswahl=$semid&redirect_to=adminarea_start.php&new_sem=TRUE\"><img src=\"pictures/admin.gif\" alt=\"Veranstaltungsdaten bearbeiten\" border=\"0\"></a></td>", $semid);
		 echo "</tr>\n";
		}
	echo "</table></td></tr>";

     }

?>
	<tr>
	<td class="blank" colspan=2>&nbsp;</td>
	</tr>

	<tr>
	<td class="blank" align = left width="90%"><blockquote>
<?

	    echo "<br>Um eine neue Veranstaltung anzulegen, benutzen Sie bitte den<br>\n";
	    echo "<a href=\"admin_seminare_assi.php?new_session=TRUE\">Veranstaltungs-Assistenten</a><br>\n";
	echo"</blockquote></td>";
?>
	<td class="blank" align = right><img src="pictures/board1.jpg" width="266" height="173" border="0"></td>
	</tr>
<?
}

ELSEIF ($perm->have_perm("root")){


//Anzeigemodul fuer alle Seminare f�r root
	?>
	<table width="100%" border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td class="topic" colspan=2><img src="pictures/meinesem.gif" border="0" align="texttop"><b>&Uuml;bersicht &uuml;ber Veranstaltungen</></td>
		</tr>
		<tr>
			<td class="blank" align = left colspan=2><br /><blockquote>
				Um eine Veranstaltung zu bearbeiten, w&auml;hlen Sie sie &uuml;ber die Suchfunktion aus.
			</blockquote>
			</td>
		</tr>
		<tr>
			<td class="blank" colspan=2>&nbsp;
			</td>
		</tr>
		<tr>
			<td class="blank" colspan=2>
	<?
		$root_mode=TRUE;
		$target_url="seminar_main.php";	//teilt der nachfolgenden Include mit, wo sie die Leute hinschicken soll
		$target_id="auswahl"; 			//teilt der nachfolgenden Include mit, wie die id, die uebergeben wird, bezeichnet werden soll

		include "sem_browse.inc.php"; 		//der zentrale Seminarbrowser wird hier eingef&uuml;gt.

	?>
			</td>
		</tr>
		<tr>
			<td class="blank" colspan=2>
				<blockquote>Um eine neue Veranstaltung anzulegen, benutzen Sie bitte den&nbsp; <a href="admin_seminare_assi.php?new_session=TRUE">Veranstaltungs-Assistenten</a><br>
				</blockquote>
			</td>
		</tr>
	</table>
<?
}
?>
</table>
</body>
</html>
<?
  // Save data back to database.
ob_end_flush(); //Outputbuffering beenden
page_close();
  ?>
<!-- $Id$ -->