<?php

printErr($msg); 
asmenu(array(
    'index' => array('设计师分类', aurl('certcat', 'index')),
    ),
    'index'
);

$header = array(
    'cert_jibie' => '导师级别<span class="red">(|分割)</span>', 
    'cert_zhuti' => '主题<span class="red">(|分割)</span>', 
    //'cert_bumen' => '所属部门<span class="red">(|分割)</span>', 
    //'content'=>'自定义JS',

);

$form = initedForm($header, $info, $err);
$form->form(aurl('certcat', 'update'));


$form->text('cert_jibie', 80);
$form->text('cert_zhuti', 80);
//$form->text('cert_bumen', 80);


$form->submit('确定');
$form->reset();
$form->dispaly();


?>