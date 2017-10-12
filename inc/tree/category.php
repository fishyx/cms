<?php
  
class TreeCategory{
    
    var $cats = array();
    var $_pk = 'pid';
    var $_nk = 'name';
    
    var $_options = array('pk', 'nk');
    
    /**
     * put your comment there...
     * 
     * @param mixed $cats
     */
    function setData($cats) {
        $this->cats = $cats;
    }
    
    function setOption($key, $value){
        if(in_array($key, $this->_options)){
            $key = '_' . $key;
            $this->$key = $value;
        }
    }
   
    function getOption($key){
        $key = '_' . $key;
        return isset($this->$key) ? $this->$key : null;
    }
    
    /**
     * 获取各类别父id
     * 
     */
    function getCatMap() {
        $map = array();
        foreach ($this->cats as $cid => $cat) {
            $map[$cid] = $cat[$this->_pk];
        }
        return $map;
    }
    
    function getCats() {
        return $this->cats;
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $cid
    * @param mixed $key
    */
    function get($cid, $key = '') {
        if(!$key){
            return isset($this->cats[$cid]) ? $this->cats[$cid] : '';
        }
        if(isset($this->cats[$cid][$key])){
            return $this->cats[$cid][$key];
        }else{
            return null;
        }
    }
    
    function getName($cid) {
        return $this->get($cid, $this->_nk);
    }
}