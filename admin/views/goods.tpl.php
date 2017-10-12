<?php

printErr($msg); 
asmenu(array(
    'index' => array('课程列表', aurl('goods', 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '课程', aurl('goods', 'add')),
    ),
    $Action
);
$cert_arr = getCertArr();
$rmds = array(0=>'未推荐', 1=>'首页推荐');
$style = Goods::getStyle();
$jieduan = Goods::getJieduan();
if($Action == 'edit' || $Action == 'add'){
?>
<div class="notes">带 <span class="red"> * </span> 的为必填项</div>
<?php
    
    $header = array(
        'id'=>'ID', 
        'cid'=>'<span class="red"> * </span>类别',
        'style'=>'<span class="red"> * </span>上课方式',
        'sj'=>'<span class="red"> * </span>课程时长',
        'title'=>'<span class="red"> * </span>名称',
        'certId'=>'<span class="red"> * </span>讲师',
        'img' => '<span class="red"> * </span>图像',
        'rmd' => '推荐',
        'date'=>'发布时间',
        'summary' => '课程简介',
        'content' => '<span class="red"> * </span>课程详情',
   
    );
    $info['certId'] = explode(',', $info['certId']);

    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));  
    // set fields
    $form->hidden('id');
    $form->text('title', 50);
    $form->select('style', $style);
    $form->text('sj', 50);
    $select = $cat->getSelect();
    $select = $select->create('cid', $info['cid']);
    $form->setPattern('cid', $select);
    $form->img('img');
    $form->checkboxs('certId', $cert_arr);
    $form->textarea('summary');

    $form->editor('content');
    //$form->select('rmd', $rmds);
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{
        
?>
    
<form action='<?=aurl('goods', 'index')?>' method="post" name="form1" >
    类别：
    <?php 
    $cat = $cat->getSelect();
   echo $cat->create('cid', $_REQUEST['cid']);
     
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
function _getname($cid, $row) {
    $cat = new Goodscat();
    return $cat->getName($row[$cid], true);
}
    $header = array(
        'id'=>'ID', 
        'title'=>'名称', 
        'cid'=>'所属类别',
        'img'=>'图片',
        'rmd' => '推荐',

        'cdate'=>'发布时间',
    );

    $editor = initedEditor($header, $list, $Ctrl);
    $table = & $editor->getTable();
    $table->setCallback('cid', '_getname');

    $table->setPattern('img', '<img src="[img]" width="60" class="img-rounded" />');
    // set fields
    $editor->setText('title');
    $editor->setText('cdate');
    $editor->setSelect('rmd', $rmds);
    // end
    
    $editor->display();
    printPageInfo();
}

?>
