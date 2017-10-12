<?php
if(!defined('TABLE_PRE')){
    define('TABLE_PRE', '');
}

require_once(SI_INC . 'tree/categoryapp.php');

class Certcat extends CategoryApp {
	var $table = 'cert_cat';
    var $_title = "导师分类";
    var $_entitle = "CERT";
    
	function CertCat(){
        parent::CategoryApp();
        if(inAdmin()){
            $this->setObservable(getInstance('Observable'));
            $ufm =& getUFMPlug();
            $ufm->setThumbsize(80, 94);
            $this->addObserver($ufm);
        }
	}
}
    function getCertcat() {
        static $i;
        if(!$i){
            $i = new Certcat();
        }
        return $i;
    }
?>