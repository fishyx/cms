<?php

printErr($msg); 
asmenu(array(
    'index' => array('友情链接', aurl($Ctrl, 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '链接', aurl($Ctrl, 'add')),
    ),
    $Action
);

if($Action == 'edit' || $Action == 'add'){  
    
    $header = array(
        'id'=>'ID', 
        'img'=>'图片', 
		'name' => '网站名称',
        'cid' => '类型',
        'url' => '链接地址',
        'img' => '图像',
		'width' => '宽',
		'height' => '高',
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
        
    // set fields
    $form->hidden('id');
    $form->text('name', 20);
	$form->select('cid', Flink::getCats());
	$form->text('url', 40);
	
	$form->img('img');
    //$form->text('width', 4);
	//$form->text('height', 4);
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{
        
?>
    
<form action='<?=aurl($Ctrl, 'index')?>' method="post" name="form1" >
    类别：
    <?php 
    echo HtmlElement::select('f', 
        array('name'=>'网站名称', 'id'=>'编号'), 
        iif($_REQUEST['f'], 'name')
        );
    ?>
    <input type="text" name="v" size="12" value="<?= $_REQUEST['v'] ?>" />
    <input type="submit"  value=" 搜索 " /> 
</form>

<?php

    $header = array(
        'id'=>'ID', 
        'name'=>'网站名称', 
        'cid' => '链接类型', 
        'url' => '链接地址',
        'inx' => '排序',
    );
    $editor = initedEditor($header, $list, $Ctrl);

    // set fields
    $editor->setText('name');
    $editor->setSelect('cid', Flink::getCats());
    $editor->setText('url');
    $editor->setText('inx');
    // end
    $editor->display();
    printPageInfo();

}

?>
