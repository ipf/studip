<?php
// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// StudipRangeTreeViewAdmin.class.php
// Class to print out the "range tree"
//
// Copyright (c) 2002 Andr� Noack <noack@data-quest.de>
// Suchi & Berg GmbH <info@data-quest.de>
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
require_once("lib/classes/StudipRangeTree.class.php");
require_once("lib/classes/TreeView.class.php");
require_once("lib/classes/RangeTreeObject.class.php");
require_once("config.inc.php");

/**
* class to print out the admin view of the "range tree"
*
* This class prints out a html representation of the whole or part of the tree, <br>
* it also contains all functions for administrative tasks on the tree
*
* @access	public
* @author	Andr� Noack <noack@data-quest.de>
* @version	$Id$
* @package
*/
class StudipRangeTreeViewAdmin extends TreeView{


	var $tree_status;

	var $mode;

	var $edit_item_id;

	var $move_item_id;

	var $search_result;

	var $msg;

	var $marked_item;

	var $edit_cat_snap;

	var $edit_cat_item_id;

	/**
	* constructor
	*
	* calls the base class constructor, registers a session variable, calls the init function and the command parser
	* @access public
	*/
	function StudipRangeTreeViewAdmin(){
		global $sess,$_marked_item;
		$this->root_content = $GLOBALS['UNI_INFO'];
		parent::TreeView("StudipRangeTree"); //calling the baseclass constructor
		if (is_object($sess)){
			$sess->register("_marked_item");
			$this->marked_item =& $_marked_item;
		}
		$this->initTreeStatus();
		$this->parseCommand();
	}

	function initTreeStatus(){
		$view = new DbView();
		$user_id = $GLOBALS['auth']->auth['uid'];
		$user_perm = $GLOBALS['auth']->auth['perm'];
		$studip_object_status = null;
		if (is_array($this->open_items)){
			foreach ($this->open_items as $key => $value){
				if ($key != 'root'){
					$tmp = $this->tree->getAdminRange($key);
					for ($i = 0; $i < count($i); ++$i){
						if ($tmp[$i])
							$studip_object_status[$tmp[$i]] = ($user_perm == "root") ? 1 : -1;
					}
				}
			}
		}
		if (is_array($this->open_ranges)){
			foreach ($this->open_ranges as $key => $value){
				if ($key != 'root'){
					$tmp = $this->tree->getAdminRange($key);
					for ($i = 0; $i < count($i); ++$i){
						if ($tmp[$i])
							$studip_object_status[$tmp[$i]] = ($user_perm == "root") ? 1 : -1;
					}
					$tmp = $this->tree->getAdminRange($this->tree->tree_data[$key]['parent_id']);
					for ($i = 0; $i < count($i); ++$i){
						if ($tmp[$i])
							$studip_object_status[$tmp[$i]] = ($user_perm == "root") ? 1 : -1;
					}
				}
			}
		}
		if (is_array($studip_object_status) && $user_perm != 'root'){
			$view->params = array(array_keys($studip_object_status), $user_id);
			$rs = $view->get_query("view:TREE_INST_STATUS");
			while ($rs->next_record()){
				$studip_object_status[$rs->f("Institut_id")] = 1;
			}
			$view->params = array(array_keys($studip_object_status), $user_id);
			$rs = $view->get_query("view:TREE_FAK_STATUS");
			while ($rs->next_record()){
				$studip_object_status[$rs->f("Fakultaets_id")] = 1;
				if ($rs->f("Institut_id") && isset($studip_object_status[$rs->f("Institut_id")])){
					$studip_object_status[$rs->f("Institut_id")] = 1;
				}
			}
		}
		$studip_object_status['root'] = ($user_perm == "root") ? 1 : -1;
	$this->tree_status = $studip_object_status;
	}

	function parseCommand(){
		if ($_REQUEST['mode'])
			$this->mode = $_REQUEST['mode'];
		if ($_REQUEST['cmd']){
			$exec_func = "execCommand" . $_REQUEST['cmd'];
			if (method_exists($this,$exec_func)){
				if ($this->$exec_func()){
					$this->tree->init();
					$this->initTreeStatus();
				}
			}
		}
		if ($this->mode == "MoveItem")
			$this->move_item_id = $this->marked_item;
	}

	function execCommandOrderItem(){
		$direction = $_REQUEST['direction'];
		$item_id = $_REQUEST['item_id'];
		$items_to_order = $this->tree->getKids($this->tree->tree_data[$item_id]['parent_id']);
		if (!$this->isParentAdmin($item_id) || !$items_to_order)
			return false;
		for ($i = 0; $i < count($items_to_order); ++$i){
			if ($item_id == $items_to_order[$i])
				break;
		}
		if ($direction == "up" && isset($items_to_order[$i-1])){
			$items_to_order[$i] = $items_to_order[$i-1];
			$items_to_order[$i-1] = $item_id;
		} elseif (isset($items_to_order[$i+1])){
			$items_to_order[$i] = $items_to_order[$i+1];
			$items_to_order[$i+1] = $item_id;
		}
		$view = new DbView();
		for ($i = 0; $i < count($items_to_order); ++$i){
			$view->params = array($i, $items_to_order[$i]);
			$rs = $view->get_query("view:TREE_UPD_PRIO");
		}
		$this->mode = "";
		$this->msg[$item_id] = "msg�" . (($direction == "up") ? _("Element wurde um eine Position nach oben verschoben.") : _("Element wurde um eine Position nach unten verschoben."));
		return true;
	}

	function execCommandNewItem(){
		$item_id = $_REQUEST['item_id'];
		if ($this->isItemAdmin($item_id)){
			$new_item_id = DbView::get_uniqid();
			$this->tree->storeItem($new_item_id,$item_id,_("Neues Element") , $this->tree->getNumKids($item_id) +1);
			$this->anchor = $new_item_id;
			$this->edit_item_id = $new_item_id;
			$this->open_ranges[$item_id] = true;
			$this->open_items[$new_item_id] = true;
			if ($this->mode != "NewItem")
				$this->msg[$new_item_id] = "info�" . _("W&auml;hlen Sie einen Namen f�r dieses Element, oder verlinken Sie es mit einer Einrichtung in Stud.IP");
			$this->mode = "NewItem";
		}
		return false;
	}

	function execCommandSearchStudip(){
		$item_id = $_REQUEST['item_id'];
		$parent_id = $_REQUEST['parent_id'];
		$search_str = $_REQUEST['edit_search'];
		$view = new DbView();
		if(strlen($search_str) > 1){
			$view->params[0] = $search_str;
			$rs = $view->get_query("view:TREE_SEARCH_INST");
			while ($rs->next_record()){
				$this->search_result[$rs->f("Institut_id")]['name'] = $rs->f("Name");
				$this->search_result[$rs->f("Institut_id")]['studip_object'] = "inst";
			}
			if ($parent_id == "root"){
				$view->params[0] = $search_str;
				$rs = $view->get_query("view:TREE_SEARCH_FAK");
				while ($rs->next_record()){
					$this->search_result[$rs->f("Fakultaets_id")]['name'] = $rs->f("Name");
					$this->search_result[$rs->f("Fakultaets_id")]['studip_object'] = "fak";
				}
			}
		$search_msg = "info�" . sprintf(_("Ihre Suche ergab %s Treffer."),count($this->search_result));
		} else {
			$search_msg = "error�" . _("Sie haben keinen Suchbegriff eingegeben.");
		}
		if ($this->mode == "NewItem"){
			$_REQUEST['item_id'] = $parent_id;
			$this->execCommandNewItem();
		} else {
			$this->anchor = $item_id;
			$this->edit_item_id = $item_id;
		}
		$this->msg[$this->edit_item_id] = $search_msg;
		return false;
	}

	function execCommandEditItem(){
		$item_id = $_REQUEST['item_id'];
		if ($this->isItemAdmin($item_id) || $this->isParentAdmin($item_id)){
			$this->mode = "EditItem";
			$this->anchor = $item_id;
			$this->edit_item_id = $item_id;
			$this->msg[$item_id] = "info�" . _("W&auml;hlen Sie einen Namen f�r dieses Element, oder verlinken Sie es mit einer Einrichtung in Stud.IP");
		}
		return false;
	}

	function execCommandInsertItem(){
		$item_id = $_REQUEST['item_id'];
		$parent_id = $_REQUEST['parent_id'];
		$item_name = $_REQUEST['edit_name'];
		$tmp = explode(":",$_REQUEST['edit_studip_object']);
		if ($tmp[1] == "fak" || $tmp[1] == "inst"){
			$studip_object = $tmp[1];
			$studip_object_id = $tmp[0];
		} else {
			$studip_object = "";
			$studip_object_id = "";
		}
		if ($this->mode == "NewItem" && $item_id){
			if ($this->isItemAdmin($parent_id)){
				$priority = count($this->tree->getKids($parent_id));
				$affected_rows = $this->tree->InsertItem($item_id,$parent_id,$item_name,$priority,$studip_object,$studip_object_id);
				if ($affected_rows){
					$this->mode = "";
					$this->anchor = $item_id;
					$this->open_items[$item_id] = true;
					$this->msg[$item_id] = "msg�" . _("Dieses Element wurde neu eingef&uuml;gt.");
				}
			}
		}
		if ($this->mode == "EditItem"){
			if ($this->isParentAdmin($item_id)){
				$affected_rows = $this->tree->UpdateItem($item_name,$studip_object,$studip_object_id,$item_id);
				if ($affected_rows){
					$this->msg[$item_id] = "msg�" . _("Element wurde ge&auml;ndert.");
				} else {
					$this->msg[$item_id] = "info�" . _("Keine Ver&auml;nderungen vorgenommen.");
				}
				$this->mode = "";
				$this->anchor = $item_id;
				$this->open_items[$item_id] = true;

			}
		}
		return true;
	}

	function execCommandAssertDeleteItem(){
		$item_id = $_REQUEST['item_id'];
		if ($this->isParentAdmin($item_id)){
			$this->mode = "AssertDeleteItem";
			$this->msg[$item_id] = "info�" ._("Sie beabsichtigen dieses Element, inklusive aller Unterelemente, zu l&ouml;schen. ")
						. sprintf(_("Es werden insgesamt %s Elemente gel&ouml;scht!"),count($this->tree->getKidsKids($item_id))+1)
						. "<br>" . _("Wollen Sie diese Elemente wirklich l&ouml;schen?") . "<br>"
						. "<a href=\"" . $this->getSelf("cmd=DeleteItem&item_id=$item_id") . "\">"
						. "<img " .makeButton("ja2","src") . tooltip(_("l�schen"))
						. " border=\"0\"></a>&nbsp;"
						. "<a href=\"" . $this->getSelf("cmd=Cancel&item_id=$item_id") . "\">"
						. "<img " .makeButton("nein","src") . tooltip(_("abbrechen"))
						. " border=\"0\"></a>";
		}
		return false;
	}

	function execCommandDeleteItem(){
		$item_id = $_REQUEST['item_id'];
		$deleted = 0;
		$item_name = $this->tree->tree_data[$item_id]['name'];
		if ($this->isParentAdmin($item_id) && $this->mode == "AssertDeleteItem"){
			$this->anchor = $this->tree->tree_data[$item_id]['parent_id'];
			$items_to_delete = $this->tree->getKidsKids($item_id);
			$items_to_delete[] = $item_id;
			$deleted = $this->tree->DeleteItems($items_to_delete);
			if ($deleted['items']){
				$this->msg[$this->anchor] = "msg�" . sprintf(_("Das Element <b>%s</b> und alle Unterelemente (insgesamt %s) wurden gel&ouml;scht. "),htmlReady($item_name),$deleted['items']);
			} else {
				$this->msg[$this->anchor] = "error�" . _("Fehler, es konnten keine Elemente gel&ouml;scht werden!");
			}
			if ($deleted['categories']){
				$this->msg[$this->anchor] .= sprintf(_("<br>Es wurden %s Datenfelder gel&ouml;scht. "),$deleted['categories']);
			}
			$this->mode = "";
			$this->open_items[$this->anchor] = true;
		}
		return true;
	}

	function execCommandMoveItem(){
		$item_id = $_REQUEST['item_id'];
		$this->anchor = $item_id;
		$this->marked_item = $item_id;
		$this->mode = "MoveItem";
		return false;
	}

	function execCommandDoMoveItem(){
		$item_id = $_REQUEST['item_id'];
		$item_to_move = $this->marked_item;
		if ($this->mode == "MoveItem" && ($this->isItemAdmin($item_id) || $this->isParentAdmin($item_id))
			&& ($item_to_move != $item_id) && ($this->tree->tree_data[$item_to_move]['parent_id'] != $item_id)
			&& !$this->tree->isChildOf($item_to_move,$item_id)){
			$view = new DbView();
			$view->params = array($item_id, count($this->tree->getKids($item_id)), $item_to_move);
			$rs = $view->get_query("view:TREE_MOVE_ITEM");
			if ($rs->affected_rows()){
					$this->msg[$item_to_move] = "msg�" . _("Element wurde verschoben.");
				} else {
					$this->msg[$item_to_move] = "error�" . _("Keine Verschiebung durchgef�hrt.");
				}
			}
		$this->anchor = $item_to_move;
		$this->open_ranges[$item_id] = true;
		$this->open_items[$item_to_move] = true;
		$this->mode = "";
		return true;
	}

	function execCommandOrderCat(){
		$item_id = $_REQUEST['item_id'];
		$direction = $_REQUEST['direction'];
		$cat_id = $_REQUEST['cat_id'];
		$items_to_order = array();
		if ($this->isItemAdmin($item_id)){
			$range_object =& RangeTreeObject::GetInstance($item_id);
			$categories =& $range_object->getCategories();
			while($categories->nextRow()){
				$items_to_order[] = $categories->getField("kategorie_id");
			}
			for ($i = 0; $i < count($items_to_order); ++$i){
				if ($cat_id == $items_to_order[$i])
					break;
			}
			if ($direction == "up" && isset($items_to_order[$i-1])){
				$items_to_order[$i] = $items_to_order[$i-1];
				$items_to_order[$i-1] = $cat_id;
			} elseif (isset($items_to_order[$i+1])){
				$items_to_order[$i] = $items_to_order[$i+1];
				$items_to_order[$i+1] = $cat_id;
			}
			$view = new DbView();
			for ($i = 0; $i < count($items_to_order); ++$i){
				$view->params = array($i,$items_to_order[$i]);
				$rs = $view->get_query("view:CAT_UPD_PRIO");
			}
			$this->msg[$item_id] = "msg�" . _("Datenfelder wurden neu geordnet");
		}
		$this->anchor = $item_id;
		return false;
	}

	function execCommandNewCat(){
		$item_id = $_REQUEST['item_id'];
		if ($this->isItemAdmin($item_id)){
			$range_object =& RangeTreeObject::GetInstance($item_id);
			$this->edit_cat_snap =& $range_object->getCategories();
			$this->edit_cat_snap->result[$this->edit_cat_snap->numRows] =
				array("kategorie_id" => "new_entry", "range_id" => $item_id, "name" => "Neues Datenfeld", "content" => "Neues Datenfeld",
						"priority" => $this->edit_cat_snap->numRows);
			++$this->edit_cat_snap->numRows;
			$this->edit_cat_item_id = $item_id;
			$this->mode = "NewCat";
		}
		$this->anchor = $item_id;
		return false;
	}

	function execCommandUpdateCat(){
		$item_id = $_REQUEST['item_id'];
		$cat_name = $_REQUEST['cat_name'];
		$cat_content = $_REQUEST['cat_content'];
		$cat_prio = $_REQUEST['cat_prio'];
		$inserted = false;
		$updated = 0;
		if ($this->isItemAdmin($item_id)){
			$view = new DbView();
			if (isset($cat_name['new_entry'])){
				$view->params = array($view->get_uniqid(),$item_id,$cat_name['new_entry'],$cat_content['new_entry'],$cat_prio['new_entry']);
				$rs = $view->get_query("view:CAT_INS_ALL");
				if ($rs->affected_rows()){
					$inserted = true;
				}
				unset($cat_name['new_entry']);
			}
			foreach ($cat_name as $key => $value){
				$view->params = array($value,$cat_content[$key],$key);
				$rs = $view->get_query("view:CAT_UPD_CONTENT");
				if ($rs->affected_rows()){
					++$updated;
				}
			}
			if ($updated){
				$this->msg[$item_id] = "msg�" . sprintf(_("Es wurden %s Datenfelder aktualisiert."),$updated);
				if ($inserted) {
					$this->msg[$item_id] .= _(" Ein neues Datenfeld wurde eingef&uuml;gt.");
				}
			} elseif ($inserted){
				$this->msg[$item_id] = "msg�" . _("Ein neues Datenfeld wurde eingef&uuml;gt.");
			} else {
				$this->msg[$item_id] = "info�" . _("Keine Ver&auml;nderungen vorgenommen.");
			}
		}
		$this->anchor = $item_id;
		$this->mode = "";
		return false;
	}

	function execCommandDeleteCat(){
		$item_id = $_REQUEST['item_id'];
		$cat_id = $_REQUEST['cat_id'];
		if ($this->isItemAdmin($item_id)){
			$view = new DbView();
			$view->params[0] = $cat_id;
			$rs = $view->get_query("view:CAT_DEL");
			if ($rs->affected_rows()){
				$this->msg[$item_id] = "msg�" . _("Ein Datenfeld wurde gel&ouml;scht.");
			}
		}
		$this->mode = "";
		$this->anchor = $item_id;
		return false;
	}


	function execCommandCancel(){
		$item_id = $_REQUEST['item_id'];
		$this->mode = "";
		$this->anchor = $item_id;
		return false;
	}

	function isItemAdmin($item_id){
		$admin_ranges = $this->tree->getAdminRange($item_id);
		for ($i = 0; $i < count($admin_ranges); ++$i){
			if ($this->tree_status[$admin_ranges[$i]] == 1){
				return true;
			}
		}
		return false;
	}

	function isParentAdmin($item_id){
		$admin_ranges = $this->tree->getAdminRange($this->tree->tree_data[$item_id]['parent_id']);
		for ($i = 0; $i < count($admin_ranges); ++$i){
			if ($this->tree_status[$admin_ranges[$i]] == 1){
				return true;
			}
		}
		return false;
	}

	function getItemContent($item_id){

		if ($item_id == $this->edit_item_id )
			return $this->getEditItemContent();
		if ($item_id == $this->move_item_id){
			$this->msg[$item_id] = "info�" . sprintf(_("Dieses Element wurde zum Verschieben markiert. Bitte w&auml;hlen Sie ein Einf�gesymbol %s aus, um das Element zu verschieben."), "<img src=\"".$GLOBALS['ASSETS_URL']."images/move.gif\" border=\"0\" " .tooltip(_("Einf�gesymbol")) . ">");
			}
		$content = "\n<table width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\" style=\"font-size:10pt\">";
		$content .= $this->getItemMessage($item_id);
		$content .= "\n<tr><td align=\"center\">";
		if ($this->isItemAdmin($item_id)){
			$content .= "<a href=\"" . $this->getSelf("cmd=NewItem&item_id=$item_id") . "\">"
						. "<img " .makeButton("neuesobjekt","src") . tooltip(_("Innerhalb dieser Ebene ein neues Element einf�gen"))
						. " border=\"0\"></a>&nbsp;";
		}
		if ($this->isParentAdmin($item_id) && $item_id !=$this->start_item_id && $item_id != "root"){
			$content .= "<a href=\"" . $this->getSelf("cmd=EditItem&item_id=$item_id") . "\">"
			. "<img " .makeButton("bearbeiten","src") . tooltip(_("Dieses Element bearbeiten"))
			. " border=\"0\"></a>&nbsp;";

			$content .= "<a href=\"" . $this->getSelf("cmd=AssertDeleteItem&item_id=$item_id") . "\">"
			. "<img " .makeButton("loeschen","src") . tooltip(_("Dieses Element l�schen"))
			. " border=\"0\"></a>&nbsp;";
			if ($this->move_item_id == $item_id && $this->mode == "MoveItem"){
				$content .= "<a href=\"" . $this->getSelf("cmd=Cancel&item_id=$item_id") . "\">"
										. "<img " .makeButton("abbrechen","src") . tooltip(_("Verschieben abbrechen"))
										. " border=\"0\"></a>&nbsp;";
			} else {
				$content .= "<a href=\"" . $this->getSelf("cmd=MoveItem&item_id=$item_id") . "\">"
			. "<img " .makeButton("verschieben","src") . tooltip(_("Dieses Element in eine andere Ebene verschieben"))
			. " border=\"0\"></a>&nbsp;";
			}
		}
		$content .= "</td></tr></table>";
		$content .= "\n<table width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\" style=\"font-size:10pt\">";
		if ($item_id == "root"){
			$content .= "\n<tr><td class=\"topic\" align=\"left\">" . htmlReady($this->tree->root_name) ." </td></tr>";
			$content .= "\n<tr><td class=\"blank\" align=\"left\">" . htmlReady($this->root_content) ." </td></tr>";
			$content .= "\n</table>";
			return $content;
		}
		$range_object =& RangeTreeObject::GetInstance($item_id);
		$name = ($range_object->item_data['type']) ? $range_object->item_data['type'] . ": " : "";
		$name .= $range_object->item_data['name'];
		$content .= "\n<tr><td class=\"topic\" align=\"left\" style=\"font-size:10pt\">" . htmlReady($name) ." </td></tr>";
		if (is_array($range_object->item_data_mapping)){
			$content .= "\n<tr><td class=\"blank\" align=\"left\" style=\"font-size:10pt\">";
			foreach ($range_object->item_data_mapping as $key => $value){
				if ($range_object->item_data[$key]){
					$content .= "<b>" . htmlReady($value) . ":</b>&nbsp;";
					$content .= fixLinks(htmlReady($range_object->item_data[$key])) . "&nbsp; ";
				}
			}
			$content .= "&nbsp;";
		} elseif (!$range_object->item_data['studip_object']){
			$content .= "\n<tr><td class=\"blank\" align=\"left\" style=\"font-size:10pt\">" .
						_("Dieses Element ist keine Stud.IP-Einrichtung, es hat daher keine Grunddaten.");
		} else {
			$content .= "\n<tr><td class=\"blank\" align=\"left\" style=\"font-size:10pt\">" . _("Keine Grunddaten vorhanden!");
		}
		$content .= "\n<div class=\"blank\" align=\"left\" style=\"font-size:10pt\"><b>" . _("Mitarbeiter:") . "</b> " . $range_object->getNumStaff() . "</b></div>";
		if ($this->isItemAdmin($item_id) && $range_object->item_data['studip_object']){
			$content .= "\n<div class=\"blank\" align=\"center\" style=\"font-size:10pt\"><a href=\""
					. "admin_institut.php?admin_inst_id=" . $range_object->item_data['studip_object_id']
					. "\"><img " . makeButton("bearbeiten","src") . tooltip(_("Grunddaten in Stud.IP bearbeiten")) . " border=\"0\"></a></div>";
		}
		$content .= "</td></tr><tr><td>&nbsp;</td></tr>";
		if ($this->mode == "NewCat" && ($this->edit_cat_item_id == $item_id)){
			$categories =& $this->edit_cat_snap;
		} else {
			$categories =& $range_object->getCategories();
		}
		if (!$this->isItemAdmin($item_id)){
			if ($categories->numRows){
				while($categories->nextRow()){
					$content .= "\n<tr><td class=\"topic\" style=\"font-size:10pt\">" . formatReady($categories->getField("name")) . "</td></tr>";
					$content .= "\n<tr><td class=\"blank\" style=\"font-size:10pt\">" . formatReady($categories->getField("content")) . "</td></tr>";
				}
			} else {
				$content .= "\n<tr><td class=\"blank\" style=\"font-size:10pt\">" . _("Keine weiteren Daten vorhanden!") . "</td></tr>";
			}
		} else {
			$content .= "<tr><td class=\"blank\" style=\"font-size:10pt\">" . $this->getEditCatContent($item_id,$categories) . "</td></tr>";
		}
		$content .= "</table>";
		return $content;
	}

	function getItemHead($item_id){
		$head = "";
		if ($this->mode == "MoveItem" && ($this->isItemAdmin($item_id) || $this->isParentAdmin($item_id))
			&& ($this->move_item_id != $item_id) && ($this->tree->tree_data[$this->move_item_id]['parent_id'] != $item_id)
			&& !$this->tree->isChildOf($this->move_item_id,$item_id)){
			$head .= "<a href=\"" . $this->getSelf("cmd=DoMoveItem&item_id=$item_id") . "\">"
			. "<img src=\"".$GLOBALS['ASSETS_URL']."images/move.gif\" border=\"0\" " .tooltip(_("An dieser Stelle einf�gen")) . "></a>&nbsp;";
		}
		$head .= parent::getItemHead($item_id);
		if ($item_id != $this->start_item_id && $this->isParentAdmin($item_id) && $item_id != $this->edit_item_id){
			$head .= "</td><td align=\"rigth\" valign=\"bottom\" class=\"printhead\">";
			if (!$this->tree->isFirstKid($item_id)){
				$head .= "<a href=\"". $this->getSelf("cmd=OrderItem&direction=up&item_id=$item_id") .
				"\"><img src=\"".$GLOBALS['ASSETS_URL']."images/move_up.gif\" hspace=\"4\" width=\"13\" height=\"11\" border=\"0\" " .
				tooltip(_("Element nach oben verschieben")) ."></a>";
			}
			if (!$this->tree->isLastKid($item_id)){
				$head .= "<a href=\"". $this->getSelf("cmd=OrderItem&direction=down&item_id=$item_id") .
				"\"><img src=\"".$GLOBALS['ASSETS_URL']."images/move_down.gif\" hspace=\"4\" width=\"13\" height=\"11\" border=\"0\" " .
				tooltip(_("Element nach unten verschieben")) . "></a>";
			}
			$head .= "&nbsp;";
		}
		return $head;
	}

	function getEditItemContent(){
		$content = "\n<form name=\"item_form\" action=\"" . $this->getSelf("cmd=InsertItem&item_id={$this->edit_item_id}") . "\" method=\"POST\">";
		$content .= "\n<input type=\"HIDDEN\" name=\"parent_id\" value=\"{$this->tree->tree_data[$this->edit_item_id]['parent_id']}\">";
		$content .= "\n<table width=\"90%\" border =\"0\" style=\"border-style: solid; border-color: #000000;  border-width: 1px;font-size: 10pt;\" cellpadding=\"2\" cellspacing=\"2\" align=\"center\">";
		$content .=  $this->getItemMessage($this->edit_item_id,2);
		$content .= "\n<tr><td colspan=\"2\" class=\"steelgraudunkel\" ><b>". _("Element bearbeiten") . "</b></td></tr>";
		$content .= "\n<tr><td class=\"steel1\" width=\"60%\">". _("Name des Elements:") . "&nbsp;"
				. "<input type=\"TEXT\" name=\"edit_name\" size=\"50\" value=\"" . $this->tree->tree_data[$this->edit_item_id]['name']
				. "\"></td><td class=\"steel1\" align=\"left\"><input type=\"image\" "
				. makeButton("absenden","src") . tooltip("Einstellungen �bernehmen") . " border=\"0\">"
				. "&nbsp;<a href=\"" . $this->getSelf("cmd=Cancel&item_id="
				. (($this->mode == "NewItem") ? $this->tree->tree_data[$this->edit_item_id]['parent_id'] : $this->edit_item_id) ) . "\">"
				. "<img " .makeButton("abbrechen","src") . tooltip(_("Aktion abbrechen"))
				. " border=\"0\"></a></td></tr>";
		$content .= "\n<tr><td colspan=\"2\" class=\"steelgraudunkel\"><b>". _("Element mit einer Stud.IP-Einrichtung verlinken") . "</b></td></tr>";
		$content .= "\n<tr><td colspan=\"2\" class=\"steel1\">" . _("Stud.IP-Einrichtung:") . "&nbsp;";
		$content .= "\n<select name=\"edit_studip_object\" onChange=\"document.item_form.edit_name.value=document.item_form.edit_studip_object.options[document.item_form.edit_studip_object.selectedIndex].text;\">";
		$content .= "\n<option value=\"none\" ";
		$content .= ($this->tree->tree_data[$this->edit_item_id]['studip_object']) ? ">" : "selected >";
		$content .= _("Kein Link") . "</option>";
		if ($this->tree->tree_data[$this->edit_item_id]['studip_object']){
			$content .= "\n<option selected value=\"". $this->tree->tree_data[$this->edit_item_id]['studip_object_id'] . ":"
					. $this->tree->tree_data[$this->edit_item_id]['studip_object'] ."\">"
					. $this->tree->tree_data[$this->edit_item_id]['name'] ."</option>";
		}
		if (count($this->search_result)){
			foreach ($this->search_result as $key => $value){
				$content .= "\n<option value=\"" . $key . ":" . $value['studip_object'] . "\">" . $value['name'] . "</option>";
			}
		}
		$content .= "</select></td></tr></form>";
		$content .= "\n<form name=\"link_form\" action=\"" . $this->getSelf("cmd=SearchStudIP&item_id={$this->edit_item_id}") . "\" method=\"POST\"><tr><td class=\"steel1\">" . _("Stud.IP-Einrichtung suchen:") . "&nbsp;";
		$content .= "\n<input type=\"HIDDEN\" name=\"parent_id\" value=\"{$this->tree->tree_data[$this->edit_item_id]['parent_id']}\">";
		$content .= "\n<input type=\"TEXT\" name=\"edit_search\" size=\"30\"></td><td class=\"steel1\" align=\"left\"><input type=\"image\" "
				. makeButton("suchen","src") . tooltip("Einrichtung suchen") . " border=\"0\"></td></tr>";
		$content .= "\n</table>";

		return $content;
	}

	function getEditCatContent($item_id, $cat_snap){
		$content = "\n<table width=\"100%\" cellspacing=\"0\" border=\"0\" style=\"font-size:10pt\"><tr><td class=\"blank\" colspan=\"2\">" . _("Neues Datenfeld anlegen") . "&nbsp;&nbsp;"
				. "<a href=\"" . $this->getSelf("cmd=NewCat&item_id=$item_id") . "\">"
				. "<img " .makeButton("neuanlegen","src") . tooltip(_("Neues Datenfeld anlegen"))
				. " border=\"0\" align=\"absmiddle\"></a></td></tr>";
		$content .= "\n<tr><td colspan=\"2\" class=\"blank\">&nbsp;</td></tr>";
		if ($cat_snap->numRows){
			$content .= "\n<form name=\"cat_form_$item_id\" action=\"" . $this->getSelf("cmd=UpdateCat&item_id=$item_id") . "\" method=\"POST\">";
			while($cat_snap->nextRow()){
				$content .= "\n<tr><td class=\"topic\"><input type=\"TEXT\" style=\"width:90%;font-size:8pt;border:0px\" size=\"30\"  name=\"cat_name[". $cat_snap->getField("kategorie_id")
						. "]\" value=\"" . htmlReady($cat_snap->getField("name")) . "\"><input type=\"HIDDEN\" name=\"cat_prio["
						. $cat_snap->getField("kategorie_id"). "]\" value=\"" . htmlReady($cat_snap->getField("priority")) . "\"></td>"
						. "<td class=\"topic\" width=\"10%\" align=\"right\">";
				if ($cat_snap->pos && $cat_snap->getField("kategorie_id") != "new_entry"){
					$content .= "<a href=\"". $this->getSelf("cmd=OrderCat&direction=up&item_id=$item_id&cat_id=" . $cat_snap->getField("kategorie_id"))
							. "\"><img src=\"".$GLOBALS['ASSETS_URL']."images/move_up.gif\" hspace=\"4\" width=\"13\" height=\"11\" border=\"0\" "
							. tooltip(_("Datenfeld nach oben")) ."></a>";
				}
				if ($cat_snap->pos != $cat_snap->numRows-1 && $cat_snap->getField("kategorie_id") != "new_entry"){
					$content .= "<a href=\"". $this->getSelf("cmd=OrderCat&direction=down&item_id=$item_id&cat_id=" . $cat_snap->getField("kategorie_id"))
							. "\"><img src=\"".$GLOBALS['ASSETS_URL']."images/move_down.gif\" hspace=\"4\" width=\"13\" height=\"11\" border=\"0\" "
							. tooltip(_("Datenfeld nach unten")) ."></a>";
				}
				$content .= "</tr>";
				$content .= "\n<tr><td class=\"blank\" colspan=\"2\"><textarea style=\"width:100%;font-size:8pt;border:0px;\" cols=\"60\" rows=\"2\" name=\"cat_content["
						. htmlReady($cat_snap->getField("kategorie_id")) . "]\" wrap=\"virtual\">"
						. htmlReady($cat_snap->getField("content")) . "</textarea></td></tr>";
				$content .= "<tr><td class=\"blank\" colspan=\"2\"><input type=\"IMAGE\"" .makeButton("uebernehmen","src") . tooltip(_("�nderungen �bernehmen"))
						. " name=\"uebernehmen\" border=\"0\">&nbsp;"
						. "<a href=\"" . $this->getSelf("cmd=DeleteCat&item_id=$item_id&cat_id=" . $cat_snap->getField("kategorie_id"))
						. "\"><img " .makeButton("loeschen","src") . tooltip(_("Datenfeld l�schen"))
						. " border=\"0\"></a></td></tr>";
				$content .= "\n<tr><td colspan=\"2\" class=\"blank\">&nbsp;</td></tr>";
			}
		$content .= "</form>";
		} else {
			$content .= "\n<tr><td class=\"blank\">" . _("Keine weiteren Daten vorhanden!") . "</td></tr>";
		}
		$content .= "</table>";
		return $content;
	}
	function getItemMessage($item_id,$colspan = 1){
		$content = "";
		if ($this->msg[$item_id]){
			$msg = split("�",$this->msg[$item_id]);
			$pics = array('error' => 'x.gif', 'info' => 'ausruf.gif', 'msg' => 'ok.gif');
			$content = "\n<tr><td colspan=\"{$colspan}\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\" style=\"font-size:10pt\">
						<tr><td class=\"blank\" align=\"center\" width=\"25\"><img width=\"16\" height=\"16\" src=\"".$GLOBALS['ASSETS_URL']."images/" . $pics[$msg[0]] . "\" ></td>
						<td class=\"blank\" align=\"left\">" . $msg[1] . "</td></tr>
						</table></td></tr><tr>";
		}
		return $content;
	}

	function getSelf($param){
		$url = $GLOBALS['PHP_SELF'] . "?" . "foo=" . DbView::get_uniqid();
		if ($this->mode)
			$url .= "&mode=" . $this->mode;
		if ($param)
			$url .= "&" . $param;
		$url .= "#anchor";
	return $url;
	}
}
//test
//page_open(array("sess" => "Seminar_Session", "auth" => "Seminar_Auth", "perm" => "Seminar_Perm", "user" => "Seminar_User"));
//include 'lib/include/html_head.inc.php';
//$test = new StudipRangeTreeViewAdmin();
//$test->showTree();
//echo "</table>";
//page_close();
?>
