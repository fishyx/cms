<?php

loadClass('Dba');
class Flink extends Dba  {
    var $_tableName = 'flink';
    
    function init(){
        if(inAdmin()){
            loadClass('UFile');
            $this->setObservable(getInstance('Observable'));
            $ufile = new UFile();
            $ufile->setExts(UPLOAD_EXTS_ADS);
            $ufm1 =& getUFMPlug($ufile);
            $this->addObserver($ufm1);
        }
        return parent::init();
    }
    
   static function getCats() {
        return array(
            1 => '友情链接',
            2 => '战略品牌',
        );
    }
}

?>