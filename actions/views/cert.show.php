<?php require(dirname(__FILE__) . '/widget/header.php');
 $jb = $info['jibie'];
?>
<div class="pub_list_bg">
  <div class="w_1200 pub_title clr">
    <h2 class="fl"> 当前位置：<?=$this->getPath()?> </h2>
  </div>
</div>
<div class="pub_arc_des clr w_1200">
  <div class="des_box clr">
    <div class="arc_des_img fl"> <img src="<?=imageUrl($info['img'])?>" alt="<?=$info['title']?>" width="307" height="494"> </div>
    <div class="arc_des_txt fr">
      <h1><?=$info['title']?><span>(<?=$jibie[$jb]?>)</span></h1>
      <ul class="clr">
        <li class="fl"><span class="bg">从属年限</span><span class="bg2"><?=str_replace('年', '',$info['year'])?>年</span></li>
        <li class="fl"><span class="bg">擅长风格</span><span class="bg2"><?=$info['sc']?></span></li>
        <li class="fl"><span class="bg">个人简介</span><span class="bg2">
        <?=$info['content']?>
        </span></li>
      </ul>
    </div>
  </div>
  <div class="arc_mydes"><?=$info['title']?>的所有课程</div>
  <div class="kechengList">
      <ul>
      <?=$this->widget('product', array('certId'=> $info['id'], 'tpl'=>'cover'))?>
    </ul>
  </div>
</div>

<?php require(dirname(__FILE__) . '/widget/footer.php')?>