<?php
/**
* actions{{
* doc : group : feedback
* doc : title : 留言管理
* doc : action : feedback = [留言管理]
* }}
*/
class CtrlFeedbackAdmin extends CtrlActionAdmin{
	var $_defOrderField = 'id';
	
	function init(){
		$this->mod = getDTable('feedback');
	}
}

?>