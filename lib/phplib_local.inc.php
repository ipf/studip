<?
# Lifter002: TODO
# Lifter007: TODO
# Lifter003: TODO
// +---------------------------------------------------------------------------+
// This file is part of Stud.IP
// phplib_local.inc.php
// This file contains several phplib classes extended for use with Stud.IP
//
// Copyright (c) 2003 Andr� Noack <noack@data-quest.de>
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


//
require_once('lib/deputies_functions.inc.php');

$_never_globalize_request_params = array('msg','_msg','errormsg','meldung','sms_msg','_html_head_title','_include_stylesheet',
                                    '_include_extra_stylesheet','_include_additional_header','_include_additional_html'
                                    );
foreach($_never_globalize_request_params as $one_param){
    if (isset($_REQUEST[$one_param])){
        unset($GLOBALS[$one_param]);
    }
}

// set default time zone
date_default_timezone_set(@date_default_timezone_get());

// set assets url
require_once('lib/classes/Assets.class.php');
Assets::set_assets_url($GLOBALS['ASSETS_URL']);

// globale template factory anlegen
require_once 'vendor/flexi/flexi.php';
$GLOBALS['template_factory'] =
    new Flexi_TemplateFactory($STUDIP_BASE_PATH . '/templates');

// set default exception handler
function studip_default_exception_handler($exception) {
    if ($exception instanceof AccessDeniedException) {
        header('HTTP/1.1 403 ' . $exception->getMessage());
        $template = 'access_denied_exception';
    } else if ($exception instanceof CheckObjectException) {
        header('HTTP/1.1 403 ' . $exception->getMessage());
        $template = 'check_object_exception';
    } else {
        header('HTTP/1.1 500 ' . $exception->getMessage());
        error_log($exception->__toString());
        $template = 'unhandled_exception';
    }
    while (ob_get_level()) {
        ob_end_clean();
    }
    try {
        echo $GLOBALS['template_factory']->render($template,
                                              compact('exception'));
    } catch (Exception $e) {
        echo 'Error: ' . htmlspecialchars($e->getMessage());
    }
    exit;
}
set_exception_handler('studip_default_exception_handler');

require_once 'lib/classes/URLHelper.php';
require_once 'lib/navigation/Navigation.php';
require_once 'lib/navigation/AutoNavigation.php';

// set dummy navigation until db is ready
Navigation::setRootNavigation(new Navigation(''));

// set default pdo connection
require_once('lib/classes/DBManager.class.php');
DBManager::getInstance()
  ->setConnection('studip',
                  'mysql:host='.$GLOBALS['DB_STUDIP_HOST'].
                  ';dbname='.$GLOBALS['DB_STUDIP_DATABASE'],
                  $GLOBALS['DB_STUDIP_USER'],
                  $GLOBALS['DB_STUDIP_PASSWORD']);

/**
 * @deprecated
 */
class DB_Seminar extends DB_Sql {
    function DB_Seminar($query = false){
        $this->Host = $GLOBALS['DB_STUDIP_HOST'];
        $this->Database = $GLOBALS['DB_STUDIP_DATABASE'];
        $this->User = $GLOBALS['DB_STUDIP_USER'];
        $this->Password = $GLOBALS['DB_STUDIP_PASSWORD'];
        parent::DB_Sql($query);
    }
}

require_once 'lib/msg.inc.php';
require_once('lib/language.inc.php');
require_once('lib/classes/auth_plugins/StudipAuthAbstract.class.php');
require_once('lib/classes/Config.class.php');
require_once('lib/classes/UserConfig.class.php');
require_once('lib/classes/StudipNews.class.php');
require_once('lib/classes/StudipCacheFactory.class.php');
require_once 'lib/classes/SessionDecoder.class.php';
require_once 'lib/classes/StudipMail.class.php';


//Besser hier globale Variablen definieren...
$GLOBALS['_fullname_sql'] = array();
$GLOBALS['_fullname_sql']['full'] = "TRIM(CONCAT(title_front,' ',Vorname,' ',Nachname,IF(title_rear!='',CONCAT(', ',title_rear),'')))";
$GLOBALS['_fullname_sql']['full_rev'] = "TRIM(CONCAT(Nachname,', ',Vorname,IF(title_front!='',CONCAT(', ',title_front),''),IF(title_rear!='',CONCAT(', ',title_rear),'')))";
$GLOBALS['_fullname_sql']['no_title'] = "CONCAT(Vorname ,' ', Nachname)";
$GLOBALS['_fullname_sql']['no_title_rev'] = "CONCAT(Nachname ,', ', Vorname)";
$GLOBALS['_fullname_sql']['no_title_short'] = "CONCAT(Nachname,', ',UCASE(LEFT(TRIM(Vorname),1)),'.')";
$GLOBALS['_fullname_sql']['no_title_motto'] = "CONCAT(Vorname ,' ', Nachname,IF(motto!='',CONCAT(', ',motto),''))";
//software version - please leave it as it is!
$SOFTWARE_VERSION = '1.12 alpha svn';

// set up global navigation
require_once 'lib/navigation/StudipNavigation.php';
Navigation::setRootNavigation(new StudipNavigation(''));

/*class for config; load config in globals (should be deprecated in future)
----------------------------------------------------------------*/
Config::GetInstance()->extractAllGlobal(FALSE);

/* set default umask to a sane value */
umask(022);

/*mail settings
----------------------------------------------------------------*/
if($GLOBALS['MAIL_TRANSPORT']){
    $mail_transporter_name = strtolower($GLOBALS['MAIL_TRANSPORT']) .'_message';
} else {
    $mail_transporter_name = 'smtp_message';
}
include 'vendor/email_message/email_message.php';
include 'vendor/email_message/' . $mail_transporter_name . '.php';
$mail_transporter_class = $mail_transporter_name . '_class';
$mail_transporter = new $mail_transporter_class;
if($mail_transporter_name == 'smtp_message'){
    include 'vendor/email_message/smtp.php';
    $mail_transporter->localhost = ($GLOBALS['MAIL_LOCALHOST'] == "") ? $_SERVER["SERVER_NAME"] : $GLOBALS['MAIL_LOCALHOST'];
    $mail_transporter->smtp_host = ($GLOBALS['MAIL_HOST_NAME'] == "") ? $_SERVER["SERVER_NAME"] : $GLOBALS['MAIL_HOST_NAME'];
}
$mail_transporter->default_charset = 'WINDOWS-1252';
$mail_transporter->SetBulkMail((int)$GLOBALS['MAIL_BULK_DELIVERY']);
StudipMail::setDefaultTransporter($mail_transporter);
unset($mail_transporter);

class Seminar_CT_Sql extends CT_Sql {
    var $database_table = PHPLIB_SESSIONDATA_TABLE; // and find our session data in this table.
}


class Seminar_Session extends Session {
    var $classname = "Seminar_Session";

    var $cookiename     = "Seminar_Session"; // defaults to classname
    var $magic    = "sdfghjdfdf";      // ID seed
    var $mode      = "cookie";    // We propagate session IDs with cookies
    var $fallback_mode  = "cookie";
    var $lifetime       = 0;         // 0 = do session cookies, else minutes
    var $that_class     = "Seminar_CT_Sql"; // name of data storage container
    var $gc_probability = 2;
    var $allowcache = "nocache";
    var $cookie_secure = false;
    var $cookie_httponly = true;

    /**
     * Returns true, if the current session is valid and belongs to an
     * authenticated user. Does not start a session.
     *
     * @static
     * @return bool
     */
    function is_current_session_authenticated(){
        return Seminar_Session::get_current_session_state() == 'authenticated';
    }

    /**
     * Returns the state of the current session. Does not start a session.
     * possible return values:
     * 'authenticated' - session is valid and user is authenticated
     * 'nobody' - session is valid, but user is not authenticated
     * false - no valid session
     *
     * @static
     * @return string|false
     */
    function get_current_session_state(){
        static $current_session_state = null;
        if(!is_null($current_session_state)){
            return $current_session_state;
        }
        $state = false;
        if(is_object($GLOBALS['user'])) {
            $state = in_array($GLOBALS['user']->id, array('nobody','form')) ? 'nobody' : 'authenticated';
        } else {
            $sess = $GLOBALS['sess'];
            if(!is_object($sess)){
                $sess = new Seminar_Session();
            }
            $sid = $_COOKIE[$sess->cookiename];
            if($sid){
                $session_vars = Seminar_Session::get_session_vars($sid);
                $session_auth = $session_vars['auth']->auth;
                if($session_auth['perm'] && $session_auth['exp'] > time()){
                    $state = 'authenticated';
                } else {
                    $state = in_array($session_auth['uid'], array('nobody','form')) ? 'nobody' : false;
                }
            }
        }
        return ($current_session_state = $state);
    }

    /**
     * returns a SessionDecoder object containing the session variables
     * for the given session id
     *
     * @static
     * @param string $sid a session id
     * @return SessionDecoder
     */
    function get_session_vars($sid){
        $sess = $GLOBALS['sess'];
        if(!is_object($sess)){
            $sess = new Seminar_Session();
        }
        $storage_class = $sess->that_class;
        $storage = new $storage_class();
        $storage->ac_start();
        return new SessionDecoder($storage->ac_get_value($sid));
    }

    /**
     * returns a random string token for XSRF prevention
     * the string is stored in the session
     *
     * @static
     * @return string
     */
    function get_ticket(){
        static $studipticket;
        if (!$studipticket){
            $studipticket = $_SESSION['last_ticket'] = md5(uniqid('studipticket',1));
        }
        return $studipticket;
    }

    /**
     * checks the given string token against the one stored
     * in the session
     *
     * @static
     * @param string $studipticket
     * @return bool
     */
    function check_ticket($studipticket){
        $check = (isset($_SESSION['last_ticket']) && $_SESSION['last_ticket'] == $studipticket);
        $_SESSION['last_ticket'] = null;
        return $check;
    }


    function Seminar_Session(){
        $this->cookie_path = $GLOBALS['CANONICAL_RELATIVE_PATH_STUDIP'];
        if (method_exists($this, 'Session')){
            $this->Session();
        }
    }

    //erweiterter Garbage Collector
    function gc(){
        mt_srand((double)microtime()*1000000);
        $zufall = mt_rand();
        if (($zufall % 100) < $this->gc_probability){
            StudipNews::DoGarbageCollect();
        }
        if (($zufall % 1000) < $this->gc_probability){
            //messages aufr�umen
            $db->query("SELECT message_id, count( message_id ) AS gesamt, count(IF (deleted =0, NULL , 1) ) AS geloescht
                        FROM message_user GROUP BY message_id HAVING gesamt = geloescht");
            $result = array();
            $i = 0;
            while($db->next_record()) {
                $result[floor($i / 100)][] = $db->Record[0];
                ++$i;
            }
            for ($i = 0; $i < count($result); ++$i){
                $ids =  join("','", $result[$i]);
                $db->query("DELETE FROM message_user WHERE message_id IN('$ids')");
                $db->query("DELETE FROM message WHERE message_id IN('$ids')");
                $db->query("SELECT dokument_id FROM dokumente WHERE range_id IN('$ids')");
                while ($db->next_record())
                    delete_document($db->f("dokument_id"));
            }
            //Attachments von nicht versendeten Messages aufr�umen
            $db->query("SELECT dokument_id FROM dokumente WHERE range_id = 'provisional' AND chdate < UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL -2 HOUR))");
                while($db->next_record()) {
                    delete_document($db->f("dokument_id"));
                }

            unset($result);

        }
    //weiter mit gc() in der Super Klasse
    parent::gc();
    }
}

class Seminar_User_CT_Sql extends CT_Sql {
    var $database_table = PHPLIB_USERDATA_TABLE;
}

class Seminar_User extends User {
    var $classname = "Seminar_User";
    var $magic    = "dsfgakdfld";     // ID seed
    var $that_class     = "Seminar_User_CT_Sql"; // data storage container
    var $fake_user = false;
    var $cfg = null; //UserConfig object

    function Seminar_User($uid = null){
        if ($uid){
            if (!is_object($GLOBALS['auth']) ||
            (is_object($GLOBALS['auth']) && $uid != $GLOBALS['auth']->auth['uid'])){
                $this->fake_user = true;
                $this->register_globals = false;
                $this->start($uid);
            }
        }
    }

    function start($uid){
        parent::start($uid);
        $this->cfg = UserConfig::get($uid);
    }

    function freeze(){
        if ($this->fake_user){
            $this->fake_freeze();
            return true;
        } else {
            return parent::freeze();
        }
    }

    function fake_freeze(){
        $changed = $this->get_last_action();
        if(!$this->that->ac_store($this->id, $this->name, $this->serialize())){
            $this->that->ac_halt("User: freeze() failed.");
        }
        $this->set_last_action($changed);
    }

    function get_last_action(){
        return $this->that->ac_get_changed($this->id, $this->name);
    }

    function set_last_action($timestamp = 0){
        if ($timestamp <= 0){
            $timestamp = time();
        }
        $this->that->ac_set_changed($this->id, $this->name, $timestamp);
    }
}


//
// Seminar_Challenge_Crypt_Auth: Keep passwords in md5 hashes rather
//             than cleartext in database
// Author: Jim Zajkowski <jim@jimz.com>

class Seminar_Auth extends Auth {
    var $classname      = "Seminar_Auth";

    var $lifetime       =  60;

    var $magic    = "Fdfglkdfsg";  // Challenge seed
    var $database_class = "DB_Seminar";
    var $database_table = "auth_user_md5";
    var $error_msg = "";

    //constructor
    function Seminar_Auth() {
    }

    function start(){
        //load the lifetime from the settings
        $this->lifetime = $GLOBALS['AUTH_LIFETIME'];
        return parent::start();
    }

    function login_if($ok){
        if ($ok){
            parent::login_if($ok);
            if (is_object($GLOBALS['user'])){
                $GLOBALS['user']->start($this->auth['uid']);
            }
        }
        return true;
    }

    function is_authenticated(){

        $cfg = Config::GetInstance();
        //check if the user got kicked meanwhile, or if user is locked out
        if ($this->auth['uid'] && !in_array($this->auth['uid'], array('form','nobody'))){
            $this->db->query(sprintf("select username,perms,locked from %s where user_id = '%s'", $this->database_table, $this->auth['uid']));
            $this->db->next_record();
            if (!$this->db->f('username') || $this->db->f('locked')){
                $this->unauth();
            } else {
                $actual_perms = $this->db->f('perms');
            }
        } elseif ($cfg->getValue('MAINTENANCE_MODE_ENABLE') && isset($_REQUEST['username'])) {
            $this->db->query(sprintf("select username,perms from %s where username = '%s' AND perms='root'", $this->database_table, $_REQUEST['username']));
            $this->db->next_record();
            $this->auth["uname"] = $this->db->f('username');
            $actual_perms = $this->db->f('perms');
        }
        if ($cfg->getValue('MAINTENANCE_MODE_ENABLE') && $actual_perms != 'root'){
            $this->unauth();
            include_once 'lib/include/html_head.inc.php';
            parse_window('error�' . _("Das System befindet sich im Wartungsmodus. Zur Zeit ist kein Zugriff m�glich."), '�', $GLOBALS['UNI_NAME'] . ' ' . _("Wartungsmodus"), '&nbsp;');
            include_once 'lib/include/html_end.inc.php';
            die;
        }
        return parent::is_authenticated();
    }

    function auth_preauth() {
        global $auto_user,$auto_response,$auto_id,$resolution,$TMP_PATH;
        // is Single Sign On activated?
        if ($GLOBALS["sso"]){
            // then do login
            $provider = $_REQUEST['sso'];
            $authplugin = StudipAuthAbstract::GetInstance($provider);
            $authplugin->authenticateUser("","","");
            if ($authplugin->getUser()){
                $uid = $authplugin->getStudipUserid($authplugin->getUser());
                $this->db->query(sprintf("select username,perms,auth_plugin from %s where user_id = '%s'",$this->database_table,$uid));
                $this->db->next_record();
                $this->auth["jscript"] = true;
                $this->auth["perm"]  = $this->db->f("perms");
                $this->auth["uname"] = $this->db->f("username");
                $this->auth["auth_plugin"]  = $this->db->f("auth_plugin");
                $this->auth_set_user_settings($uid);
                return $uid;
            }
        }
        // end of single sign on
        if (!$auto_user OR !$auto_response OR !$auto_id){
            return false;
        }
        $aktuell = time();
        $folder = dir($TMP_PATH);
        while ($entry = $folder->read()){
            if (!strncmp($entry,"auto_key",8)){
                if ($aktuell-filemtime("$TMP_PATH/$entry") > 30){
                    unlink("$TMP_PATH/$entry");
                }
            }
        }
        $folder->close;
        if (file_exists("$TMP_PATH/auto_key_$auto_id")){
            $fp = @fopen("$TMP_PATH/auto_key_$auto_id","r");
            $auto_challenge = fgets($fp,100);
            fclose($fp);
            unlink("$TMP_PATH/auto_key_$auto_id");
        } else {
            $this->error_msg= _("Fehler beim Auto-Login!") . "<br>";
            return false;
        }
        $this->auth["uname"] = $auto_user;  // This provides access for "loginform.ihtml"
        $this->auth["jscript"] = true;
        $expected_response = "";
        for ($i = 0;$i < strlen($auto_response)/2;$i++){
            $s = (256-(ord(substr($auto_challenge,$i,1))-hexdec(substr($auto_response,$i*2,2)))) % 256;
            $expected_response .= chr($s);
        }
        $check_auth = StudipAuthAbstract::CheckAuthentication($auto_user,$expected_response,$this->auth['jscript']);
        if ($check_auth['uid']){
            $uid = $check_auth['uid'];
            $this->db->query(sprintf("select * from %s where user_id = '%s'",$this->database_table,$uid));
            $this->db->next_record();
            if ($this->db->f("perms") == "root" || $this->db->f("perms") == "admin"){
                $this->error_msg= sprintf(_("Autologin ist mit dem Status: %s nicht m�glich!"), $this->auth["perm"]);
                return false;
            }
            if($check_auth['need_email_activation'] == $uid){
                $this->need_email_activation = $uid;
                $_SESSION['semi_logged_in'] = $uid;
                return false;
            }
            $this->auth["perm"]  = $this->db->f("perms");
            $this->auth["uname"] = $this->db->f("username");
            $this->auth["auth_plugin"]  = $this->db->f("auth_plugin");
            $this->auth_set_user_settings($uid);
            return $uid;
        } else {
            $this->error_msg = $check_auth['error'];
            return false;
        }
    }

    function auth_loginform() {
        // first of all init I18N because seminar_open is not called here...
        require_once('lib/visual.inc.php');
        require_once('config.inc.php');

        global $_language, $_language_path;

        if (!isset($_language)) {
            $_language = get_accepted_languages();
        }
        if (!$_language) {
            $_language = $GLOBALS['DEFAULT_LANGUAGE'];
        }
        // init of output via I18N
        $_language_path = init_i18n($_language);

        // load the default set of plugins
        PluginEngine::loadPlugins();

        if ($_REQUEST['username'] && !$_COOKIE[$GLOBALS['sess']->name]){
            $login_template = $GLOBALS['template_factory']->open('nocookies');
        } else if (isset($this->need_email_activation)) {
            $login_template = $GLOBALS['template_factory']->open('login_emailactivation');
            $login_template->set_attribute('uid', $this->need_email_activation);
        } else {
            unset($_SESSION['semi_logged_in']); // used by email activation
            $login_template = $GLOBALS['template_factory']->open('loginform');
            $login_template->set_attribute('loginerror', (isset($this->auth["uname"]) && $this->error_msg));
            $login_template->set_attribute('error_msg', $this->error_msg);
            $login_template->set_attribute('uname', (isset($this->auth["uname"]) ? $this->auth["uname"] : $_REQUEST['shortcut']));
            $login_template->set_attribute('self_registration_activated', $GLOBALS['ENABLE_SELF_REGISTRATION']);
        }
        $GLOBALS['HELP_KEYWORD'] = 'Basis.AnmeldungLogin';
        $header_template = $GLOBALS['template_factory']->open('header');
        $header_template->current_page = _('Login');
        $header_template->link_params = array('cancel_login' => 1);

        include 'lib/include/html_head.inc.php';
        echo $header_template->render();
        echo $login_template->render();
        include 'lib/include/html_end.inc.php';
        page_close();
    }

    function auth_validatelogin() {
        global $username, $password, $resolution;
        global $_language, $_language_path, $login_ticket;

        //prevent replay attack
        if (!Seminar_Session::check_ticket($login_ticket)){
            return false;
        }

        // check for direct link
        if (!isset($_language) || $_language == "") {
            $_language = get_accepted_languages();
        }

        $_language_path = init_i18n($_language);


        $this->auth["uname"] = $username;   // This provides access for "loginform.ihtml"
        $this->auth["jscript"] = ($resolution != "");

        $check_auth = StudipAuthAbstract::CheckAuthentication(stripslashes($username),stripslashes($password),$this->auth['jscript']);

        if ($check_auth['uid']) {
            $uid = $check_auth['uid'];
            $this->db->query(sprintf("select * from %s where user_id = '%s'",$this->database_table,$uid));
            $this->db->next_record();
            if($check_auth['need_email_activation'] == $uid){
                $this->need_email_activation = $uid;
                $_SESSION['semi_logged_in'] = $uid;
                return false;
            }
            $this->auth["perm"]  = $this->db->f("perms");
            $this->auth["uname"] = $this->db->f("username");
            $this->auth["auth_plugin"]  = $this->db->f("auth_plugin");
            $this->auth_set_user_settings($uid);
            return $uid;
        } else {
            $this->error_msg = $check_auth['error'];
            return false;
        }
    }

    function auth_set_user_settings($uid){
        global $resolution, $_language;
        $divided = explode("x",$resolution);
        $this->auth["xres"] = ($divided[0] != 0) ? $divided[0] : 1024; //default
        $this->auth["yres"] = ($divided[1] != 0) ? $divided[1] : 768; //default
        // Change X-Resulotion on Multi-Screen Systems (as Matrox Graphic-Adapters are)
        if (($this ->auth["xres"] / $this ->auth["yres"]) > 2){
            $this->auth["xres"] = $this->auth["xres"] /2;
        }
        //restore user-specific language preference
        $db = new DB_Seminar("SELECT preferred_language FROM user_info WHERE user_id='$uid'");
        if ($db->next_record()) {
            if ($db->f("preferred_language")) {
                // we found a stored setting for preferred language
                $_language = $db->f("preferred_language");
            }
        }
    }
}

class Seminar_Default_Auth extends Seminar_Auth {
    var $classname = "Seminar_Default_Auth";

    var $nobody    = true;

    function Seminar_Default_Auth(){
        Seminar_Auth::Seminar_Auth();
    }
}


class Seminar_Register_Auth extends Seminar_Auth {
    var $classname = "Seminar_Register_Auth";
    var $magic     = "dsdfjhgretha";  // Challenge seed

    var $mode      = "reg";
    var $error_msg = ""; // Was l�uft falsch bei der Registrierung ?

    function auth_registerform() {

        require_once('lib/visual.inc.php');
        require_once('config.inc.php');

        // set up user session
        include 'lib/seminar_open.php';

        if (!$_COOKIE[$GLOBALS['sess']->name]) {
            $register_template = $GLOBALS['template_factory']->open('nocookies');
        } else {
            $register_template = $GLOBALS['template_factory']->open('registerform');
            $register_template->set_attribute('validator',  new email_validation_class());
            $register_template->set_attribute('error_msg', $this->error_msg);
            $register_template->set_attribute('username', $_POST['username']);
            $register_template->set_attribute('Vorname', $_POST['Vorname']);
            $register_template->set_attribute('Nachname', $_POST['Nachname']);
            $register_template->set_attribute('Email', $_POST['Email']);
            $register_template->set_attribute('title_front', $_POST['title_front']);
            $register_template->set_attribute('title_rear', $_POST['title_rear']);
            $register_template->set_attribute('geschlecht', $_POST['geschlecht']);
        }
        $GLOBALS['HELP_KEYWORD'] = 'Basis.AnmeldungRegistrierung';
        $header_template = $GLOBALS['template_factory']->open('header');
        $header_template->current_page = _('Registrierung');

        include 'lib/include/html_head.inc.php';
        echo $header_template->render();
        echo $register_template->render();
        include 'lib/include/html_end.inc.php';
    }

    function auth_doregister() {
        global $username, $password, $Vorname, $Nachname, $geschlecht,$emaildomain,$Email,$title_front,$title_front_chooser,$title_rear,$title_rear_chooser, $CANONICAL_RELATIVE_PATH_STUDIP, $UNI_NAME_CLEAN, $DEFAULT_LANGUAGE;

        global $_language, $_language_path;

        $this->error_msg = "";

        // check for direct link to register2.php
        if (!isset($_language) || $_language == "") {
            $_language = get_accepted_languages();
        }

        $_language_path = init_i18n($_language);

        $this->auth["uname"]=$username;                 // This provides access for "crcregister.ihtml"

        $validator=new email_validation_class;  // Klasse zum Ueberpruefen der Eingaben
        $validator->timeout=10;                                 // Wie lange warten wir auf eine Antwort des Mailservers?

        if (!Seminar_Session::check_ticket($_REQUEST['login_ticket'])){
            return false;
        }

        $username = trim($username);
        $Vorname = trim($Vorname);
        $Nachname = trim($Nachname);

        // accept only registered domains if set
        $cfg = Config::GetInstance();
        $email_restriction = $cfg->getValue('EMAIL_DOMAIN_RESTRICTION');
        if ($email_restriction) {
            $Email = trim($Email) . '@' . trim($emaildomain);
        } else {
            $Email = trim($Email);
        }

        if (!$validator->ValidateUsername($username))
        {
            $this->error_msg=$this->error_msg. _("Der gew�hlte Username ist zu kurz!") . "<br>";
            return false;
        }                                                       // username syntaktisch falsch oder zu kurz
        // auf doppelte Vergabe wird weiter unten getestet.

        if (!$validator->ValidatePassword($password))
        {
            $this->error_msg=$this->error_msg. _("Das Passwort ist zu kurz!") . "<br>";
            return false;
        }

        if (!$validator->ValidateName($Vorname))
        {
            $this->error_msg=$this->error_msg. _("Der Vorname fehlt oder ist unsinnig!") . "<br>";
            return false;
        }              // Vorname nicht korrekt oder fehlend
        if (!$validator->ValidateName($Nachname))
        {
            $this->error_msg=$this->error_msg. _("Der Nachname fehlt oder ist unsinnig!") . "<br>";
            return false;              // Nachname nicht korrekt oder fehlend
        }
        if (!$validator->ValidateEmailAddress($Email))
        {
            $this->error_msg=$this->error_msg. _("Die E-Mail-Adresse fehlt oder ist falsch geschrieben!") . "<br>";
            return false;
        }              // E-Mail syntaktisch nicht korrekt oder fehlend

        $REMOTE_ADDR=$_SERVER["REMOTE_ADDR"];
        $Zeit=date("H:i:s, d.m.Y",time());

        if (!$validator->ValidateEmailHost($Email)) {     // Mailserver nicht erreichbar, ablehnen
            $this->error_msg=$this->error_msg. _("Der Mailserver ist nicht erreichbar, bitte �berpr�fen Sie, ob Sie E-Mails mit der angegebenen Adresse verschicken und empfangen k�nnen!") . "<br>";
            return false;
        } else {                      // Server ereichbar
            if (!$validator->ValidateEmailBox($Email)) {    // aber user unbekannt. Mail an abuse!
                StudipMail::sendAbuseMessage("Register", "Emailbox unbekannt\n\nUser: $username\nEmail: $Email\n\nIP: $REMOTE_ADDR\nZeit: $Zeit\n");
                $this->error_msg=$this->error_msg. _("Die angegebene E-Mail-Adresse ist nicht erreichbar, bitte �berpr�fen Sie Ihre Angaben!") . "<br>";
                return false;
            } else {
                ;                        // Alles paletti, jetzt kommen die Checks gegen die Datenbank...
            }
        }

        $check_uname = StudipAuthAbstract::CheckUsername($username);

        if ($check_uname['found']){
            //   error_log("username schon vorhanden", 0);
            $this->error_msg = $this->error_msg. _("Der gew�hlte Username ist bereits vorhanden!") . "<br>";
            return false;                  // username schon vorhanden
        }

        $this->db->query(sprintf("select user_id ".
        "from %s where Email = '%s'",
        $this->database_table,
        addslashes($Email)));

        while($this->db->next_record()) {
            //error_log("E-Mail schon vorhanden", 0);
            $this->error_msg=$this->error_msg. _("Die angegebene E-Mail-Adresse wird bereits von einem anderen User verwendet. Sie m�ssen eine andere E-Mail-Adresse angeben!") . "<br>";
            return false;                  // Email schon vorhanden
        }

        // alle Checks ok, Benutzer registrieren...
        $newpass = md5($password);
        $uid = md5(uniqid($this->magic));
        $perm = "user";
        $this->db->query(sprintf("insert into %s (user_id, username, perms, password, Vorname, Nachname, Email) ".
        "values ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
        $this->database_table, $uid, addslashes($username), $perm, $newpass,
        addslashes($Vorname), addslashes($Nachname), addslashes($Email)));
        $this->auth["perm"] = $perm;

        if($title_front == "")
            $title_front = $title_front_chooser;

        if($title_rear == "")
            $title_rear = $title_rear_chooser;

        // Anlegen eines korespondierenden Eintrags in der user_info
        $this->db->query("INSERT INTO user_info SET user_id='$uid', mkdate='".time()."', geschlecht='$geschlecht', title_front='$title_front', title_rear='$title_rear'");

        // Abschicken der Bestaetigungsmail
        $to = $Email;
        $secret= md5("$uid:$this->magic");
        $url = $GLOBALS['ABSOLUTE_URI_STUDIP'] . "email_validation.php?secret=" . $secret;
        $mail = new StudipMail();
        $abuse = $mail->getReplyToEmail();
        // include language-specific subject and mailbody
        include_once("locale/$_language_path/LC_MAILS/register_mail.inc.php");
        $mail->setSubject($subject)
            ->addRecipient($to)
            ->setBodyText($mailbody)
            ->send();
        return $uid;
    }
}



class Seminar_Perm extends Perm {
    var $classname = "Seminar_Perm";

    var $permissions = array(
    "user"       => 1,
    "autor"      => 3,
    "tutor"      => 7,
    "dozent"     => 15,
    "admin"      => 31,
    "root"       => 63
    );
    var $studip_perms = array();
    var $fak_admins = array();

    function perm_invalid($does_have, $must_have) {
        global $perm, $auth, $sess;
        global $RELATIVE_PATH_CHAT;
        include('lib/include/perminvalid.ihtml');
    }

    function get_perm($user_id = false){
        global $auth,$user;
        if (!$user_id) $user_id = $user->id;
        if ($user_id && $user_id == $auth->auth['uid']){
            return $auth->auth['perm'];
        } else if ($user_id && $this->studip_perms['studip'][$user_id]){
            return $this->studip_perms['studip'][$user_id];
        } else if ($user_id) {
            $db = new DB_Seminar("SELECT perms FROM auth_user_md5 WHERE user_id = '$user_id'");
            if (!$db->next_record()){
                return false;
            } else {
                return $this->studip_perms['studip'][$user_id] = $db->f(0);
            }
        }
    }

    function have_perm($perm, $user_id = false) {

        $pageperm = explode(",", $perm);
        $userperm = explode(",", $this->get_perm($user_id));

        list($ok0, $pagebits) = $this->permsum($pageperm);
        list($ok1, $userbits) = $this->permsum($userperm);

        $has_all = (($userbits & $pagebits) == $pagebits);
        if (!($has_all && $ok0 && $ok1) ) {
            return false;
        } else {
            return true;
        }
    }


    function get_studip_perm($range_id, $user_id = false) {

        if (!$user_id) {
            $user_id = $GLOBALS['user']->id;
        }

        if (!isset($this->studip_perms[$range_id][$user_id])) {
            $this->studip_perms[$range_id][$user_id] = $this->get_uncached_studip_perm($range_id, $user_id);
        }
        return $this->studip_perms[$range_id][$user_id];
    }

    function get_uncached_studip_perm($range_id, $user_id) {
        global $auth, $user;
        $db = new DB_Seminar;
        $status = false;
        if ($user_id && $user_id == $auth->auth['uid']){
            $user_perm = $auth->auth["perm"];
        } else {
            $user_perm = $this->get_perm($user_id);
            if (!$user_perm){
                return false;
            }
        }
        if ($user_perm == "root") {
            return "root";
        } elseif ($user_perm == "admin") {
            $db->query("SELECT seminare.Seminar_id FROM user_inst
                        LEFT JOIN seminare USING (Institut_id)
                        WHERE inst_perms='admin' AND user_id='$user_id' AND seminare.Seminar_id='$range_id'");
            if ($db->num_rows()) {
                $status = "admin";
            } else {
                $db->query("SELECT Seminar_id FROM user_inst a LEFT JOIN Institute b ON(a.Institut_id=b.Institut_id AND b.Institut_id=b.fakultaets_id)
                            LEFT JOIN Institute c ON (b.Institut_id=c.fakultaets_id) LEFT JOIN seminare d ON (d.Institut_id=c.Institut_id) WHERE a.user_id='$user_id' AND a.inst_perms='admin' AND d.Seminar_id='$range_id'");
                if ($db->num_rows()) {
                    $status = "admin";
                } else {
                    $db->query("SELECT a.Institut_id FROM user_inst a LEFT JOIN Institute b ON(a.Institut_id=b.fakultaets_id) WHERE user_id='$user_id' AND a.inst_perms='admin'
                                AND b.Institut_id='$range_id'");
                    if ($db->num_rows()) {
                        $status = "admin";
                    }
                }
            }
        }

        if ($status) {
            return $status;
        }

        if (get_config('DEPUTIES_ENABLE') && isDeputy($user_id, $range_id)) {
            $status = 'dozent';
        } else {
            $db->query("SELECT status FROM seminar_user WHERE user_id='$user_id' AND Seminar_id='$range_id'");
            if ($db->next_record()){
                $status=$db->f("status");
            } else {
                $db->query("SELECT inst_perms FROM user_inst WHERE user_id='$user_id' AND Institut_id='$range_id'");
                if ($db->next_record()){
                    $status=$db->f("inst_perms");
                }
            }
        }
        return $status;
    }

    function have_studip_perm($perm, $range_id, $user_id = false) {

        $pageperm = explode(",", $perm);
        $userperm = explode(",", $this->get_studip_perm($range_id, $user_id));

        list ($ok0, $pagebits) = $this->permsum($pageperm);
        list ($ok1, $userbits) = $this->permsum($userperm);

        $has_all = (($userbits & $pagebits) == $pagebits);

        if (!($has_all && $ok0 && $ok1) ) {
            return false;
        } else {
            return true;
        }
    }

    function is_fak_admin($user_id = false){
        global $user;
        if (!$user_id) $user_id = $user->id;
        $user_perm = $this->get_perm($user_id);
        if ($user_perm == "root") {
            return true;
        }
        if ($user_perm != "admin"){
            return false;
        }
        if (isset($this->fak_admins[$user_id])){
            return $this->fak_admins[$user_id];
        } else {
            $db = new DB_Seminar("SELECT a.Institut_id FROM user_inst a LEFT JOIN Institute b ON(a.Institut_id=b.Institut_id AND b.Institut_id=b.fakultaets_id)
                                    WHERE a.user_id='$user_id' AND a.inst_perms='admin' AND NOT ISNULL(b.Institut_id)");
            if ($db->next_record()){
                $this->fak_admins[$user_id] = true;
                return true;
            } else {
                $this->fak_admins[$user_id] = false;
                return false;
            }
        }
    }
}

require_once 'lib/plugins/plugins.inc.php';

?>
