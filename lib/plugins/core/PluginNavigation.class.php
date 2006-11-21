<?php

/**
 *
 *	@author Dennis Reil, <dennis.reil@offis.de>
 *	@version $Revision$
 *	@package pluginengine
 * @subpackage core
 *
 */

class PluginNavigation {

	var $displayname;
	var $link;
	var $submenu;
	var $active;
	var $linkparams;
	var $icon;

    function PluginNavigation() {
    	$this->displayname = "";
    	$this->link = "";
    	$this->submenu = array();
    	$this->active = false;
    	$this->linkparams = array();
    	$this->icon = "";
    }

    /**
     * Getter und Setter zu den Attributen
     */


    /**
    * Returns the displayname, usually used for creating a link
    */
    function getDisplayname(){
    	return $this->displayname;
    }

    /**
    *
    * @return array with parameters for the link generated by the pluginengine
    */
    function getLinkParams(){

    	return array("plugin_subnavi_params" => urlencode($this->link));
    }

    /**
    * Add a new parameter for the link generation. If the key is already
    */
    function addLinkParam($key, $value){
    	$this->linkparams[$key] = $value;
    }

    function getSubmenu(){
    	return $this->submenu;
    }

    function isActive(){
    	return $this->active;
    }

    function setDisplayname($newdisplayname){
    	$this->displayname = $newdisplayname;
    }

    function setLinkParam($newlink){
    	$this->link = $newlink;
    }

    function addSubmenu($subnavigation){
    	if (is_a($subnavigation,'PluginNavigation') || is_subclass_of($subnavigation,'PluginNavigation')){
	    	//$subnavigation->setPluginpath($this->pluginpath);
    		$this->submenu[] = $subnavigation;

    	}
    }

    function removeSubmenu($subnavigation){
    	$this->submenu = array_diff($this->submenu,$subnavigation);
    }


    function setActive($value=true){
    	$this->active = $value;
    }

    /**
     * L�scht das komplette Untermen�
     */
    function clearSubmenu(){
    	$this->submenu = array();
    }

     function getIcon(){
    	return $this->icon;
    }

    function setIcon($newicon){
    	$this->icon = trim($newicon);
    }

    function hasIcon(){
    	return (strlen($this->icon) > 0);
    }
}
?>
