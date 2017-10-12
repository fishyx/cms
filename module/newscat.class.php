<?php
if(!defined('TABLE_PRE')){
    define('TABLE_PRE', '');
}

require_once(SI_INC . 'tree/categoryapp.php');

class Newscat extends CategoryApp {
	var $table = 'news_cat';
    var $_title = "新闻中心";
    var $_entitle = "NEWS";
    static function fixId($id){
        $maps = array(
            'qyzx' => 1, // 企业咨询
            'zsjm' => 2, // 招商加盟
            'xwgg' => 3, // 新闻公告
        );
        if(isset($maps[$id])){
            return $maps[$id];
        }
        return intval($id);
    }   
	function NewsCat(){
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