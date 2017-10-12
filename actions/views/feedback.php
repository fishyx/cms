<?php require(dirname(__FILE__) . '/widget/header.php')?>
<!--内页导航-->
<div class="intros">
  <div class="sub-intros">
    <div class="intros-tit"><span>申请加盟</span></div>
    <div class="rit-mbx rit-mbx2"> <span>当前位置：</span><a href="#">首页</a> <font> &gt; </font> <small>新闻中心</small> </div>
  </div>
</div>
<!--内页内容-->
<div class="conter" style="margin-top:0px;">
  <h3 class="newlists">在线留言</h3>
  <div class="join_apply">
    <h1>巴洛克瓷砖倾听您的意见或者<span style="color: red;">加盟</span></h1>
<form action="<?=$this->url('feedback')?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="q" value="feedback-insert" />
                    <table cellspacing="0" cellpadding="4" class="form" style="border-collapse:collapse;" width="100%">
                        <tr>
                            <th align="right" class="mes_title"><span style="color: red;">*</span><?php echo _T("联系人:")?></th>
                            <td ><input type="text" name="linkman" size="40" value="<?=$_POST['linkman']?>" /></td>
                        </tr>
                        <tr>
                            <th align="right" class="mes_title"><span style="color: red;">*</span><?php echo _T("电 话:")?></th>
                            <td ><input type="text" name="phone" size="40" value="<?=$_POST['phone']?>" /></td>
                        </tr>
                        <tr>
                            <th align="right" class="mes_title">email:</th>
                            <td ><input type="text" name="email" size="40" value="<?=$_POST['email']?>" /></td>
                        </tr>
                        <tr>
                            <th align="right" class="mes_title">QQ:</th>
                            <td ><input type="text" name="qq" size="40" value="<?=$_POST['qq']?>" /></td>
                        </tr>
                        <tr>
                            <th align="right" class="mes_title"><span style="color: red;">*</span><?php echo _T("留言内容:")?></th>
                            <td ><textarea name="content" rows="8" cols="55" ><?=$_POST['content']?></textarea></td>
                        </tr>
                        <tr>
                            <th align="right" class="mes_title"><?php echo _T("验证码:")?></th>
                            <td><input name="vcode" type="text" size="8" msg="<?php echo _T("验证码请输入右边4位字符!")?>">
                                <img src="imgcode.php" style="cursor:hand" id="_fbk_imgvcode"
                                     alt="<?php echo _T("更换验证码")?>" onclick="this.src='imgcode.php?' + Math.random()" />
                                <span style="color:#666">(<?php echo _T("如果看不清，点击更换")?>)</span>
                            </td>
                        </tr>
                        <tr class="submitRow">
                            <th>&nbsp;</th>
                            <td><input type="submit" value=" <?php echo _T("发送留言")?> " submit/>
                                <input type="reset" value=" <?php echo _T("重新输入")?> " />
                            </td>
                        </tr>
                    </table>
                </form>
  </div>
</div>





<?php require(dirname(__FILE__) . '/widget/footer.php')?>



