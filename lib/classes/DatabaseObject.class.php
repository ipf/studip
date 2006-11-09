<?php
// +--------------------------------------------------------------------------+
// This file is part of Stud.IP
// DatabaseObject.class.php
//
// Class to provide basic properties of an DatabseObject in Stud.IP
//
// Copyright (c) 2003 Alexander Willner <mail@AlexanderWillner.de>
// +--------------------------------------------------------------------------+
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or any later version.
// +--------------------------------------------------------------------------+
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// +--------------------------------------------------------------------------+


# Include all required files ================================================ #
require_once ("lib/classes/Object.class.php");
# ====================================================== end: including files #


# Define all required constants ============================================= #
/**
 * @const INSTANCEOF_STUDIPOBJECT Is instance of a studip object
 * @access public
 */
define ("INSTANCEOF_DATABASEOBJECT", "DatabaseObject");
# =========================================================================== #


/**
 * DatabaseObject.class.php
 *
 * Class to provide basic properties of an DatabaseObject in Stud.IP
 *
 * @author      Alexander Willner <mail@alexanderwillner.de>
 * @version     $Id$
 * @copyright   2003 Stud.IP-Project
 * @access      public
 * @package     studip_core
 * @modulegroup core
 */
class DatabaseObject extends Object {

# Define all required variables ============================================= #
  /**
   * Databaseobject
   * @access   private
   * @var      object $db
   */
  var $db;
# =========================================================================== #


# Define constructor and destructor ========================================= #
   /**
    * Constructor
    * @access   public
    */
   function DatabaseObject () {
 
     /* For good OOP: Call constructor ------------------------------------- */
     parent::Object ();
     $this->instanceof = INSTANCEOF_DATABASEOBJECT;
     /* -------------------------------------------------------------------- */

     /* Set default values ------------------------------------------------- */
     $this->db = DatabaseObject::getDBObject ();
     /* -------------------------------------------------------------------- */
   }
# =========================================================================== #


# Define public functions =================================================== #
    /**
     * Get a DB-Object. Helpfull for static methods
     * @return  object  A DB-Object
     * @access public
     */
    function getDBObject () {
        return new DB_Seminar ();
    }
   /**
    * Gets the objectID
    * @access  public
    * @return  string  The objectID
    */
   function getObjectID () {
     return $this->objectID;
   }

   /**
    * Sets the objectID
    * @access  public
    * @param   String  $objectID  The object ID
    */
   function setObjectID ($objectID) {
     if (empty ($objectID))
       throwError (1, _("Die ObjectID darf nicht leer sein."));
     else
       $this->objectID = $objectID;
   }

   /**
    * Gets the authorID
    * @access  public
    * @return  string  The authorID
    */
   function getAuthorID () {
     return $this->authorID;
   }

   /**
    * Sets the authorID
    * @access  public
    * @param   String  $authorID  The author ID
    */
   function setAuthorID ($authorID) {
     if (empty ($authorID))
       throwError (1, _("Die AuthorID darf nicht leer sein."));
     else
       $this->authorID = $authorID;
   }

   /**
    * Gets the rangeID
    * @access  public
    * @return  string  The rangeID
    */
   function getRangeID () {
     return $this->objectID;
   }

   /**
    * Sets the rangeID
    * @access  public
    * @param   String  $rangeID  The range ID
    */
   function setRangeID ($rangeID) {
     if (empty ($rangeID))
       throwError (1, _("Die RangeID darf nicht leer sein."));
     else
       $this->rangeID = $rangeID;
   }
# =========================================================================== #


# Define static functions =================================================== #

# =========================================================================== #


# Define private functions ================================================== #

# =========================================================================== #

}
?>
