<?php

printErr($msg); 
asmenu(array(
    'index' => array('导师列表', aurl('cert', 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '导师', aurl('cert', 'add')),
    ),
    $Action
);
$jiebie = Cert::getJiebie();
$zhuti = Cert::getZhuti();

$rmds = array(0=>'未推荐', 1=>'首页推荐',2=>'首页推荐右');
if($Action == 'edit' || $Action == 'add'){  
    
    $header = array(
        'id'=>'ID', 
        'title'=>'姓名',
        'img' => '头像',
        'jingyan' => '经验',
        'year' => '年限',
        'jibie' => '讲师级别',
        'zhiwei' => '职位',
        'zhuti' => '专注领域',
        'sc' => '擅长领域',
        'zp' => '近期作品',
        'content' => '个人详情',
        
        'rmd' => '推荐',
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
    $form->text('title', 20);
    $form->select('zhuti',$zhuti);
    $form->select('jibie',$jiebie);
    $form->text('year', 10);
    $form->text('zhiwei', 30);
    $form->textarea('sc', 3);
    //$form->textarea('zp');
    //$form->textarea('ln');
    // set fields
    $form->hidden('id');
    $form->editor('content');
    
    $form->img('img');
    $form->select('rmd', $rmds);
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{
        
?>

<form action='<?=aurl('cert', 'index')?>' method="post" name="form1" >

    <?php 
 
    echo '关键字:';
    echo HtmlElement::select('f', 
        array('title'=>'标题', 'id'=>'ID'), 
        iif($_REQUEST['f'], 'title')
        );
    ?>
    <input type="text" name="v" size="12" value="<?= $_REQUEST['v'] ?>" />
    <input type="submit"  value=" 搜索 " /> 
</form>

<?
function _getname($cid, $row) {
    $cat = new Certcat();
    return $cat->getName($row[$cid], true);
}
    $header = array(
        'id'=>'ID', 
        'title'=>'姓名', 
        'cid'=>'职位',
        'img'=>'头像',
        'rmd'=>'推荐',
        'cdate'=>'发布时间',
    );

    $editor = initedEditor($header, $list, $Ctrl);
           $table = & $editor->getTable();
    $table->setCallback('cid', '_getname');

    $table->setPattern('img', '<img src="[img]" width="60" />');
    // set fields
    $editor->setText('title');
    $editor->setSelect('rmd', $rmds);
    // end
    
    $editor->display();
    printPageInfo();
}

?>
