<?php
  class WidgetProductcat extends Widget{
      function __construct(){
          $this->model = getDTable('goodsCat');
          $this->cats_db = getDTable('goods_cat');
      }
      function execute($params = array()){
          return array('goodscat' => $this->model,'cats_db'=> $this->cats_db);
      }
  }
?>
