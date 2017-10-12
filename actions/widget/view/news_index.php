
        <?php foreach($list as $item){ ?>
   <div class="home_news_item">
            <dl>
              <dt><?php echo cuttitle($item['cdate'],0,10) ?></dt>
              <dd class="t"><a href="<?php echo $this->url('news.show', $item['id']) ?>" target="_blank"><?php echo cuttitle($item['title'],0,20) ?></a></dd>
              <dd class="spec"><a href="<?php echo $this->url('news.show', $item['id']) ?>" target="_blank"><?php echo cuttitle($item['content'],0,50) ?></a></dd>
            </dl>
          </div>
    
    
    <?php
        }
     ?>
