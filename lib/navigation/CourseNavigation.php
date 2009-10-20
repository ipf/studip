<?php
/*
 * CourseNavigation.php - navigation for course / institute area
 *
 * Copyright (c) 2009  Elmar Ludwig
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

require_once 'lib/functions.php';

if (get_config('ELEARNING_INTERFACE_ENABLE')) {
    require_once $GLOBALS['RELATIVE_PATH_ELEARNING_INTERFACE'].'/ObjectConnections.class.php';
}

if (get_config('RESOURCES_ENABLE')) {
    require_once $GLOBALS['RELATIVE_PATH_RESOURCES'].'/resourcesFunc.inc.php';
}

class CourseNavigation extends Navigation
{
    /**
     * Initialize a new Navigation instance.
     */
    public function __construct()
    {
        parent::__construct(_('Veranstaltung'));
    }

    /**
     * Initialize the subnavigation of this item. This method
     * is called once before the first item is added or removed.
     */
    public function initSubNavigation()
    {
        global $AUTO_INSERT_SEM, $SEM_CLASS, $SEM_TYPE;
        global $SessSemName, $forum, $perm, $user;

        parent::initSubNavigation();

        // list of used modules
        $Modules = new Modules;
        $modules = $Modules->getLocalModules($SessSemName[1]);
        $studygroup_mode = $SEM_CLASS[$SEM_TYPE[$SessSemName['art_num']]['class']]['studygroup_mode'];

        $db = DBManager::get();
        $result = $db->query("SELECT admission_binding FROM seminare WHERE seminar_id = '$SessSemName[1]'");
        $admission_binding = $result->fetchColumn();

        $rule = AuxLockRules::getLockRuleBySemId($SessSemName[1]);
        $sem_class = $SessSemName['class'];

        if ($modules['scm']) {
            $scms = array_values(StudipScmEntry::GetSCMEntriesForRange($SessSemName[1]));
        }

        // general information
        $navigation = new Navigation(_('�bersicht'));

        if ($sem_class == 'sem') {
            $navigation->addSubNavigation('info', new Navigation(_('Kurzinfo'), 'seminar_main.php'));

            if (!$studygroup_mode) {
                $navigation->addSubNavigation('details', new Navigation(_('Details'), 'details.php'));
                $navigation->addSubNavigation('print', new Navigation(_('Druckansicht'), 'print_seminar.php'));
            }

            if ($perm->have_studip_perm('tutor', $SessSemName[1]) && !$studygroup_mode) {
                $navigation->addSubNavigation('admin', new Navigation(_('Administration dieser Veranstaltung'), 'admin_seminare1.php?new_sem=TRUE'));
            }

            if (!$admission_binding && !$perm->have_studip_perm('tutor', $SessSemName[1]) && $user->id != 'nobody') {
                $navigation->addSubNavigation('leave', new Navigation(_('Austragen aus der Veranstaltung'), 'meine_seminare.php?auswahl='.$SessSemName[1].'&cmd=suppose_to_kill'));
            }
        } else {
            $navigation->addSubNavigation('info', new Navigation(_('Info'), 'institut_main.php'));
            $navigation->addSubNavigation('courses', new Navigation(_('Veranstaltungen'), 'show_bereich.php?level=s&id='.$SessSemName[1]));
            $navigation->addSubNavigation('schedule', new Navigation(_('Veranstaltungs-Timetable'), 'mein_stundenplan.php?inst_id='.$SessSemName[1]));

            if ($perm->have_studip_perm('tutor', $SessSemName[1])) {
                if ($perm->have_perm('admin')) {
                    $navigation->addSubNavigation('admin', new Navigation(_('Administration der Einrichtung'), 'admin_institut.php?new_inst=TRUE'));
                } else {
                    $navigation->addSubNavigation('admin', new Navigation(_('Administration der Einrichtung'), 'admin_lit_list.php?new_inst=TRUE&view=literatur_inst'));
                }
            }
        }

        $this->addSubNavigation('main', $navigation);

        // admin (study group only)
        if ($studygroup_mode && $perm->have_studip_perm('dozent', $SessSemName[1])) {
            $navigation = new Navigation(_('Admin'));
            $navigation->addSubNavigation('admin', new Navigation(_('Admin'), 'dispatch.php/course/studygroup/edit/'.$SessSemName[1]));
            $this->addSubNavigation('studygroup', $navigation);
        }

        // forum
        if ($modules['forum']) {
            $navigation = new Navigation(_('Forum'), 'forum.php?view=reset');
            $navigation->addSubNavigation('view', new Navigation(_('Themenansicht'), 'forum.php?view='.$forum['themeview']));

            if ($user->id != 'nobody') {
                $navigation->addSubNavigation('unread', new Navigation(_('neue Beitr�ge'), 'forum.php?view=neue&sort=age'));
                $navigation->addSubNavigation('recent', new Navigation(_('letzte Beitr�ge'), 'forum.php?view=flat&sort=age'));
                $navigation->addSubNavigation('search', new Navigation(_('Suchen'), 'forum.php?view=search&reset=1'));
            }

            $navigation->addSubNavigation('export', new Navigation(_('Druckansicht'), 'forum_export.php'));

            if ($perm->have_studip_perm('tutor', $SessSemName[1]) || $SEM_CLASS[$SEM_TYPE[$SessSemName['art_num']]['class']]['topic_create_autor']) {
                $navigation->addSubNavigation('create_topic', new Navigation(_('neues Thema anlegen'), 'forum.php?view='.$forum['themeview'].'&neuesthema=TRUE#anker'));
            }

            $navigation->addSubNavigation('settings', new Navigation(_('Forum anpassen'), 'forum.php?forumsend=anpassen'));
            $this->addSubNavigation('forum', $navigation);
        }

        // participants
        if ($user->id != 'nobody') {
            if ($modules['participants']) {
                $navigation = new Navigation(_('TeilnehmerInnen'));

                if ($studygroup_mode) {
                    $navigation->addSubNavigation('view', new Navigation(_('TeilnehmerInnen'), 'dispatch.php/course/studygroup/members/'.$SessSemName[1]));
                    $this->addSubNavigation('members', $navigation);
                } else if (!is_array($AUTO_INSERT_SEM) || !in_array($SessSemName[1], $AUTO_INSERT_SEM) || $perm->have_studip_perm('tutor', $SessSemName[1])) {
                    $navigation->addSubNavigation('view', new Navigation(_('TeilnehmerInnen'), 'teilnehmer.php'));

                    if (is_array($rule['attributes']) && in_array(1, $rule['attributes'])) {
                        $navigation->addSubNavigation('aux_data', new Navigation(_('Zusatzangaben'), 'teilnehmer_aux.php'));
                    }

                    $navigation->addSubNavigation('view_groups', new Navigation(_('Funktionen / Gruppen'), 'statusgruppen.php?view=statusgruppe_sem'));

                    if ($perm->have_studip_perm('tutor', $SessSemName[1]) && !LockRules::check($SessSemName[1], 'groups')) {
                        $navigation->addSubNavigation('edit_groups', new Navigation(_('Funktionen / Gruppen verwalten'), 'admin_statusgruppe.php?new_sem=TRUE&range_id='.$SessSemName[1]));
                    }

                    $this->addSubNavigation('members', $navigation);
                }
            } else if ($modules['personal']) {
                $navigation = new Navigation(_('Personal'));
                $navigation->addSubNavigation('view', new Navigation(_('MitarbeiterInnen'), 'institut_members.php'));

                if ($perm->have_studip_perm('tutor', $SessSemName[1]) && $perm->have_perm('admin')) {
                    $navigation->addSubNavigation('edit_groups', new Navigation(_('Funktionen / Gruppen verwalten'), 'admin_roles.php?new_sem=TRUE&range_id='.$SessSemName[1]));
                }

                $this->addSubNavigation('faculty', $navigation);
            }
        }

        // files
        if ($modules['documents']) {
            $navigation = new Navigation(_('Dateien'));
            $navigation->addSubNavigation('tree', new Navigation(_('Ordneransicht'), 'folder.php?cmd=tree'));
            $navigation->addSubNavigation('all', new Navigation(_('Alle Dateien'), 'folder.php?cmd=all'));
            $this->addSubNavigation('files', $navigation);
        }

        // schedule
        if ($modules['schedule'] && $user->id != 'nobody') {
            $navigation = new Navigation(_('Ablaufplan'));
            $navigation->addSubNavigation('all', new Navigation(_('alle Termine'), 'dates.php?cmd=setType&type=all'));
            $navigation->addSubNavigation('type1', new Navigation(_('Sitzungstermine'), 'dates.php?cmd=setType&type=1'));
            $navigation->addSubNavigation('other', new Navigation(_('andere Termine'), 'dates.php?cmd=setType&type=other'));

            if ($perm->have_studip_perm('tutor', $SessSemName[1])) {
                $navigation->addSubNavigation('topics', new Navigation(_('Ablaufplan bearbeiten'), 'themen.php?seminar_id='.$SessSemName[1]));
            }

            $this->addSubNavigation('schedule', $navigation);
        }

        // information page
        if ($modules['scm']) {
            $navigation = new Navigation($scms[0]['tab_name']);

            foreach ($scms as $scm) {
                $navigation->addSubNavigation($scm['scm_id'], new Navigation($scm['tab_name'] , 'scm.php?show_scm=' . $scm['scm_id']));
            }

            if ($perm->have_studip_perm('tutor', $SessSemName[1])) {
                $navigation->addSubNavigation('new_entry', new Navigation(_('neuen Eintrag anlegen'), 'scm.php?show_scm=new_entry&i_view=edit'));
            }

            $this->addSubNavigation('scm', $navigation);
        }

        // literature
        if ($modules['literature']) {
            $navigation = new Navigation(_('Literatur'));
            $navigation->addSubNavigation('view', new Navigation(_('Literatur'), 'literatur.php?view=literatur_'.$sem_class));

            if ($sem_class == 'inst') {
                $navigation->setTitle(_('Literatur zur Einrichtung'));
            }

            $navigation->addSubNavigation('print', new Navigation(_('Druckansicht'), 'lit_print_view.php?_range_id=' . $SessSemName[1]));

            if ($perm->have_studip_perm('tutor', $SessSemName[1])) {
                $navigation->addSubNavigation('edit', new Navigation(_('Literatur bearbeiten'), 'admin_lit_list.php?view=literatur_'.$sem_class.'&new_'.$sem_class.'=TRUE&_range_id='.$SessSemName[1]));
            }

            $this->addSubNavigation('literature', $navigation);
        }

        // wiki
        if ($modules['wiki']) {
            $navigation = new Navigation(_('Wiki'));
            $navigation->addSubNavigation('show', new Navigation(_('WikiWikiWeb'), 'wiki.php?view=show'));
            $navigation->addSubNavigation('listnew', new Navigation(_('Neue Seiten'), 'wiki.php?view=listnew'));
            $navigation->addSubNavigation('listall', new Navigation(_('Alle Seiten'), 'wiki.php?view=listall'));
            $navigation->addSubNavigation('export', new Navigation(_('Export'), 'wiki.php?view=export'));
            $this->addSubNavigation('wiki', $navigation);
        }

        // resources
        if (get_config('RESOURCES_ENABLE')) {
            if (checkAvailableResources($SessSemName[1])) {
                $navigation = new Navigation(_('Ressourcen'), 'resources.php?view=openobject_main&view_mode=oobj');
                $navigation->addSubNavigation('overview', new Navigation(_('�bersicht'), 'resources.php?view=openobject_main'));
                $navigation->addSubNavigation('group_schedule', new Navigation(_('�bersicht Belegung'), 'resources.php?view=openobject_group_schedule'));
                $navigation->addSubNavigation('view_details', new Navigation(_('Details'), 'resources.php?view=openobject_details'));
                $navigation->addSubNavigation('view_schedule', new Navigation(_('Belegung'), 'resources.php?view=openobject_schedule'));
                $navigation->addSubNavigation('edit_assign', new Navigation(_('Belegungen bearbeiten'), 'resources.php?view=openobject_assign'));
                $this->addSubNavigation('resources', $navigation);
            }
        }

        // content modules
        if (get_config('ELEARNING_INTERFACE_ENABLE') && $modules['elearning_interface'] && $user->id != 'nobody') {
            $navigation = new Navigation(_('Lernmodule'));

            if (ObjectConnections::isConnected($SessSemName[1])) {
                $elearning_nav = new Navigation(_('Lernmodule dieser Veranstaltung'), 'elearning_interface.php?view=show&seminar_id=' . $SessSemName[1]);

                if ($sem_class == 'inst') {
                    $elearning_nav->setTitle(_('Lernmodule dieser Einrichtung'));
                }

                $navigation->addSubNavigation('show', $elearning_nav);
            }

            if ($perm->have_studip_perm('tutor', $SessSemName[1])) {
                $navigation->addSubNavigation('edit', new Navigation(_('Lernmodule hinzuf�gen / entfernen'), 'elearning_interface.php?view=edit&seminar_id=' . $SessSemName[1]));
            }

            $this->addSubNavigation('elearning', $navigation);
        }

        // activated plugins
        if ($GLOBALS['PLUGINS_ENABLE']) {
            PluginEngine::getPlugins('StandardPlugin', $SessSemName[1]);
        }
    }
}
