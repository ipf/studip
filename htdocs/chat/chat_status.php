<?
/**
* Status Window for the Chat
* 
* This script prints a status bar for the chat
*
* @author		Andr� Noack <andre.noack@gmx.net>
* @version		$Id$
* @access		public
* @modulegroup		chat_modules
* @module		chat_status
* @package		Chat
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// chat_status.php
// 
// Copyright (c) 2003 Andr� Noack <noack@data-quest.de>
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

/**
* Close the actual window if PHPLib shows login screen
* @const CLOSE_ON_LOGIN_SCREEN
*/
define("CLOSE_ON_LOGIN_SCREEN",true);
page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$perm->check("user");

//chat eingeschaltet?
if (!$CHAT_ENABLE) {
	page_close();
	die;
}
include ("$ABSOLUTE_PATH_STUDIP/seminar_open.php"); // initialise Stud.IP-Session
require_once $ABSOLUTE_PATH_STUDIP.$RELATIVE_PATH_CHAT."/ChatServer.class.php";
//Studip includes

$chatServer =& ChatServer::GetInstance($CHAT_SERVER_NAME);
$chatServer->caching = true;

?>
<html>
<head>
	<title>ChatStatus</title>
	<?php include $ABSOLUTE_PATH_STUDIP.$RELATIVE_PATH_CHAT."/chat_style.inc.php";?>
<script type="text/javascript">
/**
* JavaScript 
*/
	function printhelp(){
		parent.frames['frm_input'].document.inputform.chatInput.value="/help";
		parent.frames['frm_input'].document.inputform.submit();
	}
	
	function doLock(){
		parent.frames['frm_input'].document.inputform.chatInput.value="/lock";
		parent.frames['frm_input'].document.inputform.submit();
	}
	
	function doUnlock(){
		parent.frames['frm_input'].document.inputform.chatInput.value="/unlock";
		parent.frames['frm_input'].document.inputform.submit();
	}


</script>

</head>
<body>
<?
//darf ich �berhaupt hier sein ?
if (!$chatServer->isActiveUser($user->id,$chatid)) {
	?><table width="100%"><tr><?
	my_error(_("Sie sind nicht in diesem Chat angemeldet!"),"chat",1,false);
	?></tr></table></body></html><?
	page_close();
	die;
}

?>
<div align="center">
	<table width="98%" border="0" bgcolor="white" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="80%" align="left" class="topic" ><b>&nbsp;Chat -
			<?=htmlReady($chatServer->chatDetail[$chatid]["name"])?></b></td>
			<td width="20%" align="right" class="topic" >
			<a href="javascript:printhelp();">
			<img src="<?=$CANONICAL_RELATIVE_PATH_STUDIP?>pictures/hilfe.gif" border=0 align="texttop" <?=tooltip(_("Chat Kommandos einblenden"))?>>
			</a>&nbsp; 
			<a href="<?=$CANONICAL_RELATIVE_PATH_STUDIP?>show_smiley.php" target=new>
			<img src="<?=$CANONICAL_RELATIVE_PATH_STUDIP?>pictures/smile/smile.gif" border=0 align="absmiddle" <?=tooltip(_("Alle verf�gbaren Smileys anzeigen"))?>>
			</a>&nbsp; </td>
		</tr>
	</table>
</div>
</body>
</html>
<?
page_close();
?>

