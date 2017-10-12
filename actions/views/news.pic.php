<?php require(dirname(__FILE__) . '/widget/header.php')?>

<div class="boxs" style=" background-color:#EBEBEB;">
	<div class="n_a_m_t">当前位置：<?php echo $this->getPath() ?></div>
	<div class="cls"></div>
	<div class="i_main">
		<div class="n_main_right_1"><?php echo $this->typeName ?>   <span style="font-size:15px; color:#990000">LOVE TRIP CLUB</span></div>
		<div class="main_body">
			<div class="show_pic">
			<ul>
                <?php foreach($list as $info){ ?>
                    <li>
                        <div>
                            <a href="<?=url('news.show', $info['id'])?>"><img
                                src="<?=$info['img']?>">
                            <div class="pic_title"><?=$info['title']?></div></a>
                        </div>
                    </li>
                <?php } ?>
			</ul>
			</div>
		</div>

        <div class="pages">
            <?php echo $pages; ?>
        </div>
	    <div class="cls"></div>
	</div>
</div>

<?php require(dirname(__FILE__) . '/widget/footer.php')?>
