<?php
/**
 * \ingroup Core
 * �۲����Ļ���
 * 
 * Implementation of the Observer pattern. Copied/Inspired ;) from
 * http://www.phppatterns.com/index.php/article/articleview/27/1/1/.
 * Base Observer class     
 */
class Observer {

	/**
     * Abstract function implemented by children to repond to
     * to changes in Observable subject
     * @return void
     */
	function updateNotify($params, $observable) {
		// trigger_error ('Update not implemented');
		// child class add code here
	}
}
?>