<?php

printErr($msg); 
asmenu(array(
    'index' => array('留言管理', aurl($Ctrl, 'index')),
    ),
    'index'
);

?>

<form action='?' method="get" name="form1" >
<input type="hidden" name="q" value="<?= $Acpre ?>index" />
	搜索方式：
	<?php 
	echo HtmlElement::select('f', 
		array('linkman'=>'联系人', 'id'=>'ID'), 
		iif($_REQUEST['f'], 'linkman')
		);
	?>
	<input type="text" name="v" size="12" value="<?= $_REQUEST['v'] ?>" />
	<input type="submit"  value=" 搜索 " /> 
	<?php //printAddBnt($Acpre); ?>
</form>

<?php

$shows = array('1' => '已处理', '0' => '未处理');

if($Action == 'edit'){  
	$header = array(
		'id'=>'ID', 
		'qq'=>'留言内容', 
		'linkman' => '装修方式',
		'area' => '房屋面积',
		'email' => '电话',
		'qq' => '区域',
		'recontent' => '回复内容',
		'show' => '前台显示'
	);
	
	$form = initedForm($header, $info, $err);
	$form->form(aurl($Ctrl, 'update'));
	
	// set fields
	$form->hidden('id');
    //$form->text('title');
	$form->text('linkman');
    $form->text('email');
    $form->text('qq');
	$form->text('area');

	// end
	
	$form->submit('确定');
	$form->reset();
	$form->dispaly();
	
}else{

	$header = array(
		'id'=>'ID', 
		//'title'=>'标题', 
		'linkman'=>'装修方式', 
        'email'=>'电话', 
        'qq'=>'区域', 
		'area'=>'房屋面积', 
        'cdate'=>'发布时间',
		
	);

	$editor = initedEditor($header, $list, $Ctrl);
	
	// set fields
	$editor->setText('content');
	$editor->setText('linkman');
    $editor->setText('phone');
	$editor->setSelect('show', $shows);
	// end
	
	$editor->display();
	printPageInfo();

}

?>
