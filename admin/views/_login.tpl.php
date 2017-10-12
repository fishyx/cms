<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <title><?php echo Configs::get('siteName'); ?>后台管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk">

    <script>jc001.load("bootstrapvalidator");</script>
    
    <link href="admin/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="admin/css/login.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .form-control-feedback{right: 10px;}
        #imgCode{width: 92px; height: 35px;}
    </style>
</head>
<body>
<h1 style="font-size: 24px;">
    <?php echo Configs::get('siteName'); ?><sup>V2016</sup>
    <span style="margin-top: 15px; font-size: 34px; display: block;">后台管理系统</span>
</h1>

<div class="login" style="margin-top:50px;">

    <div class="header">
        <div class="switch" id="switch"><a class="switch_btn_focus" id="switch_qlogin" href="javascript:void(0);" tabindex="7">快速登录</a>
            <div class="switch_bottom" id="switch_bottom" style="position: absolute; width: 64px; left: 0px;"></div>
        </div>
    </div>

    <div class="web_qr_login" id="web_qr_login" style="display: block; padding-bottom: 20px;">
        <!--登录-->
        <div class="web_login" id="web_login">

            <div class="login-box">

                <div class="login_form">
                   <form method="post" action="admin.php">
                      <input type="hidden" name="q" value="login" />
                        <div class="form-group">

                            <label class="input-tips" for="name">帐号：</label>
                            <div class="col-lg-9">
                                <input type="text" id="name" name="username" value="<?= gav($_REQUEST, 'username') ?>" class="form-control" />
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <label class="input-tips" for="password">密码：</label>
                            <div class="col-lg-9">
<input type="password" id="password" name="pwd" class="form-control" value="<?= gav($_REQUEST, 'pwd') ?>"/>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <label class="input-tips" for="password">验证码：</label>
                            <div class="col-lg-9">
                                <img id="imgCode" align="absmiddle" style="cursor:pointer" src="imgcode.php"  />
<!--<a href="#none" id="chgImgCode">刷新</a>-->
                                <input type="text" name="vcode" size="4" maxlength="4" value="" style="display: inline-block; width:100px; float:right; padding-left: 5px; padding-right: 10px;;" class="form-control" onclick="this.src=<?=BASE_URL?>'imgcode.php?' + Math.random()" />
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div style="padding-left:68px;margin-top:20px;"><input type="submit" value="登 录" style="width:150px;" class="button_blue"/></div>
                    </form>
                </div>

            </div>

        </div>
        <!--登录end-->
    </div>

</div>
<div class="jianyi">*推荐使用ie8或以上版本ie浏览器或Chrome内核浏览器访问本站</div>

</body>
</html>