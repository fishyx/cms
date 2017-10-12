<?php

printErr($msg); 
asmenu(array(
    'index' => array('图文列表', aurl('cases', 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '图文', aurl('cases', 'add')),
    ),
    $Action
);

$rmds = array(0=>'未推荐', 1=>'首页推荐',2=>'首页推荐2',3=>'首页推荐3',4=>'首页推荐4',5=>'首页推荐5',6=>'首页推荐6',);
$hx = Cases::getStyle();
$area = Cases::getSize();
if($Action == 'edit' || $Action == 'add'){  
    
    $header = array(
        'id'=>'ID', 
        'cid'=>'类别', 
        'title'=>'标题', 
        'summary' => '简介',
        'content' => '详细介绍',
        'comefrom' => '来源',
        'shejishi' => '设计师',
        'attr_area' => '面积',
        'area' => '面积',
        'attr_hx' => '户型',
        'shejishi' => '设计师',
        'price' => '造价',
        'img' => '图像',
        'accessory' => '附件',
        'rmd' => '推荐',
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
        
    // set fields
    $form->hidden('id');
    $form->text('title', 50);
    $form->text('price', 30);
    $form->select('shejishi', $cert_arr);
    $select = $cat->getSelect();
    $form->setPattern('cid', $select->create('cid', $info['cid']));
    $form->select('attr_hx',$hx);
    $form->select('attr_area',$area);
    $form->text('area', 20);
    //$form->textarea('summary');
    $form->editor('content');
    $form->img('img');
    //$form->file('accessory', '允许上传：doc|zip|rar');
    $form->select('rmd', $rmds);
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{
        
?>

<form action='<?=aurl('cases', 'index')?>' method="post" name="form1" >
    类别：
    <?php 
   $select = $cat->getSelect();
    echo $select->create('cid', iif($_REQUEST['cid'], $_REQUEST['cid'])); 
    echo '关键字:';
    echo HtmlElement::select('f', 
        array('title'=>'标题', 'id'=>'新闻ID'), 
        iif($_REQUEST['f'], 'title')
        );
    ?>
    <input type="text" name="v" size="12" value="<?= $_REQUEST['v'] ?>" />
    <input type="submit"  value=" 搜索 " /> 
</form>

<?php

    $header = array(
        'id'=>'ID',
        'title'=>'标题',
        'rmd' => '推荐',
        'img'=>'图片',
        'cdate'=>'发布时间',
        'author' => '作者',
    );

    $editor = initedEditor($header, $list, $Ctrl);
       $table =  $editor->getTable();
    $table->setCallback('cid', '_getname');

    $table->setPattern('img', '<img src="[img]" width="60" />');
    // set fields
    $editor->setText('title');
    $editor->setText('author');
    $editor->setSelect('rmd', $rmds);
    // end

    $editor->display();
    printPageInfo();
}
?>
