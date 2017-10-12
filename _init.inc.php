<?php
header("Content-type: text/html; charset=utf-8"); 
define('SI_ROOT',   str_replace('\\','/',dirname(__FILE__)) . '/');
ini_set('display_errors', 0);
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED ^ E_Strict);
date_default_timezone_set('Asia/Shanghai');
$_SERVER['DOCUMENT_ROOT'] = ucfirst(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']));
if($_SERVER['DOCUMENT_ROOT'] . '/' == SI_ROOT){
    define('SI_ROOT_WEB', '/');
}else{
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $scriptName = rtrim($scriptName, '\\');
    $scriptName = rtrim($scriptName, '/');
    define('SI_ROOT_WEB', $scriptName.'/');
}
define('DEBUG', false);
define('SI_INC',     SI_ROOT . 'inc/');
define('SI_CFG',    SI_ROOT . 'config/');
define('SI_LANG',     SI_CFG . 'language/');
define('SI_APP',     SI_ROOT . 'apps/');
define('SI_MOD',     SI_ROOT . 'module/');
define('SI_TMP',     SI_ROOT . '_tmp/');
define('SI_LIB',    SI_ROOT . 'libs/');

require SI_INC . 'configs.class.php';
ob_start();
define('TABLE_PRE', Configs::get('dbprefix'));
ob_end_clean();
//define('TABLE_PRE', 'tengrui_');
$HTTP_HOST = 'http://' . $_SERVER['HTTP_HOST'] . SI_ROOT_WEB;
rtrim($HTTP_HOST, '\\');
define('BASE_URL', $HTTP_HOST);
if(!defined('DEBUG_TRACK')){
    define('DEBUG_TRACK', true);
}

/*if(PHP_VERSION < 5)
     include_once(SI_INC . 'exception.class.php');*/

/**
 * MYSQL_DEBUG_MODE
 * 
 * 1. halt error
 * 2. log query sqls
 * 4. explain sql
 */
    
if(!defined('MYSQL_DEBUG_MODE')){
    define('MYSQL_DEBUG_MODE', 3);
}

defined('IS_POST') 
    or define('IS_POST', (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'));

/**
 * DEBUG
 * 
 * 1. print process time, init set_error_handler at php4
 * 4. set shopid == 3
 * 8. enable set style,layout from REQUEST
 * 16. 
 */
if(!defined('DEBUG')){
    if(isset($_REQUEST['DEBUG'])){
        define('DEBUG', intval($_REQUEST['DEBUG']));
    }else{
        define('DEBUG', 4 + 8);
    }
}

if(DEBUG){
    require(SI_INC . 'timer.class.php');
    $timer = new Timer(true);
    if(!gav($_REQUEST, 'isAjax') && (DEBUG & 1)){
        function page_shutdown(){
            global $timer;
            echo '<br /> time:' .$timer->stop() . ' s <br />';
            debug_sql();
            if(is_array($GLOBALS['__TRACK_INFO'])){
                foreach($GLOBALS['__TRACK_INFO'] as $info){
                    echo $info;
                    echo '<br />';
                }
            }
        }
        register_shutdown_function('page_shutdown');
    }
}

/**
* put your comment there...
* @return Mysql
*/
function & getDb(){
    static $instance;
    if(!isset($instance)){
        require(SI_INC . "mysql.php");
        if(strpos($_SERVER['HTTP_HOST'], 'qj'))
            $instance = new Mysql(Configs::get('hostname'), Configs::get('username'), Configs::get('password'), Configs::get('database'));
        else
            $instance = new Mysql(Configs::get('hostname'), Configs::get('username'), Configs::get('password'), Configs::get('database'));
    }

    return $instance;
}
/**
* put your comment there...
* 
* @param mixed $name
* @return Dba
*/
function getDTable($name){
    $className = ucfirst($name);
    if(!class_exists($className)){
        $name = strtolower($name);
        $classFile = SI_MOD . $name . '.class.php';
        if(file_exists($classFile)){
            include_once($classFile);
        }
    }
    
    if(!class_exists($className)){
        include_once(SI_INC . 'dba.php');
        $class = new Dba(strtolower($name));
    }else{
        $class = new $className();
    }
    
    return $class;
}

function loadModule($name) {
    $className = ucfirst($name);
    if(!class_exists($className)){
        $name = strtolower($name);
        $classFile = SI_MOD . $name . '.class.php';
        if(file_exists($classFile)){
            include_once($classFile);
        }
    }
}

if(!defined('CLASS_ROSES_FILE')){
    define('CLASS_ROSES_FILE', SI_CFG . 'class_roses.php');
}

function &getInstance($className, $caller = null, $filepath = ''){
    static $roses;
    if(!isset($roses)){
        if(file_exists(CLASS_ROSES_FILE)){
            $roses = include(CLASS_ROSES_FILE);
        }else{
            $roses = Configs::get('ClassRoses');
        }
        
        if(!is_array($roses)){
            $roses = array();
        }
    }
    
    if(isset($roses[$className]) 
        && $roses[$className] 
        && isset($roses[$className][$caller])
    ){
        $className = $roses[$className][$caller]['classname'];
        if(isset($roses[$className][$caller]['file'])){
            $filepath = $roses[$className][$caller]['file'];
        }
    }
    
    loadClass($className, $filepath);
    $instance = new $className();
    
    return $instance;
}

if(!defined('CLASS_MAPPING_FILE')){
    define('CLASS_MAPPING_FILE', SI_CFG . 'classfiles.php');
}
function loadClass($className, $filepath = ''){
    if(class_exists($className)){
        return true;
    }
    static $roses;
    if(!isset($roses)){
        if(file_exists(CLASS_MAPPING_FILE)){
            $roses = include(CLASS_MAPPING_FILE);
        }else{
            $roses = Configs::get('ClassFiles');
        }
        if(!is_array($roses)){
            $roses = array();
        }
    }
    
    if(isset($roses[$className]) && $roses[$className]){
        $filepath = $roses[$className];
    }elseif(!$filepath){
        $filepath = SI_MOD . strtolower($className) . '.class.php';
    }
    
    if(!file_exists($filepath)){
        throw(new Exception("file({$className},{$filepath}) not found"));
    }
    
    require_once($filepath);
    if(!class_exists($className)){
        throw(new Exception("Class({$className}) not found({$filepath})"));
    }
    
    return true;
}

function & getPager($nums = '', $total = ''){
    static $pager;
    if(!isset($pager)){
        include_once(SI_INC . 'pager.class.php');
        $pager = new Pager($nums, $total);
    }
    return $pager;
}
function & getMpager($nums = '', $total = ''){
    static $pager;
    if(!isset($pager)){
        include_once(SI_INC . 'mpager.class.php');
        $pager = new Mpager($nums, $total);
    }
    return $pager;
}
function getSmarty(){
    static $smarty;
    if(!isset($smarty)){
        include_once(SI_LIB . 'Smarty/Smarty.class.php');
        if(class_exists('Smarty')){
            $smarty = new Smarty();
        }
    }
    return $smarty;
}
function setSmarty($caching = false){
    $smarty = getSmarty();
    $smarty->template_dir = SI_ROOT . 'templates/';
    $smarty->compile_dir =  SI_ROOT . 'templates_c/';
    $smarty->cache_dir = SI_ROOT  .'cache/';
    $smarty->config_dir = SI_ROOT .' libs/Smarty/';
    $smarty->left_delimiter = '{';
    $smarty->right_delimiter = '}';
    $smarty->caching = $caching;    
    //$smarty->register_prefilter('plug_prefix');
    include(SI_INC . 'helper.php');
    $smarty->registerFilter('pre','yx');
    return $smarty;
}
function yx($tpl_source, $smarty){
    $tpl_source = preg_replace('/{\s*tag_([^ \}]+)/', '{tag name="$1"', $tpl_source);
    $tpl_source = preg_replace('/{\s*blk_([^ \}]+)/', '{blk name="$1"', $tpl_source);
    return $tpl_source;
}
function getImgCode(){
    require_once(SI_INC . 'session.inc.php');
    require_once(SI_INC . 'imgcode.php');
    
    $img = new Imgcode();
    $img->image();
}
function getRight(){
    SI_INC . 'right.class.php';
    require_once(SI_INC . 'right.class.php');
    $right = new Authority();  
    return $right->get_right();
}

function checkImgCode($code){
    require_once(SI_INC . 'session.inc.php');
    require_once(SI_INC . 'imgcode.php');
    $imgcode = new Imgcode();
    return $imgcode->check($code);
}

function debug_sql($dba = null){
    include_once(SI_INC . 'html/table.php');
    $table = new HtmlTable();
    $table->disableOrder();
    
    $db = is_object($dba) ? $dba->getDbo() : getDb(); 
    $sqls = $db->getLog();
    
    echo '<p />';
    
    $table->setData($sqls, true);
    $table->setTdAttr('sql', ' width="60%"');
    $table->display();
}

function imageMini($img){
    return BASE_URL . preg_replace('/(.+)(\/[^\/]+\..{3,4})$/', '$1/thumbs$2', $img);
}

function imageUrl($img){
    return BASE_URL . $img;
}

function image($params){
    static $importJs;
    
    if(!$params["file"] && $params["src"]){
        $params["file"] = $params["src"];
    }
    $img = trim($params["file"], "/");
    $img = BASE_URL . $img;
    $r = array();
    if(!preg_match('/\.(jpg|gif|bmp|png|swf|jpeg|j)$/i', $img, $r)){
        if($params['none']){
            $img = "/images/noPic.jpg";
        }else{
            return '';
        }
    }elseif(
        $params["mini"] == 1 
        && !is_numeric(strpos($img, "/thumbs/")) 
        && !is_numeric(strpos($img, 'images'))
        ){
        $img = preg_replace('/(.+)(\/[^\/]+\..{3,4})$/', '$1/thumbs$2', $img);
        $params['error'] = true;
    }
    
    if($r[1] == 'swf'){
        if($params['trans']){
            $trans1 = '<param name=wmode value="transparent">';
            $trans2 = 'wmode="transparent"';
        }
        $html = <<<END
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"[attr]>
<param name=movie value="[src]">
<param name=quality value="high">
{$trans1}
<embed src="[src]" {$trans2} quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" 
    type="application/x-shockwave-flash"[attr]>
</embed>
</object>
END;
    }else{
        $html = '<img src="[src]"[attr][js]>';
        $js = '';
        if($params['js'] && $params['width']){
            $js = " onload='if(this.width>{$params['width']})this.width={$params['width']}'";
            unset($params['width']);
        }
        $html = str_replace('[js]', $js,  $html);
    }
   
    $attr = '';
    $attrs = array('width', 'height', 'alt', 'style', 'id');
    foreach ($params as $key => $value) {
        if(in_array($key, $attrs) && $value){
            $attr .= ' ' . $key . '="' . $value . '"';
        }
    }
    
    $html = str_replace('[src]', $img, $html);
    $html = str_replace('[attr]', $attr, $html);
    
    return $html;
}

function inAdmin(){
    return defined('IN_ADMIN') && IN_ADMIN;
}


function unhtml($content){
    $content=htmlspecialchars($content);
    $content=str_replace(chr(13),"<br>",$content);
    $content=str_replace(chr(32),"&nbsp;",$content);
    $content=str_replace("[_[","<",$content);
    $content=str_replace("]_]",">",$content);
    $content=str_replace("|_|"," ",$content);
   return trim($content);
}

function getnewslist($cid, $cond, $nums = 5){
    global $news;
    $news->setCid($cid);
    return $news->getlist($nums, $cond);
}


function _T($msg){
//    if(!isset($GLOBALS['LANG'][$msg])){
//        $GLOBALS['LANG'][$msg] = $msg;
//    }

    if(LANG != 'en'){
        $args = func_get_args();
        $args[0] = $msg;       
        return call_user_func_array("sprintf", $args);
    }

    static $langs;
    if(!isset($langs)){
        $file = SI_LANG . 'lang_en.php';
        $rows = file($file);
        foreach ($rows as $value) {
            $value = trim($value);
            if($value){
                $value = explode("=", $value, 2);
                $value = array_map("trim", $value);
                if($key = $value[0]){
                    $langs[$key] = $value[1];
                }
            }
        }
    }
    if(isset($langs[$msg])){
        $args = func_get_args();
        $args[0] = $langs[$msg];
        return call_user_func_array("sprintf", $args);
    }else{
        return $msg;
    }
}

function cuttitle($title,$start,$end){
        
        $title =  strip_tags($title); 
        $title = str_replace('&nbsp;','',$title);
        $nums = strlen($title);
        $title = mb_substr($title,$start,$end,'utf-8');  
        if($nums>($end * 2)){           
             echo $title . '...';
        }else{
            echo $title;
        }
    }
function cuttitle_b($title,$start,$end){
        $nums = strlen($title);
         $title = mb_substr($title,$start,$end,'utf-8');        
        if($nums>($end*2)){
            return  strip_tags($title."..."); 
        }else{
            return $title;
        }
    } 
function gav($arr, $key, $def = null) {
    if(is_array($arr)){
        return array_key_exists($key, $arr) ? $arr[$key] : $def;
    }else if(is_object($arr)){
        return $arr[$key];
    } else {
        throw(new Exception('arg 1 is not a array'));
        return false;
    }
}       
function setRequset(){
    return  array('hx', 'cid', 'c','_c', '_a','area','jiebie','bumen','jingyan','jieduan','style','type');
}
function url_search_set($key, $value){
    $path = parse_url($_SERVER['REQUEST_URI']);
    $query = $path['query'];
    parse_str($query, $args);

    $validAgs = setRequset();
    foreach($args as $k => $v){
        if(!in_array($k, $validAgs)){
            unset($args[$k]);
        }
    }

    $args[$key] = $value;
    return $path['path'] . '?' . http_build_query($args);
}
function getMemberSession(){
    if(isset($_SESSION['huiyuan']) && $_SESSION['huiyuan']){
        return $_SESSION['huiyuan'];
    }
    return null;
}
?>
