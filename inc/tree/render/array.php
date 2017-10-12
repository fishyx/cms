<?php

require_once(dirname(dirname(__FILE__)) . '/render.php');

class TreeArray extends TreeRender{
    var $store = array();
    
    function tree($root = 0) {
        if(!is_object($this->cat)){
            throw(new Exception('Property cat is not a object(categorytree)'));
            return;
        }
        $this->store = array();
        $this->cat->traversal($root, $this);
        return $this->store;
    }
    
    function leaf($cid, $deep) {
        $this->store[$cid] = array($this->cat->getName($cid), $deep);
    }
    
    function branchStart($cid, $deep) {
        $this->leaf($cid, $deep);
    }
    
    function branchStop($cid, $deep) {
    }
}
