<?php
if(!defined('TABLE_PRE')){
    define('TABLE_PRE', '');
}

require_once(SI_INC . 'tree/categoryapp.php');

class Goodscat extends CategoryApp {
	var $table = 'goods_cat';
    var $_title = "产品类别";
    var $_entitle = "PRODUCT";
    
	function GoodsCat(){
        parent::CategoryApp();
        if(inAdmin()){
            $this->setObservable(getInstance('Observable'));
            $ufm =& getUFMPlug();
            $ufm->setThumbsize(80, 94);
            $this->addObserver($ufm);
        }
	}
}
function getGcat() {
    static $i;
    if(!$i){
        $i = new Goodscat();
    }
    return $i;
}
?>