<?php
/**
* actions{{
* doc : groups : siteright
* doc : title : 菜单
* doc : action : siteright = [菜单管理]
* }}
*/
  loadModule('siteright');
  class CtrlSiterightAdmin extends CtrlActionAdmin{
      var $mod;
      var $_defOrderField = 'inx';
      function init(){
        $this->mod = getDTable('siteright');
    }
    function ActionBulid(){
        $flag = $this->mod->buildRights();
        if($flag){
            $this->view->setVar('msg', '重构完成');
            parent::ActionIndex();
        }
    }
  }
?>
