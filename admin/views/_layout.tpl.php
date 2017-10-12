<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>欢迎光临<?php echo Configs::get('siteName'); ?> 管理中心</title>
<meta http-equiv="Cache-Control" content="private">  
<link href="<?php echo $_CSS; ?>css.css" rel="stylesheet" type="text/css">
<link href="<?php echo $_CSS; ?>new.css" rel="stylesheet" type="text/css">
<link href="js/dateInput/css/css.css" rel="stylesheet" type="text/css">
<?php insertJs('dateInput') ?>
<?php insertJs('majax') ?>
<?php insertJs('ckfinder') ?>
<?php insertJs('jquery') ?>
</head>

<body>
<div id="top">
	<div class="left fl"><h1><a href="?"><?php echo Configs::get('siteName'); ?></a></h1></div>
	<div class="right fr"><h2><?php echo date('Y年m月d日'); ?> 星期<?php echo date('w'); ?></h2></div>
	<div class="cls"></div>
</div>

<div id="vbox">
	欢迎管理员<font  color="red"><?=$_SESSION['__asiteUser__']['username']?></font>登录管理中心 ! 上次登录时间:<?=$_SESSION['__asiteUser__']['lastLoginAt']?>&nbsp;&nbsp;登录IP：<?=$_SESSION['__asiteUser__']['lastLoginIp']?>
    <?php if(Configs::get('lang_en')){ ?>
    <a href="<?=BASE_URL?>admin.php?lang=cn" <?php if(LANG == 'cn'){echo 'class="red"';}?>>中文版</a> | 
    <a href="<?=BASE_URL?>admin.php?lang=en" <?php if(LANG == 'en'){echo 'class="red"';}?>>英文版</a>
    <?php } ?>

	<span class="button">
		<a href="?q=logout"><img src="<?= $_IMG ?>/logout.gif" /></a>
	</span>
</div>

<div id="area">
    <div class="left fl">
        <div class="text">
        <ul>
            <?php 
            foreach($Navs as $ac => $menu){ ?>
            <li><a href="<?= aurl($ac) ?>"><?= $menu ?></a></li>
            <?php } ?>
            <li><a href="<?= aurl('logout') ?>">退出管理</a></li>
        </ul>
        </div>
    </div>
    
    <div class="right">
        <div class="marMainFrame" style="padding:4px 12px; ">
            <?php
            if(file_exists($cnt_page)){
                include($cnt_page);
            }
            ?>
        </div>
    </div>
	<div class="cls"></div>
</div>

<div id="footer">
	<div class="left fl">版权所有(C)<?php echo date('Y');?>  --  <?php echo Configs::get('siteName'); ?></div>
	<div class="right fr">Design by modaodesign</div>
	<div class="cls"></div>
</div>
</body>
</html>
