<?php
  class WidgetCertcat extends Widget{
      function __construct(){
          $this->model = getDTable('certCat');
      }
      function execute($params = array()){
          return array('certcat' => $this->model);
      }
  }
?>
