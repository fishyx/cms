<?php
/**
* actions{{
* doc : groups : index
* doc : title : 管理首页
* doc : action : index = [管理首页]
* }}
*/
require_once(SI_INC . 'cnfdb.class.php');

class CtrlIndexAdmin extends CtrlAction{
    
    function ActionIndex() {
        if(!$_POST){
            $info = Cnfdb::toArray();
        }else{
            $info = $_POST;
        }
        $this->set('info', $info);
        $this->setTpl('index');
    }
    
    function ActionUpdate() {
        $_POST = format($_POST);
        foreach ($_POST as $key => $value) {
            Cnfdb::set($key, $value);
        }
        $this->set('msg', '操作成功');
        $this->ActionIndex();
    }

}

?>
