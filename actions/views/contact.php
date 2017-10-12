<?php require(dirname(__FILE__) . '/widget/header.php')?>
<div class="pub_list_bg">
  <div class="w_1200 pub_title clr">
    <h2 class="fl"> 当前位置：<a href="#">主页</a> &gt; <a href="#">联系我们</a> &gt; </h2>
  </div>
</div>
<div class="g-text">
  <div class="left">
    <div class="text  f-fl">
      <div class="tt">九正科技实业有限公司</div>
      <div class="address">四川省成都市金牛区沙湾东二路1号世纪加州A座28楼</div>
      <div class="info"> <span>400-6464-001</span> <span><?=Cnfdb::get('phone')?></span> <span><?=Cnfdb::get('tel')?></span> <span><?=Cnfdb::get('email')?></span> </div>
    </div>
  </div>
  <div class="right">
    <div class="text">
      <div class="tt">7x24小时全天候为您服务</div>
      <div class="tel">400-6464-001</div>
      <div class="info"> <span> <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?=Cnfdb::get('qq')?>&amp;site=&amp;menu=yes" target="_blank" title="给我留言"> 123456789(客服1) </a> </span> 
       <span> <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1123456789&amp;site=&amp;menu=yes" target="_blank" title="给我留言"> <?=Cnfdb::get('qq')?>(客服1) </a> </span> 
        <span> <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1123456789&amp;site=&amp;menu=yes" target="_blank" title="给我留言"> 123456789(客服1) </a> </span> 
         <span> <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1123456789&amp;site=&amp;menu=yes" target="_blank" title="给我留言"> 123456789(客服1) </a> </span>  </div>
    </div>
    <div class="g-img">
      <div class="code"> <img src="/templates/ratuo2017/images/ratuo_code.jpg" height="140" width="140" alt="">
        <p>微信客服</p>
      </div>
    </div>
  </div>
</div>
<?php require(dirname(__FILE__) . '/widget/footer.php')?>