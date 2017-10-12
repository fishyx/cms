<?php

class WidgetNews extends Widget{
    /**
     * @var News
     */
    protected $model;

    /** @var NewsCat  */
    protected $modelCat;

    function __construct(){
        $this->model = getDTable('news');
        $this->modelCat = getDTable('newsCat');
    }

    public function slider($params = array()){
        $data = $this->execute($params);
        $images = array();
        $links = array();
        foreach($data['list'] as $item){
            if(!$item['img']){
                continue;
            }
            $images[] = $item['img'];
            $links[] = urlencode($this->caller->url('news.show', "id=" . $item['id']));
        }

        $data['images'] =  $images;
        $data['links'] =  $links;
        return $data;
    }

    /**
     * @param array $params
     * @return array()
     */
    function execute($params = array()){
        $cond = getCond();

        $catName = '新闻动态';
        $cid = gav($params, 'cid');
        if($cid){
            $cid = $this->modelCat->fixId($cid);
            $catName = $this->modelCat->getName($cid);
            $this->model->setCid($cid);
        }

        $nums = gav($params, 'nums', 10);

        if(gav($params, 'rmd')== 'hit'){
            $cond->orderby('hits', 'desc');
        }elseif(gav($params, 'rmd')){
            $cond->eq('rmd', gav($params, 'rmd'));
        }
        $list =  $this->model->getList($nums, $cond);
        return array(
            'catName' => $catName,
            'list' => $list
        );
    }
}