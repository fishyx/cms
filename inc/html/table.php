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

require_once 'row.php';

/**
 * Enter description here...
 *
 */
class HtmlTable extends HtmlRow{
	
	var $showStyle = false;
	
	var $orderFields = array();
	
	var $disableOrder =  false;
	
	var $orderAppendString;
	
	function HtmlTable($data = null, $htmlId = ''){
		$this->setData($data);
		$this->setWrapId($htmlId);
	}

	function setShowStyle($flag){
		$this->showStyle = $flag;
	}
	
	function setOrderField($key, $flag = true){
		if(is_array($key)){
			$this->orderFields = array_merge($this->orderFields, $key);
		}else{
			$this->orderFields[$key] = $flag;
		}
	}
	
	function disableOrder($flag = true){
		$this->disableOrder = $flag;
	}
	
	function appendOrderString($string){
		if($string{0} == '?'){
			$string = substr($string, 1);
		}
		$this->orderAppendString = $string;
	}
	
// thead functions
// 
	function _buildDefaultHeader(){
		if(!is_array($this->data)){
			return false;
		}
		
		reset($this->data);
		$firstEl = current($this->data);
		if(is_array($firstEl)){
			$thead = array_keys($firstEl);
			foreach ($thead as $index => $field) {
				if(is_numeric($field)){
					unset($thead[$index]);
				}
			}
			$thead = array_combine($thead, $thead);
			$this->header = $thead;
			return true;
		}
		return false;
	}

	function _buildHead(){
		if(!$this->header && !$this->_buildDefaultHeader()){
			return '';
		}
		$html = "<thead><tr>\n";
		foreach ($this->header as $field => $th){
			$th = $this->_thFieldValue($field, $th);
			$attr = $this->thAttr($field);
			$html .= "\t<th{$attr}>{$th}</th>\n";
		}
		$html .= "</tr></thead>\n\n";
		
		return $html;
	}
	
	function _thFieldValue($field, $th){
		if($this->disableOrder 
			|| (isset($this->orderFields[$field]) && !$this->orderFields[$field])
		){
			return $th;
		}
		
		$className = '';
		if($_GET['order'] == $field){
			$by = ($_GET['by'] == 'd') ? 'a' : 'd';
			$className = ($_GET['by'] == 'd') ? 'desc' : 'asc';
			$className = " class=\"{$className}\"";
		}else{
			$by = 'd';
		}
		$th = "<a href=\"?{$this->orderAppendString}&order={$field}&by={$by}&\"{$className}>{$th}<a/>";
		return $th;
	}
	
	function getOrderString($orderKey = true, $tansFields = array()){
		$by = $_REQUEST['by'] == 'd' ? ' desc ' : ' asc ';
		$order = ($_REQUEST['order'] 
			? (isset($tansFields[$_REQUEST['order']]) 
				? $tansFields[$_REQUEST['order']] 
				: $_REQUEST['order'])
			: '');
		if($order){
			if($orderKey){
				$order = ' ORDER BY ' . $order ;
			}
			$order .= $by;
		}
		return $order;
	}
	
	function getReqOrderFiled($tansFields = array()){
		$order = ($_REQUEST['order'] 
			? (isset($tansFields[$_REQUEST['order']]) 
				? $tansFields[$_REQUEST['order']] 
				: $_REQUEST['order'])
			: '');
		return $order;
	}
	
	function getReqOrderWay(){
		return $_REQUEST['by'] != 'a' ? ' desc ' : ' asc ';
	}
	
// tbody functions
//

	function _builBody(){
		if(!is_array($this->header) && !$this->_buildDefaultHeader()){
			return '';
		}
		
		if(!is_array($this->data)){
			return '';
		}
		
		// tbody
		$i = 0;
		$table_body = "<tbody>\n";
		foreach ($this->data as $row) {
			$className = ($i++) % 2 ? ' class="sowp"' : '';
			$table_body .= "<tr{$className}>\n";
			foreach (array_keys($this->header) as $key){
				$field = $this->fieldValue($key, $row);
				$attr = $this->tdAttr($key, $row);
				$table_body .= "\t<td{$attr}>{$field}</td>\n";
			}
			$table_body .= "</tr>\n";
		}
		$table_body .= "</tbody>\n";
		
		return $table_body;
	}
	
	function _buildFoot(){
		return '';
	}

// get html
//
	function getHtml(){
		$id = $this->htmlId ? ' id="' . $this->htmlId . '"' : '';
		$table =  '<table cellspacing="0" cellpadding="4" class="datalist"' . $id . '>' . "\n";
		$table .= $this->_buildHead() . $this->_builBody() . $this->_buildFoot();
		$table .= "</table>\n";
		if($this->showStyle){
			$table .= $this->getStyle();
		}
		
		return $table;
	}
	
	function display(){
		echo $this->getHtml();
	}
	
	function getStyle(){
		return '
<style type="text/css">
	.tbllist{border-collapse:collapse; border:1px solid #ddd}
	.tbllist td{ border: 1px solid #e7e7e7; }
	.tbllist th{ border: 1px solid #ccc; }
	.tbllist th a{ color:#333; text-decoration:none; }
	.tbllist .desc{ padding-left:12px; background:url(s_desc.png) no-repeat 0 50% }
	.tbllist .asc{ padding-left:12px; background:url(s_asc.png) no-repeat 0 50% }
	.tbllist thead{ background: #e7e7e7; }
	.tbllist .sowp td{ background: #f7f7f7; }
	.tbllist form{ display:inline }
</style>';
	}
}

?>