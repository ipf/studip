<?php
// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// RangeTreeObject.class.php
// Class to handle items in the "range tree"
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

require_once ("$ABSOLUTE_PATH_STUDIP./StudipRangeTree.class.php");
require_once ("$ABSOLUTE_PATH_STUDIP./RangeTreeObjectInst.class.php");
require_once ("$ABSOLUTE_PATH_STUDIP./RangeTreeObjectFak.class.php");

/**
* base class for items in the "range tree"
*
* This class is used for items 
*
* @access	public
* @author	Andr� Noack <noack@data-quest.de>
* @version	$Id$
* @package	
*/
class RangeTreeObject {
	
	/**
	* item_id in range_tree
	*
	* 
	* @access	public
	* @var		string $tree_item_id
	*/
	var $tree_item_id;
	/**
	* References the tree object
	*
	* 
	* @access	private
	* @var		object StudipRangeTree $tree
	*/
	var $tree;
	/**
	* associative array with data from database fields
	*
	* 
	* @access	public
	* @var		array $item_data
	*/var $item_data;
	
	/**
	* Factory method
	*
	* 
	* @access public
	* @static
	* @param	string	$item_id
	* @return	object RangeTreeObject
	*/
	function &GetInstance($item_id){
		$tree = &StudipRangeTree::getInstance();
		$class_name = "RangeTreeObject" . $tree->tree_data[$item_id]['studip_object'];
		return new $class_name($item_id);
	}
	
	/**
	* Constructor
	*
	* Do not use directly, call factory method instead
	* @access private
	* @param	string	$item_id
	*/
	function RangeTreeObject($item_id) {
		$this->tree =& StudipRangeTree::getInstance();
		$this->tree_item_id = $item_id;
		$this->item_data = $this->tree->tree_data[$item_id];
		$this->getCategories();
	}
	
	/**
	* Returns all tree items which are kids of this object
	*
	* 
	* @access public
	* @param	boolean	$as_value_list
	* @return	mixed	returns numeric array if param is false, else comma separated string
	*/
	function getAllItemKids($as_value_list = false){
		return ($as_value_list) ? $this->getValueList($this->tree->getKidsKids($this->tree_item_id)) : $this->tree->getKidsKids($this->tree_item_id);
	}
	
	/**
	* Returns all tree items which are kids of this object and are "real" Stud.IP objects
	*
	* 
	* @access public
	* @param	boolean	$as_value_list
	* @return	mixed	returns numeric array if param is false, else comma separated string
	*/
	function getAllObjectKids($as_value_list = false){
		$all_object_kids = array_merge($this->getInstKids(),$this->getFakKids());
		return ($as_value_list) ? $this->getValueList($all_object_kids) : $all_object_kids;
	}
	
	/**
	* Returns all tree items which are kids of this object and are Stud.IP "Einrichtungen"
	*
	* 
	* @access public
	* @param	boolean	$as_value_list
	* @return	mixed	returns numeric array if param is false, else comma separated string
	*/
	function getInstKids($as_value_list = false){
		$all_kids = $this->tree->getKidsKids($this->tree_item_id);
		$inst_kids = array();
		for ($i = 0; $i < count($all_kids); ++$i){
			if ($this->tree->tree_data[$all_kids[$i]]['studip_object'] == 'inst'){
				$inst_kids[] = $this->tree->tree_data[$all_kids[$i]]['studip_object_id'];
			}
		}
		return ($as_value_list) ? $this->getValueList($inst_kids) : $inst_kids;
	}
	
	/**
	* Returns all tree items which are kids of this object and are Stud.IP "Fakultaeten"
	*
	* 
	* @access public
	* @param	boolean	$as_value_list
	* @return	mixed	returns numeric array if param is false, else comma separated string
	*/
	function getFakKids($as_value_list = false){
		$all_kids = $this->tree->getKidsKids($this->tree_item_id);
		$fak_kids = array();
		for ($i = 0; $i < count($all_kids); ++$i){
			if ($this->tree->tree_data[$all_kids[$i]]['studip_object'] == 'fak'){
				$inst_kids[] = $this->tree->tree_data[$all_kids[$i]]['studip_object_id'];
			}
		}
		return ($as_value_list) ? $this->getValueList($fak_kids) : $fak_kids;
	}
	
	/**
	* Returns Stud.IP range_id of the next "real" object
	*
	* This function is a wrapper for the according function in StudipRangeTree
	* @see StudipRangeTree::getAdminRange()
	* @access public
	* @return	string	is primary key from table "institute" or "fakultaeten"
	*/
	function getAdminRange(){
		return $this->tree->getAdminRange($this->tree_item_id);
	}
	
	/**
	* Returns tree path of the current object
	*
	* This function is a wrapper for the according function in StudipRangeTree
	* @see StudipRangeTree::getItemPath()
	* @access public
	* @return	string	
	*/
	function getItemPath(){
		return $this->tree->getItemPath($this->tree_item_id);
	}
	
	/**
	* extends the $item_data array
	*
	* This function fills the $item_data array with fields from the according database table (is of no use in the base class)
	* @abstract	
	* @access private
	*/
	function initItemDetail(){
		if ($type = $this->item_data['studip_object']){
			$view = new DbView();
			$rs = $view->get_query("view:TREE_OBJECT_DETAIL:".$this->tree->studip_objects[$type]['table'].",".
			$this->tree->studip_objects[$type]['pk'].",".$this->item_data['studip_object_id']);
			if($rs->next_record()){
				$i = 1;
				foreach ($rs->Record as $key => $value) {
					if ( !($i % 2) ) 
					$this->item_data[$key] = $value;
					++$i;
				}
			}
		return true;
		}
	return false;
	}
	
	/**
	* fetch categories of this object from database
	*
	* the categories are appended to the $item_data array, key 'categories', value is object of type DbSnapshot
	* @access private
	* @return	boolean	true if categories were found
	*/
	function getCategories(){
		$view = new DbView();
		$rs = $view->get_query("view:TREE_OBJECT_CAT:" . $this->tree_item_id );
		if (is_object($rs)){
			$this->item_data['categories'] =& new DbSnapshot($rs);
			return true;
		}
	return false;
	}
	
	/**
	* transform numerical array into a comma separated string
	*
	* the result could be used in a SQL query
	* @access private
	* @param	array	$list
	* @return	string
	*/
	
	function getValueList($list){
		$value_list = false;
		if (count($list) == 1) 
			$value_list = "'$list[0]'";
		else 
			$value_list = "'".join("','",$list)."'";
		return $value_list;
	}

}

?>
