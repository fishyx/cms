    <?php
        foreach($list as $v){
    ?>
    <li class="<? if($_REQUEST['id'] == $v['id']){echo 'current';}?> fl"><a href="<?=$this->url('about', array('id'=> $v['id']))?>"><?=$v['title']?></a></li>
    <?php
        }
     ?>