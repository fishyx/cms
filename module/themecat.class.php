<?php
if(!defined('TABLE_PRE')){
    define('TABLE_PRE', '');
}

require_once(SI_INC . 'tree/categoryapp.php');

class Themecat extends CategoryApp {
	var $table = 'theme_cat';

	function ThemeCat(){
        parent::CategoryApp();
        if(inAdmin()){
            $this->setObservable(getInstance('Observable'));
            $ufm =& getUFMPlug();
            $ufm->setThumbsize(80, 94);
            $this->addObserver($ufm);
        }
	}
}

?>