<?php

loadClass('Dba');

class Goods extends Dba  {
	var $_tableName = 'goods';
	
    function init(){
        if(inAdmin()){
            $this->setObservable(getInstance('Observable'));
            $ufm = getUFMPlug();
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


			'content' => array(
				VALID_NOT_EMPTY, '请录入产品内容', true
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
            loadModule('Goodscat');
            $this->cat = new Goodscat();
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
    static function getStyle(){
        $arr = array(1=>'线上课程', 2=> '线下课程'); 
        return $arr;
    }   
    static function getSize(){
        $size = Cnfdb::get('goods_chicun');
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
    static function getJieduan(){
        $size = Cnfdb::get('goods_jieduan');
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
function getCertArr(){
    loadClass('cert');
    $cert = new Cert();
    $list = $cert->getList(20, 'order by id desc');
    $arr = array();
    foreach($list as $info){
        $arr[$info['id']] = $info['title'];
    }
    return $arr;
}
?>