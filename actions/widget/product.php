<?php

class WidgetProduct extends Widget{
    /**
     * @var Goods
     */
    protected $model;

    /** @var GoodsCat  */
    protected $modelCat;

    function __construct(){
        $this->model = getDTable('Goods');
        $this->modelCat = getDTable('GoodsCat');
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
            $links[] = urlencode($this->caller->url('product.show', "id=" . $item['id']));
        }

        $data['images'] =  $images;
        $data['links'] =  $links;
        return $data;
    }

    public function cover($params = array()){
        $cid = gav($params, 'cid', 0);
        $this->model->setCid($cid);
        $cond = getCond();
        $cond->eq('rmd', 1);
        $row = $this->model->getRow($cond);
        return array(
            'info' => $row
        );
    }

    /**
     * @param array $params
     * @return array()
     */
    function execute($params = array()){
        $cond = getCond();
        $catName = '产品介绍';
        $cid = gav($params, 'cid');
        if($cid){
            $catName = $this->modelCat->getName($cid);
            $this->model->setCid($cid);
        }

        $cond->orderby('inx', 'asc');
        $cond->orderby('id', 'desc');
        
        $nums = gav($params, 'nums', 10);
        if(gav($params, 'rmd')){
            $cond->eq('rmd', gav($params, 'rmd'));
        }
        if(gav($params, 'certId')){
            $cond->like('certId', gav($params, 'certId'));
        }
        $list =  $this->model->getList($nums, $cond);
        $jieduan = Goods::getJieduan();
        return array(
            'catName' => $catName,
            'list' => $list,
            'jieduan' => $jieduan,
            'len' => gav($params, 'len', 20)
        );
    }
}