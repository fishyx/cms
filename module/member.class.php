<?php

loadClass('Dba');
class Member extends Dba {
    var $_tableName = 'member';
    function init(){
        $this->setObservable(getInstance('Observable'));
        $ufm =& getUFMPlug();
        $ufm->setThumbsize(200, 180);
        $this->addObserver($ufm);
        return parent::init();
    }        
    function getVRules(){
        return array(
            'title' => array(
                VALID_NOT_EMPTY, '职位不能为空',     true
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