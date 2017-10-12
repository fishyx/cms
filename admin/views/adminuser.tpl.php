<?php

printErr($msg); 
asmenu(array(
    'index' => array('用户管理', aurl($Ctrl, 'index')),
    'add' => array(($Action != 'edit' ? '增加' : '编辑') . '用户', aurl($Ctrl, 'add')),
    ),
    $Action
);

if($Action == 'edit' || $Action == 'add'){  
    
    $header = array(
        'id'=>'ID', 
        'username'=>'用户名', 
        'password' => '密码',
        'usergroup' => '用户组',
        'dispurviews' => '权限(勾上即不需要此权限)',
    );
    
    $info['dispurviews'] = explode(',',$info['dispurviews']);
    /**
    * put your comment there...
    * 
    * @var HtmlForm
    */
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));        
    // set fields
    $form->hidden('id');
    $form->text('username', 20);
    $form->text('password', 20);    
    $form->text('usergroup',10);
    $purviews = getRight();
    $purviews['siteright'] = "菜单";
    $form->checkboxs('dispurviews', $purviews);   
    // end    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();   
}else{
    $header = array(
        'id'=>'ID', 
        'username'=>'用户名', 
        'password' => '密码',
        'usergroup' => '用户组',
        //'dispurviews' => '权限',
    );
    $editor = initedEditor($header, $list, $Ctrl);    
    // set fields
    $editor->setText('username');
    $editor->setText('password');
    $editor->setText('usergroup');
    $editor->setCheckbox('right',2);
    // end
    
    $editor->display();
    printPageInfo();

}

?>
