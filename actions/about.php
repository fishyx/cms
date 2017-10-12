<?php

class ActionAbout extends BaseAction{
    function execute() {

        /** @var About $about */
        $about = getDTable('theme');
        loadModule('themecat');
        $aboutcat = new Themecat();
        $id = intval($_GET['id']);

        if(!$id){
            $id = 1;
        }

        $this->abouts = $about->getList(10, ' ORDER BY inx asc, id desc');
        $this->info = $about->getRow($id);
        if(!$this->info){
            throw new Exception("Info not found");
            
        }
        $this->title = $this->info['title'];

        $catName = $aboutcat->getName($this->info['cid']);
        $this->catName = $catName;
        //$this->getSeo()->setVar('title', 2222);
        $this->getPath()->addItem($this->info['title']);
    }
}