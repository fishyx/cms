<?php
/**
* actions{{
* doc : group : case
* doc : title : 案例类别管理
* doc : action : casescat = [案例类别管理]
* }}
*/
loadModule('Casescat');

class CtrlCasescatAdmin extends CtrlActionCategory  {
	function init(){
		$this->tpl = 'casescat';
		$this->nameKey = 'name';
		$this->op = aurl('Casescat', 'index');
		$this->mod = new CasesCat();
	}

    function ActionDel() {
        $id = intval($_REQUEST['id']);
        $obj = getDTable('cases');
        if($obj->getCount(' AND cid=' . $id)){
            $this->setErr('请先删除该类别下的文章');
            $this->ActionIndex();
        }else{
            return parent::ActionDel();
        }
    }
}

?>