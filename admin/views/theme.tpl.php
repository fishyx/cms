<?php

printErr($msg); 
asmenu(array(
    'index' => array('单页列表', aurl('theme', 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '单页', aurl('theme', 'add')),
    ),
    $Action
);

$rmds = array(0=>'未推荐', 1=>'推荐', '2' => '头条');
if($Action == 'edit' || $Action == 'add'){  
    
    $header = array(
        'id'=>'ID', 
        'cid'=>'类别', 
        'title'=>'标题', 
        'alias'=>'别名', 
        'summary' => '简介',
        'content' => '详细介绍',
        'comefrom' => '来源',
        'author' => '作者',
        'img' => '图像',
        'accessory' => '附件',
        'inx'=>'排序'
       // 'rmd' => '推荐',
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
        
    // set fields
    $form->hidden('id');
    $form->text('title', 50);
   // $form->setPattern('cid', $cat->getSelect()->create('cid', $info['cid']));
   $select = $cat->getSelect();
    $form->setPattern('cid', $select->create('cid', $info['cid']));
   // $form->text('alias', 10);
    //$form->textarea('summary');
    $form->editor('content');
    $form->img('img');
   // $form->file('accessory', '允许上传：doc|zip|rar');
  //  $form->text('comefrom', 20);
    $form->text('inx', 5);
   // $form->select('rmd', $rmds);
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{
        
?>
    
<form action='<?=aurl('theme', 'index')?>' method="post" name="form1" >
    类别：
    <?php 
        $select = $cat->getSelect();
    echo $select->create('cid', $_REQUEST['cid']); 
    echo '关键字:';
    echo HtmlElement::select('f', 
        array('title'=>'标题'), 
        iif($_REQUEST['f'], 'title')
        );
    ?>
    <input type="text" name="v" size="12" value="<?= $_REQUEST['v'] ? $_REQUEST['v'] : '' ?>" />
    <input type="submit"  value=" 搜索 " /> 
</form>

<?

    $header = array(
        'id'=>'ID', 
        'title'=>'标题', 
        'cid'=>'类别名称', 
       // 'rmd' => '推荐',
        'cdate'=>'发布时间',
        'inx'=>'排序',
        'alias'=>'别名',
       // 'inx'=>'排序',
        //'author' => '作者',
    );
    function _getname($cid, $row) {
        $cat = new Themecat();
        return $cat->getName($row[$cid], true);
    }
    $editor = initedEditor($header, $list, $Ctrl);
    $talbe =& $editor->getTable();
    $talbe->setCallback('cid', '_getname');
    // set fields
    $editor->setText('title');
    $editor->setText('cdate');
    $editor->setText('alias');
    $editor->setText('inx');
    $editor->setSelect('rmd', $rmds);
   // $editor->setDelPattern('<a class=del href="' . aurl($Ctrl, 'del', 'id', '[id]').'">删除</a>');
  // $editor->setDelPattern('');
    // end
    
    $editor->display();
    printPageInfo();
}

?>
<script type="text/javascript">
   jQuery(".del").click(function(){
        if(!confirm("确认删除")){
            return false;
        }
    });
</script>
