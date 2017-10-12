<?php
//header('Content-Type:text/html;charset=utf-8');
require("_init.inc.php");
require(SI_INC . "comm.func.php");
loadClass('Cnfdb');
Cnfdb::get('name');
require('actions/base.php');

function safe($var){
    if(is_array($var)){
        foreach($var as $k => $v){
            $var[$k] = safe($v);
        }
        return $var;
    }else{
        return htmlentities($var, null, 'UTF-8');
    }
}
$_POST = safe($_POST);
$_GET = safe($_GET);
$_REQUEST = safe($_REQUEST);
$ctrl = gav($_REQUEST, 'c');
try{
$action = BaseAction::getInstance($ctrl);
$action->run();
}
catch(Exception $e){
     echo 'Message: ' .$e->getMessage();
     echo '<br/>File:' . $e->getFile();
     echo '<br/>error online:' . $e->getLine();
}
