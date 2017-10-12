<?php

class ActionCert extends BaseAction{
  
    function execute() {
        loadModule('certcat');
        $obj = getDTable('cert');      
        $pager = getPager();
        $pager->setNums(12);

        $catName = '';
        $cond = getCond();
        $cid = intval($_REQUEST['cid']);
        $type = intval($_REQUEST['type']);
        $jibie = intval($_REQUEST['jibie']);
        $jingyan = intval($_REQUEST['jingyan']);
        $zhuti = intval($_REQUEST['zhuti']);
        if($jingyan){
            $cond->eq('jingyan', $jingyan);
        }
        if($jibie){
            $cond->eq('jibie', $jibie);
        }
        if($jingyan){
            $cond->eq('jingyan', $jingyan);
        }
        if($zhuti){
            $cond->eq('zhuti', $zhuti);
        }
        $cat = getCertcat();
        if($cid){
            $obj->setCid($cid);
            $catName = $cat->getName($cid);
        }
         $cond->orderby("id", 'desc');
        $jiebie = Cert::getJiebie();
        $zhuti = Cert::getZhuti();
  
        $this->jiebie = $jiebie;
        $this->zhuti = $zhuti;
        $this->bumen = $bumen;
        $this->title = $catName;
        $this->list = $obj->getAll($pager, $cond);
        $this->cat = $cat;
        $this->pages = $pager->getHtml();
    }
}