<?php

class ActionCertShow extends BaseAction{
    function execute() {
        /** @var goods $news */
        $news = getDTable('cert');
        /** @var GoodsCat $cat */
        $cat = getDTable('certCat');

        $id = intval($_REQUEST['id']);
        $news->increment('hits', $id);
        $jiebie = Cert::getJiebie();
        $bumen = Cert::getBumen();
        $this->jibie = $jiebie;
        $this->jingyan = $jingyan;
        $this->bumen = $bumen;
        $this->info = $news->getRow($id);
        $this->catName = $cat->getName($this->info['cid']) ? $cat->getName($this->info['cid']) : '导师团队';
        $this->getPath()->addItem($this->catName, $this->url('cert', 'cid=' . $this->info['cid']));
        $this->getSeo()->setVar('title', $this->info['title']);
    }
}