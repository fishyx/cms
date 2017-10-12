       <?php foreach($list as $item){ ?>
          <li>
            <div class='kpt1'>
              <div class="img"><img src="<?=imageMini($item['img'])?>" title="<?=$item['title']?>" alt="案例名称" /></div>
              <div class="xian"> <a href="<?php echo $this->url('cases.show', "id={$item['id']}") ?>" title="<?=$item['title']?>"><?=$item['title']?></a> </div>
            </div>
          </li>
           <?php } ?>
