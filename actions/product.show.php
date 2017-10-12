<?php

class ActionProductShow extends BaseAction{
    function execute() {
        /** @var goods $news */
        $news = getDTable('goods');
        /** @var GoodsCat $cat */
        $cat = getDTable('goodsCat');

        $id = intval($_REQUEST['id']);
        $type = intval($_REQUEST['type']);
        $news->increment('hits', $id);
        if($type){
            $this->TPL = 'product'.$type.'.show.php';
        }
        $this->info = $news->getRow($id);
        $this->catName = $cat->getName($this->info['cid']);
        $this->getPath()->addItem($this->catName, $this->url('product', 'cid=' . $this->info['cid']));
        $this->getSeo()->setVar('title', $this->info['title']);
    }
}
?>