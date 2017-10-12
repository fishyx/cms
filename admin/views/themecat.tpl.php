<?php 
printErr($msg); 
asmenu(array(
    'index' => array('单页类别管理', aurl('themecat', 'index')),
    ),
    $Action
);

switch ($Action) {
case 'index' : 
?>

<form action='<?=aurl('themecat', 'insert')?>' method="post" name="form1" >
    选择父级：
    <?php 
    /**
    * put your comment there...
    * 
    * @var CategoryApp
    */
        $select = $cat->getSelect();
    echo $select->create('pid', iif($_REQUEST['pid'], $_REQUEST['catid'])); 
    ?>
    
    名称：<input type="text"  name="name" size="12" value="<?= $_POST['name'] ?>" />
    英文名称：<input type="text"  name="en_name" size="12" value="<?= $_POST['en_name'] ?>" />
    
    <input type="submit"  value="创建新类别" />
</form>

<hr />

<div class="list_div" id="mainList">
<div class="location">
    <a href="<?=aurl('themecat', 'index')?>">全部类别</a> > <?= $path ?>
</div>
<FORM  action="<?=aurl('themecat', 'upid')?>" method='POST'>
<table width="100%" class="datalist" align="center" cellpadding="3" cellspacing="1">
<thead>
	<tr>
		<th>id</th>
        <th><b>名称</b></th>
		<th><b>英文名称</b></th>
        <th>排序</th>
		<th>操作</th>
	</tr>
</thead>
<?php 
foreach($cat->getChild($cid) as $scid){

?>
    <tr>
		<td align="left">
			<input type="checkbox" name="id[]" value="<?= $scid ?>"><?= $scid ?></td>
		
		<td><input type="text" value="<?= $cat->getName($scid) ?>" 
                onchange="MAjax.send('<?=aurl('themecat', 'ajax')?>&f=name&id=<?= $scid ?>&v=' + encodeURI(this.value))"></td>
        <td><input type="text" value="<?= $cat->get($scid, 'en_name') ?>" 
				onchange="MAjax.send('<?=aurl('themecat', 'ajax')?>&f=en_name&id=<?= $scid ?>&v=' + encodeURI(this.value))"></td>
				
      	<td><input type=text size="10" value="<?= $cat->get($scid, 'inx') ?>"
				onchange="MAjax.send('<?=aurl('themecat', 'ajax')?>&f=inx&id=<?= $scid ?>&v=' + encodeURI(this.value))"></td>
      
       
	    <td>
        <?php if($cat->getChild($scid)){ ?>
            <a href="<?=aurl('themecat', 'index', 'catid', $scid)?>">编辑子类</a>
        <?php } ?>
            <a href="<?=aurl('themecat', 'edit', 'id', $scid)?>">编辑信息</a>
			<a href="<?=aurl('themecat', 'del', 'id', $scid)?>">删除</a>!
		</td>
	</tr>
<?php } ?>
</table>
</form>
</div>

<?php
break;
case 'edit' : 

    $header = array(
        'id'=>'ID', 
        'name'=>'类别名称', 
        'pid'=>'上级类别', 
        'img' => '图片',
        'intro' => '内容简介',
    );
    
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, 'update'));
        
    // set fields
    $form->hidden('id');
    $form->text('name', 20);
        $select = $cat->getSelect();
    $form->setPattern('pid', $select->create('cid', $info['cid']));
    $form->img('img');
    $form->editor('intro');
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();

break;
}
?>
