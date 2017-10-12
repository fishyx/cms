<?php
  $child1 = $newscat->getChild(0);
?> 

<?php
foreach($child1 as $v1){
?>           
    <li><a href="<?=$this->url('news.list', array('cid'=> $v1))?>"><?=$newscat->getName($v1)?></a></li>
<?php
}
?>

