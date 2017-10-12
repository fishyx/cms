<?php
  class WidgetCasescat extends Widget{
      function __construct(){
          $this->model = getDTable('casesCat');
      }
      function execute($params = array()){
          return array('casescat' => $this->model);
      }
  }
?>
