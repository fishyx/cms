<div class="ttl"><a href="<?=BASE_URL?>">首页</a> > <a href="<?=url('news.list',array('cid'=>$cid ? $cid : 1))?>">新闻中心</a> > <span><?=$cats->getName($info['cid'])?></span></div>
<div class="cnt news_list">
    <h1><?= $info['title'] ?></h1>
    <p class="news_time">作者:<?= $info['author'] ?>&nbsp;&nbsp;时间：<?= cuttitle($info['cdate'],0,10) ?>&nbsp;&nbsp;出处：<?= $info['comefrom'] ?></p>
        <div align="center"><?
    $arr=getimagesize($info['img']);
    if($arr[0]>400){
        $width = 400;
    }else{
        $width = $arr[0];
    }
?>
     </div>
     <div class="main_cnt">
     <!--内容分页开始-->
    <?
    $content =explode('[page]',$info['content']);
    $total = count($content);
    $p = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
    $pagehtml = '';
    if($total>0){
        for($i=1;$i<=$total;$i++){
            if($p == $i){
                $pagehtml .=  "<span><strong><a href = ".url("news.detail",$info['id']).'?p='.$i.">{$i}</a></strong></span>";
            }else{
                $pagehtml .=  "<a href = ".url("news.detail",$info['id']).'?p='.$i.">{$i}</a>";
            }

        }
    }
    $content = $content[$p-1];
    echo $content;
    if($total > 1){
        echo " <div class='page'> 
        {$pagehtml}
        </div>
        ";
    }
    ?>
    </div>
<!--内容分页结束-->
    <!--<p style="padding-top:30px;">关键词：正文内容关键词</p>-->
    <div class="list_baidu">
<!-- Baidu Button BEGIN -->
    <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
        <a class="bds_qzone">QQ空间</a>
        <a class="bds_tsina">新浪微博</a>
        <a class="bds_tqq">腾讯微博</a>
        <a class="bds_renren">人人网</a>
        <span class="bds_more">更多</span>
        <a class="shareCount"></a>
    </div>
<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=704339" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
    document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
</script>
<!-- Baidu Button END -->
    </div>
    <div class="clearfix"></div>
    <p class="news_t1">   
<?php
if($upAc['id']){
?>
    上一条：<a href="<?=url('news.detail',$upAc['id'])?>"><?=$upAc['title']?></a><br />  
<?php
    }
if($next['id']){    
?>    
  下一条：  <a href="<?=url('news.detail',$next['id'])?>"><?=$next['title']?></a></span>
<?php
    }
?>    
    </p>
    <p style="padding-top:10px;">
文章标题：<a href="<?="http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]?>"target="_blank"><?= $info['title'] ?></a><br />
    文章地址：<a href="<?="http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]?>"target="_blank"><?="http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"]?></a><br />
    版权所有：转载时必须以连接形式注明作者和原始出处并保留本申明！
    </p>
    <div class="list_ttl"><h4>九正推荐阅读</h4><h5><a href="<?=url('news.list',array('cid'=>$cid ? $cid : 1))?>">查看更多...</a></h5></div>
    <div class="list_cnt">
        <ul>
<?php
$r_list = $mod->getList(5,'And rmd = 1'); 
if(is_array($r_list)){
    foreach($r_list as $_r_info){
        

?>        
            <li><span class="fl"><a href="<?=url('news.detail',$_r_info['id'])?>">・<?=cuttitle($_r_info['title'],0,30)?></a></span><span class="fr"><?=cuttitle($_r_info['cdate'],0,10)?></span></li>
<?php
        }
}
?>            
        </ul>
    </div>
</div>