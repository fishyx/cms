<?php

printErr($msg); 
asmenu(array(
    'index' => array('会员管理', aurl($Ctrl, 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '会员', aurl('user', 'add')),
    ),
    'index'
);

?>



<?php

$shows = array('1' => '前台显示', '0' => '前台隐藏');

if($Action == 'edit' || $Action == 'add'){  
    $header = array(
        'id'=>'ID', 
        'user'=>'用户名(可为手机号码)',
        'pass'=>'密码', 
        'email' => '邮箱',
        'phone' => '电话',
        'qq'=>'qq',
        'area'=>'所属专卖店',
        'money'=>'消费积分',
        'vip_num'=>'会员卡号',
        'truename'=>'客户姓名',
        'address'=>'地址',

    );
    /**
    * put your comment there...
    * 
    * @var HtmlForm
    */
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
    
    // set fields
    $form->hidden('id');
    $form->text('user');
    $form->text('pass');
    $form->text('vip_num');
    $form->text('truename');
    $form->text('address');
    $form->text('email');

    $form->text('phone');
    $form->text('qq');
    $form->text('money');
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{
    ?>
    <form action='<?=aurl('user', 'index')?>' method="post" name="form1" >
    类别：
    <?php 
    echo '关键字:';
    echo HtmlElement::select('f', 
        array('area'=>'地区'), 
        iif($_REQUEST['f'], 'area')
        );
        
    ?>
    <input type="submit"  value=" 搜索 " /> 
</form>
    <?
$type = array(0=>'待审核',1 => '普通会员',2 => 'VIP会员');
    $header = array(
        'id'=>'ID', 
        'user'=>'用户名',
        'pass'=>'密码', 
        //'area'=>'所属专卖店', 
        'phone'=>'电话', 
        'type'=>'会员类型',
        'cdate'=>'创建时间'
    );
    
    $editor = initedEditor($header, $list, $Ctrl);
    
    // set fields
    $editor->setText('pass');
    $editor->setText('email');
    $editor->setText('phone');
    // end
    $editor->setSelect('type', $type);
    $editor->display();
    printPageInfo();

}

?>
