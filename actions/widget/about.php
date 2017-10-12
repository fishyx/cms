<?php

class WidgetAbout extends Widget{
    /**
     * @var About
     */
    protected $model;

    function __construct(){
        $this->model = getDTable('theme');
    }

    /**
     * @param array $params
     * @return array()
     */
    function execute($params = array()){
        $cond = getCond();

        $cid = gav($params, 'cid');
  /*      if($cid){
            $cid = $this->model->fixId($cid);
        }else{
            $cid = $this->model->getDefaultId();
        }*/
        $cond->eq('id', $cid);
        $about =  $this->model->getRow($cond);
        return array(
            'about' => $about,
            'len' => gav($params, 'len', 200),
        );
    }
}