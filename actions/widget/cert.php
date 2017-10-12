<?php
class WidgetCert extends Widget{
    /**
     * @var About
     */
    protected $model;

    function __construct(){
        $this->model = getDTable('cert');
        $this->modelCat = getDTable('certCat');
    }

    /**
     * @param array $params
     * @return array()
     */
    function execute($params = array()){
        $cond = getCond();
        $cid = gav($params, 'cid');

        $pager = getPager();
        $pager->setNums(12);

        $catName = '';
        $cond = getCond();
        //$cid = intval($_REQUEST['cid']);
        if($cid){
            $catName = $this->modelCat->getName($cid);
            $this->model->setCid($cid);
        }
         if(gav($params, 'rmd')){
            $cond->eq('rmd', gav($params, 'rmd'));
        }
         if(gav($params, 'type')){
            $cond->eq('type', gav($params, 'type'));
        }
        
        $cond->orderby("inx", 'asc');
        $cond->orderby("id", 'desc');
        $nums = $params['nums'] ? $params['nums'] : 10;
        $this->list = $this->model->getList($nums, $cond);
        $this->pages = $pager->getHtml();
        return array(
            'catName' => $catName,
            'list' => $this->list,
            'modelCat' => $this->modelCat,
            'jibie' => Cert::getJiebie(),
        );
    }
}