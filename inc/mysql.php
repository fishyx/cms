<?php

if(!defined('MYSQL_DEBUG_MODE')){
	define('MYSQL_DEBUG_MODE', 1);
}

if(!defined('MYSQL_FETCH_MODE')){
	define('MYSQL_FETCH_MODE', MYSQL_ASSOC);
}

/**
* Date 2006-01-03
* Author @stcer
* Function :Get the datasource connection/close,get the Resultset
* param dataSourceServer,user,password,dataBase,link
*
**/
class Mysql{
	
	var $server;
    var $user;
    var $password;
    var $dataBase;
	
	var $queryId;
	var $linkId;
	
	var $debug 		= MYSQL_DEBUG_MODE;
	var $_errorNum;
	var $_errorMsg;
	var $autoFilterFileds = true;
	
	var $_log;
	
	function Mysql($server, $user, $pwd, $dataBase = ''){
		$this->server 	= $server;
		$this->user 	= $user;
		$this->password = $pwd;
		$this->dataBase = $dataBase;
	}
   
	function connect(){
        if ($this->linkId){
        	return $this->linkId;
        }
        
		$this->linkId = mysqli_connect($this->server, $this->user, $this->password,$this->dataBase);
		if(!$this->linkId){
			$this->halt("connect failed."); 
			return false;
		}
/*		if($this->dataBase && !mysql_select_db($this->dataBase, $this->linkId)){
			$this->halt("cannot use database {$this->dataBase}"); 
			return false;
		}*/
		$this->execute("SET NAMES utf8");
		return $this->linkId;
    }
	
	function selectDb($dbName){
		if(0 == $this->linkId){
			$this->halt("Cannot use database {$dbName} beacause have not any Active conn"); 
			return false;
		}
		if(!mysql_select_db($dbName, $this->linkId)){
			$this->halt("cannot use database {$dbName}"); 
			return false;
		}
		$this->dataBase = $dbName;
		return true;
	}
	
	function query($sql){
		if(!$this->linkId && !$this->connect()){ 
			$this->halt("Query cannot execute beacause have not any Active conn");
			return false;
		}
		
		if($this->debug & 2){
			$log = array();
			$start = microtime();
			$start = explode (' ', $start);
			$start = $start[1] + $start[0];
			$log['sql'] = $sql;
		}
		if(isset($_REQUEST['sqltest'])){
            echo $sql . '<br />';
        }
		$this->queryId = mysqli_query($this->linkId, $sql);
		if(!$this->queryId){ 
			$this->halt("Invalid SQL: {$sql}");
			return false;
		}
		
		if($this->debug & 2){
			$stop = microtime();
			$stop = explode (' ', $stop);
			$stop = $stop[1] + $stop[0];
			$log['time'] = round(($stop - $start), 5);
			if($this->debug & 4){
				$log['expain'] = $this->explain($sql);
			}
			if(is_resource($this->queryId)){
				$log['rows'] = mysqli_num_rows($this->queryId);
			}
			$this->_log[] = $log;
		}
		
        return $this->queryId;
    }
	
	function execute($sql){
        return $this->query($sql) ? true : false;
    }
    
    function explain($sql){
    	$result = array();
    	if(is_resource($res = mysqli_query('EXPLAIN ' . $sql, $this->linkId))){
    		while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$result[] = $row;
			}
    	}
    	return $result;
    }
	
	function & toArray($sql, $key = '', $type = MYSQLI_ASSOC){
		$result = array();
		if($res = $this->query($sql)){
			while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if($key){
					$result[$row[$key]] = $row;
				}else{
					$result[] = $row;
				}
			}
		}
		return $result;
	}
	
	function & selectLimit($sql, $length = null, $offset = null){
        if ($offset !== null) {
            $sql .= "\nLIMIT " . (int)$offset;
            if ($length !== null) {
                $sql .= ', ' . (int)$length;
            } else {
                $sql .= ', 1024';
            }
        } elseif ($length !== null) {
            $sql .= "\nLIMIT " . (int)$length;
        }
        return $this->toArray($sql);
    }
	
	function getOne($sql){
		$row = $this->getRow($sql);
		if($row){
			return array_shift($row);
		}
		return false;
	}
	
	function getRow($sql){
		if($res = $this->query($sql)){
			$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
		}
		if(!$row){
			$row = array();
		}
		return $row;
	}
	
 	function & getCol($sql, $col = 0) {
        $res = $this->query($sql);
        if(!$res){
        	return array();
        }
        
        $data = array();
        while ($row = mysqli_fetch_row($res)) {
            $data[] = $row[$col];
        }
        return $data;
    }
    
    function insertId(){
        return mysqli_insert_id($this->linkId);
    }
    
	function affectedRows(){
        return mysql_affected_rows($this->linkId);
    }
    
    function fields($table){
    	$metaInfo = $this->meta($table);
    	$fields = array();
    	foreach ($metaInfo as $field){
    		$fields[] = $field['Field'];
    	}
    	return $fields;
    }
    
    function meta($table){
    	$sql = 'SHOW FIELDS FROM ' . $table;
    	return $this->toArray($sql);
    }
    
    function free(){ 
		mysql_free_result($this->queryId); 
		$this->queryId = 0; 
	}
	
	function close(){
        mysql_close($this->linkId);
    }
	
    function setDebug($mode = 1){
    	$this->debug = intval($mode);
    }
    
	function halt($msg){ 
		//trigger_error( $this->_errorNum, E_USER_NOTICE );
		$this->_errorMsg = mysql_error($this->linkId); 
		$this->_errorNum = mysql_errno($this->linkId); 
		$this->haltMsg($msg); 
		if($this->debug & 1){
			if(function_exists('debug_backtrace')) {
				$i = 0;
				foreach(debug_backtrace() as $trace) {
				    print('#' . ++$i . '--' . $trace["file"] );
	                print( "(".$trace["line"]."): " );
	                if( $trace["class"] != "" )
	                    print( $trace["class"]."." );
	                print( $trace["function"] );
	                print( "<br/>" );
				}
			}
			die("Mysql halted.");
		}
	}
	
	function haltMsg($msg){ 
		printf("<b>Database error:</b> %s <br />\n", $msg); 
		printf("<b>MySQL Error:</b>(%s) %s <br />\n", $this->_errorNum, $this->_errorMsg); 
	}
	
	function getLog(){
		return $this->_log;
	}
	
/**
 * Enter description here...
 *
 * @param unknown_type $table
 * @param unknown_type $info
 */
	function insert($table, $data, $autoFilter = true){
		$this->filterFileds($table, $data, $autoFilter);
		
		$fields = array_map(array($this, 'quote'), array_keys($data));
		$fields = implode(',', $fields);
		
		$values = array_map(array($this, 'formatValue'), $data);
		$values = implode(',', $values);
		
		$sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $fields, $values);
		if($this->execute($sql)){
			$insertId = $this->insertId();
			if($insertId){
				return $insertId;
			}
			return true;
		}
		return false;
	}
	
	function update($table, $data, $cond, $autoFilter = true){
		$this->filterFileds($table, $data, $autoFilter);
		
		if(!$data){
			return false;
		}
		
		$updateStr = $separator = '';
		foreach($data as $key => $value){
			$key = $this->quote($key);
			$value = $this->formatValue($value);
			
			$updateStr .= "{$separator}\n{$key} = {$value}";
			$separator = ',';
		}
		
		$sql = sprintf('UPDATE %s SET %s WHERE 1 %s', $table, $updateStr, $cond);

		return $this->execute($sql);
	}
	
	function filterFileds($table, & $data, $autoFilter){
		if(!$autoFilter){
			return $data;
		}
		
		$fields = $this->fields($table);
		foreach (array_keys($data) as $key) {
			if(!in_array($key, $fields)){
				unset($data[$key]);
			}
		}
		return $data;
	}
	
	function formatValue($value){
		if (strlen($value) === 0 
    		|| (is_string($value) 
    			&& !preg_match('/^[a-zA-Z_]+\s*\(.*?\)$/', $value))
	    ){
	        $value = "'{$value}'";
	    }
	    return $value;
	}
	
	function quote($str, $prefix = null){
	    $pos = strpos($str, '.');
        if ($pos) {
            $prefix = substr($str, 0, $pos);
            $str = substr($str, $pos + 1);
        }
        if (preg_match('/[^`\*]+/', $str)) {
            $str =  "`{$str}`";
        }
        if ($prefix) {
        	$prefix = trim($prefix, '.');
        	if(preg_match('/[^`]+/', $prefix)){
        		$prefix = "`{$prefix}`";
        	}
        	$prefix .= '.'; 
        }
        return $prefix . $str;
    }
}
 
?>