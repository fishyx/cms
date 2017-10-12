<?php
  $child1 = $goodscat->getChild(0);
?> 

<?php
foreach($child1 as $v1){
?>           
    <li><a href="<?=$this->url('product.list', array('cid'=> $v1))?>"><?=$goodscat->getName($v1)?></a></li>
<?php
}
?>

