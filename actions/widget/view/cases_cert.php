       <?php
       foreach($list as $item){ ?>
           <li><a href="<?php echo $this->url('cases.show', "id={$item['id']}") ?>" title="<?=$item['title']?>" target='_blank' > <span class='imga'><img src="<?=$item['img']?>" alt=<?=$item['title']?> ></span>
                   <h2><?=$item['title']?></h2>
                   <p>户型：<?= $style[$item['attr_hx']]?></p>
                   <p>面积：<?= $size[$item['attr_area']]?></p>
                   <p>风格：<?=$modelCat->getName($item['cid'])?></p>
               </a> </li>
           <?php } ?>
