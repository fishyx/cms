<?php

class ActionFeedback extends BaseAction{
    protected $typeName = '';
    protected $title = '在线留言';
    function execute() {
        $feedback = getDTable('feedback');
        if($_REQUEST['q'] == 'feedback-insert'){
            if(!$feedback->add($_POST)){
            echo "<script>alert('请按要求填写，谢谢');history.go(-1);</script>";
            exit;
            }else{
                echo "<script>alert('留言已经发送成功,谢谢您的留言');history.go(-1);</script>";
                exit;
            }
        }else{
            echo "<script>alert('非法请求');history.go(-1);</script>";
            exit;
        }
    }
}