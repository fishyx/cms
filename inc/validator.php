<?php
/**
 * @example 
 *  
 **/

define('VALID_NOT_EMPTY', 	'strlen');
define('VALID_NUM', 		'is_numeric');
define('VALID_FLOAT', 		'is_float');
define('VALID_EMAIL', 		'Verifier::email');
define('VALID_CN', 			'Verifier::cn');
define('VALID_EN', 			'Verifier::en');
define('VALID_DATE', 		'Verifier::date');
define('VALID_BETWEEN', 	'Verifier::between');
define('VALID_ALNUM', 		'Verifier::alnum');
define('VALID_ZIP', 		'Verifier::zip');
define('VALID_QQ', 			'Verifier::qq');
define('VALID_HTTPURL',		'Verifier::httpurl');
define('VALID_ASCII', 		'Verifier::ASCII');

class Validator{
	
	var $_rules = array();
	
	var $_require = array();
	
	var $_errMsg = array();
	
	var $_err = array();
	
	var $_callback;
	
	function setRule($key, $rule = '', $errMsg = '', $require = true){
		$this->_rules[$key] = $rule;
		if($errMsg){
			$this->setErrmsg($key, $errMsg);
		}
		if($require){
			$this->setRequire($key, $require);
		}
	}

	function setErrmsg($key, $message = ''){
		if(is_array($key)){
			$this->_errMsg = array_merge($this->_errMsg, $key);
		}else{
			$this->_errMsg[$key] = $message;
		}
	}
	
	function setRequire($key, $require = true){
		if(is_array($key)){
			$this->_require = array_merge($this->_require, $key);
		}else{
			$this->_require[$key] = $require;
		}
	}
	
	/*
	 * @parameter $ruler array [0] => regular expression, [1] => error message, [2] => is require
	 */
	function setRules($rules){
		foreach ($rules as $key => $rule){
			if(is_array($rule)){
				if(!isset($rule[2])){
					$rule[2] = true;
				}
				$this->setRule($key, $rule[0], $rule[1], $rule[2]);
			}else{
				$this->setRule($key, $rule);
			}
		}
	}
	
	function setCallback($key, $callback = null, $globals = array()){
		if(is_array($key)){
			$this->_callback = $key;
		}else{
			$this->_callback[$key] = $callback;
			if($globals){
				$this->globals[$key] =& $globals;
			}
		}
	}
	
	function validateValue($value, $express, $require){ 
		if(empty($value) && !$require){
			return true;
		}
		
		if(is_array($express)){
			$args = array_slice($express, 1);
			$express = $express[0];
			array_unshift($args, $value);
		}else{
			$args = (array)$value;
		}
		
		$match = true;
		$callback = explode('::', $express);
		if(count($callback) == 2){
			$match = call_user_func_array($callback, $args);
		}elseif(function_exists($express)){
			$match = call_user_func_array($express, $args);
		}elseif(strpos($express, '/') === 0){
			$match = preg_match($express, $value);
		}
		
		return $match;
	}
	
	/**
	 * @parameter $chkRequire boolean 
	 */
	function validate($data, $chkRequire = false){
		$this->_err = array();
		
		if(!is_array($data) || !is_array($this->_rules)){
			$this->setErr('system', 'parameter error');
			return false;
		}
		
		foreach($data as $key => $value){
			$isValid = true;
			$method = 'validate_' . $key;
			
			if(isset($this->_callback[$key])){
				// field callback
				if(is_array($this->_callback[$key])){
					$isValid = call_user_method(
						$this->_callback[$key][1], 
						$this->_callback[$key][0], 
						$value, $key, $this->globals[$key]
					);
				}else{
					$isValid = call_user_func(
						$this->_callback[$key], 
						$value, $key, $this->globals[$key]
					);
				}
			}elseif(method_exists($this, $method)){
				// this->validate_key
				$isValid = !call_user_method($method, $this, $value);
			}elseif($this->_rules[$key]){
				// regexpress , or
				// function, or 
				// object static method
				$isValid = $this->validateValue(
					$value, 
					$this->_rules[$key], 
					$this->_require[$key]
				);
			}
			
			if(!$isValid){
				if(isset($this->_errMsg[$key])){
					$msg = $this->_errMsg[$key];
				}else{
					$msg = 'Filed : ' . $key . ' invalid';
				}
				$this->setErr($msg, $key);
			}
		}
		
		if($chkRequire){
			foreach ($this->_require as $key => $require) {
				// if requie then check data[$key] exist
				// default requie any key
				if($require && !array_key_exists($key, $data)){
					$this->setErr($this->_errMsg[$key], $key);
				}
			}
		}
		
		if($this->_err){
			return false;
		}
		return true;
	}
	
	// --------------------
	// error dispose functions
	function setErr($msg, $key = ''){
		$this->_err[$key] = $msg;
	}
	
	function getErr(){
		return $this->_err;
	}
}// end class


/**
 * Enter description here...
 *
 */
class Verifier{
	function email($value){
		return preg_match('#^(_|\w)[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+\.[a-zA-Z]{2,4}$#', $value);
	}
	
	function cn($value){
		return preg_match('#[\xa1-\xff]+#', $value);
	}
	
	function en($value){
		return preg_match('#^[a-zA-Z]+$#', $value);
	}
	
 	function alnum($value){
    	return ctype_alnum($value);
    }
	
	function qq($value){
		return preg_match('#^[1-9]\d{4,11}$#', $value);
	}
	
	function httpurl($value){
		return preg_match('#^(http://)?[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-_]+)+.*$#', $value);
	}
	
	function zip($value){
		return preg_match('#^\d{6}$#', $value);
	}
	
	function between($value, $min, $max){
		return $value >= $min && $value <= $max;
	}
	
 	function ASCII($value){
 		$ar = null;
        $count = preg_match_all('/[\x20-\x7f]/', $value, $ar);
        return $count == strlen($value);
    }
    
	function date($value){
        $test = @strtotime($value);
        return $test !== -1 && $test !== false;
    }
}

?>
