<?php require(dirname(__FILE__) . '/widget/header.php')?>
<div class="pub_list_bg">
  <div class="w_1200 pub_title clr">
    <h2 class="fl"> 当前位置：<?=$this->getPath();?> </h2>
  </div>
</div>
<div class="news-list w_1200">
  <div class="left">
    <ul>
        <?php foreach($list as $info){ ?>
      <li>
        <div class="news-images"> <a href="<?php echo $this->url('news.show', $info['id'] ) ?>"> <img src="<?= $info['img'] ? $info['img'] : 'images/ketang5.jpg'?>" alt="" width="279" height="185"> </a> </div>
        <div class="news-info-right"> <a href="<?php echo $this->url('news.show', $info['id'] ) ?>">
          <h3><?=cuttitle($info['title'], 0, 30)?></h3>
          </a>
          <p> <?=cuttitle($info['content'], 0, 220)?></p>
        </div>
      </li>
      <?php
        }
       ?>
    </ul>
    <div class="pages clr">
      <ul class="pagelist clr auto">
        <?=$pages?>
      </ul>
    </div>
  </div>
  <? require 'widget/_left.php'?>
</div>
<?php require(dirname(__FILE__) . '/widget/footer.php')?>