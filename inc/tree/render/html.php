<?php

require_once(dirname(dirname(__FILE__)) . '/render.php');

/**
* 
*/
class TreeHtml extends TreeRender{
    
    var $html;
    
    /**
    * tree
    * 
    * @param mixed $root
    */
    function tree($root = 0) {
        if(!is_object($this->cat)){
            throw(new Exception('Property cat is not a object(categorytree)'));
            return;
        }
        $this->html = '';
        $this->cat->traversal($root, $this);
        return $this->html;
    }
    
    function leaf($cid, $deep) {
        $name = $this->_buildLink($cid);
        $space = $this->_spaceLen($deep + 1);
        $stype = $this->_style($deep + 1);
        $this->html .= $this->_space($space) . "<li{$stype}>{$name}</li>\n";
    }
    
    function branchStart($cid, $deep) {
        if($deep > 0){
            $name = $this->_buildLink($cid);
            $space = $this->_spaceLen($deep);
            $stype = $this->_style($deep);
            $this->html .= $this->_space($space) . "<li{$stype}>\n";
            $this->html .= $this->_space($space + 1) . "<h4>{$name}</h4>\n";
            $this->html .= $this->_space($space + 1) . "<ul>\n";
        }
    }
    
    function branchStop($cid, $deep) {
        if($deep > 0){
            $space = $this->_spaceLen($deep);
            $this->html .= $this->_space($space + 1) . "</ul>\n";
            $this->html .= $this->_space($space) . "</li>\n";
        }
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $deep
    */
    function _spaceLen($deep) {
        return $deep * 2 - 1;
    }
    
    function _space($deep) {
        return str_repeat(' ', $deep * 4);
    }
    
    function _stylecname($deep) {
        return $deep == 1 ? 'croot' : '';
    }
    
    function _style($deep) {
        $className = $this->_stylecname($deep);
        return $className ? ' class="' . $className . '"' : '';
    }
    
    /**
    * path
    * 
    * @param mixed $cid
    */
    function path($cid) {
        $cat = $this->cat;
        $parents = $cat->getParents($cid);
        
        $html = '';
        $parents[] = $cid;
        foreach ($parents as $pid) {
            if($pid){
                $html .= $this->_buildLink($pid) . ' / ';
            }
        }
        return $html;
    }
    
    function _buildLink($cid){
        static $ms;
        if(!isset($ms)){
            if(!isset($this->_link) || !$this->_link){
                $this->_link = '<a href="?catid=[id]">[' . $this->getOption('nk') . ']</a>';
            }
            if(preg_match_all('/\[([^\]]+)\]/', $this->_link, $ms)){
                $ms = array_filter(array_unique(array_values($ms[1])));
            }else{
                $ms = array();
            }
        }
        
        $html = $this->_link;
        foreach ($ms as $name){
            if($name == 'id'){
                $value = $cid;
            }else{
                $value = $this->cat->get($cid, $name);
            }
            $html = str_replace("[{$name}]", $value, $html);
        }
        return $html;
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $link
    */
    function setLinkParttern($link) {
        $this->_link = $link;
    }
}
