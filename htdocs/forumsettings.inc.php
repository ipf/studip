<?
/*
folder.php - Anzeige und Verwaltung des Ordnersystems
Copyright (C) 2002 Ralf Stockmann <rstockm@gwdg.de>

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

//Standard herstellen

$cssSw=new cssClassSwitcher;	

if ($forumsend=="bla"){
	$forum["neuauf"]=$neuauf;
}

?>
<table width="100%" border=1 cellpadding=0 cellspacing=0 align="center" border=0>
<tr>
<td class="topic" colspan=2><img src="pictures/einst.gif" border="0" align="texttop"><b>&nbsp;<?print _("Einstellungen des Forums anpassen");?></b></td>
</tr>
<tr>
<td class="blank" colspan=2>&nbsp;
</td>
</tr>
<tr>
<td class="blank" width="100%" align="center">
<blockquote><br><font size="-1"><b><?print _("Auf dieser Seite k&ouml;nnen Sie die Bedienung des Stud.IP-Forensystems an Ihre Bed&uuml;rfnisse anpassen.");?>
</blockquote><p>
<table width="99%" border=0 cellpadding=2 cellspacing=0 align="center"  border=0>


	
</table><br/>

<table><tr><td class=steel1 width="100%" valign=top>
<?
echo "<form action=\"$PHP_SELF?view=$view\" method=\"POST\">";
?>
<table width="100%" cellpadding=8 cellspacing=0 border=0>
	<tr>
		<th width="50%" align=center>Option&nbsp; </th>
		<th align=center> &nbsp;Auswahl</th>
	</tr>
	<tr  <? $cssSw->switchClass() ?>>
		<td  align="right" class="blank" style="border-bottom:1px dotted black;">
			<font size="-1">
			<?print _("Neue Beitr�ge immer aufgeklappt");?></font>
		</td>
		<td class="steelgraulight">
			<input type="CHECKBOX" name="neuauf" value="1"<?IF($forum["neuauf"]==1) echo " checked";?>>
		</td>
	</tr>

	<tr  <? $cssSw->switchClass() ?>>
		<td  align="right" class="blank" style="border-bottom:1px dotted black;">
			<font size="-1">
			<?print _("Alle Beitr�ge im Flatview immer aufgeklappt");?></font>
		</td>
		<td >
			<input type="CHECKBOX" name="flatallopen" value=TRUE<?if($forum["flatallopen"]==TRUE) echo " checked";?>>
	</td>
	</tr>
	<tr  <? $cssSw->switchClass() ?>>
		<td  align="right" class="blank" style="border-bottom:1px dotted black;">
			<font size="-1">
			<?print _("Bewertungsbereich bei ge�ffneten Postings immer anzeigen");?></font>
		</td>
		<td class="steelgraulight">
			<input type="CHECKBOX" name="rateallopen" value=TRUE<?if($forum["rateallopen"]==TRUE) echo " checked";?>>
	</td>
	</tr>	
	<tr  <? $cssSw->switchClass() ?>>
		<td  align="right" class="blank" style="border-bottom:1px dotted black;">
			<font size="-1">
			<?print _("Bilder im Bewertungsbereich anzeigen");?></font>
		</td>
		<td class="steelgraulight">
			<input type="CHECKBOX" name="showimages" value=TRUE<?if($forum["showimages"]==TRUE) echo " checked";?>>
	</td>
	</tr>	
	<tr>
		<td align=right class=blank style="border-bottom:1px dotted black;">
			<font size=-1><?echo _("Anzahl der Postings pro Seite im Flatview");?></font>
		</td>
		<td align=left >
			<font size=-1>
			&nbsp;<select name="postingsperside">
			<?
			for ($i=5;$i<55;$i+=5) {
				echo "<option value=\"$i\"";
				if ($i == $forum["postingsperside"]) echo " selected";
				echo ">$i";
			}
			?>
			</select>
		</td>
	</tr>	
	<tr>
		<td align=right class=blank style="border-bottom:1px dotted black;">
			<font size=-1>Sortierung der Themenanzeige</font>&nbsp;&nbsp;
		</td>
		<td align=left class=steelgraulight>
			<font size=-1>
			<input type=radio value="asc" name=sortthemes <?if ($forum["sortthemes"]=="asc") echo "checked";?>> Alter des Ordners - neue unten<br>
			<input type=radio value="desc" name=sortthemes <?if ($forum["sortthemes"]=="desc") echo "checked";?>> Alter des Ordners - neue oben<br>
			<input type=radio value="last" name=sortthemes <?if ($forum["sortthemes"]=="last") echo "checked";?>> Alter des neuesten Beitrags - neue oben<br>
		</td>
	</tr>
	<tr>
		<td align=right class=blank style="border-bottom:1px dotted black;">
			<font size=-1><?echo _("ForumAutoShrink(TM) Engine aktivieren");?></font>
		</td>
		<td align=left >
			<font size=-1>
			&nbsp;<select name="shrink">
			<?
			for ($i=1;$i<20;$i+=1) {
				echo "<option value=\"$i\"";
				if ($i == $forum["postingsperside"]) echo " selected";
				echo ">$i Wochen";
			}
			?>
			</select>
		</td>
	</tr>


	<input type="HIDDEN" name="forumsend" value="bla">
	<tr  <? $cssSw->switchClass() ?>>
	<td  class="steel1" colspan=2 align="middle"><br />
		<font size=-1><input type="IMAGE" <?=makeButton("uebernehmen", "src") ?> border=0 value="<?_("�nderungen �bernehmen")?>"></font>&nbsp;
	</td>
</tr>		
</form>	
</table>
</td>
</tr>
</table>
</form>  
<br><br>
</td>
 </tr>
</table>
<br>


<? IF ($forumsend=="anpassen") {
	echo " </td></tr></table>";
	die;
	}
