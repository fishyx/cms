<?php
/**
* actions{{
* doc : group : theme
* doc : title : 专题类别
* doc : action : themecat= [专题类别]
* }}
*/
loadModule('Themecat');

class CtrlThemecatAdmin extends CtrlActionCategory  {
	function init(){
		$this->tpl = 'themecat';
		$this->nameKey = 'name';
		$this->op = aurl('Themecat', 'index');
		$this->mod = new ThemeCat();
	}

    function ActionDel() {
        $id = intval($_REQUEST['id']);
        $obj = getDTable('theme');
        if($obj->getCount(' AND cid=' . $id)){
            $this->setErr('请先删除该类别下的文章');
            $this->ActionIndex();
        }else{
            return parent::ActionDel();
        }
    }
}

?>