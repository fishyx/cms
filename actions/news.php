<?php

class ActionNews extends BaseAction{
    protected $typeName = '';
    function execute() {
        /** @var News $news */
        $news = getDTable('news');
        /** @var Newscat $newsclass */
        $newsclass = getDTable('newscat');
        $this->cat = $newsclass;
        // list
        $cid = $newsclass->fixId($_REQUEST['cid']);
        $cid = intval($cid);
        if($cid){
            $news->setCid($cid);
            $this->typeName = $newsclass->getName($cid);
        }else{
            $this->typeName = '资讯中心';
        }

        $cond = '';
        if(isset($_REQUEST['searchkey'])){
            $sk = format($_REQUEST['searchkey']);
            $cond = " AND `title` LIKE '%{$sk}%'";
            $this->typeName = "'{$sk}'";
        }
        
        $cond .= ' order by  id desc';
        $pager = getPager();
        $pager->setNums(10);
        $this->list = $news->getAll($pager, $cond);
        $this->pages = $pager->getHtml();
        $this->getPath()->addItem($this->typeName, $this->url('news', array('cid'=>1)));
        if(isset($this->typeName))
        $this->getSeo()->setVar('type', $this->typeName);
        
        

    }
}