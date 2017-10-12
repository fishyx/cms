<?php
/**
* actions{{
* doc : group : flink
* doc : title : 友情链接管理
* doc : action : flikn = [友情链接管理]
* }}
*/
class CtrlFlinkAdmin extends CtrlActionAdmin {
    
    var $_defOrderField = 'id';
    
    function init(){
        $this->mod = getDTable('Flink');
    }
}

?>