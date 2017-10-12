<?php

if(!defined('TABLE_PRE')){
    define('TABLE_PRE', '');
}

class Cnfdb{
	var $stack = array();
    var $table = 'cnf';
	var $tablePre = TABLE_PRE;
	static function & getInstance(){
		static $instance;
		if(!isset($instance)){
			$instance = new Cnfdb();
            $instance->table = $instance->tablePre . $instance->table;
		}
		return $instance;
	}
	
	static function get( $key, $defaultValue = null ){
		$instance = & Cnfdb::getInstance();
		if(!isset($instance->stack[$key])){
            $db = getDb();
			$sql = "SELECT value FROM {$instance->table} WHERE code='{$key}'";
			$instance->stack[$key] = $db->getOne($sql);
		}
  		
		if($instance->stack[$key]){
			return $instance->stack[$key];
		}
	    return $defaultValue;
	}
    
    static function toArray() {
        $instance = & Cnfdb::getInstance();
        
        $instance->reset();
        $db = getDb();
        $sql = "SELECT code, value FROM {$instance->table}";
        $data = $db->toArray($sql);
        $instance->stack = array_to_hashmap($data, 'code', 'value');
        return $instance->stack;
    }
	
	function set($key, $value){
		$instance = & Cnfdb::getInstance();
		$sql = "REPLACE {$instance->table}(code, value)VALUES('{$key}', '{$value}')";
		$db = getDb();;
        $db->query($sql);
        $instance->reset($key);
	}
    
    function reset($key = '') {
        $instance = & Cnfdb::getInstance();
        if(!$key){
            $instance->stack = array();
        }else{
            unset($instance->stack[$key]);
        }
    }
}


?>