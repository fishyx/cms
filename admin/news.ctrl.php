<?php
/**
* actions{{
* doc : group : news
* doc : title : 新闻管理
* doc : action : news = [新闻中心]
* }}
*/
loadModule('News');
loadModule('Newscat');

class CtrlNewsAdmin extends CtrlActionAdmin {
    
    var $_defOrderField = 'id';
    
    var $cat;
    
    function init(){
        $this->mod = new News();
        $this->cat = new Newscat();
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