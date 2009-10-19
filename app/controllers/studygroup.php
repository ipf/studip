<?php

/*
 * Copyright (C) 2009 - André Klaßen <aklassen@uos.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 */

require_once 'app/controllers/authenticated_controller.php';
require_once 'app/models/studygroup.php';

if (!defined('ELEMENTS_PER_PAGE')) define("ELEMENTS_PER_PAGE", 20); 

class StudygroupController extends AuthenticatedController {
	
  function search_action($page,$sort) {
    $this->sort = $sort;
    $this->page = $page;

    $anzahl = StudygroupModel::countGroups();

    // lets calculate borders 
    if($this->page < 1 || $this->page > ceil($anzahl/ELEMENTS_PER_PAGE)) $this->page = 1;
    $this->lower_bound = ($this->page - 1) * ELEMENTS_PER_PAGE;

    $groups = StudygroupModel::getAllGroups($this->sort, $this->lower_bound, ELEMENTS_PER_PAGE);

    
    $this->tabs = 'links_seminare';
    $this->reiter_view = 'studygroups_search';
    $GLOBALS['CURRENT_PAGE'] =  _('Studiengruppen suchen');
    
    $this->groups = $groups;
    $this->anzahl = $anzahl;
    $this->userid = $GLOBALS['auth']->auth['uid'];
  }
}
