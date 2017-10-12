<?php

class ViewRenderPHP {
	var $_tplSuffix = '.tpl.php';
	
	var $view;
	
	function ViewRenderPHP(& $view){
		if($view){
			$this->setView($view);
		}
	}
	
	function setView(&$view){
		$this->view =& $view;
	}

	function setTplPath($path){
		if(substr($path, -1, 1) != '/'){
			$path .= '/';
		}
		$this->_tplPath = $path;
	}

	function getTplFullPath($tpl){     
		return $this->_tplPath . $tpl . $this->_tplSuffix;
	}
	
	function render(){
		if($this->view->isErr()){
			$errmsg = $this->view->getErr();
			$tpl = $this->view->getTpl('err');
		}else{
			$tpl = $this->view->getTpl();
		}
		
		if(!$tpl){
			$this->fileNotFound($tpl);
		}
		
	    $tplfile = $this->getTplFullPath($tpl);
		if($layout = $this->view->getLayout()){// use layout
			$layoutFile = $this->getTplFullPath($layout);
			if(file_exists($layoutFile) && file_exists($tplfile)){
				$this->view->set('cnt_page', $tplfile);
				$filename = $layoutFile;
			}else{
				$this->fileNotFound($tplfile);
			}
		}elseif(file_exists($tplfile)){
			$filename = $tplfile;
		}elseif($errmsg){
			echo $errmsg['title'] . " ({$errmsg['info']})";
			exit;
		}else{
			$this->fileNotFound($tplfile);
		}		
		$params =& $this->view->getParams();   
		extract($params->getAsArray(), EXTR_REFS);
		include($filename);
	}
	
	function fileNotFound($file){
		http404();
		echo $file . ' not found';
		exit;
	}
}

?>