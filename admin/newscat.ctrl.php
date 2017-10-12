<?php
/**
* actions{{
* doc : group : news
* doc : title : 新闻类别管理
* doc : action : newscat = [新闻类别管理]
* }}
*/
loadModule('Newscat');

class CtrlNewscatAdmin extends CtrlActionCategory  {
	function init(){
		$this->tpl = 'newscat';
		$this->nameKey = 'name';
		$this->op = aurl('Newscat', 'index');
		$this->mod = new NewsCat();
	}
    
    function ActionDel() {
        $id = intval($_REQUEST['id']);
        $news = getDTable('news');
        if($news->getCount(' AND cid=' . $id)){
            $this->setErr('请先删除该类别下的文章');
            $this->ActionIndex();
        }else{
            return parent::ActionDel();
        }
    }
}

?>