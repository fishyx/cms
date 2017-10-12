 <?php
  $child1 = $cats_db->getList(10,'');;
foreach($child1 as $k => $info){
?> 
        <li>
          <div class="bg-list-img" style="background:url(<?=$info['img']?>) no-repeat center center; background-size:100% 100%;"></div>
          <div class="bg-list-tit">
            <h4><?=$info['name']?></h4>
          </div>
          <a class="bg-list-more" href="<?=$this->url('product.list', array('cid'=> $info['id']))?>">view all</a>
          <div class="bg-list-yew">
            <h5><?=$info['name']?></h5>
            <small>0<?=$k+1?></small> </div>
          <div class="bg-list-hs">
            <div class="bg-list-sub-hs">
              <p><?=$info['intro']?></p>
            </div>
          </div>
        </li>
        <?php
}
         ?>