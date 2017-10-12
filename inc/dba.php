<?php

define('SQL_INSERT', 'INSERT INTO %s (%s) VALUES(%s)');
define('SQL_UPDATE', 'UPDATE %s SET %s WHERE 1 %s');
define('SQL_DELETE', 'DELETE FROM %s WHERE 1 %s');
define('SQL_SELECT', 'SELECT %s FROM %s WHERE 1 %s');
define('SQL_COUNT', 'SELECT COUNT(*) AS num FROM %s WHERE 1 %s');
define('SQL_COUNT_GROUP', 'SELECT COUNT(*) FROM(SELECT null FROM %s WHERE 1 %s) AS _c');

if(!defined('LIST_DEFAULT_ITEMS')){
	define('LIST_DEFAULT_ITEMS', 15);
}

if(!defined('TABLE_PRE')){
    define('TABLE_PRE', '');
}

if(!defined('TABLE_PRE_LNG')){
    define('TABLE_PRE_LNG', '');
}

class Dba{

    var $_tableName;
	var $_primaryKey = 'id';
	var $__primaryKey;
	
	var $autoFilterFileds = true;
	var $_fileds;
	var $_filedNameMapping;
	
	var $_filedsVRule;
	var $_filedsUnique;
	var $autoValidate = true;
	var $autoValidateUnique = true;
	
	var $_cond;
	var $_defCond;
	
	var $_errors;
	var $_errorsMsg;
    
    var $_table_pre = TABLE_PRE;
    var $_table_pre_lang = TABLE_PRE_LNG;
	
    /**
    * put your comment there...
    * 
    * @var Mysql
    */
	var $dbo;
	
	var $observable;
	
	function Dba($tableName = '', $primaryKey = '', $dbo = null){
		if(!$dbo){
			$dbo =& getDb();
		}
		$this->setDbo($dbo);
		$this->setTableName($tableName);
		$this->setPrimaryKey($primaryKey);
		$this->init();
	}
	
	function init(){
		// rewrite this method;
        $this->_tableName = $this->_table_pre . $this->_table_pre_lang . $this->_tableName;
	}
	
// set properties
	function setTableName($tableName){
		if($tableName){
			$this->_tableName = $tableName;
		}
	}
	
	function setPrimaryKey($primaryKey){
		if($primaryKey){
			$this->__primaryKey = $primaryKey;
			$this->_primaryKey = $this->dbo->quote($primaryKey);
		}elseif($this->_primaryKey && !isset($this->__primaryKey)){
			$this->__primaryKey = $this->_primaryKey;
		}
	}
	
	function setDbo(& $dbo){
		$this->dbo =& $dbo;
	}
	
	function & getDbo(){
		return $this->dbo;
	}

// ------------------
// validate data functions

	function validate(& $data, $full = false){
		if(!$this->_validator && $this->autoValidate){
			$this->setDefValidator();
			$rule = $this->getVRules();
			if($rule){
				if($full){// for add
					unset($rule[$this->__primaryKey]);
				}
				$this->_validator->setRules($rule);
			}
		}
		$flag = true;   
		if($this->_validator){
			$flag = $this->_validator->validate($data, $full);
			if(!$flag){
				$this->setErr($this->_validator->getErr());
			}
		}
		
		if($flag && $this->autoValidateUnique){
			$flag = $this->validateUnique($data, $full);
		}
		
		$this->notify('validate_post', $flag);
		return $flag;
	}

	function validateUnique($data, $is_add = true){
		$uniqueFileds = $this->getUniqueFileds();
		if(!$uniqueFileds){
			return true;
		}
		
		$cond = '';
		if(!$is_add && isset($data[$this->__primaryKey])){
			$cond = " AND {$this->__primaryKey} != '{$data[$this->__primaryKey]}'";
		}
		
		$true = true;
		foreach($uniqueFileds as $filed){
			if(!isset($data[$filed])){
				continue;
			}
			
			if(!(!$is_add && $filed == $this->__primaryKey) 
				&& $this->getCount($cond . " AND {$filed} = '{$data[$filed]}'")
				){
				$true = false;
				$this->setErr('Not_Unique_' . $filed, $filed);
			}
		}
		
		return $true;
	}
	
	function getVRules(){
		if(!isset($this->_filedsVRule)){
			$this->_parseMeta();
		}
		return $this->_filedsVRule;
	}
	
	function setVRules($filedsVRule){
		$this->_filedsVRule = $filedsVRule;
	}
	
	function getUniqueFileds(){
		if(!isset($this->_filedsUnique)){
			$this->_parseMeta();
		}
		return $this->_filedsUnique;
	}
	
	function setDefValidator(){
		$this->_validator =& getInstance('Validator', get_class($this));
	}
	
	/**
	 * set _fileds, _filedsVRule, _filedsUnique from table meta
	 *
	 * @return boolean
	 */
	function _parseMeta(){
		loadClass('Validator');
		
		$fileds = array();
		$this->_filedsUnique = array();
		$this->_filedsVRule = array();
		
		$metaInfo = $this->dbo->meta($this->_tableName);
		foreach ($metaInfo as $filed){
			$fileds[] = $filed['Field'];
			
			if($filed['Key'] == 'PRI' || $filed['Key'] == 'UNI'){
				$this->_filedsUnique[] = $filed['Field'];
			}
			
			$notNull = false;
			if($filed['Null'] == 'NO' && strlen($filed['Default']) == 0){
				$notNull = true;
			}
			
			$rule = $msg = ''; 
			if(preg_match('/int/i', $filed['Type'])){
				$rule = VALID_NUM;
			}elseif(preg_match('/float|double/i', $filed['Type'])){
				$rule = VALID_FLOAT;
			}elseif(preg_match('/date|time|year/i', $filed['Type'])){
				$rule = VALID_DATE;
			}
			
			if($notNull && !$rule){
				$rule = VALID_NOT_EMPTY;
			}
			
			$msg = 'Invalid_Filed_' . $filed['Field'];
			$this->_filedsVRule[$filed['Field']] = array($rule, $msg, $notNull);
		}
		
		if(!$this->_fileds){
			$this->_fileds = $fileds;
		}
		
		return true;
	}
	
// ------------------
// filter data functions

	function filter(& $data){}
	
	function filterFileds(& $data){
		if(!$this->_fileds && $this->autoFilterFileds){
			$this->setDefaultFileds();
		}
		
		if($this->_fileds){
			if(!is_array($this->_fileds)){
				$this->setFileds($this->_fileds);
			}
			foreach (array_keys($data) as $key) {
				if(!in_array($key, $this->_fileds)){
					unset($data[$key]);
				}
			}
		}
		return $data;
	}
	
	function & getFileds(){
		if(!$this->_fileds && $this->autoFilter){
			$this->setDefaultFileds();
		}
		return $this->_fileds;
	}
	
	function setFileds($fileds){
		if(!is_array($fileds)){
			$fileds = str_replace('`', '', $fileds);
			$fileds = preg_split('/[,\s]/s', $fileds, -1, PREG_SPLIT_NO_EMPTY);
			$fileds = array_map('trim', $fileds);
			$fileds = array_filter($fileds);
		}
		$this->_fileds = $fileds;
	}

	function setDefaultFileds(){
		$this->_fileds = $this->dbo->fields($this->_tableName);	
	}
	
	function setKeyMapping($mapping){
		if(is_array($mapping)){
			$this->_filedNameMapping = $mapping;
		}
	}
	
	function transferKey(& $data){
		if($this->_filedNameMapping && is_array($this->_filedNameMapping)){
			foreach ($this->_filedNameMapping as $key => $value) {
				if(isset($data[$value])){
					$data[$key] = $data[$value];
					unset($data[$value]);
				}
			}
		}
		return $data;
	}
	
// ------------------
// condition functions
	function & getCond(){
		if(!$this->_cond){
			$this->_cond =& getInstance('Condition', get_class($this));
		}
		return $this->_cond;
	}
	
	function setCond(& $cond){
		if(is_object($cond)){
			$this->_cond =& $cond;
		}
	}
	
	function resetCond(){
		$this->_cond->clearAll();
	}
	
	function &getDefCond(){
		if(!$this->_condDef){
			$this->_condDef =& getInstance('Condition', get_class($this));
		}
		return $this->_condDef;
	}
	
	function setDefCond(& $cond){
		if(is_object($cond)){
			$this->_condDef = $cond;
		}
	}
	
	function resetDefCond(){
		$this->_condDef->clearAll();
	}
	
	function getCondString($cond = null, $mask = 7){
		$_condSelf =  
			(is_object($this->_condDef) ? $this->_condDef->getCond($mask) : '') . 
			(is_object($this->_cond) ? $this->_cond->getCond($mask) : '');
			
		if(is_object($cond)){
			$_condSelf .= (is_object($cond) ? $cond->getCond($mask) : '');
		}elseif(($mask & 1) && is_numeric($cond)){
			$_condSelf .= " AND {$this->_primaryKey}  = " . intval($cond);
		}elseif(($mask & 1) && is_string($cond)){
			$_condSelf .= $cond;
		}
		
		return $_condSelf;
	}

// ---------------
// data processor

	function add($data){       
		if(isset($data[$this->__primaryKey]) && $data[$this->__primaryKey] === ''){     
			unset($data[$this->__primaryKey]);
		}
        $this->filter($data);
		if(!$this->validate($data, true)){
			return false;
		}
		$this->transferKey($data);        
		$this->filterFileds($data);        
		$this->notify('add_pre', $data);
		$flag = $this->dbo->insert($this->_tableName, $data, false);
		$this->notify('add_post', $flag);
		return $flag;
	}
	
	function update($data, $cond = null){
		$this->filter($data);
		if(!$this->validate($data, false)){
			return false;
		}
		
		$this->transferKey($data);
		$this->filterFileds($data);
		if(!$data){
            throw(new Exception('data is null for update'));
		}
		
		$condStr = $this->getCondString($cond, 1);
		if(!$condStr){
			throw(new Exception('Condition is null for update'));
		}
		
		$vars = array('data'=> &$data, 'cond'=> &$cond);
		$this->notify('update_pre', $vars);
		$flag = $this->dbo->update($this->_tableName, $data, $condStr, false);
		$this->notify('update_post', $flag);
		
		return $flag;
	}
	
	function updateField($key, $value, $cond = null){
		$condStr = $this->getCondString($cond, 1);
		$sql = "UPDATE {$this->_tableName} SET {$key}= '{$value}' WHERE 1 {$condStr}";
		return $this->dbo->execute($sql);
	}
    
    function increment($key, $cond, $nums = 1){
        $condStr = $this->getCondString($cond, 1);
        $sql = "UPDATE {$this->_tableName} SET {$key} = {$key} + ({$nums}) WHERE 1 {$condStr}";
        return $this->dbo->execute($sql);
    }
	
	function del($cond = null){
		$condStr = $this->getCondString($cond);
		$sql = sprintf(SQL_DELETE, $this->_tableName, $condStr);
		
		$this->notify('del_pre', $cond);
		$flag = $this->dbo->execute($sql);
//		$flag = true;
		$this->notify('del_post', $flag);
		
		if($flag){
			return $this->dbo->affectedRows();
		}else{
			return false;
		}
	}
    
    function getFiled($field, $cond) {
        $condStr = $this->getCondString($cond);
        $sql = sprintf(SQL_SELECT, $field, $this->_tableName, $condStr);
        return $this->dbo->getOne($sql);
    }
	
	function getRow($cond = null){
		$condStr = $this->getCondString($cond);
		 $sql = sprintf(SQL_SELECT, '*', $this->_tableName, $condStr);
		
		$this->notify('getRow_pre', $cond);
		$row = $this->dbo->getRow($sql);  
		$this->notify('getRow_post', $row);
		
		return $row;
	}
	
	function getCount($cond = null){
		$condStr = $this->getCondString($cond, 1);
		$groupStr = $this->getCondString($cond, 2);
		
		$sql = $groupStr ? SQL_COUNT_GROUP : SQL_COUNT;
		$sql = sprintf($sql, $this->_tableName, $condStr . $groupStr);
		return $this->dbo->getOne($sql);
	}
	
// list functions
	function setListFields($fields){
		$this->_listFields = $fields ? $fields : '*';
	}
	
	function setListOffset($offset){
		$this->_listOffset = intval($offset);
	}
	
	function getList($nums, $cond = null){
		$nums = intval($nums);
		if(!$nums){
			$nums = LIST_DEFAULT_ITEMS;
		}
		if(!isset($this->_listOffset)){
			$this->_listOffset = 0;
		}
		if(!isset($this->_listFields)){
			$this->_listFields = '*';
		}
		
		$condStr = $this->getCondString($cond);
		$sql = sprintf(SQL_SELECT, $this->_listFields, $this->_tableName, $condStr);
		return $this->dbo->selectLimit($sql, $nums, $this->_listOffset);
	}
	
	function getAll($pager = null, $cond = null){
		if(is_object($pager)){
			$pager->setTotal(intval($this->getCount($cond)));
			$nums = $pager->nums;
			$offset = $pager->start;
		}elseif($pager){
			$nums = intval($pager);
			$offset = 0;
		}
		
		if(!isset($this->_listFields)){
			$this->_listFields = '*';
		}
		
		$condStr = $this->getCondString($cond);
		$sql = sprintf(SQL_SELECT, $this->_listFields, $this->_tableName, $condStr);
		
		if(is_object($pager) || $nums){
			return $this->dbo->selectLimit($sql, $nums, $offset);
		}else{
			return $this->dbo->toArray($sql);
		}
	}

// error handle
	function setErr($err, $key = ''){
		if(!isset($this->_errorsMsg)){
			$this->setErrMsg($this->getErrMsgs());
		}
		
		if(!is_array($this->_errors)){
			$this->_errors = array();
		}
		
		if(is_array($err)){
			foreach ($err as $index => $errCode) {
				if(isset($this->_errorsMsg[$errCode])){
					$err[$index] = $this->_errorsMsg[$errCode];
				}
			}
			$this->_errors = array_merge($this->_errors, $err);
		}else{
			if(isset($this->_errorsMsg[$err])){
				$err = $this->_errorsMsg[$err];
			}
			$this->_errors[$key] = $err;
		}
	}
	
	function getErr(){
		return $this->_errors;
	}
	
	function setErrMsg($err, $msg = ''){
		if(!is_array($this->_errorsMsg)){
			$this->_errorsMsg = array();
		}
		if(is_array($err)){
			$this->_errorsMsg = array_merge($this->_errorsMsg, $err);
		}else{
			$this->_errorsMsg[$err] = $msg;
		}
	}
	
	function getErrMsgs(){
		// rewrite this method
		return array();
	}
	
// ---------------
// plugin
    function setObservable($obj){
        $this->observable = $obj;
    }
	
	function addObserver(& $observer){
		if($this->observable){
			$this->observable->addObserver($observer);
		}
	}
	
	function delObserver(& $anObserver){
		if($this->observable){
			$this->observable->delObserver($anObserver);
		}
	}
	
	function clearObserver(){
		if($this->observable){
			$this->observable->clearObserver();
		}
	}
	
	//function notify( $ac, & $vars = '', & $obj = null )
    function notify($ac, & $vars, $obj = null){

        
        if($this->observable){
            if(!is_object($obj)){
                $obj =& $this;
            }

            $params['_ac'] = $ac;
            $params['_obj'] =& $obj;
            $params['_vars'] =& $vars;
            $this->observable->notify($params);
   
        }
    }
}

class Condition{
	var $cond;
	
	var $order;
	
	var $group;
	
	var $fileds = array();
	
	function andStart(){
		$this->fileds[] = ' AND ( ';
	}
	
	function orStart(){
		$this->fileds[] = ' OR ( ';
	}
	
	function end(){
		$this->fileds[] = ' ) ';
	}
	
	function in($key, $value, $lgc = 'AND'){
		if(is_array($value)){
			$value = implode(',', $value);
		}
		$this->fileds[$key] = " {$lgc} {$key} IN({$value})";
	}
	
	function like($key, $value, $lgc = ' AND', $useIndex = false){
		$cond = " {$lgc} {$key} LIKE '";
		if(!$useIndex){
			$cond .= '%';
		}
		$cond .= $value . "%'";
		$this->fileds[$key] = $cond;
	}
	
	function eq($key, $value, $lgc = ' AND'){
		$this->fileds[$key] = " {$lgc} {$key} = '{$value}'";
	}
    
    function notNull($key, $lgc = ' AND') {
        $this->fileds[$key] = " {$lgc} {$key} IS NOT NULL";
    }
	
	function neq($key, $value, $lgc = ' AND'){
		$this->fileds[$key] = " {$lgc} {$key} != '{$value}'";
	}
	
	function gt($key, $value, $lgc = ' AND'){
		$this->fileds[$key] = " {$lgc} {$key} > '{$value}'";
	}
	
	function gte($key, $value, $lgc = ' AND'){
		$this->fileds[$key] = " {$lgc} {$key} >= '{$value}'";
	}
	
	function lt($key, $value, $lgc = ' AND'){
		$this->fileds[$key] = " {$lgc} {$key} < '{$value}'";
	}
	
	function lte($key, $value, $lgc = ' AND'){
		$this->fileds[$key] = " {$lgc} {$key} <= '{$value}'";
	}
	
	function between($key, $min, $max, $lgc = ' AND'){
		$this->fileds[$key] = " {$lgc} {$key} BETWEEN '{$min}' AND '{$max}'";
	}
    function betweenNums($key, $min, $max, $lgc = ' AND'){
        $this->fileds[$key] = " {$lgc} {$key} BETWEEN {$min} AND {$max}";
    }	
	function groupby($key){
		$this->group = $key;
	}
	
	function orderby($key, $by = 'desc'){
		if($this->order){
			$this->order .= ', ';
		}
		$this->order = "{$key} {$by}";
	}
	
	/**
	 * Enter description here...
	 *
	 * @param int $mask
	 * @return string
	 */
	function getCond($mask = 7){
		$str = '';
		if($mask & 1){
			$str = implode(' ', $this->fileds);
		}
		if($this->group && ($mask & 2)){
			$str .= ' GROUP BY ' . $this->group;
		}
		if($this->order && ($mask & 4)){
			$str .= ' ORDER BY ' . $this->order;
		}
		return $str;
	}
	
	function clearCond(){
		$this->cond = '';
		$this->fileds = array();
	}
	
	function clearGroup(){
		$this->group = '';
	}
	
	function clearOrder(){
		$this->order = '';
	}
	
	function clearAll(){
		$this->clearCond();
		$this->clearGroup();
		$this->clearOrder(); 
	}
}
/**
* put your comment there...
* 
* @return Condition
*/
function &getCond(){
	return new Condition();
}


?>
