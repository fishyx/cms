<?php

class Timer
{
	var $startTime;
	
	function Timer($autoStart = false){
		if($autoStart){
			$this->start();
		}
	}

	function start(){
		$this->starttime = $this->_time();
	}
	
	function stop($echo = false, $str = ''){
		$times = round($this->_time() - $this->starttime, 5);
		if($echo){
			echo $str . $times;
		}
		return $times;
	}
	
	function _time(){
		$mtime = microtime ();
		$mtime = explode (' ', $mtime);
		return $mtime[1] + $mtime[0];
	}
}

?>