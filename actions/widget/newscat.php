<?php
  class WidgetNewscat extends Widget{
      function __construct(){
          $this->model = getDTable('newscat');
      }
      function execute($params = array()){
          return array('newscat' => $this->model);
      }
  }
?>
