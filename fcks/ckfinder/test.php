<?php
date_default_timezone_set('Asia/Shanghai');
require(dirname(dirname(dirname(__FILE__))) . "/inc/session.inc.php");
$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$baseUrl = substr(__FILE__, 0, strpos(__FILE__, 'fck'));
echo '<pre>';
print_r($_SERVER['DOCUMENT_ROOT']);
echo '</pre>';

$baseUrl = str_replace('\\', '/', $baseUrl);
$baseUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $baseUrl);
echo '<pre>';
print_r($baseUrl);
echo '</pre>';
exit;
$baseUrl = '/' . ltrim($baseUrl, '/');

$baseUrl =  $baseUrl . 'uploads/fck/' . date('Y-m') . '/';

$baseDir = resolveUrl($baseUrl);
