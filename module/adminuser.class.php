<?php

loadClass('Dba');
class Adminuser extends Dba {
    var $_tableName = 'adminuser';

    /**
     * Enter description here...
     *
     * @param unknown_type $username
     * @param unknown_type $pwd
     * @return unknown
     */
    function checkCode($vcode){
        if(checkImgCode($vcode)){
            return true;
        }
        
    }
    function login($username, $pwd){

        $cond = getCond();
        $cond->eq('username', $username);
        $cond->eq('password', $pwd);
        $loginedUser = $this->getRow($cond);
        if(!$loginedUser){
            return false;
        }
        if($loginedUser['dispurviews']){
           $loginedUser['dispurviews'] = explode(',', $loginedUser['dispurviews']);
        }else{
            $loginedUser['dispurviews'] = array();
        }
        
        unset($loginedUser['password']);
        $_SESSION[$this->getSessionKey()] = $loginedUser;
        
        return $loginedUser;
    }
    function add_none($username){
        $ip = real_ip();   
        $date = date("Y-m-d H:i:s");
        $cond = getCond();
        $cond->eq('username',$username);
        $arr = array('lastLoginAt'=>$date,'lastLoginIp'=>$ip);
        $this->update($arr,$cond);
    }
    function getSessionKey(){
        return '__asiteUser__';
    }
    
    function checkLogin(){
        $seKey = Adminuser::getSessionKey();
        return isset($_SESSION[$seKey]) && $_SESSION[$seKey];
    }
    
    function checkRight($op){
        $user = $this->getLoginedUser();
        if(!$user){
            return false;
        }
        isset($op) ? $op : $op = 'theme';
        return !in_array($op, $user['dispurviews']);
    }
    
    function getLoginedUser(){
        return $_SESSION[Adminuser::getSessionKey()];
    }
    
    function logout(){
        unset($_SESSION[Adminuser::getSessionKey()]);
    }
    
    function getUserMenus(){
        $_Navs = array();
        if(file_exists(SI_CFG . 'purviews.php')){
            $_Navs = getRight();
            
            if(!is_array($_Navs)){
                $_Navs = array();
            }
        }
        if($_Navs){
            foreach (array_keys($_Navs) as $op) {
                if($this->checkRight($op)){
                    unset($_Navs[$op]);
                }
            }
        }
        return $_Navs;
    }
}

?>
