<?php
/**
* actions{{
* doc : groups : goods
* doc : title : 产品管理
* doc : action : goods = [产品管理]
* }}
*/
loadModule('Goods');
loadModule('Goodscat');

class CtrlGoodsAdmin extends CtrlActionAdmin {
    
    var $_defOrderField = 'id';
    
    var $cat;
    
    function init(){
        $this->mod = new Goods();
        $this->cat = new Goodscat();
        $this->view->setVar('cat', $this->cat);
    }
    
    function &setSearch(& $cond){
        // search
        parent::setSearch($cond);
        if($cid = $_REQUEST['cid']){
            $this->mod->setCid($cid);
        }
    }
    function ActionInsert(){
        if(isset($_POST['certId'])&&is_array($_POST['certId'])){
            $_POST['certId'] = implode(',', $_POST['certId']);
        }
        parent::ActionInsert();
    }
    function ActionUpdate(){

        if(isset($_POST['certId'])&&is_array($_POST['certId'])){
            $_POST['certId'] = implode(',', $_POST['certId']);
        }
        parent::ActionUpdate();
    }
    function ActionHuodong(){        
    }
}

?>