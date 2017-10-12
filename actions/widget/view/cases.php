
<ul>
    <?php foreach($list as $item){ ?>

            <li><a href="<?php echo $this->url('cases.show', "id={$item['id']}") ?>"><img src="<?=imageMini($item['img'])?>"><?php echo mb_substr($item['title'], 0, 20, 'utf-8') ?></a></li>
    <?php } ?>
</ul>