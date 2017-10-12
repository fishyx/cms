<?php

require_once('properties.php');

class View {

	var $_params;

	var $_isErr = false;

	var $_tplName;
	
	var $_errTplName;
	
	var $_layout;
	
	function View($tplName = '', $values = array()){
		$this->_params = new Properties($values);
		if($tplName){
			$this->setTpl($tplName);
		}
		$this->init();
	}
	
	function init(){
		// 'rewrite this method!';
	}
	
	function & getParams(){
		return $this->_params;
	}

// 
// main functions
// 
	function setLayout($layout){
		$this->_layout = $layout;
	}
	
	function getLayout(){
		return $this->_layout;
	}
	
	function setTpl($name){
		$this->set('cnt_page', $name);
		$this->_tplName = $name;
	}
	
	function setTplPrefix($prefix){
		$this->_tplPrefix = $prefix;
	}
	
	function getTpl($type = ''){
		if($type == 'err'){
			return $this->_errTplName;
		}
		return $this->_tplName;
	}
	
	function set($key, $value){
		$this->_params->setValue($key, $value);
	}
	
	function setVar($key, $value){
		$this->_params->setValue($key, $value);
	}

	function get($key, $def = null){
		return $this->_params->getValue($key, $def);
	}

	function setCharset($charset){
		$this->charset = $charset;
		header('Content-Type:text/html;charset=' . $charset);
	}
	
// 
// error functions
// 
	function setErr($title, $info = ''){
		$this->errMsg['title'] = $title;
		$this->errMsg['info'] = $info;
	}
	
	function getErr(){
		return $this->errMsg;
	}
	
	function isErr(){
		return !empty($this->errMsg);
	}
	
	function setErrTpl($name){
		$this->set('cnt_page', $name);
		$this->_errTplName = $name;
	}
}

?>