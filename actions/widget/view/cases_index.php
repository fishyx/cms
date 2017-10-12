<?php foreach($list as $item){ ?>
<li>
  <div class="from_img"> <img src="<?=imageMini($item['img'])?>" class="img-responsive" alt="<?=$item['title']?>" title="<?=$item['title']?>">
    <div class="shade"></div>
    <div class="more_f"><span>MORE</span></div>
  </div>
  <div class="from_txt_w">
    <div class="zhezhao"><a href="<?php echo $this->url('cases.show', "id={$item['id']}") ?>" title="<?=$item['title']?>"></a></div>
    <p class="t"><?php echo mb_substr($item['title'], 0, 20, 'utf-8') ?></p>
    <p class="k"><?php cuttitle($item['content'],0,20) ?></p>
  </div>
</li>
 <?php } ?>
 
 
 