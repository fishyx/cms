<?php

loadClass('Dba');

class Ads extends Dba  {
    var $_tableName = 'ads';
    
    function init(){
        if(inAdmin()){
            $this->setObservable(getInstance('Observable'));
            loadClass('UFile');
            $ufile = new UFile();
            $ufile->setAutoThumb(false);
            $ufile->setExts(UPLOAD_EXTS_ADS);
            $ufm1 =& getUFMPlug($ufile);
            $ufm1->setFieldName('src');
            $ufm1->setFormName('src');
            $this->addObserver($ufm1);
        }
        return parent::init();
    }
}

?>