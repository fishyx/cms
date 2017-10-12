<?php

if(!defined('UPLOAD_PATH')){
	define('UPLOAD_PATH', ROOT_WWW . 'upload/');
}

if(!defined('UPLOAD_MAX_SIZE')){
	define('UPLOAD_MAX_SIZE', 1024 * 1024); // 1 M
}

if(!defined('UPLOAD_EXTS_IMGS')){
	define('UPLOAD_EXTS_IMGS', 'jpg|jpeg|gif|png');
}

if(!defined('UPLOAD_EXTS_ADS')){
	define('UPLOAD_EXTS_ADS', 'jpg|jpeg|gif|png|swf');
}


define('UPLOAD_ERR_EXT', 			100);
define('UPLOAD_ERR_DIR_NONEXIST', 	101);

class Uploader {
	
	var $filename;
	
	var $savepath = UPLOAD_PATH;
	
	var $maxsize = UPLOAD_MAX_SIZE;
	
	var $extions = UPLOAD_EXTS_IMGS;
	
	var $autoCreateDir = true;
	
	var $errorCode;
	
	var $errorMsg;
	
	function setPath($path){
		if(substr($path, -1, 1) != '/'){
			$path .= '/';
		}
		$this->savepath = $path;
	}
	
	function setFilename($fileName){
		$this->filename = $fileName;
	}
	
	function setMaxSize($size){
		$this->maxsize = intval($size);
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $exts eg : 'jpg|jpeg|gif|png|swf'
	 */
	function setExts($exts){
		$this->extions = $exts;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param string $formname
	 * @return boolean or string (filename)
	 */
	function upload($formname = 'img'){
		$ufile =& $_FILES[$formname];
		if(!$ufile){
			$this->setError(UPLOAD_ERR_NO_FILE);
			return false;
		}
		
		// status
		if($ufile['error'] != UPLOAD_ERR_OK){
			$this->setError($ufile['error']);
			return false;
		}
		
		// size
		if($ufile['size'] > $this->maxsize){
			$this->setError(UPLOAD_ERR_INI_SIZE);
			return false;
		}
		
		// extion
		$extion = File::get_expand_name($ufile['name']);
		if(!$this->checkExtion($extion)){
			$this->setError(UPLOAD_ERR_EXT);
			return false;
		}
		
		// filename
		if(empty($this->filename)){
			$filename = $this->_randFilename($ufile['name']);
			$this->setFilename($filename);
		}
		
		$fullfilename = $this->savepath . $this->filename . '.' . $extion;
		$dir = dirname($fullfilename);
		if(!is_dir($dir)){
			if($this->autoCreateDir){
				File::dir_create($dir);
			}else{
				$this->setError(UPLOAD_ERR_DIR_NONEXIST);
				return false;
			}
		}
		
		// upload
		if(move_uploaded_file($ufile['tmp_name'], $fullfilename)){
			return $this->filename . '.' . $extion;
		}
		
		$this->setError(UNKNOWN);
		return false;
	}
	
	function checkExtion($extion){
		return preg_match("/^{$this->extions}$/i", $extion);
	}
	
	function _randFilename(){
		return date("ymdHis") . mt_rand(1, 10);
	}
	
	function setError($code){
		if($code == UPLOAD_ERR_OK){
			return;
		}
		
		$this->errorCode = $code;
		switch ($code) {
			case UPLOAD_ERR_INI_SIZE :
			case UPLOAD_ERR_FORM_SIZE :
				$this->errorMsg = '上传的文件超过设置大小';
				break;
				
			case UPLOAD_ERR_PARTIAL :
				$this->errorMsg = '文件只有部分被上传';
				break;
				
			case UPLOAD_ERR_NO_FILE :
				$this->errorMsg = '没有文件被上传';
				break;
				
			case UPLOAD_ERR_NO_TMP_DIR :
				$this->errorMsg = '找不到临时文件夹';
				break;
				
			case UPLOAD_ERR_CANT_WRITE :
				$this->errorMsg = '文件写入失败';
				break;

			case UPLOAD_ERR_EXT :
				$this->errorMsg = '文件类型禁止上传';
				break;
				
			case UPLOAD_ERR_DIR_NONEXIST : 
				$this->errorMsg = '上传目录不存在';
			
			default : 
				$this->errorMsg = '未知错误';
		}
	}
	
	function getErrMsg(){
		return $this->errorMsg;
	}
	
	function getErrCode(){
		return $this->errorCode;
	}
}

?>
