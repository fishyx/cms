<?php
  
class TreeRender{
    /**
    * put your comment there...
    * 
    * @var CategoryTree
    */
    var $cat;
    var $options = array();
    
    function setOption($k, $v = '') {
        if(is_array($k)){
            $this->options = array_merge($this->options, $k);
        }else{
            $this->options[$k] = $v;
        }
    }
    
    function getOption($k) {
        $v = JArray::getValue($this->options, $k);
        if(!$v){
            $v = $this->cat->getOption($k);
        }
        return $v;
    }
    
    function setCat($cat) {
        $this->cat = $cat;
    }
}
