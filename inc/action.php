<?php

abstract class Action {
    protected $urlPre = 'index.php';

    protected $TPL;
    protected $viewDir;
    protected $autoRender = true;

    protected $widgetDir = null;

    function run(){
        if(method_exists($this, 'preAction')){
            $this->preAction();
        }

        $this->execute();

        if(method_exists($this, 'postAction')){
            $this->postAction();
        }

        if($this->autoRender)
            $this->render();
    }

    abstract function execute();

    /** @var  PathWay */
    protected $path;
    function getPath(){
        if(!isset($this->path)){
            $this->path = new Pathway();
        }
        return $this->path;
    }

    /** @var  PathWay */
    protected $seo;
    function getSeo(){
        if(!isset($this->seo)){
            $this->seo = new Seo();
        }
        return $this->seo;
    }

    function render($data = array(), $tpl = null, $dir = null){
        if(!isset($this->TPL) || !$this->TPL){
            $x  = preg_split("/(?=[A-Z])/", get_class($this));
            array_shift($x);
            array_shift($x);
            $x = array_map('strtolower', $x);
            $x = implode('.', $x);

            $this->TPL = str_replace('action', '', $x . '.php');
        }

        if(!isset($this->viewDir)){
            $this->viewDir = dirname(dirname(__FILE__)) . '/views/';
        }
        $tpl = ($dir ? $dir : $this->viewDir) . ($tpl ? $tpl : $this->TPL);
        if(!file_exists($tpl)){
            throw new Exception("template file not found({$tpl})");
        }
        
        $vars = get_object_vars($this);
        extract($vars, EXTR_REFS);
        extract($data, EXTR_REFS);
        include($tpl);
    }

    function widget($name, $params = array()){
        $names = explode('.', $name, 2);
        $widget = Widget::getInstance($names[0]);
        $widget->setCaller($this);
        if(isset($names[1]) && method_exists($widget, $names[1])){
            $method = $names[1];
        }else{
            $method = 'execute';
        }

        $data = call_user_func(array($widget, $method), $params);
        if(isset($params['return']) && $params['return']){
            return $data;
        }
        $tpl_suffix = isset($params['tpl']) ? $params['tpl'] : '';
        $tpl = 'view/' . strtolower($name) . ($tpl_suffix ? "_" . $tpl_suffix : '') . ".php";
        $this->render($data, $tpl, $this->widgetDir);
    }

    function url($c = 'index', $args = '') {
        if($c == 'index'){
            return '/';
        }
        if(is_array($args)){
            $args = http_build_query($args);
        }elseif(is_numeric($args)){
            $args = 'id=' . $args;
        }
        if(!$c)
            return "#none";
        $tmp = explode('.', $c);
        $url .= '?c=' . $tmp[0];

        if(isset($tmp[1]))
            $url .= '&_a=' . $tmp[1];
  
        return BASE_URL . $this->urlPre . $url .'&'.$args;
    }

    /**
     * @param $name
     * @return self
     * @throws Exception
     */
    static function getInstance($name){
        if(!$name){
            $name = 'index';
        }
        
        if($_REQUEST['_a']){
            if($_REQUEST['_a'] !== 'list'){
                $name .= '.' .$_REQUEST['_a'];
            }
            
        }
        
        $class = 'Action' .  str_replace('.', '', ucfirst($name));
        if(!class_exists($class)){
            $fileName = self::getActionDir() . '/' . strtolower($name) . '.php';
            if(!file_exists($fileName)){
                throw new Exception("Action not found({$class})");
            }
            include($fileName);
            if(!class_exists($class)){
                throw new Exception("Action class not found({$class})");
            }
        }
        return new $class();
    }

    protected static $actionDir;

    /**
     * @param mixed $actionDir
     */
    public static function setActionDir($actionDir) {
        self::$actionDir = $actionDir;
    }

    /**
     * @return mixed
     */
    public static function getActionDir() {
        if(!isset(self::$actionDir)){
            throw new Exception("Invalid action dir, call Action::ActionDir(DIR) first");
        }
        return self::$actionDir;
    }
}

abstract class Widget{
    protected static $widgetDir;
    protected static $instances = array();

    /**
     * @param mixed $widgetDir
     */
    public static function setWidgetDir($widgetDir){
        self::$widgetDir = $widgetDir;
    }

    /**
     * @return mixed
     */
    public static function getWidgetDir(){
        if(!isset(self::$widgetDir)){
            self::$widgetDir = dirname(__FILE__) . '/widget/';
        }
        return self::$widgetDir;
    }

    /**
     * @param $name
     * @return self
     * @throws Exception
     */
    static function getInstance($name){
        if(isset(self::$instances[$name])){
            return self::$instances[$name];
        }

        $class = 'Widget' .  ucfirst($name);
        if(!class_exists($class)){
            $fileName =  self::getWidgetDir() . strtolower($name) . '.php';
            if(!file_exists($fileName)){
                throw new Exception("Widget not found({$class})");
            }
            include($fileName);
            if(!class_exists($class)){
                throw new Exception("Widget class not found({$class})");
            }
        }
        self::$instances[$name] = new $class();
        return self::$instances[$name];
    }

    /**
     * @param array $params
     * @return array()
     */
    abstract function execute($params = array());

    /** @var  Action */
    protected $caller;

    /**
     * @param mixed $caller
     */
    public function setCaller($caller){
        $this->caller = $caller;
    }

    protected function url(){
        return call_user_func_array(array($this->caller, 'url'), func_get_args());
    }
}

class Pathway {
    /**
     * Array to hold the pathway item objects
     * @access private
     */
    protected $_pathway = null;

    /**
     * Integer number of items in the pathway
     * @access private
     */
    protected  $_count = 0;

    /**
     * put your comment there...
     *
     * @var stdClass
     */
    protected $separator = ' > ';

    /**
     * Class constructor
     */
    function __construct($options = array()){
        //Initialise the array
        $this->_pathway = array();
    }

    function getPathway(){
        $pw = $this->_pathway;
        return array_values($pw);
    }

    function setPathway($pathway){
        $oldPathway = $this->_pathway;
        $pathway    = (array) $pathway;

        // Set the new pathway.
        $this->_pathway = array_values($pathway);

        return array_values($oldPathway);
    }

    function addItem($name, $link = ''){
        $ret = false;

        if ($this->_pathway[] = $this->_makeItem($name, $link)) {
            $ret = true;
            $this->_count++;
        }

        return $ret;
    }

    protected function _makeItem($name, $link){
        $item = new stdClass();
        $item->name = html_entity_decode($name);
        $item->link = $link;

        return $item;
    }

    function __toString(){
        $items = array();
        foreach ($this->_pathway as $value) {
            if($value->link){
                $items[] = "<a href=\"{$value->link}\">{$value->name}</a>";
            }else{
                $items[] = $value->name;
            }
        }
        return implode($this->separator, $items);
    }

    function setSeparator($chars){
        $this->separator = $chars;
    }
}


/**
 * Class JSeo
 */  
class Seo{
    protected $pattern = array();
    protected $values = array();
    protected $vars = array();
                                                                 
    function __construct(){
        $this->vars = array(
            'sp', 'sp2', 'title'
            );
        $this->values = array();
        foreach ($this->vars as $k) {
            $this->values[$k] = '';
        }

        $this->values['sp'] = ' - ';
        $this->values['sp2'] = '_';
    }

    function setPattern($pattern, $key = null){
        if(is_array($pattern)){
            $this->pattern = $pattern;
        }elseif($key){
            $this->pattern[$key] = $pattern;
        }
    }

    function setVar($k, $value){
        if($value){
            $this->values[$k] = $value;
        }
        if(!in_array($k, $this->vars)){
            $this->vars[] = $k;
        }
    }

    /**
     * put your comment there...
     *
     * @param mixed $pattern
     * @return string
     */
    private function genString($pattern){
        $string = $pattern;
        foreach ($this->vars as $k) {
            $string = str_replace("{{$k}}", isset($this->values[$k]) ? $this->values[$k] : '', $string);
        }  
        $string = preg_replace('/\{.+?\}/', '', $string);
        
        $string = preg_replace("/({$this->values['sp']}){2,}/", $this->values['sp'], $string);
        $string = trim($string, $this->values['sp']);

        $string = preg_replace("/({$this->values['sp2']}){2,}/", $this->values['sp2'], $string);
        $string = trim($string, $this->values['sp2']);

        return $string;
    }

    /**
     * put your comment there...
     *
     */
    function getTitle()   {
        return $this->genString($this->pattern['title']);
    }

    function getDesc(){
        if(!isset($this->pattern['description'])){
            return '';
        }
        return $this->genString($this->pattern['description']);
    }

    function getKeywords(){
        if(!isset($this->pattern['keywords'])){
            return '';
        }
        return $this->genString($this->pattern['keywords']);
    }
}