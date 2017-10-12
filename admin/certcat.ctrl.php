<?php
/**
* actions{{
* doc : group : cert
* doc : title : 证书类别管理
* doc : action : certcat = [证书类别管理]
* }}
*/
require_once(SI_INC . 'cnfdb.class.php');

class CtrlCertcatAdmin extends CtrlAction  {
	function init(){
	}
    function ActionIndex() {
        if(!$_POST){
            $info = Cnfdb::toArray();
        }else{
            $info = $_POST;
        }
        $this->set('info', $info);
        $this->tpl = 'certcat';
    }
    function ActionUpdate() {
        $_POST = format($_POST);
        foreach ($_POST as $key => $value) {
            Cnfdb::set($key, $value);
        }
        
        $this->set('msg', '操作成功');
        $this->setTpl('certcat');
        $this->ActionIndex();
    }
}

?>