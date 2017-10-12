<?php
/*
********************************************************
Html package
--------------------------------------------------------
Version  : 0.0.1 PHP >= 4.3
Date     : 2008-07-14
Web site : www.jc001.cn
Author   : stcer ab12cxyh@163.com(msn)
********************************************************
You can redistribute and modify it even for commercial usage
*/

if (!defined('HTML_FORM_TEXT_SIZE')) {
    define('HTML_FORM_TEXT_SIZE', 40);
}

if (!defined('HTML_FORM_MAX_FILE_SIZE')) {
    define('HTML_FORM_MAX_FILE_SIZE', 1048576); // 1 MB
}

if (!defined('HTML_FORM_PASSWD_SIZE')) {
    define('HTML_FORM_PASSWD_SIZE', 8);
}

class HtmlElement {
	
	static function hidden($name, $value, $attr = ''){
        return "<input type=\"hidden\" name=\"{$name}\" value=\"{$value}\" $attr/>\n";
    }
    
	static function text($name, $value = null, $size = HTML_FORM_TEXT_SIZE, $attr = '', $maxlength = 0){
        $str  = '<input type="text" name="' . $name . '" ';
        $str .= 'size="' . $size . '" value="' . $value . '" ';
        if ($maxlength) {
            $str .= 'maxlength="' . $maxlength. '" ';
        }
        return $str . $attr . "/>\n";
    }
	
	static function password($name, $value = null,  $size = HTML_FORM_PASSWD_SIZE, $attr = '', $maxlength = 0){
        $str  = '<input type="password" name="' . $name . '" ';
        $str .= 'size="' . $size . '" value="' . $value . '" ';
        if ($maxlength) {
            $str .= 'maxlength="' . $maxlength. '" ';
        }
        return $str . $attr . "/>\n";
    }
    
	static function select($name, $values, $sel = '', $attr = '', $choose = null){
        if(!is_array($values)){
            return '';
        }
        
        $html = '';
        $select = false;
        if($choose){
            $select = ($sel === '') ? ' selected="true"' : '';
            $html = "\t<option value='' {$select}>{$choose}</option>\n";
        }
        
        foreach ($values as $key => $title){
            $select = !$select && ($sel == $key) ? ' selected="true"' : '';
            $html .= "\t<option value=\"{$key}\"{$select}>{$title}</option>\n";
        }
        
        return "\n<select name=\"{$name}\" {$attr}>\n{$html}</select>\n";
    }
	
	static function checkbox($name, $value, $default = null, $title = '', $attr = ''){
        $str = "<input type=\"checkbox\" name=\"{$name}\"  id=\"{$name}\" value=\"{$default}\" {$attr}";
        if ($default == $value) {
            $str .= ' checked="checked"';
        }
        $str .= "/>\n";
		if($title){
        	$str .= "<label for=\"{$name}\">{$title}</label>";
        }
        return $str;
    }
    
	static function checkboxs($name, $values, $selected = array(), $attr = null, $separator = '', $htmlItemPre = '', $htmlItemPost = ''){
		$ix = 0;
	    if (!is_array($selected)) {
	        $selected = array($selected);
	    }
	    $str = '';
	    foreach ($values as $value => $title) {
	        $value_h = htmlspecialchars($value);
	        $title = nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($title)));
	        $id = $name . $ix++;
	        
	        $str .= $htmlItemPre;
	        $str .= "<input name=\"{$name}\" type=\"checkbox\" id=\"{$id}\" value=\"{$value_h}\" ";
	        if (in_array($value, $selected)) {
	            $str .= "checked=\"checked\"";
	        }
	        $str .=  " {$attr} />";
	        $str .=  "<label for=\"{$id}\">{$title}</label>";
	        $str .=  $separator;
	        $str .=  $htmlItemPost;
	        
	        $ix++;
	        $str .=  "\n";
	    }
	    return $str;
    }
    
	static function radio($name, $value, $default = false, $title = '', $attr = ''){
        $str = '<input type="radio" name="' . $name . '"' .
        	   ' id="' . $name . '"' .
               ' value="' . $value . '"' .
               ($default == $value ? ' checked="checked"' : '') .
               ' ' . $attr . "/>\n";
               
        if($title){
        	$str .= "<label for=\"{$name}\">{$title}</label>";
        }
        return $str;
    }
    
	static function radios($name, $values, $checked = null, $extra = null, $separator = '') {
	    $ix = 0;
	    $str = '';
	    foreach ($values as $value => $title) {
	        $value_h = htmlspecialchars($value);
	        $title = nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($title)));
	        $id = $name . $ix++;
	        $str .= "<input name=\"{$name}\" type=\"radio\" id=\"{$id}\" value=\"{$value_h}\" ";
	        if ($value == $checked) {
	            $str .= "checked=\"checked\"";
	        }
	        $str .= " {$extra} />";
	        $str .= "<label for=\"{$id}\">{$title}</label>";
	        $str .= $separator;
	        $ix++;
	        $str .= "\n";
	    }
	    return $str;
	}
    
	static function textarea($name, $value = null, $rows = 5, $cols = 40, $attr = '', $maxlength = 0){
        $str  = '<textarea name="' . $name . '" rows="' . $rows . '"';
        $str .= ' cols="' . $cols . '" ';
        if ($maxlength) {
            $str .= 'maxlength="' . $maxlength. '" ';
        }
        $str .=  $attr . '>';
        $str .= $value;
        $str .= "</textarea>\n";

        return $str;
    }
    
	static function file($name = 'userfile', $maxsize = HTML_FORM_MAX_FILE_SIZE, $size = HTML_FORM_TEXT_SIZE, $accept = '', $attr = ''){
        $str  = "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"{$maxsize}\"/>\n";
        $str .= "<input type=\"file\" name=\"{$name}\" size=\"{$size}\" ";
        if ($accept) {
            $str .= "accept=\"{$accept}\"";
        }
        return $str . $attr . "/>\n";
    }
    
	static function submit($title = 'Submit Changes', $attr = ''){
        return "<input type=\"submit\" value=\"{$title}\" {$attr}/>\n";
    }
    
    static function reset($title = 'Clear contents', $attr = ''){
        return "<input type=\"reset\" value=\"{$title}\" {$attr}/>\n";
    }
}

?>
