<?php
/*
bb.php - Big Brother Bilder Browser
Copyright (C) 2001 Cornelis Kater <ckater@gwdg.de>

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
$perm->check("root");

include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session

// -- here you have to put initialisations for the current page
// chdir ('/usr/local/httpd/htdocs/studip/');

// Start of Output
include ("$ABSOLUTE_PATH_STUDIP/html_head.inc.php"); // Output of html head
include ("$ABSOLUTE_PATH_STUDIP/header.php");   // Output of Stud.IP head
include ("$ABSOLUTE_PATH_STUDIP/links_admin.inc.php");  //Linkleiste fuer admins

	$db=new DB_Seminar;
	$db2=new DB_Seminar;
	 ?>
  
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr><td class="topic" colspan=2><img src="pictures/blank.gif" width="5" height="5" border="0"><b>Der geheime Bilderbrowser</b></td></tr>
	<tr><td class="blank" colspan=2><br><br>Unsch&ouml;n dass wir uns hier sehen... diese Seite ist das geheime Easteregg von Stud.IP. Wenn es jemand hierher geschafft hat, der nicht zum Team geh&ouml;rt, dann k&uuml;ndige ich.<br><br>
	<i>Cornelis</i><br><br>
	<?
	$folder=opendir($ABSOLUTE_PATH_STUDIP."/user/");
	$i=0;
	while ($entry=readdir($folder))
		{
		$i++;
		if (($entry!="..") && ($entry!=".") && ($entry!="")) $file[$i]=array("time"=>filemtime($ABSOLUTE_PATH_STUDIP."/user/".$entry), "file"=>$entry);
		}
	rsort ($file);
	$i=5;
	?><table border="0" cellpadding="0" cellspacing="2" width="100%" align="center"><?
	echo "<tr>";
	for ($i; $i-5 <sizeof($file); $i++)
		{
		$usid=substr($file[$i-5]["file"], 0, strrpos($file[$i-5]["file"], "."));
		$db->query("SELECT username FROM auth_user_md5 WHERE user_id='$usid'");
		$db->next_record();
		$usame=$db->f("username");
		echo "<td class=\"angemeldet\" width=\"25%\" align=\"center\" valign=\"center\"><a href=\"about.php?username=", $usame, "\"><img border=0 src=\"user/", $file[$i-5]["file"],"\"></a><br />";
		echo "<font size=-1>".get_fullname($usid)."<br/>".date("d.m.Y", $file [$i-5]["time"])."</font></td>";
		if ((($i % 4) ==0)  && (!$i==0))  echo"</tr><tr>";
		}
	echo "</tr></table>";
  
?>	
		</td></tr></table>
 
<?
	 page_close();
 ?>
</body>
</html>
