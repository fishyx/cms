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

if (!defined('HTML_FORM_TEXTAREA_WT')) {
    define('HTML_FORM_TEXTAREA_WT', 55);
}

if (!defined('HTML_FORM_TEXTAREA_HT')) {
    define('HTML_FORM_TEXTAREA_HT', 6);
}

if (!defined('HTML_FORM_TH_ATTR')) {
    define('HTML_FORM_TH_ATTR', 'align="right" ');
}

if (!defined('HTML_FORM_TD_ATTR')) {
    define('HTML_FORM_TD_ATTR', '');
}

if (!defined('HTML_FORM_ERROR_WRAP')) {
    define('HTML_FORM_ERROR_WRAP', '<span class="form_error">%s</span>');
}

require_once dirname(__FILE__) . '/row.php';
require_once dirname(__FILE__) . '/element.php';

class HtmlForm extends HtmlRow {
    
    public $fileds = array();
    
    var $hiddens = array();
    
    var $spRows = array();
    
    var $form;
    
    var $submit;
    
    var $reset;
    
    var $err = array();
    
    var $errWrap = HTML_FORM_ERROR_WRAP;
    
    function HtmlForm($data = array(), $action = ''){
        $this->setData($data);
        $this->form($action);
    }

    function setErr($key, $msg = ''){
        if(is_array($key)){
            $this->err = $this->err + $key; 
        }else{
            $this->err[$key] = $msg;
        }
    }
    
    function setErrWrap($pattern){
        $this->errWrap = $pattern;
    }

// form element

    function form($action = '', $name = '', $attr = '', $method = 'post', $enctype = 'multipart/form-data', $target = ''){
        if(!$action){
            return;
        }
        
        $str = "<form action=\"" . $action . "\" method=\"$method\"";
        if ($name) {
            $str .= " name=\"$name\"";
        }
        if ($target) {
            $str .= " target=\"$target\"";
        }
        if ($enctype) {
            $str .= " enctype=\"$enctype\"";
        }
        $str .= ' ' . $attr . ">\n";
        $str .= "%s</form>\n\n";
        
        $this->form = $str;
    }
    
    function _buildDefaultHeader(){
        if(!is_array($this->data)){
            return false;
        }
        $thead = array_keys($this->data);
        foreach ($thead as $index => $key) {
            if(is_numeric($key)){
                unset($thead[$index]);
            }
        }
        if($thead){
            $thead = array_combine($thead, $thead);
            $this->header = $thead;
        }
        return true;
    }
    
    function text($key, $size = HTML_FORM_TEXT_SIZE, $attr = ''){
        $this->fileds[$key] = HtmlElement::text($key, $this->data[$key], $size, $attr);
    }
    
    function password($key, $size = HTML_FORM_TEXT_SIZE, $attr = ''){
        $this->fileds[$key] = HtmlElement::password($key, $this->data[$key], $size, $attr);
    }
    
    function textarea($key, $rows = HTML_FORM_TEXTAREA_HT, $cols = HTML_FORM_TEXTAREA_WT, $attr = ''){
        $this->fileds[$key] = HtmlElement::textarea($key, $this->data[$key], $rows, $cols, $attr);
    }
    
    function select($key, $values, $attr = ''){
        $this->fileds[$key] = HtmlElement::select($key, $values, $this->data[$key], $attr);
    }
    
    function radios($key, $values, $attr = ''){
        $this->fileds[$key] = HtmlElement::radios($key, $values, $this->data[$key], $attr);
    }
    
    function checkboxs($key, $values, $attr = '', $sp = '', $pre = '', $post = ''){
        $this->fileds[$key] = HtmlElement::checkboxs($key . '[]', $values, $this->data[$key], $attr, $sp, $pre, $post);
    }
    
    function file($key, $exts = UPLOAD_EXTS_IMGS, $filesrc = '<a href="%s" target="_blank">已上传</a>'){
        $html = '';
        if($this->data[$key]){
            $html = sprintf($filesrc, $this->data[$key]);
        }else{
            $html = $exts;
        }
        $this->fileds[$key] = HtmlElement::file($key);
        $this->fileds[$key] .= $html;
    }
    
    function img($key){
        $html = HtmlElement::file($key);
        if($this->data[$key]){
            $html .= '<br />';
            $html .= image(array('src'=>$this->data[$key], 'width' => 150));
            $html .= '<input class="none" type="checkbox" value="1" name="img_del">删除图片';
        }
        $this->fileds[$key] = $html;
    }
    
    function addHidden($key, $value, $attr = ''){
        $this->hiddens[$key] = HtmlElement::hidden($key, $value, $attr);
    }
    
    function hidden($key, $attr = ''){
        $this->hiddens[$key] = HtmlElement::hidden($key, $this->data[$key], $attr);
    }
    
    function submit($title = 'submit', $attr = ''){
        $this->submit = HtmlElement::submit($title, $attr);
    }
    
    function reset($title = 'reset', $attr = ''){
        $this->reset = HtmlElement::reset($title, $attr);
    }
    
// end

// extract elements
    function blank($title = ''){
        static $wrapCounter;
        $key = '__blank' . $wrapCounter++;
        $this->fileds[$key] = "<tr><td class=\"blank\" colspan=\"2\">{$title}</td></tr>\n";
        $this->spRows[] = $key;
    }
    
    function wrapStart($id){
        static $wrapCounter;
        $key = '__wrap' . $wrapCounter++;
        
        $this->fileds[$key] = "<tbody id=\"{$id}\">\n";
        $this->spRows[] = $key;
    }
    
    function wrapEnd(){
        static $wrapCounter;
        $key = '__wrap_end' . $wrapCounter++;
        $this->fileds[$key] = "</tbody>\n";
        $this->spRows[] = $key;
    }
    
    function fill($html){
        static $counter;
        $key = '__fill' . $counter++;
        $this->fileds[$key] = $html;
        $this->spRows[] = $key;
    }
    
    function editor($key, $width = '98%', $height = '500px'){
        ob_start();
        getEditor($key, $this->data[$key], $width, $height);
        $this->fileds[$key] = ob_get_contents();
        ob_end_clean();
        //exit;
    }
    
    function date($key){
        $this->fileds[$key] = "<script>new DateInput('{$key}', '{$this->data[$key]}')</script>";
    }
    
    function region($key){
        $this->setPattern($key, selectRegion($key, $this->data[$key]));
    }
    
    function gcat($key, $scriptVarName = ''){
        $this->setPattern($key, selectGoodsCat($key, $this->data[$key], false, $scriptVarName));
    }

// end

    function setCallback($key, $callback = null, $globals = array()){
        $this->fileds[$key] = '';
        parent::setCallback($key, $callback, $globals);
    }
    
    function setPattern($key, $filedPattern = ''){
        $this->fileds[$key] = '';
        parent::setPattern($key, $filedPattern);
    }
    
    function getHtml(){
        if(!is_array($this->header) && !$this->_buildDefaultHeader()){
            return '';
        }
        
        $html = '';
        foreach ($this->hiddens as $hidden) {
            $html .= $hidden;
        }
        
        $id = $this->htmlId ? ' id="' . $this->htmlId . '"' : '';
        $html .=  '<table border="1" cellspacing="0" cellpadding="4" class="form "' . $id . '>' . "\n";
        foreach($this->fileds as $key => $filedValue){
            if(in_array($key, $this->spRows)){
                $html .= $filedValue;
            }else{
                $html .= $this->_buildRow($key, $filedValue);
            }
        }
        
        if($this->submit || $this->reset){
            $html .= "<tr class=\"submitRow\">\n\t<th>&nbsp;</th>\n";
            $html .= "\t<td>{$this->submit} {$this->reset}</td>\n</tr>\n";
        }
        $html .= "</table>\n";
        
        if($this->form){
            $html = sprintf($this->form, $html);
        }
        
        if($this->showStyle){
            $html .= $this->getStyle();
        }
        return $html;
    }
    
    function _buildRow($key, $filedValue){
        $html = '';
        
        // th
        $thAttr = $this->thAttr($key);
        if(!$thAttr){
            $thAttr = HTML_FORM_TH_ATTR;
        }
        $html .= "<tr>\n\t<th {$thAttr}>{$this->header[$key]}</th>\n";
        
        // td
        $tdAttr = $this->tdAttr($key, $this->data);
        if(!$tdAttr){
            $tdAttr = HTML_FORM_TD_ATTR;
        }
        if(isset($this->callback[$key])){
             // field callback
             if(is_array($this->callback[$key])){
                  $filedValue = call_user_method(
                      $this->callback[$key][1], 
                      $this->callback[$key][0], 
                      $key, $this->data
                  );
             }else{
                  $filedValue = call_user_func(
                      $this->callback[$key], 
                      $key, $this->data
                  );
             }
        }elseif(isset($this->pattern[$key])){
            // field Pattern
            $filedValue = $this->parsePattern($this->pattern[$key], $this->data);
        }
        
        // error msg
        $errmsg = '';
        if($this->err[$key]){
            $errmsg = sprintf($this->errWrap, $this->err[$key]);
        }
        
        $html .= "\t<td {$tdAttr}>{$filedValue}{$errmsg}</td>\n</tr>\n";
        return $html;
    }
    
    function dispaly(){
        echo $this->getHtml();
    }
    
    function getStyle(){
        return '
<style type="text/css">
    .form{border-collapse:collapse; border:1px solid #ddd}
    .form td{ border: 1px solid #e7e7e7; }
    .form th{ border: 1px solid #ddd; background:#fff; }
</style>';
    }
}

?>
