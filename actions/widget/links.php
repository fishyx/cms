<?php

class WidgetLinks extends Widget{
    /**
     * @var Flink
     */
    protected $model;

    function __construct(){
        $this->model = getDTable('flink');
    }

    /**
     * @param array $params
     * @return array()
     */
    function execute($params = array())  {
        $cond = getCond();
        $pos = gav($params, 'cid');
        $type = gav($params, 'type');
        if($pos){
            $cond->eq('cid', $pos);
        }
        if($type){
            $cond->eq('type', $type);
        }
        $list = $this->model->getList(gav($params, 'nums', 20), $cond);
        return array(
            'list' => $list
        );
    }
}