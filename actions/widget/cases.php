<?php
class WidgetCases extends Widget{
    /**
     * @var About
     */
    protected $model;

    function __construct(){
        $this->model = getDTable('cases');
        $this->modelCat = getDTable('CasesCat');
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
        $style = Cases::getStyle();
        $size = Cases::getSize();

        //$cid = intval($_REQUEST['cid']);
        if($cid){
            $catName = $this->modelCat->getName($cid);
            $this->model->setCid($cid);
        }
         if(gav($params, 'shejishi')){
            $cond->eq('shejishi', gav($params, 'shejishi'));
        }
        if(gav($params, 'rmd')){
            $cond->eq('rmd', gav($params, 'rmd'));
        }
        $cond->orderby("inx", 'asc');
        $cond->orderby("id", 'desc');
        $nums = $params['nums'] ? $params['nums'] : 10;
        $this->list = $this->model->getList($nums, $cond);
        $this->pages = $pager->getHtml();
        return array(
            'catName' => $catName,
            'style' => $style,
            'size' => $size,
            'list' => $this->list,
            'modelCat' => $this->modelCat,
        );
    }
}