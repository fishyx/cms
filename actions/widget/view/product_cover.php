   <?php
        foreach($list as $info){
    ?>
    <li><a href="<?=$this->url('product.show', $info['id'])?>"><div class="img"><img src="<?=imageUrl($info['img'])?>"></div><span><?=$info['title']?></span></a></li>
    
    <?php
         }
     ?>