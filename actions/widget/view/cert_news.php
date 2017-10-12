<?php foreach($list as $item){ ?>
<li>
<a href="<?php echo $this->url('cert.show', "id={$item['id']}") ?>"  class="title">
  <img src="<?=imageUrl($item['img'])?>" class="fl" />
  <h3 class="fl">
  设计师：<?=$item['title']?>
  </h3>
  </a>          
  </li>
  <?php
}
   ?>