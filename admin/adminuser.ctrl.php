<?php
/**
* actions{{
* doc : group : system
* doc : title : 用户管理
* doc : action : adminuser = [管理管理员]
* }}
*/
class CtrlAdminuserAdmin extends CtrlActionAdmin{
    
    function init(){
        
        $this->mod = getDTable('adminuser');
    }
    
    function ActionLogin(){
        $this->view->setLayout('');
        if($_REQUEST['vcode']){
            if($this->mod->checkCode($_REQUEST['vcode'])){
                
                
        if($_REQUEST['username'] && $_REQUEST['pwd']){
            if($this->mod->login($_REQUEST['username'], $_REQUEST['pwd'])){
                $this->mod->add_none($_REQUEST['username']);
                redirect(SI_ROOT_WEB . 'admin.php');

            }else{
                $this->setV('msg', '用户名或密码错误');
            }
        }else{
            $this->setV('msg', '请先登录!');
        }
        }else{
           $this->setV('msg','验证码错误');
        }
        }else{
            $this->setV('msg','请登录');
        }
        $this->view->setTpl('_login');
        
    }
    
    function ActionLogout(){
        $this->view->setLayout('');
        $this->mod->logout();
        $this->setV('msg', '您已经退出登录!');
        $this->view->setTpl('_login');
    }
    
    function ActionOffside(){
        $this->view->setErr('not enough purview');
    }
    function ActionUpdate(){
        if(is_array($_POST['dispurviews'])){
        $_POST['dispurviews'] = implode(',', $_POST['dispurviews']);
        }
        if(!$_POST['dispurviews']){
            $_POST['dispurviews'] = '';
        }
        parent::ActionUpdate();
    }
    function ActionInsert(){
        if(is_array($_POST['dispurviews']))
        $_POST['dispurviews'] = implode(',', $_POST['dispurviews']);
        parent::ActionInsert();        
    }
}

?>
