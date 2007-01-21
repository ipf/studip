<?php
/**
 * Diese Datei soll die originale visual.inc.php Datei erweitern bzw. ev
 * in Zukunft ersetzen...
 *
 * @author      Alexander Willner <mail@alexanderwillner.de>
 * @version     $Id$
 * @copyright   2003 Stud.IP-Project
 * @access      public
 * @module      visual
 * @package     vote
 * @modulegroup vote_modules
 */


# Include all required files ================================================ #
require_once ('config.inc.php');
require_once ('cssClassSwitcher.inc.php');
# ====================================================== end: including files #



# Define public functions =================================================== #
/**
 * Creates a message for a success or error report
 *
 * @access  public
 * @param   String   $text      The text
 * @param   String   $imgURL    URL for the icons. See PICTURE_*
 * @param   String   $color     Color for the message. See COLOR_*
 * @return  String   The HTML-sourcecode
 */
function createReportMessage ($text, $imgURL, $color) {
   $html = "\n";

   $html .=
      "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n".
      " <tr>\n".
      "  <td class=\"\" align=\"center\" width=\"50\">\n".
      "   <img src=\"".$imgURL."\">\n".
      "  </td>\n".
      "  <td class=\"\" align=\"left\">\n".
      "   <font color=\"".$color."\">\n".
      $text."\n".
      "   </font>\n".
      "  </td>\n".
      " </tr>\n".
      "</table>\n";

   return $html;
}


/**
 * Erstellt den Anfang einer Box
 *
 * @access  public
 * @param   String   title       Der Titel der Box
 * @param   String   width       Angabe der Breite der Box in Pixel o. Prozent
 * @param   String   extraTitle  Ein zusätzlicher kleiner Titel
 * @param   String   imgURL      Die URL eines Icons
 * @param   String   imgTitle    Die Textbeschreibung zu dem Icon
 * @param   String   adminURL    Die URL der Adminseite
 * @param   String   adminImgURL Die URL des Adminicons
 * @param   String   adminTitle  Die Textbeschreibung zu dem Adminicon
 * @param   String   cssClass    Die CSS-Klasse
 * @returns Der HTML-Quelltext
 */
function createBoxHeader ($title, $width, $extraTitle = "",
			  $imgURL = "", $imgTitle = "",
			  $adminURL = "", $adminImgURL = "", $adminTitle = "",
			  $cssClass = "steel1") {
   $html = "";

   $html .=
      "<table border=\"0\" bgcolor=\"#FFFFFF\" cellspacing=\"0\" ".
      "       cellpadding=\"2\" align=\"center\" width=\"".$width."\">\n".
      " <tr>\n".
      "  <td class=\"topic\" colspan=\"2\" nowrap>";

   if ($imgURL) {
      $html .=
	 "<img src=\"".$imgURL."\" border=\"0\" alt=\"".$imgTitle."\" ".
	 " title=\"".$imgTitle."\" align=\"texttop\">";
   }

   $html .=
      "<b>&nbsp;".$title."</b>";

   if ($extraTitle) {
       $html .= "<font size=\"-1\">".$extraTitle."</font>";
   }

   $html .=
      "</td>".
      "<td align=\"right\" class=\"topic\">".
      "&nbsp;";

   if ($adminURL) {
      $html .=
	  "<a href=\"".$adminURL."\"><img src=\"".$adminImgURL."\" border=\"0\" ".
	  " alt=\"".$adminTitle."\" title=\"".$adminTitle."\"></a>&nbsp;";
   }

   $html .=
      "  </td>\n".
      " </tr>\n".
      " <tr>\n".
      "  <td class=\"".$cssClass."\" colspan=\"3\">\n";


   return $html;
}

/**
 * Beendet den HTML-Quelltext für eine Box
 *
 * @access  public
 * @returns Der HTML-Quelltext
 */

function createBoxFooter () {
   $html =
      "  </td>\n".
      " </tr>\n".
      "</table>\n".
      "<br>\n";

   return $html;
}

function createBoxLineHeader () {
   $html = "";
   $html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n";
   return $html;
}

function createBoxLineFooter () {
   $html = "";
   $html .=
      "</table>\n";
   return $html;
}

/**
 * Neue Version der klappbaren Zeilen in einer Box.
 * Z.Z. ohne Funktionalität
 *
 * @access  public
 * @param   String   title       Der Titel der Zeile
 * @param   String   imgURL      Die URL des Icons
 * @param   String   userName    Der Name des Erstellers
 * @param   String   userID      Die ID des Erstellers
 * @param   String   date        Der UNIX-TIMESTAMP des Erstelldatums
 * @returns Der HTML-Quelltext
 */

##############################
####  wird z.Zt. nicht benutzt!
##############################

function createBoxLine ($title, $imgURL, $userName, $userID, $date) {
   $html = "";
   $timecolor = "#BBBBBB";

   $htmlArrow =
      "  <td bgcolor=\"".$timecolor."\" class=\"printhead3\" nowrap ".
      "      width=\"1%\" align=\"left\" valign=\"top\">\n".
      "   &nbsp;<img src=\"".$GLOBALS['ASSETS_URL']."images/forumrotrunt.gif\">\n".
      " </td>";

   $htmlIcon =
      " <td class=\"printhead\" nowrap width=\"1%\" valign=\"middle\">".
      "  <img src=\"".$imgURL."\" border=\"0\">".
      " </td>";

   $htmlTitle =
      " <td class=\"printhead\" align=\"left\" width=\"20%\" nowrap ".
      "     valign=\"bottom\">".
      "  &nbsp;".$title.
      " </td>";

   $htmlUser =
      "<td align=\"right\" class=\"printhead\" width=\"99%\" ".
      "    valign=\"bottom\">\n".
      " <a href=\"about.php?username=".$userID."\">\n".
      "  <font size=\"-1\" color=\"#333399\">".$userName."</font>\n".
      " </a>\n".
      " <font size=\"-1\">&nbsp;".date ("d.m.Y", $date)."</font>&nbsp;".
      "</td>\n";


   $html .=
      " <tr>" .
      $htmlArrow . $htmlIcon . $htmlTitle . $htmlUser .
      " </tr>";

   return $html;
}

function createBoxContentHeader () {
   $html = "";

   $html .=
      " <tr>\n".
      "  <td class=\"printcontent\" width=\"22\">&nbsp;&nbsp;&nbsp;".
      "&nbsp;&nbsp;\n".
      "  </td>\n".
      "  <td colspan=\"3\"class=\"printcontent\" width=\"0\"><br>\n";

   return $html;
}

function createBoxContentFooter () {
   $html = "";

   $html .=
      "  </td>\n".
      " </tr>\n";

   return $html;
}

/**
 * Creates an errormessage
 * @param    object StudipObejct   $object   A StudIP-Obeject
 * @returns  String   The HTML-errortext
 */
function createErrorReport (&$object, $errortitle = "") {
   $html = "";
   if (empty ($errortitle)) {
       $errortitle = ( count( $object->getErrors() ) > 1 )
	   ? _("Es sind Fehler aufgetreten.")
	   : _("Es ist ein Fehler aufgetreten.");
   }

   $html .=  createReportMessage ($errortitle, VOTE_ICON_ERROR,
				  VOTE_COLOR_ERROR);

   $html .= "<ul>\n";
   foreach ($object->getErrors () as $error) {
      $html .= " <li><font size=\"-1\">".$error["string"]."</font>\n";
      if ($error["type"] == ERROR_CRITICAL) {
	 $html .= "<ul>\n";
	 $html .= "<li>"._("Datei: ")."<b>".$error["file"]."</b></li>\n";
	 $html .= "<li>"._("Zeile: ")."<b>".$error["line"]."</b></li>\n";
	 $html .= "</ul>\n";
      }
      $html .= "</li>\n";
   }
   $html .= "</ul>\n";

   return $html;
}
# ===================================================== end: public functions #
?>