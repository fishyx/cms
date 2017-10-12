<?php
/**
* actions{{
* doc : group : theme
* doc : title : 专题
* doc : action : theme = [专题]
* }}
*/
loadModule('Theme');
loadModule('Themecat');

class CtrlThemeAdmin extends CtrlActionAdmin {
    
    var $_defOrderField = 'id';
    
    var $cat;
    
    function init(){
        $this->mod = new Theme();
        $this->cat = new Themecat();
        $this->view->setVar('cat', $this->cat);
    }
    
    function &setSearch(& $cond){
        // search
        parent::setSearch($cond);
        if($cid = $_REQUEST['cid']){
            $this->mod->setCid($cid);
        }
    }
}

?>