<?php
/**
* actions{{
* doc : group : cert
* doc : title : 证书管理
* doc : action : cert = [证书管理]
* }}
*/
loadModule('Cert');
loadModule('Certcat');

class CtrlCertAdmin extends CtrlActionAdmin {
    
    var $_defOrderField = 'id';
    
    //var $cat;
    
    function init(){
        $this->mod = new Cert();
       // $this->cat = new Certcat();
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