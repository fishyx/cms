<?php

class CategoryTree{
    
    var $html;
    var $select;
    var $array;
    
    var $_options = array();

    /**
    * put your comment there...
    * 
    * @var mixed
    */
    var $instance;
    
    /**
    * put your comment there...
    * @return Goodscat
    */
    function getInstance(){
        static $instance;
        if(!isset($instance)){
            $instance = new self();
        }
        return $instance;
    }
      
    /**
    * put your comment there...
    * 
    * @param NCategory $cat
    * @param Tree $tree
    * @return CategoryTree
    */
    function CategoryTree($cat = null, $tree = null) {
        // set cat;
        if(is_object($cat)){
            $this->cat = $cat;
        }else{
            $this->cat = $this->getCat();
        }
        
        // set tree
        if(is_object($tree)){
            $this->tree = $tree;
        }else{
            $this->tree = $this->getTree();
        }
    }
    
    function __get($k) {
        if($k == 'tree'){
            return $this->getTree();
        }elseif($k == 'cat'){
            return $this->getCat();
        }
    }
    
    function getTree(){
        if(!isset($this->tree)){
            require_once(dirname(__FILE__) . '/tree.php');
            $this->tree = new Tree();
            $this->tree->setMap($this->cat->getCatMap());
        }
        return $this->tree;
    }
    
    function getCat(){
        if(!isset($this->cat)){
            require_once(dirname(__FILE__) . '/category.php');
            $this->cat = new TreeCategory();
            $this->load();
        }
        return $this->cat;
    }
    
    function load() {}
    
    /**
    * render fuctions
    * 
    */
    function getHtml() {
        if(!isset($this->html)){
            require_once(dirname(__FILE__) . '/render/html.php');
            $this->html = new TreeHtml();
            $this->initHtmlOptions();
            $this->html->setCat($this);
        }
        return $this->html;
    }
    
    function initHtmlOptions() {}
    
    /**
    * put your comment there...
    * 
    */
    function getSelect() {
        if(!isset($this->select)){
            require_once(dirname(__FILE__) . '/render/selectsimple.php');
            $this->select = new TreeSelectSimple();
            $this->select->setCat($this);
        }
        return $this->select;
    }
    
    function initSelectOptions() {}
    
    /**
    * put your comment there...
    * 
    * @return TreeArray
    */
    function getArray($return = 'object') {
        if(!isset($this->array)){
            require_once(dirname(__FILE__) . '/render/array.php');
            $this->array = new TreeArray();
            $this->array->setCat($this);
        }
        
        if($return == 'array'){
            return $this->array->tree(0);
        }else{
            return $this->array;
        }
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $cats
    */
    function setData($cats) {
        $this->cat->setData($cats);
    }
    
    function getCatsForSelect() {
        $cats = array();
        $pk = $this->getOption('pk');
        $nk = $this->getOption('nk');
        foreach ($this->cat->getCats() as $cid => $item) {
            $cats[$cid] = array($pk => $item[$pk], $nk => $item[$nk]);
        }
        return $cats;
    }
    
    /**
    * proxy functions
    * 4 funcitons
    */
    function get($cid, $k = '') {
        return $this->cat->get($cid, $k);
    }
    
//    function getName($cid) {
//        return $this->cat->getName($cid);
//    }
    
    function getName($cid, $full = false, $returnArr = ' - ') {
        if(!$full){
            return $this->cat->getName($cid);
        }
        
        $names = array();
        $parents = $this->getParents($cid);
        if($parents){
            foreach ($parents as $pid) {
                $names[$pid] = $this->cat->getName($pid);
            }
        }
        $names[$cid] = $this->cat->getName($cid);
        if($returnArr){
            if(is_string($returnArr)){
                return implode($returnArr, $names);
            }
        }
        return $returnArr;
    }
    
    function getOption($k){
        return $this->cat->getOption($k);
    }
    
    function setOption($k, $v){
        return $this->cat->setOption($k, $v);
    }
    
    // tree functions
    // 9 functions
    function getPid($cid, $deep = null) {
        return $this->tree->getPid($cid, $deep);
    }
    
    function getParents($cid) {
        return $this->tree->getParents($cid);
    }
    
    function getTopcid($cid) {
        $tcid = $this->getPid($cid, 0);
        if(!$tcid){
            return $cid;
        }else{
            return $tcid;
        }
    }
    
    function getTopName($cid) {
        return $this->getName($this->getTopcid($cid));
    }
    
    function getChild($cid, $self = false) {
        return $this->tree->getChild($cid, $self);
    }
    
    function getChildren($cid, $self = false) {
        return $this->tree->getChildren($cid, $self);
    }
    
    function isBranch($cid) {
        return $this->tree->isBranch($cid);
    }
    
    function isLeaf($cid) {
        return $this->tree->isLeaf($cid);
    }
    
    function getPath($cid) {
        return $this->tree->getPath($cid);
    }
    
    function getDeep($cid) {
        return $this->tree->getDeep($cid);
    }
    
    function traversal($cid, & $observer) {
        return $this->tree->traversal($cid, $observer);
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $topid
    * @return string
    */
    function tops($topid = 0) {
        $cats = array();
        foreach ($this->getChild($topid) as $cid) {
            $cats[$cid] = $this->getName($cid);
        }
        return $cats;
    }
}

?>
