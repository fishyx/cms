<?php

printErr($msg); 
asmenu(array(
    'index' => array('菜单管理', aurl($Ctrl, 'index')),
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
    $form = initedForm($header, $info, $err);
    $form->form(aurl($Ctrl, $nextAc));
        
    // set fields
    $form->hidden('id');
    $form->text('username', 20);
    $form->text('password', 20);
    
    $form->text('usergroup',10);
    $purviews = include(SI_CFG . 'purviews.php');
    $form->checkboxs('dispurviews', $purviews);   
    // end
    
    $form->submit('确定');
    $form->reset();
    $form->dispaly();
    
}else{

    $header = array(
        'id'=>'ID', 
        'name'=>'name', 
        'action' => 'action',
        'inx' => '排序',
       // 'enable' => '是否有效',
    );

    $editor = initedEditor($header, $list, $Ctrl);
    
    // set fields
    $editor->setText('name');
    $editor->setText('action');
    $editor->setText('inx');
    //$editor->setCheckbox('enable',1);
    $editor->setDelPattern('');
    $editor->setEditPattern('');
    // end
    
    $editor->display();
    printPageInfo();

}

?>
