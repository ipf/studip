<?
# Lifter002: TODO
# Lifter007: TODO
# Lifter003: TODO
# Lifter010: TODO
/**
* ResourceObject.class.php
* 
* class for a resource-object
* 
*
* @author       Cornelis Kater <ckater@gwdg.de>, Suchi & Berg GmbH <info@data-quest.de>
* @access       public
* @modulegroup      resources
* @module       ResourceObject.class.php
* @package      resources
*/

// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// ResourceObject.class.php
// Klasse fuer ein Ressourcen-Object
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

require_once $GLOBALS['RELATIVE_PATH_RESOURCES'] . "/lib/AssignObject.class.php";


/*****************************************************************************
ResourceObject, zentrale Klasse der Ressourcen Objekte
/*****************************************************************************/
class ResourceObject {
    
    function Factory(){
        static $ressource_object_pool;
        $argn = func_num_args();
        if ($argn == 1){
            if ( ($id = func_get_arg(0)) ){
                if (is_object($ressource_object_pool[$id]) && $ressource_object_pool[$id]->getId() == $id){
                    return $ressource_object_pool[$id];
                } else {
                    $ressource_object_pool[$id] = new ResourceObject($id);
                    return $ressource_object_pool[$id];
                }
            }
        }
        return new ResourceObject(func_get_args());
    }
    
    var $id;                //resource_id des Objects;
    var $db;                //Datenbankanbindung;
    var $name;              //Name des Objects
    var $description;           //Beschreibung des Objects;
    var $owner_id;              //Owner_id;
    var $category_id;           //Die Kategorie des Objects
    var $category_name;         //name of the assigned catgory
    var $category_iconnr;           //iconnumber of the assigned catgory
    var $is_room = null;
    var $is_parent = null;
    var $my_state = null;
    
    //Konstruktor
    /*
    function ResourceObject($name='', $description='', $parent_bind='', $root_id='', $parent_id='', $category_id='', $owner_id='', $id = '') {
        global $user;
        
        $this->user_id = $user->id;
        $this->db=new DB_Seminar;
        
        if(func_num_args() == 1) {
            $id = func_get_arg(0);
            $this->restore($id);
        } elseif(func_num_args() == 7) {
            $this->name = func_get_arg(0);
            $this->description = func_get_arg(1);
            $this->parent_bind = func_get_arg(2);
            $this->root_id = func_get_arg(3);
            $this->parent_id = func_get_arg(4);
            $this->category_id = func_get_arg(5);
            $this->owner_id = func_get_arg(6);
            if (!$this->id)
                $this->id=$this->createId();
            if (!$this->root_id) {
                $this->root_id = $this->id;
                $this->parent_id = "0";
            }
            $this->changeFlg=FALSE;

        }
    }
    */
    
    function ResourceObject($argv) {
        global $user;
        
        $this->user_id = $user->id;
        $this->db = new DB_Seminar;
        
        if($argv && !is_array($argv)) {
            $id = $argv;
            $this->restore($id);
        } elseif (count($argv) == 7) {
            $this->name = $argv[0];
            $this->description = $argv[1];
            $this->parent_bind = $argv[2];
            $this->root_id = $argv[3];
            $this->parent_id = $argv[4];
            $this->category_id = $argv[5];
            $this->owner_id = $argv[6];
            if (!$this->id)
                $this->id=$this->createId();
            if (!$this->root_id) {
                $this->root_id = $this->id;
                $this->parent_id = "0";
            }
            $this->chng_flag=FALSE;

        }
    }
    
    function createId() {
        return md5(uniqid("DuschDas",1));
    }

    function create() {
        $query = sprintf("SELECT resource_id FROM resources_objects WHERE resource_id ='%s'", $this->id);
        $this->db->query($query);
        if ($this->db->nf()) {
            $this->chng_flag=TRUE;      
            return $this->store();
        } else
            return $this->store(TRUE);
    }
    
    function setName($name){
        $this->name= $name;
        $this->chng_flag = TRUE;
    }

    function setDescription($description){
        $this->description= $description;
        $this->chng_flag = TRUE;
    }

    function setCategoryId($category_id){
        $this->category_id=$category_id;
        $this->chng_flag = TRUE;
    }

    function setMultipleAssign($value){
        if ($value) {
            $this->multiple_assign = true;
        } else {
            // multiple assigns where allowed and are not allowed anymore - update
            if ($this->multiple_assign) {
                // update the table resources_temporary_events or bad things will happen
                $this->updateAllAssigns();
            }
            
            $this->multiple_assign = false;
        }
        
        $this->chng_flag = TRUE;
    }

    function setParentBind($parent_bind){
        if ($parent_bind==on)
            $this->parent_bind=TRUE;
        else
            $this->parent_bind=FALSE;
        $this->chng_flag = TRUE;
    }

    function setLockable($lockable){
        if ($lockable == on)
            $this->lockable=TRUE;
        else
            $this->lockable=FALSE;
        $this->chng_flag = TRUE;
    }

    function setOwnerId($owner_id){
        $old_value = $this->owner_id;
        $this->owner_id=$owner_id;
        $this->chng_flag = TRUE;
        if ($old_value != $owner_id)
            return TRUE;
        else
            return FALSE;
    }
    
    function setInstitutId($institut_id){
        $this->institut_id=$institut_id;
        $this->chng_flag = TRUE;
    }


    function getId() {
        return $this->id;
    }

    function getRootId() {
        return $this->root_id;
    }

    function getParentId() {
        return $this->parent_id;
    }

    function getName() {
        return $this->name;
    }

    function getCategoryName() {
        return $this->category_name;
    }

    function getCategoryIconnr() {
        return $this->category_iconnr;
    }

    function getCategoryId() {
        return $this->category_id;
    }

    function getDescription() {
        return $this->description;
    }

    function getOwnerId() {
        return $this->owner_id;
    }

    function getInstitutId() {
        return $this->institut_id;
    }
    
    function getMultipleAssign() {
        return $this->multiple_assign;
    }
    
    function getParentBind() {
        return $this->parent_bind;
    }
    
    function getOwnerType($id='') {
        if (!$id)
            $id=$this->owner_id;

        //Is it a global?
        if ($id == "global"){
            return "global";
        } else if ($id == "all"){
            return "all";
        } else {
            $type = get_object_type($id);
            return ($type == "fak") ? "inst" : $type;
        }
    }
    
    function getOrgaName ($explain=FALSE, $id='') {
        if (!$id)
            $id=$this->institut_id;

        $query = sprintf("SELECT Name FROM Institute WHERE Institut_id='%s' ",$id);
        $this->db->query($query);
        
        if ($this->db->next_record())
            if (!$explain)
                return $this->db->f("Name");
            else
                return $this->db->f("Name")." ("._("Einrichtung").")";  
    }
    
    function getOwnerName($explain=FALSE, $id='') {
        if (!$id)
            $id=$this->owner_id;

        switch ($this->getOwnerType($id)) {
            case "all":
                if (!$explain)
                    return _("jederR");
                else
                    return _("jedeR (alle Nutzenden)");
            break;
            case "global":
                if (!$explain)
                    return _("Global");
                else
                    return _("Global (zentral verwaltet)");
            break;
            case "user":
                if (!$explain)
                    return get_fullname($id,'full');
                else
                    return get_fullname($id,'full')." ("._("NutzerIn").")";
            break;
            case "inst":
                $query = sprintf("SELECT Name FROM Institute WHERE Institut_id='%s' ",$id);
                $this->db->query($query);
                if ($this->db->next_record())
                    if (!$explain)
                        return $this->db->f("Name");
                    else
                        return $this->db->f("Name")." ("._("Einrichtung").")";
            break;
            case "sem":
                $query = sprintf("SELECT Name FROM seminare WHERE Seminar_id='%s' ",$id);
                $this->db->query($query);
                if ($this->db->next_record())
                    if (!$explain)
                        return $this->db->f("Name");
                    else
                        return $this->db->f("Name"). " ("._("Veranstaltung").")";
            break;
        }
    }
    
    /**
     * This function creates a link to show an room in a new window/tab/popup. This function should not be used from outside of this class anymore
     *
     * @param bool   $quick_view
     * @param string $view
     * @param string $view_mode
     * @param int    $timestamp jump to this date in the room-assignment-plan
     *
     * @return string href-part of a link
     */
    private function getLink($quick_view = FALSE, $view ="view_schedule", $view_mode = "no_nav", $timestamp = FALSE) {
        if (func_num_args() == 1) {
            $timestamp = func_get_arg(0);
        }
        return URLHelper::getLink(sprintf ("resources.php?actual_object=%s&%sview=%s&%sview_mode=%s%s", $this->id, ($quick_view) ? "quick_" : "", $view, ($quick_view) ? "quick_" : "", $view_mode, ($timestamp > 0) ? "&start_time=".$timestamp : ""));
    }
    
    function getFormattedLink($javaScript = TRUE, $target_new = TRUE, $quick_view = TRUE, $view ="view_schedule", $view_mode = "no_nav", $timestamp = FALSE, $link_text = FALSE) {
        global $auth;
        
        if (func_num_args() == 1) {
            $timestamp = func_get_arg(0);
            $javaScript = TRUE;
        }

        if (func_num_args() == 2) {
            $timestamp = func_get_arg(0);
            $link_text = func_get_arg(1);
            $javaScript = TRUE;
        }

        
        if ($this->id) {
            if ((!$javaScript) || (!$auth->auth["jscript"]))
                return "<a ".(($target_new) ? "target=\"_blank\"" : "")." href=\"".$this->getLink($quick_view, $view, $view_mode, ($timestamp > 0) ? $timestamp : FALSE)."\">".(($link_text) ? $link_text : $this->getName())."</a>";
            else
                return "<a href=\"javascript:void(null)\" onClick=\"window.open('".$this->getLink($quick_view, $view, $view_mode, ($timestamp > 0) ? $timestamp : FALSE)."','','scrollbars=yes,left=10,top=10,width=1000,height=680,resizable=yes')\" >".(($link_text) ? $link_text : $this->getName())."</a>";
        } else
            return FALSE;
    }
    
    function getOrgaLink ($id='') {
        if (!$id)
            $id=$this->institut_id;
        
        return  sprintf ("institut_main.php?auswahl=%s",$id);   
    }

    
    function getOwnerLink($id='') {
        global $PHP_SELF;
        
        if (!$id)
            $id=$this->owner_id;
        switch ($this->getOwnerType($id)) {
            case "global":
                return '#a';
            case "all":
                return '#a';
            break;
            case "user":
                return  sprintf ("about.php?username=%s",get_username($id));
            break;
            case "inst":
                return  sprintf ("institut_main.php?auswahl=%s",$id);
            break;
            case "sem":
                return  sprintf ("seminar_main.php?auswahl=%s",$id);
            break;
        }
    }
    
    function getPlainProperties($only_requestable = FALSE) {
        $query = sprintf("SELECT b.name, a.state, b.type, b.options FROM resources_objects_properties a LEFT JOIN resources_properties b USING (property_id) LEFT JOIN resources_categories_properties c USING (property_id) WHERE resource_id = '%s' AND c.category_id = '%s' %s ORDER BY b.name", $this->id, $this->category_id, ($only_requestable) ? "AND requestable = '1'" : "");     
        $this->db->query($query);
        
        $i=0;
        while ($this->db->next_record()) {
            if ($i)
                $plain_properties .= " \n";
            $plain_properties .= $this->db->f("name").": ".(($this->db->f("type") == "bool") ? (($this->db->f("state")) ? $this->db->f("options") : "-") : $this->db->f("state"));
            $i++;
        }
        
        return $plain_properties;
    }
    

    function getSeats() {
        if (is_null($this->my_state)){
            $query = sprintf("SELECT a.state FROM resources_objects_properties a LEFT JOIN resources_properties b USING (property_id) LEFT JOIN resources_categories_properties c USING (property_id) WHERE resource_id = '%s' AND c.category_id = '%s' AND b.system = 2 ORDER BY b.name", $this->id, $this->category_id);
            $this->db->query($query);
            if ($this->db->next_record()) {
                $this->my_state = $this->db->f("state");
            }
        }
        return is_null($this->my_state) ? false : $this->my_state;
    }

    function isUnchanged() {
        if ($this->mkdate == $this->chdate)
            return TRUE;
        else
            return FALSE;
    }

    function isDeletable() {
        return (!$this->isParent() && !$this->isAssigned());
    }

    function isParent() {
        if (is_null($this->is_parent)){
            $query = sprintf ("SELECT resource_id FROM resources_objects WHERE parent_id = '%s' LIMIT 1", $this->id);
            $this->db->query($query);
            if ($this->db->next_record()){
                $this->is_parent = true;
            }
        }
        return (!is_null($this->is_parent));
    }
    
    function isAssigned() {
        if (is_null($this->is_assigned)){
            $query = sprintf ("SELECT assign_id FROM resources_assign WHERE resource_id = '%s' LIMIT 1", $this->id);
            $this->db->query($query);
            if ($this->db->next_record()){
                $this->is_assigned = true;
            }
        }
        return (!is_null($this->is_assigned));
    }
    
    function isRoom() {
        if (is_null($this->is_room)){
            $query = sprintf ("SELECT is_room FROM resources_objects LEFT JOIN resources_categories USING (category_id) WHERE resource_id = '%s'", $this->id);
            $this->db->query($query);
            $this->db->next_record();
            if ($this->db->f("is_room")){
                $this->is_room = true;
            }
        }
        return (!is_null($this->is_room));
    }
    
    function isLocked() {
        if (($this->isRoom()) 
        && ($this->isLockable())
        && (isLockPeriod("edit")))
            return isLockPeriod("edit");
        else
            return FALSE;
    }

    function isLockable() {
        return $this->lockable;
    }
    
    function flushProperties() {
        $query = sprintf("DELETE FROM resources_objects_properties WHERE resource_id='%s' ",$this->id);
        $this->db->query($query);
        if ($this->db->affected_rows())
            return TRUE;
        else 
            return FALSE;
    }
    
    function storeProperty ($property_id, $state) {
        $query = sprintf("INSERT INTO resources_objects_properties SET resource_id='%s', property_id='%s', state='%s' ",$this->id, $property_id, $state);
        $this->db->query($query);
        if ($this->db->affected_rows())
            return TRUE;
        else 
            return FALSE;
    }
    
    function deletePerms ($user_id) {
        $query = sprintf("DELETE FROM resources_user_resources WHERE user_id='%s' AND resource_id='%s'",$user_id, $this->id);
        $this->db->query($query);
        if ($this->db->affected_rows())
            return TRUE;
        else 
            return FALSE;
    }
    
    function storePerms ($user_id, $perms='') {
        $query = sprintf("SELECT user_id FROM resources_user_resources WHERE user_id='%s' AND resource_id='%s'",$user_id, $this->id);
        $this->db->query($query);
        
        //User_id zwingend notwendig
        if (!$user_id)
            return FALSE;
        
        //neuer Eintrag 
        if (!$this->db->num_rows()) {
            if (!$perms)
                $perms="autor";
            $query = sprintf("INSERT INTO resources_user_resources SET perms='%s', user_id='%s', resource_id='%s'",$perms, $user_id, $this->id);
            $this->db->query($query);
            if ($this->db->affected_rows())
                return TRUE;
            else 
                return FALSE;

        //alter Eintrag wird veraendert
        } elseif ($perms) {
            $query = sprintf("UPDATE resources_user_resources SET perms='%s' WHERE user_id='%s' AND resource_id='%s'",$perms, $user_id, $this->id);
            $this->db->query($query);
            if ($this->db->affected_rows())
                return TRUE;
            else 
                return FALSE;
        } else
            return FALSE;
    }
    
    function restore($id='') {

        if(func_num_args() == 1)
            $query = sprintf("SELECT resources_objects.*, resources_categories.name AS category_name, resources_categories.iconnr FROM resources_objects LEFT JOIN resources_categories USING (category_id) WHERE resource_id='%s' ",$id);
        else 
            $query = sprintf("SELECT resources_objects.*, resources_categories.name AS category_name, resources_categories.iconnr FROM resources_objects LEFT JOIN resources_categories USING (category_id) WHERE resource_id='%s' ",$this->id);
        $this->db->query($query);
        
        if($this->db->next_record()) {
            $this->id = $id;
            $this->name = $this->db->f("name");
            $this->description = $this->db->f("description");
            $this->owner_id = $this->db->f("owner_id");
            $this->institut_id = $this->db->f("institut_id");
            $this->category_id = $this->db->f("category_id");
            $this->category_name = $this->db->f("category_name");
            $this->category_iconnr = $this->db->f("iconnr");
            $this->parent_id =$this->db->f("parent_id");
            $this->lockable = $this->db->f("lockable");
            $this->multiple_assign = $this->db->f("multiple_assign");
            $this->root_id =$this->db->f("root_id");
            $this->mkdate =$this->db->f("mkdate");
            $this->chdate =$this->db->f("chdate");
            
            if ($this->db->f("parent_bind"))
                $this->parent_bind = TRUE;
            else
                $this->parent_bind = FALSE;
            
            return TRUE;
        }
        return FALSE;
    }

    function store($create=''){
        // Natuerlich nur Speichern, wenn sich was gaendert hat oder das Object neu angelegt wird
        if(($this->chng_flag) || ($create)) {
            $chdate = time();
            $mkdate = time();
            
            if($create) {
                //create level value
                if (!$this->parent_id)
                    $level=0;
                else {
                    $query = sprintf("SELECT level FROM resources_objects WHERE resource_id = '%s'", $this->parent_id);
                    $this->db->query($query);
                    $this->db->next_record();
                    $level = $this->db->f("level") +1;
                }

                $query = sprintf("INSERT INTO resources_objects SET resource_id='%s', root_id='%s', " 
                    ."parent_id='%s', category_id='%s', owner_id='%s', institut_id = '%s', level='%s', name='%s', description='%s', "
                    ."lockable='%s', multiple_assign='%s',mkdate='%s', chdate='%s' "
                             , $this->id, $this->root_id, $this->parent_id, $this->category_id, $this->owner_id, $this->institut_id
                             , $level, $this->name, $this->description, $this->lockable, $this->multiple_assign 
                             , $mkdate, $chdate);
            } else
                $query = sprintf("UPDATE resources_objects SET root_id='%s'," 
                    ."parent_id='%s', category_id='%s', owner_id='%s', institut_id = '%s', name='%s', description='%s', "
                    ."lockable='%s', multiple_assign='%s' WHERE resource_id='%s' "
                             , $this->root_id, $this->parent_id, $this->category_id, $this->owner_id, $this->institut_id
                             , $this->name, $this->description, $this->lockable, $this->multiple_assign 
                             , $this->id);
            $this->db->query($query);

            if ($this->db->affected_rows()) {
                $query = sprintf("UPDATE resources_objects SET chdate='%s' WHERE resource_id='%s' ", $chdate, $this->id);
                $this->db->query($query);
                return TRUE;
            } else
                return FALSE;
        }
        return FALSE;
    }

    function delete() {
        $this->deleteResourceRecursive ($this->id);
    }
    
    //delete section, very privat :)
    
    //private
    function deleteAllAssigns($id='') {
        if (!$id)
            $id = $this->id;
        $query = sprintf("SELECT assign_id FROM resources_assign WHERE resource_id = '%s' ", $id);
        $this->db->query($query);
        while ($this->db->next_record()) {
            $killAssign = AssignObject::Factory($this->db->f("assign_id"));
            $killAssign->delete();
        }
    }

    /**
     * update all assigns for this resource
     * 
     * @throws Exception 
     */
    function updateAllAssigns() {
        if (!$this->id) {
            throw new Exception('Missing resource-ID!');
        }

        $stmt = DBManager::get()->prepare("SELECT assign_id FROM resources_assign
            WHERE resource_id = ?");
        $stmt->execute(array($this->id));
        
        while ($assign_id = $stmt->fetchColumn()) {
            $assign = AssignObject::Factory($assign_id);
            $assign->updateResourcesTemporaryEvents();
        }
    }

    //private
    function deleteAllPerms($id='') {
        if (!$id)
            $id = $this->id;
        $query = sprintf("DELETE FROM resources_user_resources WHERE resource_id = '%s' ", $id);
        $this->db->query($query);           
    }

    function deleteResourceRecursive($id) {
        $db = new DB_Seminar;
        $db2 = new DB_Seminar;
        
        //subcurse to subordinated resource-levels
        $query = sprintf("SELECT resource_id FROM resources_objects WHERE parent_id = '%s' ", $id);
        $db->query($query);
            
        while ($db->next_record()) 
            $this->deleteResourceRecursive($db->f("resource_id"), $recursive);

        $this->deleteAllAssigns($id);
        $this->deleteAllPerms($id);
        $this->flushProperties($id);
    
        $query2 = sprintf("DELETE FROM resources_objects WHERE resource_id = '%s' ", $id);
        $db2->query($query2);           
    }
    
    function getPathArray($include_self = false){
        $id = $this->getId();
        if ($include_self){
            $result_arr[$id] = $this->getName();
        } else {
            $result_arr = array();
        }
        while($id){
            $query = sprintf ("SELECT name, parent_id, resource_id, owner_id FROM resources_objects WHERE resource_id = '%s' ", $id);
            $this->db->query($query);
            if ($this->db->next_record()){
                $id = $this->db->f("parent_id");
                $result_arr[$this->db->f("resource_id")] = $this->db->f("name");
            } else {
                break;
            }
        }
        return $result_arr;
    }
    
    function getPathToString($include_self = false, $delimeter = '/'){
        return join($delimeter, array_reverse(array_values($this->getPathArray($include_self))));
    }
}
