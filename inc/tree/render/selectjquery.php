<?php

require_once(dirname(__FILE__) . '/html.php');

/**
* 
*/
class TreeSelectJquery extends TreeHtml{
    
    public function __construct() {
        $this->setOption('jsClassName', 'TSelect');
    }
    
    /**
    * html for js
    * 
    * @param mixed $cid
    * @param mixed $deep
    */
    public function leaf($cid, $deep) {
        $name = $this->cat->getName($cid);
        $space = $this->_spaceLen($deep + 1);
        $stype = " rel=\"{$cid}\"";
        $this->html .= $this->_space($space) . "<li{$stype}>{$name}</li>\n";
    }
    
    public function branchStart($cid, $deep) {
        if($deep > 0){
            $name = $this->cat->getName($cid);
            $space = $this->_spaceLen($deep);
            $stype = " rel=\"{$cid}\"";
            $this->html .= $this->_space($space) . "<li{$stype}>\n";
            $this->html .= $this->_space($space + 1) . "{$name}\n";
            $this->html .= $this->_space($space + 1) . "<ul>\n";
        }
    }
    
    /**
    * 兼容老系统接口
    * 
    */
    public function clearJsFile(){
    }
    
    /**
    * for js 
    * 
    * @param mixed $file
    * @param mixed $print
    */
    public function importJs($file = true, $print = true){
        
        static $loaded, $flg;
        $html = '';
        if(!isset($flg)){
            jimport('j.html.html');
            
            $flg = true;
            $jsJquery = JHtml::importJs('jquery', false);
			$js = ui("/js/jquery/jquery.mcdropdown.js");
			$js1 = ui("/js/jquery/jquery.bgiframe.js");
			$css = ui("/js/jquery/css/jquery.mcdropdown.css");
            $html = <<<END
{$jsJquery}
<script type="text/javascript" src="{$js}"></script>
<script type="text/javascript" src="{$js1}"></script>
<!---// load the mcDropdown CSS stylesheet //--->
<link type="text/css" href="{$css}" rel="stylesheet" media="all" />
END;
        }
        
        $jsCatName = $this->getOption('jsClassName');
        if(!isset($loaded[$jsCatName])){
            $loaded[$jsCatName] = true;
            $catHtml = $this->tree();// getHtml
            $html .= <<<END
<ul id="{$jsCatName}" class="mcdropdown_menu">
{$catHtml}
</ul>
END;
            if($print){
                echo $html;
            }else{
                return $html;
            }
        }
    }
    
    public function create($name, $value = '', $params = array()){
        static $i = 0;
        $i++;
        
        $defaultParams = array(
            'length' => 1,
            'echo' => true,
            );
        $params = array_merge($defaultParams, $params);
        extract($params, EXTR_OVERWRITE);
        
        // js file
        $html = $this->importJs(null, false);
        
        // new select
        $jsCatName = $this->getOption('jsClassName');
        
        $html .= <<<END
<input type="text" name="{$name}" id="{$name}{$i}" value="" />
<script type="text/javascript">
jQuery("#{$name}{$i}").mcDropdown("#{$jsCatName}");
</script>\n
END;
        return $html;
    }
}

?>