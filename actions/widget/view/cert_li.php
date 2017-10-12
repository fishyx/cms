<?php foreach($list as $info){ ?>
           <div class="media-small-imgtxt box-s wow Inbottom animated"> <a href="" class="img" style="width: 160px; height: 160px;"> <img src="<?=imageUrl($info['img'])?>" alt=""> </a>
        <div class="media-txt">
          <p class="media-explain" title="<?=$info['title']?>"> <span>讲师名字</span><?=$info['title']?><br>
          <ul>
            <li>讲师级别：<?=$jibie[$info['jibie']]?></li>
            <li>职位：<?=$info['zhiwei']?></li>
            <li>专注年限：<?=str_replace('年', '',$info['year'])?>年</li>
            <li>擅长领域：<?=$info['sc']?></li>
          </ul>
          </p>
        </div>
      </div>
<?php
}
 ?>
 
 
    
