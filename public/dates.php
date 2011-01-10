<?php
# Lifter001: TEST
# Lifter002: TODO
# Lifter007: TODO
# Lifter003: TODO
/**
 * dates.php - schedule for students
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Gl�ggler <tgloeggl@uni-osnabrueck.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 */

page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
$perm->check("autor");

include ("lib/seminar_open.php"); // initialise Stud.IP-Session

$id = $SessSemName[1];
$issue_open = array();

URLHelper::bindLinkParam('dates_data',$showDatesFilter);
URLHelper::bindLinkParam('raumzeit_data',$raumzeitFilter);
URLHelper::bindLinkParam('rzsem_data',$rzSeminar);

require_once ('lib/classes/Seminar.class.php');
require_once ('lib/datei.inc.php');
require_once ('lib/forum.inc.php');
require_once ('lib/raumzeit/raumzeit_functions.inc.php');

if ($RESOURCES_ENABLE) {
	include_once ($RELATIVE_PATH_RESOURCES."/lib/ResourceObject.class.php");
	include_once ($RELATIVE_PATH_RESOURCES."/lib/ResourcesUserRoomsList.class.php");
	include_once ($RELATIVE_PATH_RESOURCES."/lib/VeranstaltungResourcesAssign.class.php");
	include_once ($RELATIVE_PATH_RESOURCES."/lib/ResourceObjectPerms.class.php");
}

$sem = new Seminar($id);

checkObject();
checkObjectModule("schedule");
object_set_visit_module("schedule");

mark_public_course($sem);

$CURRENT_PAGE = $SessSemName["header_line"].' - '._("Ablaufplan");

if ($type == '1') {
	Navigation::activateItem('/course/schedule/type1');
    UrlHelper::bindLinkParam('type', $type);
} else if ($type == 'other') {
	Navigation::activateItem('/course/schedule/other');
    UrlHelper::bindLinkParam('type', $type);
} else {
	Navigation::activateItem('/course/schedule/all');
}

ob_start();
// Start of Output
include ('lib/include/html_head.inc.php'); // Output of html head
include ('lib/include/header.php');   // Output of Stud.IP head

$semester = new SemesterData();
$data = $semester->getCurrentSemesterData();
if (!$raumzeitFilter || ($rzSeminar != $SessSemName[1])) {
	$raumzeitFilter = $data['beginn'];
	$rzSeminar = $SessSemName[1];
}
$sem->checkFilter();
$themen =& $sem->getIssues();

function dates_open() {
	global $issue_open;

	$issue_open[$_REQUEST['open_close_id']] = true;
}

function dates_close() {
	global $issue_open;

	$issue_open[$_REQUEST['open_close_id']] = false;
	unset ($issue_open[$_REQUEST['open_close_id']]);
}

function dates_settype() {
	global $showDatesFilter, $type;

	$showDatesFilter = $type;
}

$sem->registerCommand('open', 'dates_open');
$sem->registerCommand('close', 'dates_close');
$sem->registerCommand('setType', 'dates_settype');
$sem->processCommands();

$termine = getAllSortedSingleDates($sem);

// Export the dates
if (Request::get('export') && $rechte) {
	ob_end_clean();

	header("Content-type: application/vnd.ms-word");
	header("Content-Disposition: attachment; filename=ablaufplan.doc");
	header("Expires: 0");
	header("Cache-Control: private");
	header("Pragma: cache");
?>
<html>
	<head>
		<title>Ablaufplan</title>
	</head>
	<body>

	<?
	$semester = new SemesterData();
	$all_semester = $semester->getAllSemesterData();

	if (is_array($termine) && sizeof($termine) > 0) {
		echo '<table cellspacing="0" cellpadding="0" border="0" width="100%">';

		foreach ($termine as $singledate_id => $singledate) {

			if ( ($grenze == 0) || ($grenze < $singledate->getStartTime()) ) {
				foreach ($all_semester as $zwsem) {
					if ( ($zwsem['beginn'] < $singledate->getStartTime()) && ($zwsem['ende'] > $singledate->getStartTime()) ) {
						$grenze = $zwsem['ende'];
						?>
						<tr>
							<td colspan="2">
								<h1><?= $zwsem['name'] ?></h1>
							</td>
						</tr>
						<?
					}
				}
			}

			$tmp_ids = $singledate->getIssueIDs();
			$title = '';
			if (is_array($tmp_ids)) {
				$title = $themen[array_pop($tmp_ids)]->getTitle();
			}
			?>
			<tr>
				<td width="50%"><?= htmlReady($singledate->toString()) ?></td>
				<td width="50%"><?= htmlReady($title); ?></td>
			</tr>
			<?
		}

		echo '</table>';
	}		
} else {

if ($cmd == 'openAll') $openAll = true;
?>

<table width="100%" border="0" cellpadding="2" cellspacing="0">
  <tr>
		<TD align="center" class="blank" width="80%" valign="top">
			<TABLE width="99%" cellspacing="0" cellpadding="0" border="0">
				<? if (is_array($termine) && sizeof($termine) > 0) : ?>
                <tr>
                    <td class="steelgraulight" colspan="10" height="24" align="center">
                        <a href="<?= URLHelper::getLink('?cmd='.(($openAll) ? 'close' : 'open') .'All') ?>">
                            <? if ($openAll) : ?>
                            <?= Assets::img('close_all.gif', array('title' => _("Alle Termine zuklappen"))) ?>
                            <? else : ?>
                            <?= Assets::img('open_all.gif', array('title' => _("Alle Termine aufklappen"))) ?>
                            <? endif ?>
                        </a>
                    </td>
                </tr>
				<? endif; ?>
				<TR>
					<TD colspan="10" height="3">
					</TD>
				</TR>
				<?

				$semester = new SemesterData();
				$all_semester = $semester->getAllSemesterData();

				if (is_array($termine) && sizeof($termine) > 0) {

    				foreach ($termine as $singledate_id => $singledate) {

    					if ( ($grenze == 0) || ($grenze < $singledate->getStartTime()) ) {
    						foreach ($all_semester as $zwsem) {
    							if ( ($zwsem['beginn'] < $singledate->getStartTime()) && ($zwsem['ende'] > $singledate->getStartTime()) ) {
    								$grenze = $zwsem['ende'];
    								?>
    								<TR>
    									<TD class="steelgraulight" align="center" colspan="9">
    										<FONT size="-1"><B><?=$zwsem['name']?></B></FONT>
    									</TD>
    								</TR>
    								<?
    							}
    						}
    					}

    					// Template fuer einzelnes Datum
    					$showSpecialDays = FALSE;
    					$tpl = getTemplateDataForSingleDate($singledate, $metadate_id);
    					// If "Sitzung" shall not be shown, uncomment this
    					/*if ($tpl['type'] == 1 || $tpl['type'] == 7) {
    						unset($tpl['art']);
    					}*/

    					//calendar jump
    					$tpl['calendar'] = "&nbsp;<a href=\"".URLHelper::getLink("calendar.php?cmd=showweek&atime=" . $singledate->getStartTime());
    					$tpl['calendar'] .= "\"><img style=\"vertical-align:bottom\" src=\"".$GLOBALS['ASSETS_URL']."images/popupkalender.gif\" ";
                        $tpl['calendar'] .= tooltip(sprintf(_("Zum %s in den pers�nlichen Terminkalender springen"), date("d.m", $singledate->getStartTime())));
    					$tpl['calendar'] .= ' border="0"></a>';

    					if ($showDatesFilter) {
    						switch ($showDatesFilter) {
    							case 'all':
    								break;

    							case 'other':
    								if ($tpl['type'] == 1) {
    									$tpl['deleted'] = true;
    								}
    								break;

    							default:
    								if ($tpl['type'] != $type) {
    									$tpl['deleted'] = true;
    								}
    								break;
    						}
    					}

							if ($openAll) $tpl['openall'] = true;

    					if (!$tpl['deleted'] || $tpl['comment'])  {
    						$tpl['class'] = 'printhead';
    						$tpl['cycle_id'] = $metadate_id;

    						$issue_id = '';
    						if (is_array($tmp_ids = $singledate->getIssueIDs())) {
    							foreach ($tmp_ids as $val) {
    								if (empty($issue_id)) {
    									if (is_object($themen[$val])) {
    										$issue_id = $val;
    									}
    								} else {
    									if (is_object($themen[$val])) {
    										$tpl['additional_themes'][] = array('title' => $themen[$val]->getTitle(), 'desc' => formatReady($themen[$val]->getDescription()));
    									}
    								}
    							}
    						}
    						if (is_object($themen[$issue_id])) {
    							$tpl['issue_id'] = $issue_id;
    							$thema =& $themen[$issue_id];
    							$tpl['theme_title'] = $thema->getTitle();
    							$tpl['theme_description'] = formatReady($thema->getDescription());
    							$tpl['folder_id'] = $thema->getFolderID();
    							$tpl['forumEntry'] = $thema->hasForum();
    							$tpl['fileEntry'] = $thema->hasFile();
    							if($tpl['forumEntry']) {
									$tpl['forumCount'] = forum_count($thema->getIssueId(), $id);
								} else {
									$tpl['forumCount'] = 0;
								}
    							if($tpl['fileEntry']){
									$tpl['fileCountAll'] = doc_count($thema->getFolderId());
								} else {
									$tpl['fileCountAll'] = 0;
								}
    						}

    						include('lib/raumzeit/templates/singledate_student.tpl');
    					}
           }
				} else {
                ?>
                    <TR>
                        <TD align="center">
                            <br>
                            <?= _("Im ausgew�hlten Zeitraum sind keine Termine vorhanden."); ?>
                        </TD>
                    </TR>
                <?
                }
				?>
			</TABLE>
		</TD>
		<td class="blank" align="right" valign="top" width="270">
		<?
			//Build an infobox
			$infobox_template =& $GLOBALS['template_factory']->open('infobox/infobox_dates');

			// get a list of semesters (as display options)
			$semester_selectionlist = raumzeit_get_semesters($sem, $semester, $raumzeitFilter);

			// fill attributes
			$infobox_template->set_attribute('picture', 'schedules.jpg');
			$infobox_template->set_attribute("selectionlist_title", "Semesterauswahl");
			$infobox_template->set_attribute('selectionlist', $semester_selectionlist);
			$infobox_template->set_attribute('rechte', $rechte);

			// render template
			echo $infobox_template->render();
		?>
		</td>
	</tr>
</table>
<?php
}
include ('lib/include/html_end.inc.php');
page_close();
