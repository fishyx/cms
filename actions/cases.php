<?php

class ActionCases extends BaseAction{
  
    function execute() {
        loadModule('casescat');
        
        /** @var Goods $obj */
        $obj = getDTable('cases');      
        $pager = getPager();
        $pager->setNums(12);

        $catName = '';
        $cond = getCond();
        $cid = intval($_REQUEST['cid']);
        $cat = getCasecat();
        if($cid){
            $obj->setCid($cid);
            $catName = $cat->getName($cid);
        }
         $cond->orderby("id", 'desc');
        

        $this->title = $catName;
        $this->list = $obj->getAll($pager, $cond);
        $this->cat = $cat;
        $this->pages = $pager->getHtml();
    }
}