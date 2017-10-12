<?php
if(!defined('TABLE_PRE')){
    define('TABLE_PRE', '');
}

require_once(SI_INC . 'tree/categoryapp.php');

class Casescat extends CategoryApp {
	var $table = 'cases_cat';
    var $_title = "设计师";
    var $_entitle = "CASE";
    
	function CasesCat(){
        parent::CategoryApp();
        if(inAdmin()){
            $this->setObservable(getInstance('Observable'));
            $ufm =& getUFMPlug();
            $ufm->setThumbsize(80, 94);
            $this->addObserver($ufm);
        }
	}
}
function getCasecat() {
    static $i;
    if(!$i){
        $i = new Casescat();
    }
    return $i;
}
?>