<?php
# Lifter002: TODO
# Lifter007: TODO
# Lifter003: TODO
# Lifter010: TODO
// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// StudipLitSearchPluginPhiloFak.class.php
// 
// 
// Copyright (c) 2003 Andr� Noack <noack@data-quest.de>
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

require_once ("lib/classes/lit_search_plugins/StudipLitSearchPluginGvk.class.php");

/**
* Plugin for retrieval using Z39.50 
*
* 
*
* @access   public  
* @author   Andr� Noack <noack@data-quest.de>
* @package  
**/
class StudipLitSearchPluginPhiloFak extends StudipLitSearchPluginGvk{
    
    
    function StudipLitSearchPluginPhiloFak(){
        parent::StudipLitSearchPluginGvk();
        $this->description = "Bibliotheken der Philosophischen Fakult�t";
        $this->z_host = "z3950.gbv.de:20010/phfak_opc";
        $this->z_profile = array('1016' => _("Basisindex [ALL]"), '4' => _("Titelstichw�rter [TIT]"),
                                '5' => _("Serienstichw�rter [SER]"), '21' => _("alle Klassifikationen [SYS]"),
                                '1004' => _("Person, Author [PER]"), '1005' => _("K�rperschaften [KOR]"),
                                '1006' => _("Kongresse [KON]"), '1007' => _("alle Nummern [NUM]"),
                                '5040' => _("Schlagw�rter [SLW]"),'8062' => _("alle Titelanf�nge [TAF]"),
                                '8580' => _("Verlagsort, Verlag [PUB]"), '54' => _("Signatur [SGN]"));
        }
}
?>
