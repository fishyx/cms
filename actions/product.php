<?php

class ActionProduct extends BaseAction{
    protected $typeName = '';
    protected $searchkey = '';
    function execute() {
        $this->typeName = '课程中心';
        loadModule('goodscat');
        
        /** @var Goods $obj */
        $obj = getDTable('goods');
        //热销产品
        $cond_hots = getCond();
        $cond_hots->eq('rmd', 1);
        $this->list_hots = $obj->getList(8, $cond_hots);       
        $pager = getPager();
        $pager->setNums(12);

        $catName = '';
        $cond = getCond();
        $cid = intval($_REQUEST['cid']);
        $style = intval($_REQUEST['style']);
        $size = intval($_REQUEST['size']);
        $jieduan = intval($_REQUEST['jieduan']);
        $type = intval($_REQUEST['style']);
        $cat = getGcat();
        if($cid){
            $obj->setCid($cid);
            $catName = $cat->getName($cid);
        }
        if($type){
            $cond->eq('style', $type);
        }
         $cond->orderby("id", 'desc');
        if(isset($_REQUEST['searchkey'])){
            $sk = format($_REQUEST['searchkey']);
            $cond = " AND `title` LIKE '%{$sk}%'";
            $this->typeName = "'{$sk}'";
            if($this->typeName){
                $this->title = '搜索' . $this->typeName;
                $this->searchkey = $this->title;
            }else{
                $this->title = $catName;
            }
         }

        $this->list = $obj->getAll($pager, $cond);
        $this->cat = $cat;
        $this->pages = $pager->getHtml();
        if(isset($this->typeName))
        $this->getSeo()->setVar('type', $this->typeName);
        $this->getPath()->addItem($this->typeName, $this->url('product', array('cid'=>1)));
    }
}