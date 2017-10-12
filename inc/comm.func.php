<?php

/**
 * system global functions
 * @author stcer 2007-12-04 9:26
 */

// ---------------------------------
// compatible functions (php5)
// ---------------------------------
if(!function_exists('array_combine')){
	function array_combine($a1, $a2) {
		$a1 = array_values($a1);
		$a2 = array_values($a2);
		$c1 = count($a1);
		$c2 = count($a2);

		if ($c1 != $c2) return false;
		if ($c1 <= 0) return false;

		$output=array();
		for($i = 0; $i < $c1; $i++) {
			$output[$a1[$i]] = $a2[$i];
		}
		return $output;
	}
}

if (!function_exists('file_get_contents')){
    /**
     * @access  public
     * @param   string  $file
     * @return  mix
     */
    function file_get_contents($file){
        if (($fp = @fopen($file, 'rb')) === false){
            return false;
        }else{
            $fsize = @filesize($file);
            if ($fsize){
                $contents = fread($fp, $fsize);
            }else{
                $contents = '';
            }
            fclose($fp);
        }
        return $contents;
    }
}

if (!function_exists('file_put_contents')){
    /**
     * @access  public
     * @param   string  $file
     * @param   mix     $data
     * @return  int
     */
    function file_put_contents($file, $data){
        $contents = (is_array($data)) ? implode('', $data) : $data;
        if (($fp = @fopen($file, 'wb')) === false){
            return 0;
        }else{
            $bytes = fwrite($fp, $contents);
            fclose($fp);
            return $bytes;
        }
    }
}
/**
 * test var
 */
function track($data, $title = ''){
	if( DEBUG_TRACK === true ){
		$i = 0;
		$info = debug_backtrace();
		$code = "-- Backtrace(<b>{$title}</b>) --<br/>";
        foreach( $info as $trace ) {
            if($trace["function"] != "track") {
                $code .='#' . ++$i . '--' . $trace["file"];
                $code .="(".$trace["line"]."): ";
                if( $trace["class"] != "" )
                    $code .=$trace["class"].".";
               $code .= $trace["function"];
               $code .= "<br/>";
            }
        }
        
        $code .= '<pre>';
		$code .= var_export($data, true);
		$code .= '</pre>';
        
		$GLOBALS['__TRACK_INFO'][] = $code;
	}
}
/**
 * return a format value or a specified default
 */
define( "_NOTRIM", 0x0001 );
define( "_ALLOWHTML", 0x0002 );
define( "_ALLOWRAW", 0x0004 );
define( "_NOMAGIC", 0x0008 );
function format($result, $def=null, $mask=0){
    if(!empty($result)) {
        if(is_array($result)){
            foreach ($result as $key=>$element){
                $result[$key] = format($result[$key], $def, $mask);
            }
        }else{
            if (!($mask&_NOTRIM)){
                $result = trim($result);
            }
            
            if (!is_numeric( $result)) {
                if (!($mask&_ALLOWHTML)) 
                    $result = strip_tags($result);
            }elseif(!($mask&_ALLOWRAW)){
                $result = $result;
            }
            
            if (!get_magic_quotes_gpc() && !($mask&_NOMAGIC)) {
                $result = addslashes( $result );
            }
        }
        return $result;
    } else {
        return $def;
    }
}

function daddslashes($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = daddslashes($val);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}

/**
 * chinese cut
 *
 * @param string $str object string
 * @param int $start start postion
 * @param int $len cut len
 * @return string
 */
function substring($str, $start, $len){
	$strlen = strlen($str);
	if($start > $strlen){
		return "";
	}
	for($i = 0; $i < $start; $i++){
		if(ord($str[$i]) > 0x7f ){
			$start++;
			$i++;
		}
	}
	for($i=0; $i < $len; $i++) {
		if(ord($str[$i+$start]) > 0x7f){
			$len++;
			$i++;
		}
	}
	return substr($str, $start, $len);
}

function strcut($str, $len, $suffix = '...') {
    if(mb_strlen($str, 'utf-8') > $len){
        return mb_substr($str, 0, $len, 'utf-8') . $suffix;
    }
    return $str;
}

function iif(){
	$argsArr = func_get_args();
	$argsCount = count($argsArr);
	if($argsCount < 2){
		return $argsArr[0];
	}
	
    if($argsCount == 3 && preg_match('/^[>=<]{1,2}$/', $argsArr[2])){
        $operator = array_pop($argsArr);
        if($operator == '='){
            $operator = '==';
        }
        eval('$flag=' . $argsArr[0] . $operator . $argsArr[1] . ';');
        return $flag ? $argsArr[2] : $argsArr[3];
    }
    
	foreach ($argsArr as $val){
        if(!empty($val)){
            return $val;
        }
    }
    return $val;
}

/**
 * del empty element and trim element
 *
 * @param array $arr
 * @param boolean $trim
 */
function array_remove_empty(& $arr, $trim = true){
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            array_remove_empty($arr[$key]);
        } else {
            $value = trim($value);
            if ($value == '') {
                unset($arr[$key]);
            } elseif ($trim) {
                $arr[$key] = $value;
            }
        }
    }
}

/**
 * get a col values
 *
 * @param array $arr
 * @param string $col
 *
 * @return array
 */
function array_col_values(& $arr, $col){
    $ret = array();
    foreach ($arr as $row) {
        if (isset($row[$col])) { $ret[] = $row[$col]; }
    }
    return $ret;
}

/**
 * array to hashmap
 *
 * @param array $arr
 * @param string $keyField
 * @param string $valueField
 *
 * @return array
 */
function array_to_hashmap(& $arr, $keyField, $valueField = null) {
    $ret = array();
    if ($valueField) {
        foreach ($arr as $row) {
            $ret[$row[$keyField]] = $row[$valueField];
        }
    } else {
        foreach ($arr as $row) {
            $ret[$row[$keyField]] = $row;
        }
    }
    return $ret;
}

/**
 * @package     BugFree  
 * @version     $id: FunctionsMain.inc.php,v 1.32 2005/09/24 11:38:37 wwccss Exp $  
 *  
 * Sort an two-dimension array by some level two items use array_multisort() function.  
 *  
 * sysSortArray($Array,"Key1","SORT_ASC","SORT_RETULAR","Key2"����)  
 * @author                      Chunsheng Wang <wwccss@263.net>  
 * @param  array   $data   	the array to sort.  
 * @param  string  $key1    the first item to sort by.  
 * @param  string  $order  	the order to sort by("SORT_ASC"|"SORT_DESC")  
 * @param  string  $key_type   	the sort type("SORT_REGULAR"|"SORT_NUMERIC"|"SORT_STRING")  
 * @return array                sorted array.  
 * 
 * @example
 *
	$arr = array( 
	 	array('size' => '1235', 'type' => 'jpe'),
		array('size' => '153',	'type' => 'jpe')
	)
 	$temp = array_sort($arr, "size", "SORT_ASC");
	print_r($temp); 
 *
 */ 
function array_sort($data, $key, $order = "SORT_ASC", $key_type = "SORT_REGULAR"){
	if(!is_array($data)){
		return $data;
	}
	// Get args number.
	$arg_count = func_num_args();
	
	// Get keys to sort by and put them to rule array.
	for($i = 1; $i < $arg_count; $i++){
		$arg = func_get_arg($i);
		if(!eregi("SORT", $arg)){
			$key_name_list[] = $arg;
			$rule[]    = '$'.$arg;
		}else{
			$rule[]    = $arg;
		}
	}
	// Get the values according to the keys and put them to array.
	foreach($data as $key => $info){
		foreach($key_name_list as $key_name){
			if(!is_array(${$key_name})){
				${$key_name} = array();
			}
			${$key_name}[$key] = $info[$key_name];
		}
	}
	// Create the eval string and eval it.
	$eval_string = 'array_multisort('.join(",", $rule).', $data);';
	eval ($eval_string);
	
	return $data;
}

/**
 * real ip
 *
 * @access  public
 * @return  string
 */
function real_ip(){
    static $realip = NULL;

    if ($realip !== NULL){
        return $realip;
    }

    if (isset($_SERVER)){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
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

function http404(){
	header("HTTP/1.0 404 Not found ");
    require(SI_ROOT.'404.html');
	exit;
}

function http501(){
	header("HTTP/1.0 501 Not Implemented ");
	exit;
}

function redirect($url){
    //header('Location:' . $url);
    echo "<script>window.location.href='{$url}';</script>";
    exit;
}

function alert($message){
    echo "<script>alert('{$message}');</script>";
}

function windowclose() {
    echo "<script>window.opener=null; window.close();</script>";
    exit;
}

function msgbox($message, $url) {
    alert($message);
    redirect($url);
}

function show_error($msg){
    echo '<p class="errors">错误:' . $msg . '<p>';
    exit;
}

function murl($_c, $params = null, $lang = true){
    if(strpos($_c, 'http://') !== false){
        if($lang && LANG == 'en'){
            if(substr($_c, -1) == '?'){
                return $_c . '&lang=' . LANG;
            } else {
                return $_c . '?lang=' . LANG;
            }
        } else {
            return $_c;
        }
    }
    if(Cnfdb::get('urlRewrite')){
        return urlRewrite($_c, $params);
    }
    
    $url = BASE_URL . 'm.php?';
    if(!$_c)
        return "#none";
    $tmp = explode('.', $_c);
    $url .= '_c=' . $tmp[0];

    if(isset($tmp[1]))
        $url .= '&_a=' . $tmp[1];
    $args = '';
    if($params){
        if(is_array($params)){
            //$args = http_build_query($params);
            foreach($params as $key =>$v){
                $args .=$key . '=' . $v . '&';
            }
            $args = rtrim($args,'&');
        }elseif(is_numeric($params)){
            $args = 'id=' . $params;
        }
    }
    
    if($lang && LANG == 'en'){
        $args .= '&lang=' . LANG;
    }

    return $url . '&' . $args;
}

function urlRewrite($_c, $params = null, $lang= true){

    $suffix = '.html';
    $url = BASE_URL;
    $tmp = explode('.', $_c);
    $url .= $tmp[0] . '/';
    if(isset($tmp[1]) && ($tmp[1] != 'detail'))
        $url .= $tmp[1] . '/';
        
    if($lang && LANG == 'en'){
        $url .= '?lang=' . LANG;
    }
    
    if(!$params){
        return $url;
    }

    if(is_numeric($params)){
        $url .= $params . $suffix;
    } else if(is_array($params)){
        if(count($params) == 1 && isset($params['id'])){
            $url .= $params['id'] . $suffix;
            return $url;
        }

        foreach ($params as $key => $value) {
            $url .= $key . '-' . $value . '/';
        }
    }
    
    return $url;
}

function getRid($rid = null){
    $array = array( 
        'beijing' => '北京市', 
        'shanghai' => '上海市', 
        'tianjin' => '天津市', 
        'guangdong' => '广东省', 
        'zhejiang' => '浙江省', 
        'jiangsu' => '江苏省', 
        'fujian' => '福建省', 
        'hunan' => '湖南省', 
        'hubei' => '湖北省', 
        'chongqing' => '重庆市', 
        'liaoning' => '辽宁省', 
        'jilin' => '吉林省', 
        'heilongjiang' => '黑龙江省', 
        'hebei' => '河北省', 
        'henan' => '河南省', 
        'shandong' => '山东省', 
        'shanxi' => '陕西省', 
        'gansu' => '甘肃省', 
        'qinghai' => '青海省', 
        'xinjiang' => '新疆维吾尔自治区', 
        'shannxi' => '山西省', 
        'sichuan' => '四川省', 
        'guizhou' => '贵州省', 
        'anhui' => '安徽省', 
        'jiangxi' => '江西省', 
        'yunnan' => '云南省', 
        'neimenggu' => '内蒙古自治区', 
        'guangxi' => '广西壮族自治区', 
        'xizang' => '西藏自治区', 
        'ningxia' => '宁夏回族自治区', 
        'hainan' => '海南省'
        /*0 => '全国', 
        1 => '北京市', 
        2 => '上海市', 
        3 => '天津市', 
        4 => '广东省', 
        5 => '浙江省', 
        6 => '江苏省', 
        7 => '福建省', 
        8 => '湖南省', 
        9 => '湖北省', 
        10 => '重庆市', 
        11 => '辽宁省', 
        12 => '吉林省', 
        13 => '黑龙江省', 
        14 => '河北省', 
        15 => '河南省', 
        16 => '山东省', 
        17 => '陕西省', 
        18 => '甘肃省', 
        19 => '青海省', 
        20 => '新疆维吾尔自治区', 
        21 => '山西省', 
        22 => '四川省', 
        23 => '贵州省', 
        24 => '安徽省', 
        25 => '江西省', 
        26 => '云南省', 
        27 => '内蒙古自治区', 
        28 => '广西壮族自治区', 
        29 => '西藏自治区', 
        30 => '宁夏回族自治区', 
        31 => '海南省'*/
    );
    
    if($rid){
        if(isset($array[$rid]))
            return $array[$rid];
        return '';
    }

    return $array;
}
function forlist($arr = array()){
    foreach($arr as $key => $value){
        return li_html($key,$value);
    }
}
function li_html($html){
    return $html;
}
    function getData($tb_name,$conds,$url,$nums = 5){
        $table = getDTable($tb_name);
        $list = $table->getList($nums,$conds);
        if(is_array($list)){
            foreach($list as $info){
                echo "<li><a class=\"news\" href=\"".url($url,$info['id'])."\">".cuttitle_b($info['title'],0,24)."</a></li>";
            }
        }
    }
    
    function getThemecat($id){
        $Tcat = getDTable('theme_cat');
        $info = $Tcat->getRow($id);
        return $info;
    }
    function getTheme($cid){
        $theme = getDTable('theme');
        $list = $theme->getList(12,"And cid = {$cid}");
        if(is_array($list)){
            foreach($list as $info){
               // echo "<li><a href=\"".url('theme.detail',$info['id'])."\">".cuttitle($info['title'],0,24)."</a></li>";
                echo "<li><a href=\"".url('theme.detail',$info['id'])."\">{$info['title']}</a></li>";
            }
        }
    }
?>