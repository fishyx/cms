<?php

if(!defined('SI_ROOT')){
	define('SI_ROOT', $_SERVER['DOCUMENT_ROOT']);
}

/**
 * file upload root
 */
if(!defined('UFP_DIR')){
	define('UFP_DIR', SI_ROOT . 'uploads/');
}

/**
 * file upload web root
 */
if(!defined('UFP_WEB_DIR')){
	define('UFP_WEB_DIR', 'uploads/');
}

class UFPath{
	
	var $type;
	
	var $basePath = UFP_DIR;
	
	var $baseWebPath = UFP_WEB_DIR;
	
	var $dir;
	
	function UFPath(){
		$this->dir = date('Y-m') . '/';
	}
	
	function root($file = ''){
		return SI_ROOT . $file;
	}
	
	function webRoot($file = ''){
		return $file;
	}
	
	function path(){
		return $this->basePath . $this->dir;
	}
	
	function webPath(){
		return $this->baseWebPath . $this->dir;
	}
	
	function thumbFile($filename){
		return SI_ROOT
			. dirname($filename) 
			. '/thumbs/' 
			.  basename($filename);
	}
	
	function thumbWebFile($filename){
		return ''
			. dirname($filename)
			. '/thumbs/' 
			.  basename($filename);
	}
	
}// end


/**
 * 文件上传
 *
 */
class UFile {
	
	var $path;
	
	var $thumbWidth;
	
	var $thumbHeight;
	
	var $uploader;
    
    var $autothumb = true;
	
	function UFile($path = ''){
		if(!$path){
			$path = new UFPath();
		}
		$this->setPath($path);
		
		$this->uploader =& getInstance('Uploader');
		$this->uploader->setPath($this->path->path());
	}
	
	function & getUploader(){
		return $this->uploader;
	}
    
    function setExts($exts) {
        $this->uploader->setExts($exts);
    }
	
	function setPath($path){
		$this->path = $path;
	}
	
	function setThumbSize($width, $height){
		$this->thumbWidth = $width;
		$this->thumbHeight = $height;
	}
	
	function upload($formname = 'img'){
		if(!isset($_FILES[$formname]) || !$_FILES[$formname]['name']){
			return '';
		}
		
		$file = $this->uploader->upload($formname);
		if(!$file){
			throw(new Exception($this->uploader->getErrMsg()));
		}
        
        if($this->autothumb){// 生成小图
		    $this->thumb($file, $this->thumbWidth, $this->thumbHeight);
        }
        
		return $this->path->webPath() . $file;
	}
    
    function setAutoThumb($flag) {
        $this->autothumb = $flag;
    }
	
	function del($file){
		@unlink($this->path->root($file));
		@unlink($this->path->thumbFile($file));
	}
	
	function display($img){
		return $this->path->webRoot($img);
	}
	
	function thumb($file, $width = 0, $height = 0){
		if(strpos($file, 'http://') === 0){
			return $file;
		}
		
		$filename = $this->path->root($file);			// FilePath
		if(!file_exists($filename)){
			return $filename;
		}
		
		static $thumb;
		if(!isset($thumb)){
			$thumb =& getInstance('ImageThumb');
		}
		
		if($width){
			$thumb->setWidth($width);
		}
		if($height){
			$thumb->setHeight($height);
		}
		
		$imgThumbFile = $this->path->thumbFile($file);	// ThumbFile
		if(!is_dir(dirname($imgThumbFile))){
			mkdir(dirname($imgThumbFile), 0777);
		}
		
		if(file_exists($imgThumbFile) 
			|| $thumb->make($filename, $imgThumbFile)
			){
			return $this->path->thumbWebFile($file);	// ThumbWebFile
		}
		return '';
	}
}

class UFileManagerPlug{
	
	var $ufile;
	
	var $formname = 'img';
	
	var $fieldname = 'img';
	
	var $thumbWidth;

	var $thumbHeight;
	
	var $oimg;
	
	function UFileManagerPlug($ufile = null){
		if(!$ufile){
			$ufile = getUFM();
		}
		$this->setUfile($ufile);
	}
	
	function setUfile($obj){
		$this->ufile = $obj;
	}
	
	function setFormName($name){
		$this->formname = $name;
	}
	
	function setFieldName($name){
		$this->fieldname = $name;
	}
	
	function setThumbSize($width, $height){
		if($width){
			$this->thumbWidth = $width;
		}
		
		if($height){
			$this->thumbHeight = $height;
		}
        
        $this->ufile->setAutoThumb(false);
	}
	
	function makeThumb($file){
		if($this->thumbWidth && $this->thumbHeight){
			$this->ufile->thumb($file, $this->thumbWidth, $this->thumbHeight);
		}
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $params
	 */
	function updateNotify(& $params){
		$ac = $params['_ac'];
		$vars =& $params['_vars'];
		$obj =& $params['_obj'];
		if(method_exists($this, $ac)){
			$this->$ac($vars, $obj);
		}
	}
    
    /**
     * add
     *
     * @param array $params
     */
	function add_pre(& $vars){

		$img = $this->ufile->upload($this->formname);
        $this->oimg['add'] = $img;
		if($img){
			$vars[$this->fieldname] = $img;
			$this->makeThumb($img);
		}
	}
    
    function add_post($vars){
        $oimg = $this->oimg['add'];
        if(!$vars && $oimg){
            $this->ufile->del($oimg);
        }
        unset($this->oimg['add']);
    }
	
	/**
	 * update
	 *
	 * @param array $vars
	 * @param object $obj
	 */
	function update_pre(& $vars, $obj){
		$img = $this->ufile->upload($this->formname);
		if($img){
			$vars['data'][$this->fieldname] = $img; // set img
			$this->makeThumb($img);
			
			$row = $obj->getRow($vars['cond']);
			$this->oimg['update'] = $row[$this->fieldname];
		}
	}
	
	function update_post($vars){
		$oimg = $this->oimg['update'];
		if($vars && $oimg){
			$this->ufile->del($oimg);
		}
		unset($this->oimg['update']);
	}
	
	/**
	 * del
	 *
	 * @param string $vars
	 * @param object $obj
	 */
	function del_pre($vars, $obj){
		$row = $obj->getRow($vars);
		$this->oimg['del'] = $row[$this->fieldname];
	}
	
	function del_post($vars){
		$oimg = $this->oimg['del'];
		if($vars && $oimg){
			$this->ufile->del($oimg);
		}
		unset($this->oimg['del']);
	}
	
}

?>