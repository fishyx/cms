<?php require(dirname(__FILE__) . '/widget/header.php')?>
<div class="pub_list_bg">
  <div class="w_1200 pub_title clr">
    <h2 class="fl"> 当前位置：<?=$this->getPath()?> </h2>
  </div>
</div>
<div class="case_menu_box w_1200">
  <div class="list_menu">
    <div class="prop-attrs">
      <div class="attr">
        <div class="a-key2 ">上课方式</div>
        <div class="a-values">
          <ul>
                        <li><a  <? if(!gav($_REQUEST, 'style')){echo 'class="bor"';}?> href="<?=url_search_set('jieduan', '')?>">不限</a></li>
                        <?php
                        $style = Goods::getStyle();
                        foreach($style as $key => $info){
                            $url = url_search_set('style', $key)
                            ?>
                            <li><a <? if(gav($_REQUEST, 'style') == $key){echo 'class="bor"';} ?>  href="<?=$url?>"><?=$info?></a></li>
                            <?php
                        }
                        ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="prop-attrs">
      <div class="attr">
        <div class="a-key3 ">课程分类</div>
        <div class="a-values">
          <ul>
                        <li><a  <? if(!gav($_REQUEST, 'cid')){echo 'class="bor"';}?> href="<?=url_search_set('cid', '')?>">不限</a></li>
                        <?php
                        $cats = $cat->getChild(0);
                        foreach($cats as $v1){
                            $url = url_search_set('cid', $v1)
                            ?>
                            <li><a <? if(gav($_REQUEST, 'cid') == $v1){echo 'class="bor"';} ?> title="<?=$cat->getName($v1)?>" href="<?=$url?>"><?=$cat->getName($v1)?></a></li>
                            <?php
                        }
                        ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<ul class="lsit_case w_1200 clr">
<?php
     foreach($list as $info){
 ?>
  <li class="rel"><a target="_blank" href="<?=$this->url('product.show', $info['id'])?>" title=""> <img alt="" src="<?=imageUrl($info['img'])?>">
    <div class="case_bg abs"></div>
    <div class="case_txt abs">
      <p>课程导师：<?
        if($info['certId']){
            $certIds = explode(',', $info['certId']);
            $arr = getCertArr();
            foreach($certIds as $key){
               echo $str = $arr[$key] . '&nbsp;';
            }
            //echo $str;
        }
      ?></p>
      <p>课程类型：<?=$cat->getName($info['cid'])?></p>
      <p>课程时长：<?=$info['sj']?></p>
      <p>课程流量：<?=$info['hits']?></p>
    </div>
    <h2><span class="fl"><?=$info['title']?></span><span class="fr fg">(共1节)</span></h2>
    <h3><span class="fl">DATE:<?=cuttitle($info['cdate'], 0, 10)?></span><span class="fr fg">READ:<?=$info['hits'] + 54?></span></h3>
    </a> </li>
    <?php
         }
     ?>
</ul>
<div class="pages clr w_1200">
  <ul class="pagelist clr auto">
        <?=$pages?>    
  </ul>
</div>
<?php require(dirname(__FILE__) . '/widget/footer.php')?>




