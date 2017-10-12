<?php

class CtrlRoute {
	function route($request){
		$params = (object)null;
		$uri = $request->url;
		if(!$uri){
			return $params;
		}
		
		$uri = explode('-', $uri);
		$params->ctrl = $uri[0];
		$params->action = gav($uri, 1);
		
		return $params;
	}

	function setUrl(){
		$args = func_get_args();
		
		$url = '';
		if(count($args) == 0 || $args[0] == '/'){
			return $url;
		}
		
		$url = "?q={$args[0]}";
		if($args[1]){
			$url .= "-{$args[1]}";
		}
		
		for($i = 2, $j=count($args); $i < $j; $i++){
			$url .= '&' . $args[$i] . '=' . $args[++$i];
		}
		return $url;
	}
}

class CtrlDispatch {
	var $ctrlPath = SI_APP;
    var $params;
	
	function dispatch($params, & $response){
		if(!$params->ctrl){
			$params->ctrl = Configs::get('defCtrl');
		}
		
		if(!$params->action){
			$params->action = Configs::get('defAction');
		}
        
        $this->params =& $params;
		
		// assign public vars
		$response->set('_e', $params);
		$response->set('Action', $params->action);
		$response->set('Ctrl', $params->ctrl);
		
		// get controller file
		$file = $this->getCtrlFile();
		if(!file_exists($file)){
			$response->setErr('ctrl class file not found', $file);
			return;
		}
		
		// get controller class
        
		include_once($file);
		$ctrlName = $this->getCtrlName();
		if(!class_exists($ctrlName)){
			$response->setErr('class not found', $ctrlName);
			return;
		}
		
		// set view
        $viewName = $this->getViewName(); ;
        /**
        * put your comment there...
        * 
        * @var View
        */
		$response->setTpl($viewName);
		// process          
        $action = $this->getActionName($params);    
		$class = new $ctrlName($action, $response);
		$class->Ctrl = $params->ctrl;   
		$class->process();
	}
	
	function getCtrlFile(){
        $ctrl = strtolower($this->params->ctrl);
        $action = $this->params->action;
		$fileMapping = array(
			'index-' => 'index.ctrl.php',
		);
		
		$rkey = $ctrl . '-' . $action;
		if(isset($fileMapping[$rkey])){
			return $this->ctrlPath . $fileMapping[$rkey];
		}else{
			return $this->ctrlPath . $ctrl . '.ctrl.php';
		}
	}
	
	function getCtrlName(){
		return 'Ctrl' . ucfirst($this->params->ctrl);
	}
	
	function getActionName(){
		return 'Action' . ucfirst($this->params->action);
	}
    
    function getViewName() {
        $viewName = $this->params->ctrl;
        $viewName .= $this->params->action && $this->params->action != Configs::get('defAction') 
            ?  '-' . $this->params->action 
            : '';
        return $viewName;
    }
}

class CtrlAction {
	
	var $action;
	
	var $view;
	
	function CtrlAction($action, $view){
		$this->action = $action;
		$this->setView($view);
		$this->init();
	}
	
	function init(){
		// rewrite this method;
	}
	
	function process(){
		if(method_exists($this, $this->action)){
			$this->{$this->action}();
		}
	}

	function setView(& $view){
		$this->view =& $view;
	}
    
    function set($key, $value){
        $this->view->set($key, $value);
    }

	function setV($key, $value){
		$this->set($key, $value);
	}
	
	function setVar($key, $value){
		$this->set($key, $value);
	}
    
    function setErr($msg) {
        $this->set('msg', $msg);
    }

	function setTpl($tpl){
		$this->view->setTpl($tpl);
	}
}

function _URL(){
	$args = func_get_args();
	echo call_user_func_array(array('CtrlRoute', 'setUrl'), $args);
}

?>
