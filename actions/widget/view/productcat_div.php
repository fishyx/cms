<?php
  $child1 = $goodscat->getChild(0);
?> 

<?php
foreach($child1 as $v1){
?>           
<div class="nav_3"><a href="<?=$this->url('product.list', array('cid'=> $v1))?>"><?=$goodscat->getName($v1)?></a></div>
<?php
}
?>

