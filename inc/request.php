<?php
/**
 * @version        $Id: request.php 17293 2010-05-27 10:11:56Z infograf768 $
 * @package        j.Framework
 * @subpackage    Environment
 * @copyright    Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
//defined('JPATH_BASE') or die;

/**
 * Create the request global object
 */
$GLOBALS['_JREQUEST'] = array();

/**
 * Set the available masks for cleaning variables
 */
define('JREQUEST_NOTRIM',    1);
define('JREQUEST_ALLOWRAW',    2);
define('JREQUEST_ALLOWHTML',4);

/**
 * JRequest Class
 *
 * This class serves to provide the Joomla Framework with a common interface to access
 * request variables.  This includes $_POST, $_GET, and naturally $_REQUEST.  Variables
 * can be passed through an input filter to avoid injection or returned raw.
 *
 * @static
 * @package        j.Framework
 * @subpackage    Environment
 * @since        1.5
 */
class JRequest
{
    /**
     * Gets the full request path.
     *
     * @return string
     */
    public static function getURI()
    {
        $uri = &JFactory::getURI();
        return $uri->toString(array('path', 'query'));
    }

    /**
     * Gets the request method.
     *
     * @return string
     */
    public static function getMethod()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        return $method;
    }

    /**
     * Fetches and returns a given variable.
     *
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * an entry from $_GET, POST and PUT will result in returning an
     * entry from $_POST.
     *
     * You can force the source by setting the $hash parameter:
     *
     *    post    $_POST
     *    get        $_GET
     *    files    $_FILES
     *    cookie    $_COOKIE
     *    env        $_ENV
     *    server    $_SERVER
     *    method    via current $_SERVER['REQUEST_METHOD']
     *    default    $_REQUEST
     *
     * @param    string    $name        Variable name.
     * @param    string    $default    Default value if the variable does not exist.
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @param    string    $type        Return type for the variable, for valid values see {@link JFilterInput::clean()}.
     * @param    int        $mask        Filter mask for the variable.
     * @return    mixed    Requested variable.
     * @since    1.5
     */
    public static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0)
    {
        // Ensure hash and type are uppercase
        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        $type    = strtoupper($type);
        $sig    = $hash.$type.$mask;

        // Get the input hash
        switch ($hash){
            case 'GET':
                $input = &$_GET;
                break;
            case 'POST':
                $input = &$_POST;
                break;
            case 'FILES':
                $input = &$_FILES;
                break;
            case 'COOKIE':
                $input = &$_COOKIE;
                break;
            case 'ENV':
                $input = &$_ENV;
                break;
            case 'SERVER':
                $input = &$_SERVER;
                break;
            default:
                $input = &$_REQUEST;
                $hash = 'REQUEST';
                break;
        }

        if (isset($GLOBALS['_JREQUEST'][$name]['SET.'.$hash]) && ($GLOBALS['_JREQUEST'][$name]['SET.'.$hash] === true)) {
            // Get the variable from the input hash
            $var = (isset($input[$name]) && $input[$name] !== null) ? $input[$name] : $default;
            $var = self::_cleanVar($var, $mask, $type);
        }elseif (!isset($GLOBALS['_JREQUEST'][$name][$sig])){
            if (isset($input[$name]) && $input[$name] !== null) {
                // Get the variable from the input hash and clean it
                $var = self::_cleanVar($input[$name], $mask, $type);

                // Handle magic quotes compatability
                if (get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')) {
                    $var = self::_stripSlashesRecursive($var);
                }

                $GLOBALS['_JREQUEST'][$name][$sig] = $var;
            }elseif ($default !== null) {
                // Clean the default value
                $var = self::_cleanVar($default, $mask, $type);
            }else {
                $var = $default;
            }
        } else {
            $var = $GLOBALS['_JREQUEST'][$name][$sig];
        }

        return $var;
    }

    /**
     * Fetches and returns a given filtered variable. The integer
     * filter will allow only digits to be returned. This is currently
     * only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param    string    $name        Variable name.
     * @param    string    $default    Default value if the variable does not exist.
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @return    integer    Requested variable.
     * @since    1.5
     */
    public static function getInt($name, $default = 0, $hash = 'default')
    {
        return self::getVar($name, $default, $hash, 'int');
    }

    /**
     * Fetches and returns a given filtered variable.  The float
     * filter only allows digits and periods.  This is currently
     * only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param    string    $name        Variable name.
     * @param    string    $default    Default value if the variable does not exist.
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @return    float    Requested variable.
     * @since    1.5
     */
    public static function getFloat($name, $default = 0.0, $hash = 'default')
    {
        return self::getVar($name, $default, $hash, 'float');
    }

    /**
     * Fetches and returns a given filtered variable. The bool
     * filter will only return true/false bool values. This is
     * currently only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param    string    $name        Variable name.
     * @param    string    $default    Default value if the variable does not exist.
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @return    bool    Requested variable.
     * @since    1.5
     */
    public static function getBool($name, $default = false, $hash = 'default')
    {
        return self::getVar($name, $default, $hash, 'bool');
    }

    /**
     * Fetches and returns a given filtered variable. The word
     * filter only allows the characters [A-Za-z_]. This is currently
     * only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param    string    $name        Variable name.
     * @param    string    $default    Default value if the variable does not exist.
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @return    string    Requested variable.
     * @since    1.5
     */
    public static function getWord($name, $default = '', $hash = 'default')
    {
        return self::getVar($name, $default, $hash, 'word');
    }

    /**
     * Fetches and returns a given filtered variable. The cmd
     * filter only allows the characters [A-Za-z0-9.-_]. This is
     * currently only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param    string    $name        Variable name
     * @param    string    $default    Default value if the variable does not exist
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     * @return    string    Requested variable
     * @since    1.5
     */
    public static function getCmd($name, $default = '', $hash = 'default')
    {
        return self::getVar($name, $default, $hash, 'cmd');
    }

    /**
     * Fetches and returns a given filtered variable. The string
     * filter deletes 'bad' HTML code, if not overridden by the mask.
     * This is currently only a proxy function for getVar().
     *
     * See getVar() for more in-depth documentation on the parameters.
     *
     * @param    string    $name        Variable name
     * @param    string    $default    Default value if the variable does not exist
     * @param    string    $hash        Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     * @param    int        $mask        Filter mask for the variable
     * @return    string    Requested variable
     * @since    1.5
     */
    public static function getString($name, $default = '', $hash = 'default', $mask = 0)
    {
        // Cast to string, in case JREQUEST_ALLOWRAW was specified for mask
        return (string) self::getVar($name, $default, $hash, 'string', $mask);
    }

    /**
     * Set a variabe in on of the request variables.
     *
     * @param    string    $name        Name
     * @param    string    $value        Value
     * @param    string    $hash        Hash
     * @param    boolean    $overwrite    Boolean
     * @return    string    Previous value
     * @since    1.5
     */
    public static function setVar($name, $value = null, $hash = 'method', $overwrite = true)
    {
        //If overwrite is true, makes sure the variable hasn't been set yet
        if (!$overwrite && array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
        }

        // Clean global request var
        $GLOBALS['_JREQUEST'][$name] = array();

        // Get the request hash value
        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }

        $previous    = array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

        switch ($hash)
        {
            case 'GET' :
                $_GET[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'POST' :
                $_POST[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'COOKIE' :
                $_COOKIE[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'FILES' :
                $_FILES[$name] = $value;
                break;
            case 'ENV':
                $_ENV['name'] = $value;
                break;
            case 'SERVER':
                $_SERVER['name'] = $value;
                break;
        }

        // Mark this variable as 'SET'
        $GLOBALS['_JREQUEST'][$name]['SET.'.$hash] = true;
        $GLOBALS['_JREQUEST'][$name]['SET.REQUEST'] = true;

        return $previous;
    }

    /**
     * Fetches and returns a request array.
     *
     * The default behaviour is fetching variables depending on the
     * current request method: GET and HEAD will result in returning
     * $_GET, POST and PUT will result in returning $_POST.
     *
     * You can force the source by setting the $hash parameter:
     *
     *    post    $_POST
     *    get        $_GET
     *    files    $_FILES
     *    cookie    $_COOKIE
     *    env        $_ENV
     *    server    $_SERVER
     *    method    via current $_SERVER['REQUEST_METHOD']
     *    default    $_REQUEST
     *
     * @param    string    $hash    to get (POST, GET, FILES, METHOD).
     * @param    int        $mask    Filter mask for the variable.
     * @return    mixed    Request hash.
     * @since    1.5
     */
    public static function get($hash = 'default', $mask = 0)
    {
        $hash = strtoupper($hash);

        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }

        switch ($hash)
        {
            case 'GET' :
                $input = $_GET;
                break;

            case 'POST' :
                $input = $_POST;
                break;

            case 'FILES' :
                $input = $_FILES;
                break;

            case 'COOKIE' :
                $input = $_COOKIE;
                break;

            case 'ENV':
                $input = &$_ENV;
                break;

            case 'SERVER':
                $input = &$_SERVER;
                break;

            default:
                $input = $_REQUEST;
                break;
        }

        $result = self::_cleanVar($input, $mask);

        // Handle magic quotes compatability
        if (get_magic_quotes_gpc() && ($hash != 'FILES')) {
            $result = self::_stripSlashesRecursive($result);
        }

        return $result;
    }

    /**
     * Sets a request variable.
     *
     * @param    array    An associative array of key-value pairs.
     * @param    string    The request variable to set (POST, GET, FILES, METHOD).
     * @param    boolean    If true and an existing key is found, the value is overwritten, otherwise it is ignored.
     */
    public static function set($array, $hash = 'default', $overwrite = true)
    {
        foreach ($array as $key => $value) {
            self::setVar($key, $value, $hash, $overwrite);
        }
    }

    /**
     * Checks for a form token in the request.
     *
     * Use in conjuction with JHtml::_('form.token').
     *
     * @param    string    The request method in which to look for the token key.
     * @return    boolean    True if found and valid, false otherwise.
     */
    public static function checkToken($method = 'post')
    {
        $token = JUtility::getToken();
        if (!self::getVar($token, '', $method, 'alnum'))
        {
            $session = JFactory::getSession();
            if ($session->isNew() && !self::getVar('format')) {
                // Redirect to login screen.
                $app = &JFactory::getApplication();
                $return = JRoute::_('index.php');
                $app->redirect($return, JText::_('JLIB_ENVIRONMENT_SESSION_EXPIRED'));
                $app->close();
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Cleans the request from script injection.
     *
     * @return    void
     * @since    1.5
     */
    public static function clean(){
        return; 
        self::_cleanArray($_FILES);
        self::_cleanArray($_ENV);
        self::_cleanArray($_GET);
        self::_cleanArray($_POST);
        self::_cleanArray($_COOKIE);
        self::_cleanArray($_SERVER);

        if (isset($_SESSION)) {
            self::_cleanArray($_SESSION);
        }

        $REQUEST    = $_REQUEST;
        $GET        = $_GET;
        $POST        = $_POST;
        $COOKIE        = $_COOKIE;
        $FILES        = $_FILES;
        $ENV        = $_ENV;
        $SERVER        = $_SERVER;

        if (isset ($_SESSION)) {
            $SESSION = $_SESSION;
        }

        foreach ($GLOBALS as $key => $value)
        {
            if ($key != 'GLOBALS') {
                unset ($GLOBALS [ $key ]);
            }
        }
        $_REQUEST    = $REQUEST;
        $_GET        = $GET;
        $_POST        = $POST;
        $_COOKIE    = $COOKIE;
        $_FILES        = $FILES;
        $_ENV        = $ENV;
        $_SERVER    = $SERVER;

        if (isset ($SESSION)) {
            $_SESSION = $SESSION;
        }

        // Make sure the request hash is clean on file inclusion
        $GLOBALS['_JREQUEST'] = array();
    }

    /**
     * Adds an array to the GLOBALS array and checks that the GLOBALS variable is not being attacked.
     *
     * @param    array    $array    Array to clean.
     * @param    boolean    True if the array is to be added to the GLOBALS.
     * @since    1.5
     */
    static function _cleanArray(&$array, $globalise=false)
    {
        static $banned = array('_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals');

        foreach ($array as $key => $value)
        {
            // PHP GLOBALS injection bug
            $failed = in_array(strtolower($key), $banned);

            // PHP Zend_Hash_Del_Key_Or_Index bug
            $failed |= is_numeric($key);
            if ($failed) {
                jexit('Illegal variable <b>' . implode('</b> or <b>', $banned) . '</b> passed to script.');
            }
            if ($globalise) {
                $GLOBALS[$key] = $value;
            }
        }
    }

    /**
     * Clean up an input variable.
     *
     * @param mixed The input variable.
     * @param int Filter bit mask. 1=no trim: If this flag is cleared and the
     * input is a string, the string will have leading and trailing whitespace
     * trimmed. 2=allow_raw: If set, no more filtering is performed, higher bits
     * are ignored. 4=allow_html: HTML is allowed, but passed through a safe
     * HTML filter first. If set, no more filtering is performed. If no bits
     * other than the 1 bit is set, a strict filter is applied.
     * @param string The variable type {@see JFilterInput::clean()}.
     */
    static function _cleanVar($var, $mask = 0, $type=null)
    {
        // Static input filters for specific settings
        static $noHtmlFilter    = null;
        static $safeHtmlFilter    = null;

        // If the no trim flag is not set, trim the variable
        if (!($mask & 1) && is_string($var)) {
            $var = trim($var);
        }

        // Now we handle input filtering
        if ($mask & 2)
        {
            // If the allow raw flag is set, do not modify the variable
            $var = $var;
        }
        elseif ($mask & 4)
        {
            // If the allow html flag is set, apply a safe html filter to the variable
            if (is_null($safeHtmlFilter)) {
                /**
                * put your comment there...
                * 
                * @var JFilterInput
                */
                $safeHtmlFilter = & JFilterInput::getInstance(null, null, 1, 1);
            }
            $var = $safeHtmlFilter->clean($var, $type);
        }
        else
        {
            // Since no allow flags were set, we will apply the most strict filter to the variable
            if (is_null($noHtmlFilter)) {
                /**
                * put your comment there...
                * 
                * @var JFilterInput
                */
                $noHtmlFilter = & JFilterInput::getInstance(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
            }
            $var = $noHtmlFilter->clean($var, $type);
        }
        return $var;
    }
    
    public static function clearVar($var, $mask = 0, $type=null){
        return self::_cleanVar($var, $mask, $type);
    }
    
    public static function clearDescribe($string){
        /*
        $string = preg_replace('/<a.*?>(.+?)<\/a>/is', '$1', $string);
        */
        $string = preg_replace('/<a.+?>|<\/a>/is', '', $string);
        $string = preg_replace('/<script.+?<\/script\>/is', '', $string);
        $string = preg_replace('/<iframe.+?<\/iframe\>/is', '', $string);
        $string = preg_replace('/<object.+?<\/object\>/is', '', $string);
        $string = preg_replace('#http://[a-zA-Z0-9]+\.bmlink.com#is', '', $string);
        //$string = preg_replace('#http://[0-9a-zA-Z\.\/\%\?=]+#es', "JRequest::clearLink('\\0')", $string);
        return $string;
    }

    /**
     * Strips slashes recursively on an array.
     *
     * @param    array    $array        Array of (nested arrays of) strings.
     * @return    array    The input array with stripshlashes applied to it.
     */
    protected static function _stripSlashesRecursive($value){
        $value = is_array($value) ? array_map(array('JRequest', '_stripSlashesRecursive'), $value) : stripslashes($value);
        return $value;
    }
    
    
    public static function ip(){
        static $realip = NULL;
        if ($realip !== NULL){
            return $realip;
        }

        if (isset($_SERVER)){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* ȡX-Forwarded-For�е�һ����unknown����ЧIP�ַ��� */
                foreach ($arr AS $ip){
                    $ip = trim($ip);
                    if ($ip != 'unknown'){
                        $realip = $ip;
                        break;
                    }
                }
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }else{
                if (isset($_SERVER['REMOTE_ADDR'])){
                    $realip = $_SERVER['REMOTE_ADDR'];
                }else{
                    $realip = '0.0.0.0';
                }
            }
        }else{
            if (getenv('HTTP_X_FORWARDED_FOR')){
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }elseif (getenv('HTTP_CLIENT_IP')){
                $realip = getenv('HTTP_CLIENT_IP');
            }else{
                $realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }
    
    public static function isIframe($flg = null) {
        static $cache;
        if(!isset($cache)){
            $cache = (boolean)self::getVar('iframe');
        }
        if(!is_null($flg)){
            $cache = $flg;
        }
        return $cache;
    }
    
    public static function isFront($flg = null) {
        static $cache;
        if(!isset($cache)){
            $cache = defined('IN_JC001_FRONT') && IN_JC001_FRONT;
        }
        if(!is_null($flg)){
            $cache = $flg;
        }
        return $cache;
    }
    
    public static function isAjax($flg = null) {
        static $cache;
        if(!is_null($flg)){
            $cache = $flg;
        }
        return IS_AJAX || $cache;
    }
    
    function clearLink($string){
        if(strpos($string, 'jc001.cn') || strpos($string, '9zhen.com')){
            return $string;
        }
        return '';
    }
    
    
    public static function isMobile() {
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';   
        $mobile_browser = '0';   
        if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $mobile_browser++;   
        if((isset($_SERVER['HTTP_ACCEPT'])) && (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))   
            $mobile_browser++;   
        if(isset($_SERVER['HTTP_X_WAP_PROFILE']))   
            $mobile_browser++;   
        if(isset($_SERVER['HTTP_PROFILE']))   
            $mobile_browser++; 
        
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));   
        $mobile_agents = array(   
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',   
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',   
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',   
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',   
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',   
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',   
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',   
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',   
            'wapr','webc','winw','winw','xda','xda-'  
            );
        if(in_array($mobile_ua, $mobile_agents))   
            $mobile_browser++;
            
        if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)   
            $mobile_browser++;
            
        // Pre-final check to reset everything if the user is on Windows   
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)   
            $mobile_browser = 0;
            
        // But WP7 is also Windows, with a slightly different characteristic   
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)   
            $mobile_browser++;
        if($mobile_browser > 0)
            return true;
        else 
            return false;
    }
}
?>