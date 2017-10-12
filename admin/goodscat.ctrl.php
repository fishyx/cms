<?php
/**
* actions{{
* doc : groups : goods
* doc : title : 产品类别管理
* doc : action : goodscat = [产品类别管理]
* }}
*/
loadModule('Goodscat');

class CtrlGoodscatAdmin extends CtrlActionCategory  {
	function init(){
		$this->tpl = 'goodscat';
		$this->nameKey = 'name';
		$this->op = aurl('Goodscat', 'index');
		$this->mod = new Goodscat();
	}
    
    function ActionDel() {
        $id = intval($_REQUEST['id']);
        $obj = getDTable('goods');
        if($obj->getCount(' AND cid=' . $id)){
            $this->setErr('请先删除该类别下的产品');
            $this->ActionIndex();
        }else{
            return parent::ActionDel();
        }
    }
}

?>