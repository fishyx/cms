<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title><?php echo $this->getSeo()->getTitle() ?></title>
<meta name="keywords" content="<?= Cnfdb::get('keywords') ?>"/>
<meta name="description" content="<?= Cnfdb::get('description') ?>"/>
<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/web.js"></script>
</head>

<body>
<div class="header">
  <div class="k1400 clearfix">
    <h1 class="h_logo"><a href="<?=BASE_URL?>" class="logo"></a></h1>
    <div class="nav">
      <ul>
      <?php
           $ctrl = gav($_REQUEST, 'c');
       ?>
        <li class="navLi <? if($ctrl == ''){echo 'active';}?>"><a href="<?=BASE_URL?>">网站首页</a></li>
        <li class="navLi <? if($ctrl == 'product'){echo 'active';}?>"> <a href="<?=$this->url('product.list')?>">课程中心</a>
          <ul class="sonNav">
            <li><a href="<?=$this->url('product.list', array('style'=>1))?>">线上课程</a></li>
            <li><a href="<?=$this->url('product.list', array('style'=>2))?>">线下课程</a></li>
            <li><a href="<?=$this->url('news.list', array('cid'=>2))?>">干货分享</a></li>
          </ul>
        </li>
        <li class="navLi <? if($ctrl == 'cert'){echo 'active';}?>"> <a href="<?=$this->url('cert.list')?>">导师团队</a></li>
        <li class="navLi <? if($ctrl == 'dpage' && $_REQUEST['id']== 1){echo 'active';}?>"> <a href="<?=$this->url('dpage', 1)?>">走进商学院</a>
          <ul class="sonNav">
            <li><a href="<?=$this->url('dpage', 1)?>">了解我们</a></li>
            <li><a href="<?=$this->url('news.list', array('cid'=>1))?>">学院动态</a></li>
          </ul>
        </li>
        <li class="navLi <? if($ctrl == 'dpage' && $_REQUEST['id']== 2){echo 'active';}?>"> <a href="<?=$this->url('dpage', 2)?>">联系我们</a></li>
      </ul>
    </div>
    <div class="headerRights"> <i></i><span><?=Cnfdb::get('tel')?></span> </div>
  </div>
</div>
<div class="sbanner"> <img src="images/banner1.jpg"> </div>