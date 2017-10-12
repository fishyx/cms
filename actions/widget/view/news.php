<?php foreach($list as $item){ ?>
                <li><a href="<?php echo $this->url('news.show', "id={$item['id']}") ?>" class="font14"><?php echo $item['title'] ?></a></li>

<?php } ?>
