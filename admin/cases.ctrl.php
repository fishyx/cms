<?php
/**
* actions{{
* doc : group : case
* doc : title : 案例管理
* doc : action : cases = [案例]
* }}
*/
loadModule('Cases');
loadModule('Casescat');
loadModule('cert');
class CtrlCasesAdmin extends CtrlActionAdmin {
    
    public $_defOrderField = 'id';
    
    public $cat;
    public $cert;
    function init(){
        $this->mod = new Cases();
        $this->cat = new Casescat();
        $this->cert = new Cert();
        $list = $this->cert->getList(20,'');
        $this->view->setVar('cat', $this->cat);
        $this->view->setVar('cert', $this->cert);
        $this->view->setVar('cert_arr', $this->getCert_arr($list));
    }
    function getCert_arr($arr = array()){
        $arr_name = array();
        foreach($arr as $key => $info){
            $arr_name[$info['id']] = $info['title'];
        }
        return $arr_name;
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