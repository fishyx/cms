 <ul class="gop">
 <?php
      foreach($list as $v){
  ?>
    <li><a href="<?=$this->url('product.show', $v['id'])?>"><img src="<?php echo imageMini($v['img']) ?>" /></a><a href="<?=$this->url('product.show', $v['id'])?>"><?=cuttitle($v['title'], 0,10)?></a></li>
<?php
     }
 ?>    
 </ul>