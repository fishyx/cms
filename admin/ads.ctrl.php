<?php
/**
* actions{{
* doc : groups : ads
* doc : title : 广告管理
* doc : action : ads = [广告管理]
* }}
*/
class CtrlAdsAdmin extends CtrlActionAdmin {
    
    var $_defOrderField = 'id';
    
    function init(){
        $this->mod = getDTable('Ads');
    }
}

?>