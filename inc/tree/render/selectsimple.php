<?php

require_once(dirname(dirname(__FILE__)) . '/render.php');

class TreeSelectSimple extends TreeRender{
    function create($name, $value = '', $params = array()){
        $values = $this->cat->getArray('array');
        
        $splitor = gav($params, 'splitor');
        if(!$splitor){
            $splitor = '&nbsp;';
        };
        
        $select = array();
        foreach ($values as $cid => $item) {
            $space = str_repeat($splitor, 2 * ($item[1] - 1));
            $select[$cid] = $space . $item[0];
        }
        
        $choose = iif(gav($params, 'title'), '-choose-');
        return HtmlElement::select($name, $select, $value, '', $choose);
    }
}
