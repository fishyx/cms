<?php

class ActionDpage extends BaseAction{
    function execute() {

        /** @var About $about */
        $id = intval($_GET['id']);
        if(!$id){
            $id = 1;
        }
        switch ($id) {
           case 1:
                $this->title = '关于九正商学院';
                $this->TPL = 'guanyuwomen.php';
             break;    
           case 2:
                $this->title = '联系我们';
                $this->TPL = 'contact.php';                              
        }
        
    }

}