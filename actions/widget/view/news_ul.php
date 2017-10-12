
  <?php foreach($list as $item){ ?>
      <div class="yyhd_list"><a href="<?php echo $this->url('news.show', "id={$item['id']}") ?>" target="_blank"><?php echo cuttitle_b($item['title'],0,20) ?></a></div>
  <?php
  }
   ?>

