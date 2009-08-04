<?php

if ($membership_requested) {
	$participate = _("Mitgliedschaft bereits beantragt!");
} else {
	$participate_link = '<a href="'. UrlHelper::getLink('sem_verify.php?id='. $studygroup->getId()) .'">%s</a>';
	$participate = sprintf( $participate_link, $studygroup->admission_prelim ? _("Mitgliedschaft beantragen") : _("Arbeitsgruppe beitreten"));
}

$all_mods = array_diff_assoc($studygroup->getMembers('dozent') + $studygroup->getMembers('tutor'), array(array(md5('studygroupt_dozent') => true)));
$mods = array();
foreach($all_mods as $mod) {
	$mods[] = '<a href="'.URLHelper::getLink("about.php?username=".$mod['username']).'">'.htmlready($mod['fullname']).'</a>';
}

/* * * * * * * * * * * * *
 * * * I N F O B O X * * *
 * * * * * * * * * * * * */
$infobox['picture'] = 'groups.jpg';
$infobox['content'] = array(
	array(
		'kategorie' => _("Information"), 
		'eintrag'   => array(
			array(
				'text' => _("Hier sehen Sie weitere Informationen zur Arbeitsgruppe. Au�erdem k�nnen sie ihr beitreten/eine Mitgliedschaft beantragen."),
				'icon' => 'ausruf_small.gif'
			)
		)
	),
	array(
		'kategorie' => _("Aktionen"),
		'eintrag'   => array(
			array(
				'text' => $participate, 
				'icon' => 'link_intern.gif'
			),
		)
	)
);

$search = array(
	'text' => '<a href="'. UrlHelper::getLink($send_from_search_page) . '">'. _("zur�ck zur Suche") .'</a>',
	'icon' => 'link_intern.gif'
);

if ($send_from_search_page) {
	$infobox['content'][1]['eintrag'][] = $search;
}

/* * * * * * * * * * * *
 * * * O U T P U T * * * 
 * * * * * * * * * * * */
?>
<h1><?= $studygroup->getName() ?></h1>
<b><?= _("Moderiert von:") ?></b> <?= implode(',', $mods) ?><br>
<br>
<b><?= _("Beschreibung:") ?></b><br>
<?= FixLinks(htmlReady($studygroup->description)) ?>
