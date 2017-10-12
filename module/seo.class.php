<?php

loadClass('Dba');
class Seo extends Dba {
    var $_tableName = 'seo';
    
     function getInfoType($key = null){
        $arr = array(
            'goods' => '产品',
            'news' => '新闻',
            'cases' => '工程案例',
            'theme' => '专题新闻',
            'index' => '首页',
        );
        if($key){
            if(isset($arr[$key]))
                return $arr[$key];
            return null;
        }
        return $arr;
    }
    
     function getPageType($key = null){
        $arr = array(
            'detail' => '详细页',
            'list' => '列表页',
            'index' => '首页',
        );
        if($key){
            if(isset($arr[$key]))
                return $arr[$key];
            return null;
        }
        return $arr;
    }

    function getVRules(){
        return array(
            'info_type' => array(
                VALID_NOT_EMPTY, '信息类型不能为空', true
                ),
            'title' => array(
                VALID_NOT_EMPTY, '标题不能为空', false
                )
        );
    }
}

?>