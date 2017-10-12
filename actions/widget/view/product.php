
    <?php foreach($list as $item){ ?>
       <a href="<?php echo $this->url('product.show', $item['id']) ?>"><img src="<?=imageUrl($item['img'])?>"></a> 
   <?php } ?>
