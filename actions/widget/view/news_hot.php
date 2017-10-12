         <?php
              foreach($list as $key=>$info){
          ?>
          <li> <b class="top-num<?= $key>3 ? 2 : '';?> fl"><?=$key+1?></b> <a href="<?=$this->url('news.show', $info['id'])?>" class="title"><?=cuttitle($info['title'], 0,16)?></a> </li>
<?php
     }
 ?>