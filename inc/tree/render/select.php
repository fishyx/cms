<?php

require_once(dirname(dirname(__FILE__)) . '/render.php');

/**
* 
*/
class TreeSelect extends TreeRender{
    
    /**
    * put your comment there...
    * 
    */
    function __construct() {
        $this->setOption('jsMselectUrl', ui("/js/mselect.js"));
        
        $this->setOption('jsFile',  JPATH_WWW . "_tmp/MSTest.js");
        $this->setOption('jsUrl',  "/_tmp/MSTest.js");
        $this->setOption('jsClassName', 'TSelect');
        
        $this->setOption('useJsFile', false);
        $this->setOption('show',  false);
        $this->setOption('search', false);
    }
    
   /**
    * put your comment there...
    * 
    * @param mixed $fileName
    * @param mixed $className
    * @return int
    */
    private function createJsFile($fileName, $className){
        if(!is_object($this->cat) || empty($fileName) || empty($className)){
            return false;
        }
        $jsFileContent = $this->getJsContent($className);
        $omask = umask(0);
        $flag = file_put_contents($fileName, $jsFileContent);
        umask($omask);
        return $flag;
    }
    
    private function getJsContent($className){
        // json object
        $cats = $this->cat->getCatsForSelect();
        
        foreach ($cats as $key => $cat) {
            foreach ($cat as $key1 => $filed){
                $cats[$key][$key1] = mb_convert_encoding($filed, 'utf-8', 'gb2312');
            }
        }
        
        $pk = $this->getOption('pk');
        $nk = $this->getOption('nk');
        $className = $this->getOption('jsClassName');
        $jsFileContent = "\n
var {$className} = Class.create();
{$className}.CATS = " . json_encode($cats) . ";

(function(){
    var tree = {};
    var pid;
    var cats = {$className}.CATS;
    var map = {};
    var acdata = [];
    
    for(var cid in cats){
        pid = cats[cid]['{$pk}'];
        if(typeof(tree[pid]) != 'object'){
            tree[pid] = [];
        }
        tree[pid].push(cid);
        map[cid] = pid;
        acdata.push({'id' : cid, 'name' : cats[cid]['{$nk}']});
    }
    
    {$className}.TREE = tree;
    {$className}.MAP = map;
    {$className}.ACDATE = acdata;
})();

Object.extend({$className}.prototype, MSelect.prototype);
Object.extend({$className}.prototype, {
    init : function(){
        this.pk_name = '" . $pk . "';
        this.nk_name = '" . $nk . "';
        this.cats = {$className}.CATS;
        this.tree = {$className}.TREE;
        this.map = {$className}.MAP;
        this.acdata = {$className}.ACDATE;
    }
});
";
        return $jsFileContent;
    }
    
    /**
    * put your comment there...
    * 
    */
    public function clearJsFile(){
        $jsFile = $this->getOption('jsFile');
        if(file_exists($jsFile)){
            @unlink($jsFile);
        }
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $file
    * @param mixed $print
    */
    public function importJs($file = true, $print = true){
        static $imported, $jsFiles;
           
        $html = '';
        if(!isset($imported)){
        // import global js file
            $imported = true;
            $jsMselectUrl = $this->getOption('jsMselectUrl');
            $html = <<<END
<script type="text/javascript" src="{$jsMselectUrl}"></script>
END;
        }
        
        $jsCatName = $this->getOption('jsClassName');
        if(!isset($jsFiles[$jsCatName])){
            $jsFiles[$jsCatName] = true;
            if($file == true){
            // import js file
                $jsWebFile = $this->getOption('jsUrl');
                $jsFile = $this->getOption('jsFile');
                if(!file_exists($jsFile)){
                    $this->createJsFile($jsFile, $jsCatName);
                }
                $html .= <<<END
<script type="text/javascript" src="{$jsWebFile}"></script>\n
END;
            }else{
            // import js text
                $jsCnt = $this->getJsContent($jsCatName);
                $html .= <<<END
<script type="text/javascript">{$jsCnt}</script>\n
END;
            }
        }
        
        if($print){
            echo $html;
        }else{
            return $html;
        }
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $file
    */
    public function create($name, $value = '', $params = array()){
        static $i = 0;
        $i++;

        if(is_numeric($params)){
            $params = array('length' => $params);
        }
        if(!is_array($params)){
            $params = array();
        }
        $defaultParams = array(
            'length' => 1,
            'echo' => true,
            );
        $params = array_merge($defaultParams, $params);
        extract($params, EXTR_OVERWRITE);

        // use js file
        $file = isset($file) ? $file : $this->getOption('useJsFile');
        $html = $this->importJs($file, $echo);

        $length = intval($length);
        $jsClassName = $this->getOption('jsClassName');
        $jsVarName = 'cat' . $i;
        
        // is search
        $search = isset($search) ? $search : $this->getOption('search');
        $searchJs = $jsComplate = '';
        if($search){
            jimport('j.html.html');
            $jsComplate = JHtml::importJs('autocomplete', false);
            $searchJs = "{$jsVarName}.searchInit();\n";
        }
        
        // is show layer div
        $show = isset($show) ? $show : $this->getOption('show');
        if($show){
            jimport('j.html.html');
            $html .= JHtml::importJs('tipbox', false);
        }
        $show = $show ? 'true' : 'false';
        
        // is show used
        $used = isset($used) ? $used : $this->getOption('used');
        $usedJs = '';
        if(is_array($used) && $used){
            $usedJs = "{$jsVarName}.usedTip(" . json_encode($used)  . ");\n";
        }
        
        $html .= <<<END
{$jsComplate}
<script type="text/javascript">
    var {$jsVarName} = new {$jsClassName}('{$name}');
    {$jsVarName}.showLayer({$show});
    {$jsVarName}.render('{$value}', {$length});
    {$usedJs}
    {$searchJs}
</script>\n
END;

        return $html;
    }
    
    /**
    * ¼æÈÝ·½·¨Ãû
    * 
    * @param mixed $name
    * @param mixed $value
    * @param mixed $params
    */
    public function createSelect($name, $value = '', $params = array()){
        return $this->create($name, $value, $params);
    }
}
