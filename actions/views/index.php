<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title><?php echo $this->getSeo()->getTitle() ?></title>
<meta name="keywords" content="<?= Cnfdb::get('keywords') ?>"/>
<meta name="description" content="<?= Cnfdb::get('description') ?>"/>
<link rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.fullPage.min.js"></script>
<script type="text/javascript" src="js/web.js"></script>
<script type="text/javascript">
$(function(){
    if($.browser.msie && $.browser.version < 10){
        $('body').addClass('ltie10');
    }
    $.fn.fullpage({
        slidesColor: ['#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff'],
        anchors: ['page1', 'page2', 'page3', 'page4', 'page5', 'page6', 'page7'],
        menu: '#menu'
    });
});
</script>
<script type="text/javascript">
$(function(){    
    $('.tabPanel ul li').click(function(){
        $(this).addClass('hit').siblings().removeClass('hit');
        $('.panes>div:eq('+$(this).index()+')').show().siblings().hide();    
    })
})
</script>
</head>

<body>
<ul id="menu">
  <li data-menuanchor="page1" class="active"><a href="#page1" title="学院优势"><span>学院优势</span></a></li>
  <li data-menuanchor="page2"><a href="#page2" title="课程展示"><span>课程展示</span></a></li>
  <li data-menuanchor="page3"><a href="#page3" title="九正商学院"><span>九正商学院</span></a></li>
  <li data-menuanchor="page4"><a href="#page4" title="讲师团队"><span>讲师团队</span></a></li>
  <li data-menuanchor="page5"><a href="#page5" title="新闻资讯"><span>新闻资讯</span></a></li>
  <li data-menuanchor="page7"><a href="#page7" title="联系我们"><span>联系我们</span></a></li>
</ul>
<div class="header">
  <div class="k1400 clearfix">
    <h1 class="h_logo"><a href="#" class="logo"></a></h1>
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
<div class="section section1">
  <div class="bg bg1-3"></div>
  <div class="bg bg1-4"></div>
  <a class="godown" href="#page2"><span></span></a> </div>
<div class="section section2">
  <div class="bg2-3">
    <div class="tit">
      <h5>打造微营销快速盈利系统</h5>
      <span>16年快速互联网行业实操经验的积累，资源的沉淀，解开传统企业“互联网+”全套流程改造秘诀</span> </div>
  </div>
  <div class="bg2-4 bounceIn">
    <ul class="items list-inline" style="width: 1148px;">
      <li class="app"> <u class="cl"></u> <u class="cr"></u> <a href="#"> <i></i><strong>更前沿</strong>
        <p>课程内容与时俱进<br>
          每三个月一次更新升级</p>
        </a> </li>
      <li class="pc"> <u class="cl"></u><u class="cr"></u> <a href="#"> <i></i><strong>更专业</strong>
        <p>一对一手把手指导<br>
          16年实操经验</p>
        </a> </li>
      <li class="sys"> <u class="cl"></u><u class="cr"></u> <a href="#"> <i></i><strong>更落地</strong>
        <p>根据企业情况个性<br>
          定制方案</p>
        </a> </li>
      <li class="mobi"> <u class="cl"></u><u class="cr"></u> <a href="#"> <i></i><strong>更有效</strong>
        <p>线上线下组合<br>
          提高成交率</p>
        </a> </li>
    </ul>
  </div>
  <div class="bg2-5 bounceInRight"> 
  <?php
       $this->widget('product', array('rmd' => 1))
   ?>
   
   </div>
  <a class="godown" href="#page3"><span></span></a> </div>
<div class="section section3">
  <div class="bg2-3">
    <div class="tit">
      <h5>九正商学院</h5>
      <span>16年来我们只专心做一件事，那就是如何让传统企业通过互联网赚钱</span> </div>
  </div>
  <div class="bg3-1">
    <div class="lefts lightSpeedIn2">
      <p class="p1"></p>
      <p class="p2"></p>
      <p class="p3"></p>
    </div>
    <div class="rights lightSpeedIn"></div>
  </div>
  <a class="godown" href="#page4"><span></span></a> </div>
<div class="section section4">
  <div class="bg2-3">
    <div class="tit">
      <h5>讲师团队</h5>
      <span>具有丰富实操经验的讲师团队</span> </div>
  </div>
  <div class="jstd-wrap block-bg-gray jzBg2">
    <div class="container clearfix fadeInUp">
    <?=$this->widget('cert', array('rmd'=>2, 'tpl'=>'index'))?>
    <?=$this->widget('cert', array('rmd'=>1, 'tpl'=>'li'))?>

    </div>
  </div>
  <a class="godown" href="#page5"><span></span></a> </div>
<div class="section section5">
  <div class="bg2-3">
    <div class="tit">
      <h5>新闻资讯</h5>
      <span>汇聚行业动态 了解互联网百科</span> </div>
  </div>
  <div class="tabPanel">
    <ul class="bounceIn">
      <li class="hit">学院动态</li>
      <li>干货分享</li>
    </ul>
    <div class="panes">
      <div class="pane" style="display:block;">
        <div class="home_news_list_inner zoomInDown">
            <?php
                 $this->widget('news', array('cid'=>1, 'tpl'=>'index'));
             ?>
        </div>
      </div>
      <div class="pane">
        <div class="home_news_list_inner zoomInDown">
            <?php
                 $this->widget('news', array('cid'=>2, 'tpl'=>'index'));
             ?>
        </div>
      </div>
    </div>
  </div>
  <a class="godown" href="#page6"><span></span></a> </div>

<div class="section section7">
  <div class="bg2-3">
    <div class="tit">
      <h5>联系我们</h5>
      <span>加入我们把团队变成军队</span> </div>
  </div>
  <div class="above fadeInUp">
    <div class="wechat"><img src="images/er.jpg" alt="扫描关注微信公众账号" class="img-responsive"></div>
    <div class="left"><a class="tel" href="tel:028-8888-8888" title="咨询热线">028-8888-8888</a>
      <p>联系电话： <?=Cnfdb::get('tel')?><br>
        传　　真： <?=Cnfdb::get('fax')?><br>
        邮政编号： 610000<br>
        公司地址： <?=Cnfdb::get('address')?></p>
    </div>
    <div class="right"><br>
      电子邮箱： <?=Cnfdb::get('email')?><br>
      Copyright 2010-2016 九正商学院 版权所有<br>
      All Rights Reserved   蜀ICP备：<?=Cnfdb::get('beian')?> </div>
  </div>
</div>
</body>
</html>