<?php

loadClass('Dba');
class User extends Dba {
    var $_tableName = 'user';
        
    function getVRules(){
        return array(
            'user' => array(
                VALID_NOT_EMPTY, '用户名不能为空',     true
                ),
            'pass' => array(
                VALID_NOT_EMPTY, '密码不能为空',     true
                ),
            'email' => array(
                VALID_EMAIL, '邮箱格式无效', false
                ),    
            'phone' => array(
                '/^[\d -_]+$/', '电话号码格式无效', true
                ),    
            'qq' => array(
                '/^[\d]+$/', 'QQ号码格式无效', false
                ),
        );
    }
    function login($user,$pass){
        $cond = getCond();
        $cond->eq('pass',$pass);
        $cond->eq('user',$user);
        $login = $this->getRow($cond);
        if(!$login){
            return false;
        }
        if($login['user']){
           $_SESSION['user_login'] = $login['user']; 
        }
        return $login;
    }
}

?>