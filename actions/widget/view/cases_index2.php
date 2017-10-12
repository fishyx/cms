<?php foreach($list as $item){ ?>
<li> <a href="<?php echo $this->url('cases.show', "id={$item['id']}") ?>" title="店铺名称"> <img src="<?=imageMini($item['img'])?>" alt="<?=$item['title']?>" title="<?=$item['title']?>"> <span class="title"><?=$item['title']?></span><em style="opacity:0.6;"></em><i></i> </a> </li>
<?php } ?>