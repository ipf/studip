<?php
# Lifter002: TODO
# Lifter007: TODO

/**
 *
 *	@author Dennis Reil, <dennis.reil@offis.de>
 *	@version $Revision$
 *	@package pluginengine
 * @subpackage core
 *
 */

class PluginNavigation extends StudipPluginNavigation {

  var $active = FALSE;
  var $linkparams = array();


  /**
   * The cmd of this Navigation object.
   *
   * @access private
   * @var string
   */
  var $cmd = 'show';


  /**
   * Returns the cmd of this Navigation object.
   *
   * @return string  the cmd
   */
  function getCommand() {
    return $this->cmd;
  }


  /**
   * Sets the cmd of this Navigation's object.
   *
   * @param  string  the cmd
   *
   * @return void
   */
  function setCommand($cmd) {
    $this->cmd = $cmd;
  }


    /**
     * Returns the link used by this Navigation object.
     */
    function getLink() {
        if (isset($this->cmd)) {
            return PluginEngine::getLink($this->plugin, $this->getLinkParams(),
                                         $this->cmd);
        } else {
            return PluginEngine::getLink($this->plugin, $this->getLinkParams());
        }
    }

    /**
     * Getter und Setter zu den Attributen
     */


    /**
    *
    * @return array with parameters for the link generated by the pluginengine
    */
    function getLinkParams(){
	return $this->linkparams;
    }

    /**
    * Add a new parameter for the link generation. If the key is already
    */
    function addLinkParam($key, $value){
    	$this->linkparams[$key] = $value;
    }


    function isActive() {
    	if (strtolower($this->cmd) == strtolower($this->plugin->getCommand())) {
    		foreach ($this->linkparams as $key => $val) {
    			if (!isset($_REQUEST[$key]) || $_REQUEST[$key] != $val) {
    				return false;
    			}
    		}
    		return true;
    	}
    	return false;
    }


    /**
     * @deprecated
     */
    function setLinkParam($newlink){
	$this->addLinkParam('plugin_subnavi_params', $newlink);
    }


    /**
     * @deprecated
     */
    function setActive($value=true){
    	$this->active = $value;
    }

    /**
     * L�scht das komplette Untermen�
     */
    function clearSubmenu(){
    	$this->submenu = array();
    }
}
?>
