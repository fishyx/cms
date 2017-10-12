<?php

printErr($msg); 
asmenu(array(
    'index' => array('文章列表', aurl('news', 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '文章', aurl('news', 'add')),
    ),
    $Action
);

$rmds = array(0=>'未推荐', '1' => '首页推荐','2' => '首页图',);
if($Action == 'edit' || $Action == 'add'){  
    
    $header = array(
        'id'=>'ID', 
        'cid'=>'类别', 
        'title'=>'标题', 
        'summary' => '简介',
        'content' => '详细介绍',
        'comefrom' => '来源',
        'author' => '作者',
        'img' => '图像',
        'accessory' => '附件',
        'rmd' => '推荐',
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
        
    // set fields
    $form->hidden('id');
    $form->text('title', 50);
    
       $select = $cat->getSelect();
    $form->setPattern('cid', $select->create('cid', $info['cid']));
    
    //$form->textarea('summary');
    $form->editor('content');
    $form->img('img');
    //$form->file('accessory', '允许上传：doc|zip|rar');
    $form->text('comefrom', 20);
    $form->text('author', 10);
    $form->select('rmd', $rmds);
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{
        
?>

<form action='<?=aurl('news', 'index')?>' method="post" name="form1" >
    类别：
    <?php 
            $select = $cat->getSelect();
    echo $select->create('cid', $_REQUEST['cid']); 
    echo '关键字:';
    echo HtmlElement::select('f', 
        array('title'=>'标题', 'id'=>'新闻ID'), 
        iif($_REQUEST['f'], 'title')
        );
    ?>
    <input type="text" name="v" size="12" value="<?= $_REQUEST['v'] ?>" />
    <input type="submit"  value=" 搜索 " /> 
</form>

<?

    $header = array(
        'id'=>'ID', 
        'title'=>'标题', 
        'cid'=>'类别',
        'rmd' => '推荐',
        'cdate'=>'发布时间',
        //'author' => '作者',
    );
function _getname($cid, $row) {
    $cat = new Newscat();
    return $cat->getName($row[$cid], true);
}
    $editor = initedEditor($header, $list, $Ctrl);
    
    // set fields
    $talbe =& $editor->getTable();
    $talbe->setCallback('cid', '_getname');
    $editor->setText('title');
    $editor->setText('author');
    $editor->setSelect('rmd', $rmds);
    // end
    
    $editor->display();
    printPageInfo();
}

?>
