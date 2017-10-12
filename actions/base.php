<?php

require_once(SI_INC . 'action.php');
Action::setActionDir(dirname(__FILE__) . '/');

abstract class BaseAction extends Action {
    function __construct(){
        $this->viewDir = dirname(__FILE__) . '/views/';
        $this->widgetDir = dirname(__FILE__) . '/widget/';
        Widget::setWidgetDir($this->widgetDir);

        $path = $this->getPath();
        $path->addItem('首页', $this->url());

        $seo = $this->getSeo();
        $seo->setPattern("{title}{sp}{type}{sp}{siteName}", 'title');
        $seo->setVar('siteName', Cnfdb::get('siteName'));
    }

    protected $title;
    protected function postAction(){
        if(isset($this->title)){
            $this->getSeo()->setVar('title', $this->title);
            $this->getPath()->addItem($this->title);
        }
    }
}
