<?php

class WidgetAds extends Widget{
    /**
     * @var Ads
     */
    protected $model;

    function __construct(){
        $this->model = getDTable('ads');
    }

    public function slider($params = array()){
        $cond = getCond();
        $pos = gav($params, 'rmds');
        if($pos){
            $cond->eq('rmds', $pos);
        }

        $data = $this->model->getList(10, $cond);

        $images = array();
        $links = array();
        foreach($data as $item){
            if(!$item['src']){
                continue;
            }
            $images[] = $item['src'];
            $links[] = $item['url'] ? $item['url'] : "#none";
        }

        $data['width'] =  gav($params, 'width', 1000);
        $data['height'] =  gav($params, 'height', 300);
        $data['images'] =  $images;
        $data['links'] =  $links;
        return $data;
    }

    /**
     * @param array $params
     * @return array()
     */
    function execute($params = array())  {
        $cond = getCond();
        $pos = gav($params, 'pos');
        if($pos){
            $cond->eq('name', $pos);
        }

        $data = $this->model->getRow($cond);
        $data['width'] =  gav($params, 'width', 1000);
        $data['height'] =  gav($params, 'height', 200);
        return $data;
    }
}