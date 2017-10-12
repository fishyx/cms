<?php
/**
* actions{{
* doc : group : seo
* doc : title : 网站优化
* doc : action : seo = [网站优化]
* }}
*/
class CtrlSeoAdmin extends CtrlActionAdmin{
    var $_defOrderField = 'id';
    
    function init(){
        $this->mod = getDTable('seo');
    }

    /**
    * put your comment there...
    * 
    * @param Condition $cond
    */
    function &setSearch(& $cond){
        // search
        parent::setSearch($cond);
        if(gav($_REQUEST, 'searchID')){
            $cond->eq('id', gav($_REQUEST, 'searchID'));
        }
        
        if(gav($_REQUEST, 'page_type')){
            $cond->eq('page_type', gav($_REQUEST, 'page_type'));
        }
        
        if(gav($_REQUEST, 'info_type')){
            $cond->eq('info_type', gav($_REQUEST, 'info_type'));
        }
    }
}

?>