<?php

define('IMG_ERR_NOGD',				2);
define('IMG_ERR_NOT_EXISTS',		3);
define('IMG_ERR_INVALID_IMAGE', 	7);

class ImageToolAbs{
    
    function setError($code){
    	$this->errorCode = $code;
		switch ($code) {
			case IMG_ERR_NOGD :
				$this->errorMsg = 'GD库未安装';
				break;
				
			case IMG_ERR_NOT_EXISTS :
				$this->errorMsg = '图像文件不存在';
				break;

			case IMG_ERR_INVALID_IMAGE :
				$this->errorMsg = '图像源错误';
				break;
			
			default : 
				$this->errorMsg = '未知错误';
		}
    }
    
    function save(& $source_handle, $target){
    	$ext = pathinfo($target);
    	$ext = $ext['extension'];
    	
    	switch ($ext){
    		case 'jpg':
    		case 'JPG':
				imagejpeg($source_handle, $target);
				break;
			case 'png':
			case 'PNG':
				imagepng($source_handle,  $target);
				break;
			case 'gif':
			case 'GIF':
				imagegif($source_handle,  $target);
				break;
			default:	
                $this->setError(IMG_ERR_NONSUPPORT_TYPE);
                imagedestroy($source_handle);
                return false;
    	}
    	
    	imagedestroy($source_handle);
    	return true;
    }
    
    function getResource($img_file, $mime_type){
        switch ($mime_type){
            case 1:
            case 'image/gif':
                $res = imagecreatefromgif($img_file);
            	break;
            	
            case 2:
            case 'image/pjpeg':
            case 'image/jpeg':
                $res = imagecreatefromjpeg($img_file);
            	break;
            	
            case 3:
            case 'image/x-png':
            case 'image/png':
                $res = imagecreatefrompng($img_file);
            	break;
            	
            default:
            	return false;
        }
        return $res;
    }
    
    function gdVersion(){
        static $version = -1;
        if ($version >= 0){
            return $version;
        }
        
        if (!extension_loaded('gd')){
            $version = 0;
        }else{
            if (PHP_VERSION >= '4.3' && function_exists('gd_info')){
                $match = array();
            	$ver_info = gd_info();
                preg_match('/\d/', $ver_info['GD Version'], $match);
                $version = $match[0];
            }else{
                if (function_exists('imagecreatetruecolor')){
                    $version = 2;
                }elseif (function_exists('imagecreate')){
                    $version = 1;
                }
            }
        }
        return $version;
     }
}

if(!defined('IMG_THUMB_WIDTH')){
	define('IMG_THUMB_WIDTH', 200);
}

if(!defined('IMG_THUMB_HEIGHT')){
	define('IMG_THUMB_HEIGHT', 200);
}

class ImageThumb extends ImageToolAbs{
	
	var $width = IMG_THUMB_WIDTH;
	
	var $height = IMG_THUMB_HEIGHT;
	
	function setWidth($width){
		$this->width = intval($width);
	}
	
	function setHeight($height){
		$this->height = intval($height);
	}

	function make($img, $tarimg){
		$gd = $this->gdVersion(); 
		if ($gd == 0){
			$this->setError(IMG_ERR_NOGD);
			return false;
		}
		
		if(!($this->width && $this->height)){
			return false;
		}
		
		// get file info
        $org_info = @getimagesize($img);
        if (!$org_info){
            $this->setError(IMG_ERR_NOT_EXISTS);
            return false;
        }
        
        $img_org = $this->getResource($img, $org_info['mime']);
        if(!$img_org){
        	$this->setError(IMG_ERR_INVALID_IMAGE);
        	 return false;
        }
        
        $scale_org = $org_info[0] / $org_info[1];
		
		// thumb size
        $thumb_width = $this->width;
        $thumb_height = $this->height;
        if(!$thumb_width && !$thumb_height){
        	$thumb_width = IMG_THUMB_WIDTH;
        	$thumb_height = IMG_THUMB_HEIGHT;
        }
        
        if ($thumb_width == 0){
            $thumb_width = $thumb_height * $scale_org;
        }
        if ($thumb_height == 0){
            $thumb_height = $thumb_width / $scale_org;
        }
        
        // dst size
        if ($scale_org > 1){
            $lessen_width  = $thumb_width;
            $lessen_height = $thumb_width / $scale_org;
        }else{
            $lessen_width  = $thumb_height * $scale_org;
            $lessen_height = $thumb_height;
        }

        // dst start position
        $dst_x = ($thumb_width  - $lessen_width)  / 2;
        $dst_y = ($thumb_height - $lessen_height) / 2;
        
        $img_thumb = $this->createThumbRes($thumb_width, $thumb_height, $gd);
        if ($gd == 2){
            @imagecopyresampled(
            	$img_thumb, $img_org, 
            	$dst_x, $dst_y, 0, 0, 
            	$lessen_width, $lessen_height, $org_info[0], $org_info[1]
            );
        } else {
            @imagecopyresized(
            	$img_thumb, $img_org, 
            	$dst_x, $dst_y, 0, 0, 
            	$lessen_width, $lessen_height, $org_info[0], $org_info[1]
            );
        }
        
        imagedestroy($img_org);
        
        return $this->save($img_thumb, $tarimg);
	}
	
	// create thumb image
	function &createThumbRes($width, $height, $gd){
        if ($gd == 2){
            $img_thumb  = imagecreatetruecolor($width, $height);
        } else {
            $img_thumb  = imagecreate($width, $height);
        }
		
        $clr = imagecolorallocate($img_thumb, 255, 255, 255);
        imagefilledrectangle($img_thumb, 0, 0, $width, $height, $clr); // bgcorlor 
        return $img_thumb;
	}
}

if(!defined('IMG_WATERMARK_ALPHA')){
	define('IMG_WATERMARK_ALPHA', 65);
}

if(!defined('IMG_WATERMARK_PLACE')){
	define('IMG_WATERMARK_PLACE', 5);
}

class ImageWatermark extends ImageToolAbs {
	
	var $place = IMG_WATERMARK_PLACE;
	
	var $alpha = 65;
	
	var $watermark;
	
	function setAlpha($alpha){
		$this->alpha = intval($alpha);
	}
	
	function setWatermark($watermark){
		$this->watermark = $watermark;
	}
	
	function setPlace($place){
		$this->place = intval($place);
	}
	
	function make($filename, $targetfile){
        $gd = $this->gdVersion();
        if ($gd == 0){
            $this->setError(IMG_ERR_NOGD);
            return false;
        }
        
        $watermark = $this->watermark;
        if (!file_exists($watermark)){
            $this->setError(IMG_ERR_NOT_EXISTS);
            return false;
        }

        if ((!file_exists($filename)) || (!is_file($filename))){
            $this->setError(IMG_ERR_NOT_EXISTS);
            return false;
        }

        // water resource
        $watermark_info     = @getimagesize($watermark);
        $watermark_handle   = $this->getResource($watermark, $watermark_info[2]);
        if (!$watermark_handle){
            $this->setError(IMG_ERR_INVALID_IMAGE);
            return false;
        }

        // src resource
        $source_info    = @getimagesize($filename);
        $source_handle  = $this->getResource($filename, $source_info[2]);
        if (!$source_handle){
            $this->setError(IMG_ERR_INVALID_IMAGE);
            return false;
        }

        // get position
        switch ($this->place){
            case '1':
                $x = 0;
                $y = 0;
                break;
            case '2':
                $x = $source_info[0] - $watermark_info[0];
                $y = 0;
                break;
            case '4':
                $x = 0;
                $y = $source_info[1] - $watermark_info[1];
                break;
            case '5':
                $x = $source_info[0] - $watermark_info[0];
                $y = $source_info[1] - $watermark_info[1];
                break;
            default:
                $x = $source_info[0]/2 - $watermark_info[0]/2;
                $y = $source_info[1]/2 - $watermark_info[1]/2;
        }
		
		// process	watermark	
		imagecopymerge($source_handle, 
			$watermark_handle, 
			$x, $y, 0, 0,
            $watermark_info[0], 
            $watermark_info[1], 
            $this->alpha
        );
        
		imagedestroy($watermark_handle);
		
        return $this->save($source_handle, $targetfile);
    }
}

?>