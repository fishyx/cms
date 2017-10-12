<?php

loadClass('Dba');

class News extends Dba  {
	var $_tableName = 'news';
	
    function init(){
        if(inAdmin()){
            $this->setObservable(getInstance('Observable'));
            $ufm =& getUFMPlug();
            $ufm->setThumbsize(200, 180);
            $this->addObserver($ufm);

            // 附件
            $ufile = new UFile();
            $ufile->setExts('doc|zip|rar|xsl');
            $ufm1 =& getUFMPlug($ufile);
            $ufm1->setFieldName('accessory');
            $ufm1->setFormName('accessory');
            $this->addObserver($ufm1);
        }
        return parent::init();
    }
    
	function getVRules(){
		return array(
			'title' => array(
				VALID_NOT_EMPTY, '标题不能为空', 	true
				),
                
            'cid' => array(
                VALID_NOT_EMPTY, '请选择类别',     true
                ),
		
			'content' => array(
				VALID_NOT_EMPTY, '请录入文章内容', true
				),
			
			'rmd' => array(
				'/^\d+$/', '请选择推荐类型', false
				),
				
			'enb' => array(
				'/^\d+$/', '请选择有无效', false
				),	
		);
	}
    
    function setCid($cid) {
        if(!$this->cat){
            loadModule('newscat');
            $this->cat = new Newscat();
        }
        
        $cids = $this->cat->getChildren($cid);
        $cids[] = $cid;
        
        $cond =& $this->getCond();
        $cond->in('cid', $cids);
    }
    
    function orderBy($key, $by = 'DESC') {
        $cond =& $this->getCond();
        $cond->orderby($key, $by);
    }
}
?>