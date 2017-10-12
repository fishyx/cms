<?php

printErr($msg); 
asmenu(array(
    'index' => array('广告列表', aurl($Ctrl, 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '广告', aurl($Ctrl, 'add')),
    ),
    $Action
);
$rmds = array(1=>'banner轮播');
if($Action == 'edit' || $Action == 'add'){      
    $header = array(
        'id'=>'ID', 
        'name'=>'广告位', 
		'url' => '链接地址',
		'src' => '广告文件',
        'width' => '宽',
        'height' => '高',
        'rmds'=>'类别'
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
        
    // set fields
    $form->hidden('id');
    $form->text('name', 20);
    $form->select('rmds',$rmds);
	$form->text('url', 40);
	
	$form->text('width',4);
    $form->text('height',4);
	
    $form->img('src');
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();   
}else{
    $header = array(
        'id'=>'ID', 
        'name'=>'广告位', 
        'rmds'=>'类别',
		'url' => '链接地址',
        'width' => '宽',
        'height' => '高',
        'inx'=>'排序',
    );
    $editor = initedEditor($header, $list, $Ctrl);   
    // set fields
    $editor->setSelect('rmds', $rmds);
    $editor->setText('name');
    $editor->setText('url');
	$editor->setText('width');
	$editor->setText('height');
    $editor->setText('inx');
    // end
    $editor->display();
    printPageInfo();

}

?>
