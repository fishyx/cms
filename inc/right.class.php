<?php
loadClass('Dba');
class Authority extends Dba{
  var $_tableName = 'right';
  var $arr = array();
  function get_right(){
      $cond = getCond();
      $right = $this->getList(40,' order by id asc');
      foreach($right as $v){
          $this->arr[$v['action']] = $v['name'];
      }
      return $this->arr;
  }
}
?>
