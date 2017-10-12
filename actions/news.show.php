<?php

class ActionNewsShow extends BaseAction{
    function execute() {
        /** @var News $news */
        $news = getDTable('news');
        /** @var NewsCat $cat */
        $cat = getDTable('NewsCat');
        $this->cat = $cat;
        $id = intval($_REQUEST['id']);
        $news->increment('hits', $id);

        $this->info = $news->getRow($id);
        $this->cat = $cat;
        $info_id = $this->info['id'];
        $cid = $this->info['cid'];
        $this->next = $news->getRow(' And id > ' .$info_id.' And cid = '.$cid.' order by id asc limit 1');
        $this->upAc = $news->getRow('And id < ' .$info_id.' And cid = '.$cid.' order by id desc limit 1');
        $this->max = $news->getFiled('max(id)','And cid = '.$cid);
        $this->min = $news->getFiled('min(id)','And cid = '.$cid);
        $this->catName = $cat->getName($this->info['cid']);
        $this->getPath()->addItem($this->catName, $this->url('news', 'cid=' . $this->info['cid']));
        $this->getPath()->addItem($this->info['title']);
        $this->getSeo()->setVar('type', $this->catName);
        $this->getSeo()->setVar('title', $this->info['title']);
    }
}