<?php
  class WidgetAboutcat extends Widget{
      function __construct(){
          $this->model = getDTable('theme');
      }
      function execute($params = array()){
          $id = intval($params['id']);
          if(!$id){
              throw new Exception ('id is not found');
          }
          $cid = $this->model->getFiled('cid', ' AND id=' . $id);
          if($cid){
            $list = $this->model->getList(100, ' AND cid=' . $cid);
          } else {
            $list = array();
          }
          return array('list' => $list);
      }
  }
?>
