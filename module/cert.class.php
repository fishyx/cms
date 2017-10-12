<?php

loadClass('Dba');

class Cert extends Dba  {
	var $_tableName = 'cert';
	
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
            loadModule('certcat');
            $this->cat = new Certcat();
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
        static function getJiebie(){
        $style = Cnfdb::get('cert_jibie');
        $style = explode('|', $style);
        $arr = array();
        $i=1;
        foreach( $style as $k=>$v){   
        if($v){           
            $arr[$i] = $v;
            $i++;
        }   
        }   
        return $arr;
    }   
    static function getZhuti(){
        $size = Cnfdb::get('cert_zhuti');
        $size = explode('|', $size);
        $arr = array();
        $i=1;
        foreach( $size as $k=>$v){   
        if($v){           
            $arr[$i] = $v;
            $i++;
        }   
        }   
        return $arr;
    }
    static function getBumen(){
        $size = Cnfdb::get('cert_bumen');
        $size = explode('|', $size);
        $arr = array();
        $i=1;
        foreach( $size as $k=>$v){   
        if($v){           
            $arr[$i] = $v;
            $i++;
        }   
        }   
        return $arr;
    }
}
?>