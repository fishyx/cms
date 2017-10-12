<?php require(dirname(__FILE__) . '/widget/header.php');
$style = Goods::getStyle();
?>
<div class="pub_list_bg">
  <div class="w_1200 pub_title clr">
    <h2 class="fl"> 当前位置：</h2>
  </div>
</div>
<div class="arc_box w_1200 clr">
  <div class="cont_box_833 auto clr">
    <h1 class="clr"><span class="title"><?=$this->info['title']?></span><span class="fg">【共1节】</span> <span class="click"><em>182</em>人喜欢此节课</span> <span class="date">上传时间：<?=$this->info['cdate']?></span> </h1>
  </div>
  <div class="cont_box_xx clr">
    <dd>课程归属：九正商学院</dd>
    <dd>上课方式：<?=$style[$this->info['style']]?></dd>
    <dd>课程类型：<?=$catName?></dd>
    <dd>课程时间：<?=$this->info['sj']?></dd>
    <dd>课程主题：<?=$this->info['title']?></dd>
    <dd>设计师:
    <?php
        if($this->info['certId']){
            $certIds = explode(',', $this->info['certId']);
            $arr = getCertArr();
            foreach($certIds as $key){
               echo $str = "<a href=".$this->url('cert.show',$key).">" .$arr[$key] . '&nbsp;</a>';
            }
            //echo $str;
        }
      ?> </dd>
  </div>
  <div class="cont_box_844 auto clr">
      <?=$this->info['content']?>
  </div>
</div>



<?php require(dirname(__FILE__) . '/widget/footer.php')?>