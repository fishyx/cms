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

require_once 'table.php';

class HtmlTableEditor {

	var $postUrl;
	
	var $editFiled = array();
	
	var $filedValues = array();
	
	var $callback = array();
	
	var $styleCatName = array(
		'input' => 'aui',
		'radio' => 'aur',
		'select' => 'aus',
		'checkbox' => 'auc',
		'date' => 'aud'
	);
	
	var $callbackNames = array(
		'radio' => 'callbackRadio',
		'select' => 'callbackSelect',
		'checkbox' => 'callbackCheckbox',
		'date' => 'callbackDate',
	);
	
	var $primaryKey;
	
    /**
    * put your comment there...
    * 
    * @var HtmlTable
    */
	var $table;
	
	var $drawSelHtmlEl = false;
	
	var $drawScripts = true;
	
	var $editAction;
	
	var $delAction;
	
	var $updateTitle;
	
	function HtmlTableEditor($table = null, $key = 'id', $url = '?'){
		$this->setTable($table);
		$this->setPrimary($key);
		$this->setPostUrl($url);
	}
	
	function &getTable(){
		return $this->table;
	}

// seter funcitons
//
	function setPrimary($name){
		$this->primaryKey = $name;
	}
	
	function setTable($obj){
		$this->table = $obj;
	}
	
	function setPostUrl($url){
		$this->postUrl = $url;
	}
	
	function setShowSelHtmlEl($flag){
		$this->drawSelHtmlEl = $flag;
	}
	
	function setShowScripts($flag){
		$this->drawScripts = $flag;
	}
	
	function setText($key){
		$this->editFiled[$key] = 'input';
	}
	
	function setRadio($key, $values){
		$this->editFiled[$key] = 'radio';
		$this->filedValues[$key] = $values;
		$this->callback[$key] = $this->callbackNames['radio'];
	}
	
	function setCheckbox($key, $values){
		$this->editFiled[$key] = 'checkbox';
		$this->filedValues[$key] = $values;
		$this->callback[$key] = $this->callbackNames['checkbox'];
	}
	
	function setSelect($key, $values){
		$this->editFiled[$key] = 'select';
		$this->filedValues[$key] = $values;
		$this->callback[$key] = $this->callbackNames['select'];
	}
	
	function setDate($key, $title = 'update'){
		$this->editFiled[$key] = 'date';
		$this->callback[$key] = $this->callbackNames['date'];
		$this->updateTitle = $title;
	}
	
	function setEdit($title = 'edit', $op = 'ac=', $link = ''){
		$this->editAction = "<a href=\"?{$op}edit&{$this->primaryKey}=[{$this->primaryKey}]&{$link}\">{$title}</a>";
	}
	
	function setEditPattern($patten){
		$this->editAction = $patten;
	}
	
	function setDel($title = 'del', $op = 'ac=', $link = ''){
		$this->delAction = "<a class=\"{$title}\" href=\"?{$op}del&{$this->primaryKey}=[{$this->primaryKey}]&{$link}\">{$title}</a>";
	}
	
	function setDelPattern($patten){
		$this->delAction = $patten;
	}
	
	function setAjaxDebug($flag){
		$this->ajaxDebug = $flag;
	}

// main functon
//
	function getHtml(){
		if(!$this->primaryKey){
			die('primaryKey is null!');
		}
		
		if(!is_object($this->table) || !is_a($this->table, 'HtmlTable')){
			die('table object error!');
		}
		
		if($this->editAction || $this->delAction){
			$this->table->setHeader('Action', '&nbsp;');
			$this->table->setThAttr('Action', ' width="60"');
			$this->table->setPattern('Action', $this->editAction . ' ' . $this->delAction);
		}
		
		// set attr
		foreach ($this->editFiled as $filed => $type) {
			$attr = "id=\"{$filed}_[{$this->primaryKey}]\" ";
			if($this->styleCatName[$type]){
				$attr .= 'class="' . $this->styleCatName[$type] .  '"';
			}
			$this->table->setTdAttr($filed, $attr);
		}
		
		// set callback
		foreach ($this->callback as $key => $callback) {
			$callback = array(&$this, $callback);
			$this->table->setCallback($key, $callback);
		}
		
		// checkbox element for primary key 
		if($this->drawSelHtmlEl){
			$pattern = "<input type=\"checkbox\" class=\"none\" ";
			$pattern .= "name=\"{$this->primaryKey}[]\" ";
			$pattern .= "value=\"[{$this->primaryKey}]\" />";
			$pattern .= "[{$this->primaryKey}]";
			$this->table->setPattern($this->primaryKey, $pattern);
		}
		
		// print javascript
		if($this->drawScripts){
			$htmlId = $this->table->getWrapId();
			if($this->postUrl == '?'){
				$this->postUrl = $_SERVER['PHP_SELF'];
			}
			
			$ajaxDebug = $this->ajaxDebug ? 'true' : 'false';
			$html = "\n
<script type=\"text/javascript\" src=\"js/tableEditor.js\"></script>
<script type=\"text/javascript\">
TableEditor.setPostUrl('{$this->postUrl}');
TableEditor.init('{$htmlId}');
TableEditor.debug({$ajaxDebug});
</script>

<script type=\"text/javascript\">
   jQuery(\".del\").click(function(){
        if(!confirm(\"确认删除\")){
            return false;
        }
    });
</script>

<style type=\"text/css\">
.ajaxStart{ 
	background: #eef url(loader.gif) no-repeat 4px 50%; 
	padding:2px 4px 2px 24px;
	width: auto; } 
.ajaxComplated{ padding:2px 4px; }	
</style>
";


		}
		
		return $this->table->getHtml() . $html;
	}

// callback functions
//

	function callbackSelect($key, $row){
		$values = $this->filedValues[$key];
		if(!is_array($values)){
			return '';
		}
		
		$html = '';
		$value = $row[$key];
		foreach ($values as $key=>$name){
			$select = ($value == $key) ? ' selected' : '';
			$html .= "\t<option value=\"$key\"{$select}>{$name}</option>\n";
		}
		$html = "\n<select name=\"{$key}[{$row[$this->primaryKey]}]\">\n{$html}</select>\n";
		return $html;
	}
	
	function callbackRadio($key, $row){
		$arr = $this->filedValues[$key];
		if(!is_array($arr)){
			return '';
		}
		
		$ix = 0;
		$html = '';
		$checked = $row[$key];
	    foreach ($arr as $value => $title) {
	        $value_h = htmlspecialchars($value);
	        $title = trim($title);
	        $name = "{$key}[{$row[$this->primaryKey]}]";
	        $id = $name . $ix++;
	        $html .= "<input name=\"{$name}\" id=\"{$id}\" type=\"radio\" value=\"{$value_h}\" ";
	        if ($value == $checked) {
	            $html .= "checked=\"checked\"";
	        }
	        $html .= " />";
	        $html .= "<label for=\"{$id}\">{$title}</label>";
	        $ix++;
	        $html .= "\n";
	    }
	    return $html;
	}
	
	function callbackCheckbox($key, $row){
		$arr = $this->filedValues[$key];
		if(!is_array($arr)){
			return '';
		}
		
		$ix = 0;
		$selected = $row[$key];
	    if (!is_array($selected)) {
	        $selected = array($selected);
	    }
	    
	    $html = '';
	    foreach ($arr as $value => $title) {
	        $value_h = htmlspecialchars($value);
	        $title = nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($title)));
	        $name = "{$key}[{$row[$this->primaryKey]}][]";
	        $id = $name . $ix++;
	        $html .= "<input name=\"{$name}\" id=\"{$id}\" type=\"checkbox\" value=\"{$value_h}\" ";
	        if (in_array($value, $selected)) {
	            $html .= "checked=\"checked\"";
	        }
	        $html .= " />";
	        $html .= "<label for=\"{$id}\">{$title}</label>";
	        $html .= "\n";
	    }
	    return $html;
	}
	
	function callbackDate($key, $row){
		if(!is_numeric($row[$key])){
			$row[$key] = strtotime($row[$key]);
		}
		$oDate = date('Y-m-d', $row[$key]);
		$today = date('Y-m-d', time());
		if(($today > $oDate)){
			$html = $oDate . ' <a href="###" rel="handle">' . $this->updateTitle . '</a>';
		}else{
			$html = $oDate;
		}
		return $html;
	}
	function display(){
		echo $this->getHtml();
	}
}

?>
