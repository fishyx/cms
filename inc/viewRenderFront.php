<?php

require_once SI_INC .'viewRenderPHP.php';
require_once SI_INC . 'tsource.php';
require_once SI_INC . 'module.php';

class ViewRenderFront extends ViewRenderPHP {
	function ViewRenderFront(& $view){
		$this->setTplPath(SI_ROOT);
		parent::ViewRenderPHP($view);
	}
	
	function render(){
		$ts = new TSource();
		$tpl = $this->view->getTpl() . $this->_tplSuffix;
		$realtpl = $ts->getTpl($tpl);
		if(!$realtpl){
			if($this->view->getTpl() == 'welcome'){
				$tpl = 'index' . $this->_tplSuffix;
				$realtpl = $ts->getTpl($tpl);
			}
		}
		
		if(!$realtpl){
			$realtpl = $ts->getTpl('layout' . $this->_tplSuffix);
		}
		
		$this->view->setLayout(null);
		$this->view->setTpl($realtpl);
		$this->view->set('pageFile', $ts->getPage($tpl));
		$this->view->set('M', new Module($ts));
		$this->view->set('TS', $ts);
		
		parent::render();
	}
	
	function getTplFullPath($tpl){
		return $this->_tplPath . $tpl;
	}
}

?>