<?
/**
* AssignEvent.class.php
* 
* class for an assign-event
* 
*
* @author		Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>
* @version		$Id$
* @access		public
* @modulegroup		resources
* @module		AssignEvent.class.php
* @package		resources
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// AssignEvent.class.php
// zentrale Klasse fuer ein Belegungsevent, die konkrete Belegung
// Copyright (C) 2003 Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>
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

require_once ($GLOBALS['ABSOLUTE_PATH_STUDIP']."datei.inc.php");
require_once ($GLOBALS['RELATIVE_PATH_RESOURCES']."/lib/AssignObject.class.php");

/*****************************************************************************
AssignEvent, the assigned events 
/*****************************************************************************/
class AssignEvent {
	var $db;					//Database
	var $id;					//Id from mother AssignObject
	var $resource_id;				//resource_id from mother AssignObject
	var $assign_user_id;				//user_id of mother AssignObject
	var $user_free_name;				//free owner-name of mother AssignObject
	var $repeat_mode;				//the repeat mode of mother AssignObject
	var $begin;					//begin timestamp
	var $end;					//end timestamp

	//Konstruktor
	function AssignEvent($assign_id, $begin, $end, $resource_id, $assign_user_id, $user_free_name='') {
		global $user;
		$this->user_id = $user->id;
		//$this->db=new DB_Seminar;

		$this->assign_id=$assign_id;
		$this->begin=$begin;
		$this->end=$end;
		$this->resource_id=$resource_id;
		$this->assign_user_id=$assign_user_id;
		$this->user_free_name=$user_free_name;
		$this->id = md5(uniqid("jasony",1));
	}

	function setRepeatMode ($value) {
		$this->repeat_mode = ($value);
	}
	
	function getRepeatMode ($check_corresponding_metadata = FALSE) {
		if (($this->repeat_mode == "na") && ($check_corresponding_metadata)) {
			$assObj =& AssignObject::Factory($this->getAssignId());
			if (($assObj->getOwnerType() == "date") && (isMetadateCorrespondingDate($this->getAssignUserId())))
				return "meta";
		}
		return $this->repeat_mode;
	}


	function getId() {
		return $this->id;
	}

	function getAssignId() {
		return $this->assign_id;
	}
	
	function getAssignUserId() {
		return $this->assign_user_id;
	}

	function getResourceId() {
		return $this->resource_id;
	}

	function getUserFreeName() {
		return $this->user_free_name;
	}
	
	function getUsername($use_free_name=TRUE) {
		if ($this->assign_user_id) 
			return assignObject::getOwnerName(TRUE, $this->assign_user_id);
		elseif ($use_free_name)
			return $this->getUserFreeName();
		else 
			return FALSE;
	}
	
	function getName() {
		return $this->getUsername();
	}

	function getBegin() {
		if (!$this->begin)
			return time();
		else
			return $this->begin;
	}

	function getEnd() {
		if (!$this->end)
			return time()+3600;
		else
			return $this->end;
	}

	function store($create='') {
		// Noch fraglich, ob diese Methose existieren soll. Wenn ja muesste sie eine Splittung vornehmen
	}

	function delete() {
		// Noch fraglich, ob diese Methose existieren soll. Wenn ja muesste sie eine Splittung vornehmen
	}

}
