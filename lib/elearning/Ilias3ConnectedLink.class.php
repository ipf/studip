<?php
# Lifter002: TODO
# Lifter007: TODO
# Lifter003: TODO
require_once "ConnectedLink.class.php";

/**
* class to generate links to ILIAS 3
*
* This class contains methods to generate links to ILIAS 3.
*
* @author   Arne Schr�der <schroeder@data-quest.de>
* @access   public
* @modulegroup  elearning_interface_modules
* @module       Ilias3ConnectedLink
* @package  ELearning-Interface
*/
class Ilias3ConnectedLink extends ConnectedLink
{
    /**
    * constructor
    *
    * init class.
    * @access 
    * @param string $cms system-type
    */ 
    function Ilias3ConnectedLink($cms)
    {
        parent::ConnectedLink($cms);
        $this->cms_link = "ilias3_referrer.php";
    }

    /**
    * get user module links
    *
    * returns content module links for user
    * @access public
    * @return string html-code
    */
    function getUserModuleLinks()
    {
        global $connected_cms, $view, $search_key, $cms_select, $current_module;
        
        if ($connected_cms[$this->cms_type]->isAuthNecessary() AND (! $connected_cms[$this->cms_type]->user->isConnected()))
        {   
            $output .= $this->getNewAccountLink();
        }
        else
        {
            if (! $connected_cms[$this->cms_type]->content_module[$current_module]->isDummy() )
            {
                if ($connected_cms[$this->cms_type]->content_module[$current_module]->isAllowed(OPERATION_READ))
                {
                    $output .= "<a href=\"" . $this->cms_link . "?"
                        . "client_id=" . $connected_cms[$this->cms_type]->getClientId()
                        . "&cms_select=" . $this->cms_type
//                      . "&sess_id=" . $connected_cms[$this->cms_type]->user->getSessionId()
                        . "&ref_id=" . $connected_cms[$this->cms_type]->content_module[$current_module]->getId()
                        . "&type=" . $connected_cms[$this->cms_type]->content_module[$current_module]->getModuleType()
                        . $auth_data
                        . "&target=start\" target=\"_blank\">";
                    $output .= makeButton("starten", "img");
                    $output .= "</a>&nbsp;";
                }
                if ($connected_cms[$this->cms_type]->content_module[$current_module]->isAllowed(OPERATION_WRITE))
                {
                    $output .= "<a href=\"" . $this->cms_link . "?"
                        . "client_id=" . $connected_cms[$this->cms_type]->getClientId()
                        . "&cms_select=" . $this->cms_type
//                      . "&sess_id=" . $connected_cms[$this->cms_type]->user->getSessionId()
                        . "&ref_id=" . $connected_cms[$this->cms_type]->content_module[$current_module]->getId()
                        . "&type=" . $connected_cms[$this->cms_type]->content_module[$current_module]->getModuleType()
                        . $auth_data
                        . "&target=edit\" target=\"_blank\">";
                    $output .= makeButton("bearbeiten", "img");
                    $output .= "</a>&nbsp;";
                }
            }
        }

        return $output;
    }

    /**
    * get admin module links
    *
    * returns links add or remove a module from course
    * @access public
    * @return string returns html-code
    */
    function getAdminModuleLinks()
    {
        global $connected_cms, $view, $search_key, $cms_select, $current_module;

        $output .= "<form method=\"POST\" action=\"" . $GLOBALS["PHP_SELF"] . "\">\n";
        $output .= "<input type=\"HIDDEN\" name=\"view\" value=\"" . $view . "\">\n";
        $output .= "<input type=\"HIDDEN\" name=\"search_key\" value=\"" . $search_key . "\">\n";
        $output .= "<input type=\"HIDDEN\" name=\"cms_select\" value=\"" . $cms_select . "\">\n";
        $output .= "<input type=\"HIDDEN\" name=\"module_type\" value=\"" . $connected_cms[$this->cms_type]->content_module[$current_module]->getModuleType() . "\">\n";
        $output .= "<input type=\"HIDDEN\" name=\"module_id\" value=\"" . $connected_cms[$this->cms_type]->content_module[$current_module]->getId() . "\">\n";
        $output .= "<input type=\"HIDDEN\" name=\"module_system_type\" value=\"" . $this->cms_type . "\">\n";

        if ($connected_cms[$this->cms_type]->content_module[$current_module]->isConnected())
            $output .= "&nbsp;<input type=\"IMAGE\" " . makeButton("entfernen", "src") . " border=0 value=\"" . _("Entfernen") . "\" name=\"remove\">";
        elseif ($connected_cms[$this->cms_type]->content_module[$current_module]->isAllowed(OPERATION_WRITE))
        {   
            $output .= "<div align=\"left\"><input type=\"CHECKBOX\" value=\"1\" name=\"write_permission\" style=\"vertical-align:middle\">";
            $output .= _("Mit Schreibrechten f&uuml;r alle Dozenten/Tutoren dieser Veranstaltung") . "<br>";
            $output .= "<input type=\"CHECKBOX\" value=\"1\" style=\"vertical-align:middle\" name=\"write_permission_autor\">";
            $output .= _("Mit Schreibrechten f&uuml;r alle Teilnehmer dieser Veranstaltung") . "</div>";
            $output .= "<input type=\"IMAGE\" " . makeButton("hinzufuegen", "src") . " border=0 value=\"" . _("Hinzuf&uuml;gen") . "\" name=\"add\"><br>";
        }
        else
            $output .= "&nbsp;<input type=\"IMAGE\" " . makeButton("hinzufuegen", "src") . " border=0 value=\"" . _("Hinzuf&uuml;gen") . "\" name=\"add\">";
        $output .= "</form>";

        return $output;
//      $output .= parent::getAdminModuleLinks();
    }
    
    /**
    * get new module link
    *
    * returns link to create a new module if allowed
    * @access public
    * @return string returns html-code or false
    */
    function getNewModuleLink()
    {
        global $connected_cms, $module_type, $auth, $PHP_SELF;
        $output = "\n";
//      echo "NML.";
        if (($GLOBALS["module_type_" . $this->cms_type] != ""))
        {
//          echo "TYPE.";
            if ($connected_cms[$this->cms_type]->user->category == "")
            {
//              echo "NoCat.";
                $connected_cms[$this->cms_type]->user->newUserCategory();
                if ($connected_cms[$this->cms_type]->user->category == false)
                    return $output;
            }
            $output = "&nbsp;<a href=\"" . $this->cms_link . "?"
                . "client_id=" . $connected_cms[$this->cms_type]->getClientId()
                . "&cms_select=" . $this->cms_type
//              . "&sess_id=" . $connected_cms[$this->cms_type]->user->getSessionId()
                . "&ref_id=" . $connected_cms[$this->cms_type]->user->category
                . $auth_data
                . "&type=" . $GLOBALS["module_type_" . $this->cms_type] . "&target=new\" target=\"_blank\">";
            $output .=  makeButton("neuanlegen", "img");
            $output .= "</a>";
//          echo $output . ".";
        }
        $user_crs_role = $connected_cms[$this->cms_type]->crs_roles[$auth->auth["perm"]];
        if ($user_crs_role=="admin")
            return $output;
        else 
            return false;
    }

    /**
    * get start page link
    *
    * returns link to ilias start-page
    * @access public
    * @return string returns html-code or false
    */
    function getStartpageLink($name)
    {
        global $connected_cms, $module_type, $auth, $PHP_SELF;

        if ($connected_cms[$this->cms_type]->user->isConnected())
        {
            $output = "&nbsp;<a href=\"" . $this->cms_link . "?"
                . "client_id=" . $connected_cms[$this->cms_type]->getClientId()
                . "&cms_select=" . $this->cms_type
//              . "&sess_id=" . $connected_cms[$this->cms_type]->user->getSessionId()
                . "&target=login\" target=\"_blank\">";
            $output .=  $name;
            $output .= "</a>";
        }
        return $output;
    }
}
?>
