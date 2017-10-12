<?php

class Tree{
    
    var $catMap = array();
    var $tree = array();
    var $dtree = array();
    var $ptree = array();
    var $ctree = array();
    
    var $deep = -1;
    
    /**
     * put your comment there...
     * 
     * @param mixed $root
     * @param mixed $observer
     */
    function traversal($root, & $observer) {
        $this->deep++;
        if($this->deep > 0)
            $observer->branchStart($root, $this->deep);
        
        $child = $this->getChild($root);
        foreach ($child as $cid) {
            if($this->isBranch($cid)){
                $this->traversal($cid, $observer);
            }else{
                $observer->leaf($cid, $this->deep + 1);
            }
        }
        if($this->deep > 0)
            $observer->branchStop($root, $this->deep);
        $this->deep--;
    }
    
    /**
     * put your comment there...
     * 
     */
    function setMap($data) {
        $this->catMap = $data;
        $this->reload();
    }
    
    function reload() {
        $this->tree = array();
        $this->ptree = array();
        $this->dtree = array();
        $this->ctree = array();
        $this->_build();
    }
    
    function _build() {
        $tree = array();
        foreach ($this->catMap as $cid => $pid) {
            if($cid == $pid){
                die('category err(id:' . $cid . ')');
            }
            if(!isset($tree[$pid]) || !is_array($tree[$pid])){
                $tree[$pid] = array();
            }
            $tree[$pid][] = $cid;
        }
        $this->tree = $tree;
    }
    
    function getTree() {
        return $this->tree;
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $cid
    * @return mixed
    */
    function getDeep($cid) {
        if(!isset($this->dtree[$cid])){
            $parents = $this->getParents($cid);
            $this->dtree[$cid] = count($parents);
        }
        return $this->dtree[$cid];
    }
    
    function getPid($cid, $deep = null) {
        if(!is_numeric($deep)){
            return gav($this->catMap, $cid);
        }else{
            $parents = $this->getParents($cid);
            if(isset($parents[$deep])){
                return $parents[$deep];
            }else{
                return null;
            }
        }
    }
    
    function getParents($cid) {
        if(!isset($this->ptree[$cid])){
            $parents = array();
            if($pid = $this->getPid($cid)){
               $parents = $this->getParents($pid);
               $parents[] = $pid;
            }
            $this->ptree[$cid] = $parents;
        }
        return $this->ptree[$cid];
    }
    
    function getChild($cid, $self = false) {
        if(isset($this->tree[$cid]) && is_array($this->tree[$cid])){
            if($self){
                $cids = $this->tree[$cid];
                $cids[] = $cid;
                return $cids;
            }
            return $this->tree[$cid];
        }
        
        if($self){
            return array($cid);
        }else{
            return array();
        }
    }
    
    function getChildren($cid, $self = false) {
        if(!isset($this->ctree[$cid])){
            $children = $cats = $this->getChild($cid);
            foreach ($cats as $_cid) {
                $children = array_merge($children, $this->getChildren($_cid));
            }
            $this->ctree[$cid] = $children;
        }
        
        if($self){
            $cids = $this->ctree[$cid];
            $cids[] = $cid;
            return $cids;
        }
        
        return $this->ctree[$cid];
    }
    
    function isBranch($cid) {
        return isset($this->tree[$cid]) && is_array($this->tree[$cid]);
    }
    
    function isLeaf($cid) {
        return !$this->isBranch($cid);
    }
    
    function getPath($cid) {
        $parents = $this->getParents($cid);
        if($cid){
            $parents[] = $cid;
        }
        return implode('_', $parents);
    }
}