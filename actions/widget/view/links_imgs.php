<?php foreach($list as $item){ ?>
<div class="imga">
<img alt="<?php echo $item['name'] ?>" width="137" height="70" src="<?=imageUrl($item['img']) ?>" />
<p><?php echo $item['name'] ?></p>
</div>
<?php } ?>