<?php
  $child1 = $casescat->getChild(0);
?> 
<div class="link fr">
    <div class="swiper-container">
        <div class="swiper-wrapper">
          <?php
           foreach($child1 as $v1){
           ?>
            <div class="swiper-slide"><a href="<?=$this->url('product.list', array('cid'=> $v1))?>"><?=$casescat->getName($v1)?></a></div>\
            <?php
            }
            ?>
        </div>
    </div>
</div>

