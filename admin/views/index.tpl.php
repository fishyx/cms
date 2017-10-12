<?php

printErr($msg); 
asmenu(array(
    'index' => array('网站设置', aurl('index', 'index')),
    ),
	'index'
);

$header = array(
    'counter' => '计数器', 
    'siteName' => '网站名称', 
	'mainDomain' => '网址', 
    'company' => '公司名称', 
    'tel' => '公司电话',
    'phone' => '手机',
    'fax' => '传真',
    'address' => '公司地址', 
    'linkman' => '联系人',
    'qq' => 'QQ',
    'gonggao' => '公告',
    'urlRewrite'=>'是否静态化',
    'keywords'=>'关键词',
    'description'=>'网站描述',
    'beian'=>'备案号',
    //'content'=>'自定义JS',

);

$form = initedForm($header, $info, $err);
$form->form(aurl('index', 'update'));
	
// set fields
//$form->text('counter', 20);
$form->text('siteName', 40);
$form->text('mainDomain', 40);
$form->text('company', 40);
$form->text('phone', 24);
$form->text('fax', 24);
$form->text('tel', 24);
$form->text('linkman', 24);
$form->text('address', 40);

$form->text('qq', 40);
$form->text('beian', 24);
$form->text('keywords', 50);
$form->textarea('description');
$form->textarea('gonggao');
//$form->select('urlRewrite', array('否', '是'));

$form->submit('确定');
$form->reset();
$form->dispaly();


?>