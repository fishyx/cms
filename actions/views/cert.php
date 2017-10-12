<?php require(dirname(__FILE__) . '/widget/header.php')?>

<div class="pub_list_bg">
  <div class="w_1200 pub_title clr">
    <h2 class="fl"> 当前位置：<a href="#">主页</a> &gt; <a href="#">导师团队</a> &gt; </h2>
  </div>
</div>
<div class="case_menu_box w_1200">
  <div class="list_menu">
    <div class="prop-attrs">
      <div class="attr">
        <div class="a-key2 ">导师级别</div>
        <div class="a-values">
          <ul>
              <li><a  <? if(!gav($_REQUEST, 'jiebie')){echo 'class="bor"';}?> href="<?=url_search_set('jiebie', '')?>">不限</a></li>
              <?php
              $jibie = $this->jiebie;
              foreach($this->jiebie as $key => $info){
                $url = url_search_set('jiebie', $key)
                ?>
                <li><a <? if(gav($_REQUEST, 'jiebie') == $key){echo 'class="bor"';} ?> title="<?=$info?>" href="<?=$url?>"><?=$info?></a></li>
                <?php
              }
              ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="prop-attrs">
      <div class="attr">
        <div class="a-key5 ">专注领域</div>
        <div class="a-values">
          <ul>
              <li><a  <? if(!gav($_REQUEST, 'zt')){echo 'class="bor"';}?> href="<?=url_search_set('zt', '')?>">不限</a></li>
              <?php
              $zhuti = $this->zhuti;
              foreach($this->zhuti as $key => $info){
                $url = url_search_set('zt', $key)
                ?>
                <li><a <? if(gav($_REQUEST, 'zt') == $key){echo 'class="bor"';} ?> title="<?=$info?>" href="<?=$url?>"><?=$info?></a></li>
                <?php
              }
              ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<ul class="lsit_des w_1200 clr">
<?php
     foreach($list as $info){
 ?>
  <li class="rel"> <a target="_blank" href="<?=$this->url('cert.show', $info['id'])?>" title="<?=$info['title']?>"> <img alt="魏总管" src="<?=imageUrl($info['img'])?>"> </a>
    <div class="case_bg abs"></div>
    <div class="case_txt abs clr"> <span class="fl w_n"><b><?=$info['title']?></b></span> <span class="fr t_r"><?=cuttitle($info['zhiwei'], 0,5)?></span> <span class="fl"><?=str_replace('年', '',$info['year'])?>年</span> <span class="fr t_r">92人预约过</span> </div>
    <h2><a class="fl" href="<?=$this->url('cert.show', $info['id'])?>" target="_blank">查看详情</a><a class="fr fg" onclick="swt_hm(this)" href="javascript:void(0)" target="_self">预约导师</a></h2>
  </li>
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