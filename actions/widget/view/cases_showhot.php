<?php foreach($list as $key => $item){
if($key <4){
 ?>

                                        <li><a href="<?php echo $this->url('cases.show', "id={$item['id']}") ?>"><img height="88" alt="<?=$item['title']?>" src="<?=imageUrl($item['img'])?>"  width="120" /><?=$item['title']?></a> </li>
<?php } }?>