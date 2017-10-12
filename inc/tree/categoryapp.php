<?php

require_once(SI_INC . 'tree/categorytree.php');
class CategoryApp extends CategoryTree{
    var $table;
    var $tablePre = TABLE_PRE;
    var $tableKey = 'id';
    var $_name = 'name';
    
    var $dbo;
    
    function CategoryApp() {
        $this->dbo = getDb();
        $this->table = $this->tablePre . $this->table;
        parent::CategoryTree();
        
    }
    
    function getCondString($cond = null, $mask = 7){
        if(is_object($cond)){
            $_condSelf .= (is_object($cond) ? $cond->getCond($mask) : '');
        }elseif(($mask & 1) && is_numeric($cond)){
            $_condSelf .= " AND {$this->tableKey}  = " . intval($cond);
        }elseif(($mask & 1) && is_string($cond)){
            $_condSelf .= $cond;
        }
        
        return $_condSelf;
    }
    
    function load(){
        $this->cat->setOption('pk', 'pid');
        $this->cat->setOption('nk', 'name');
        
        $sql = "
            SELECT *
            FROM {$this->table}
            ORDER BY inx, id ASC
            ";
        $cats = $this->dbo->toArray($sql, 'id');
        $this->setData($cats);
    }
    
    function getRow($cond) {
        $condStr = $this->getCondString($cond);
        $sql = "SELECT * FROM {$this->table} WHERE 1 {$condStr}";
        return $this->dbo->getRow($sql);
    }
    
    function insert($data){
        if(!$this->_validate($data)){
            return false;
        }
        
        $this->notify('add_pre', $data);
        $flag = $this->dbo->insert($this->table, $data);
        $this->notify('add_post', $flag);
        if($flag){
            $this->load();
            return true;
        }
        return false;
    }
    
    function update($data, $id){
        $id = intval($id);
        if(!$this->_validate($data, $id)){
            return false;
        }

        if($data['pid'] == $id){
            $this->err = 'parent id is error';
            return false;
        }
        
        $vars = array('data'=> & $data, 'cond'=> $id);
        $this->notify('update_pre', $vars);
        $flag = $this->dbo->update($this->table, $data, " AND {$this->tableKey}={$id}");
        $this->notify('update_post', $flag);
         
        if($flag){
            $sql = "SELECT pid FROM {$this->table} WHERE {$this->tableKey}={$id}";
            $pid = $this->dbo->getOne($sql);
            $this->load();
            return true;
        }
        return false;
    }
    
    function del($id){
        $id = intval($id);
        $cids = $this->getChildren($id);
        if($cids){
            $this->err = 'child is not null';
            return false;
        }
        
        $this->notify('del_pre', $id);
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        $flag = $this->dbo->execute($sql);
        $this->notify('del_post', $flag);
        
        if($flag){
            $this->load();
            return true;
        }
        return false;
    }
    
    function _validate($data, $id = 0){
        $nameKey = $this->_name;
        if(isset($data[$nameKey]) && !$data[$nameKey] 
            || (!$id && !isset($data[$nameKey]))
            ){
            $this->err = 'name is null';
            return false;
        }
        
        $cond = "AND {$nameKey} = '{$data[$nameKey]}' ";
        if($id){
            $cond .= " AND id!={$id}";
        }
        
        $sql = "SELECT id FROM {$this->table} WHERE 1 {$cond}";
        if($this->dbo->getOne($sql)){
            $this->err = 'the class name is exist';
            return false;
        }
        
        return true;
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
