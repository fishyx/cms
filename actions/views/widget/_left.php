<div class="right">
    <div class="lsit_one_zk clr">
      <h2 class="fl">商学院公告</h2>
      <div class="box fl"> <span> <?=Cnfdb::get('gonggao')?></span> </div>
    </div>
    <div class="lsit_two_ph clr rel">
      <h2 class="fl">热搜新闻列表</h2>
      <em class="abs"> <img src="images/list_arc_t2.png"> </em>
      <div class="box fl">
        <ul class="clr">
            <?=$this->widget('news', array('tpl'=>'hot','rmd' => 'hit'))?>
        </ul>
      </div>
    </div>
    <div class="lsit_two_des clr rel">
      <h2 class="fl">人气导师</h2>
      <em class="abs"> <img src="images/list_arc_t2.png"> </em>
      <div class="box fl">
        <ul class="clr">
          <li> <a href="#" class="title"> <img src="images/j6.jpg" class="fl">
            <h3 class="fl"> 金牌导师：魏力 </h3>
            </a>
          </li>
          <li> <a href="#" class="title"> <img src="images/j6.jpg" class="fl">
            <h3 class="fl"> 金牌导师：魏力 </h3>
            </a>
          </li>
          <li> <a href="#" class="title"> <img src="images/j6.jpg" class="fl">
            <h3 class="fl"> 金牌导师：魏力 </h3>
            </a>
          </li>
          <li> <a href="#" class="title"> <img src="images/j6.jpg" class="fl">
            <h3 class="fl"> 金牌导师：魏力 </h3>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="lsit_two_des clr rel">
      <h2 class="fl">热门活动</h2>
      <em class="abs"> <img src="images/list_arc_t2.png"> </em>
      <div class="box fl"> <a href="/html/lbyhd/2017/0526/1560.html" class="ad"> <img src="http://www.ourun.cc/uploads/170526/3-1F5261604392A.jpg"> </a> </div>
    </div>
  </div>