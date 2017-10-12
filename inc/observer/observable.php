<?php

/**
 * \ingroup Core
 * ���۲����Ļ���
 *
 * Implementation of the Observer pattern. Copied/Inspired ;) from
 * http://www.phppatterns.com/index.php/article/articleview/27/1/1/.
 */
class Observable{

	/**
      * @private
      * $observers an array of Observer objects to notify
      */
	var $_observers;
	
	/**
      * Constructs the Observerable object
      */
	function Observable(){
		$this->_observers = array();
	}

	/**
	 * ֪ͨ�۲���
     * Calls the update() function using the reference to each
     * registered observer - used by children of Observable
     * @param array ͨ�Ų���
     * @return void
     */
	function notify($params = ""){
		$observers = count($this->_observers);
		for ($i=0; $i<$observers; $i++) {
			$this->_observers[$i]->updateNotify($params);
		}
	}

	/**
      * ע��۲��߶���
      * @return void
      */
	function addObserver (& $observer){
		$this->_observers[]=& $observer;
	}
	
	/**
	 * ɾ��۲��߶���
	 *
	 * @param object $anObserver
	 */
	function delObserver(& $anObserver){
		if(in_array($anObserver, $this->_observers)){
			unset($this->_observers[$anObserver]);
		}
	}
	
	/**
	 * ������й۲���
	 *
	 */
	function clearObserver(){
		$this->_observers = array();
	}
	
}


?>