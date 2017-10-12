<?php

loadClass('Dba');
class Feedback extends Dba {
	var $_tableName = 'feedback';
		
	function getVRules(){
		return array(
			'email' => array(
				VALID_NOT_EMPTY, '联系人不能为空', 	true
				),
    
		);
	}
}

?>