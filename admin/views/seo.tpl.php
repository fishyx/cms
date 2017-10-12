<?php

printErr($msg); 
asmenu(array(
    'index' => array('seo优化', aurl('seo', 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '优化', aurl('seo', 'add')),
    ),
    $Action
);

$rmds = array(0=>'未推荐', 1=>'推荐', '2' => '头条');
if($Action == 'edit' || $Action == 'add'){  
    $header = array(
        'id'=>'ID', 
        'info_type'=>'信息类型', 
        'page_type' => '页面类型',
        'info_id' => '信息ID',
        'title' => 'seo标题',
        'keywords' => 'seo关键字',
        'description' => 'seo描述',
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
        
    // set fields
    $form->hidden('id');
    
    $form->select('info_type', Seo::getInfoType());
    $form->select('page_type', Seo::getPageType());
    $form->text('info_id', 10);
    
    $form->text('title', 30);
    $form->text('keywords', 30);
    $form->textarea('description', 4,60);
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
?>
 <?php  
}else{
?>

<form action='<?=aurl('seo', 'index')?>' method="post" name="form1" >
    <?php 
    echo '信息类型: ', HtmlElement::select('info_type', Seo::getInfoType(), gav($_REQUEST, 'info_type'));
    echo '页面类型: ', HtmlElement::select('page_type', Seo::getPageType(), gav($_REQUEST, 'page_type'));
    echo '页面ID: ', HtmlElement::text('searchID', gav($_REQUEST, 'searchID'), 10);
    ?>
    <input type="submit"  value=" 搜索 " /> 
</form>

<?

    $header = array(
        'id'=>'ID', 
        'info_type'=>'信息类型', 
        'page_type' => '页面类型',
        'info_id'=>'信息ID',
        'title' => 'seo标题',
        'keywords' => 'seo关键字',
        'description' => 'seo描述',
    );

    $editor = initedEditor($header, $list, $Ctrl);
    
    // set fields
    $editor->setSelect('info_type', Seo::getInfoType());
    $editor->setSelect('page_type', Seo::getPageType());
    
    $editor->setText('info_id');
    $editor->setText('title');
    $editor->setText('keywords');
    $editor->setText('description');
    // end

    $editor->display();
    printPageInfo();
}

?>
