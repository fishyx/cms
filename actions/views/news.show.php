<?php require(dirname(__FILE__) . '/widget/header.php')
?>
<div class="pub_list_bg">
  <div class="w_1200 pub_title clr">
    <h2 class="fl"> 当前位置：<?=$this->getPath();?> </h2>
  </div>
</div>
<div class="news-list w_1200">
  <div class="left fl aks">
    <h1><?=$this->info['title']?></h1>
    <ul class="clr title">
        <li>DATE：<?=$this->info['cdate']?></li>
        <li>READ：<?=$this->info['hits']?></li>
        <li>来源：<?=$this->info['comefrom']?></li>
    </ul>
    <div class="cont_box">
        <?=$this->info['content']?>
    </div>    
  </div>
  <?php require(dirname(__FILE__) . '/widget/_left.php')?>
</div>

<?php require(dirname(__FILE__) . '/widget/footer.php')?>