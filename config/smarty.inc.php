<?php
require("libs/Smarty.class.php");
$smarty = new Smarty();
$smarty -> compile_dir    = './templates_c';
$smarty -> config_dir     = './configs';
$smarty -> cache_dir      = './cache';
$smarty -> left_delimiter  = '<{';
$smarty -> right_delimiter = '}>';
//$smarty -> caching        = true;
//$smarty -> cache_lifetime = 300;
?>
