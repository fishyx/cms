<?php

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

class HtmlRow {
	
	var $header;
	
	var $htmlId;
	
	var $pattern;
	
	var $callback;
	
	var $thAttr = array();
	
	var $tdAttr = array();
	
	var $fValues = array();
	
	var $data = array();
	
	var $globals = array();
	
// seter functions
//	
	function setData($data, $restHeader = false){
		if($data && is_array($data)){
			$this->data = $data;
			if($restHeader){
				$this->header = array();
			}
		}
	}
		
	function setHeader($key, $value = ''){
		if(is_array($key)){
			$this->header = $key;
		}else{
			$this->header[$key] = $value;
		}
	}
	
	function setWrapId($htmlId){
		$this->htmlId = $htmlId;
	}
	
	function getWrapId(){
		return $this->htmlId;
	}

	function setValues($key, $values){
		if(!is_array($values)){
			return;
		}
		$this->fValues[$key] = $values;
	}
	
// th attrs functions
//
	function setThAttr($key, $value = ''){
		if(is_array($key)){
			$this->thAttr = array_merge($this->thAttr, $key);
		}else{
			$this->thAttr[$key] = $value;
		}
	}
	
	function thAttr($field){
		if(isset($this->thAttr[$field]) && $this->thAttr[$field]){
			return ' ' . $this->thAttr[$field];
		}
		return '';
	}
	
// td attrs functions
//
	function setTdAttr($key, $value = ''){
		if(is_array($key)){
			$this->tdAttr = array_merge($this->tdAttr, $key);
		}else{
			$this->tdAttr[$key] = $value;
		}
	}
	
	function tdAttr($field, $row){
		if(isset($this->tdAttr[$field]) && $this->tdAttr[$field]){
			return ' ' . $this->parsePattern($this->tdAttr[$field], $row);
		}
		return '';
	}
	
// custom code functions
//
	function setPattern($key, $filedPattern = ''){
		if(is_array($key)){
			$this->pattern = array_merge($this->pattern, $key);
		}else{
			$this->pattern[$key] = $filedPattern;
		}
	}
	
	function setCallback($key, $callback = null, $globals = array()){
		if(is_array($key)){
			$this->callback = $key;
		}else{
			$this->callback[$key] = $callback;
			if($globals){
				$this->globals[$key] =& $globals;
			}
		}
	}
	
	function fieldValue($key, $row){
		//return $this->formatValue($row[$key]);
		if(isset($this->callback[$key])){
			// field callback
			 if(is_array($this->callback[$key])){
			 	 $value = call_user_func(
			 	 	$this->callback[$key],  
			 	 	$key, $row, $this->globals[$key]
			 	 );
			 }else{
			 	 $value = call_user_func(
			 	 	$this->callback[$key], 
			 	 	$key, $row, $this->globals[$key]
			 	 );
			 }
		}elseif(isset($this->pattern[$key])){
			// field Pattern
			$value = $this->parsePattern($this->pattern[$key], $row);
		}elseif(isset($this->fValues[$key]) 
			&& isset($this->fValues[$key][$row[$key]])
			){
			$value = $this->fValues[$key][$row[$key]];
		}else{
			$value = $this->formatValue($row[$key]);
		}
		return $value;
	}
	
	function parsePattern($pattern, $row){
		$r = null;
		preg_match_all('/\[([^\[\]]+)\]/', $pattern, $r);
		if($r){
			$r = array_filter(array_unique(array_values($r[1])));
			foreach ($r as $rName){
				if(isset($row[$rName])){
					$row[$rName] = $this->formatValue($row[$rName]);
					$pattern = str_replace("[$rName]", $row[$rName], $pattern);
				}
			}
		}
		return $pattern;
	}
	
	function formatValue($value){
		if(is_array($value) || is_object($value)){
			$value = '<pre>' 
				. var_export($value, true)
				. '</pre>'; 
		}
		return $value;
	}
	
}

?>
