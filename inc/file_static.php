<?php

class File
{
	function read($fileName)
	{ 
		if(!file_exists($fileName)){
			return false;
		}
		$fd = fopen($fileName, r);
		while($bufline = fgets($fd, 4096)){ 
			$buf .= $bufline;
		}
		fclose($fd);
		return $buf;
	}

	function write($fileName, $data, $method = "w")
	{ 
		if($file_handle = @fopen($fileName, $method)){
			if(flock($file, LOCK_EX)){
				$flag = fwrite($file_handle, $data);
				flock($file, LOCK_UN);
			}
		}
		@fclose($file);
		return $flag;
	}

	function dir_create($dir = '')
	{
		if (!is_dir($dir)){
			$temp = explode('/', $dir);
			$oldumask = umask(0);
			$cur_dir = '';
			for($i=0; $i < count($temp); $i++){
				$cur_dir .= $temp[$i] . '/';
				if (!is_dir($cur_dir) && !file_exists($cur_dir)){
					mkdir($cur_dir, 0777);
				}
			}
			umask($oldumask);
			return true;
		}
		return false;
	}
	
	function dir_del($directory)
	{ 
		$mydir = dir($directory); 
		while(false !== ($file = $mydir->read())){ 
			if((is_dir("$directory/$file")) AND ($file != ".") AND ($file != "..")){ 
				dir_del("$directory/$file"); 
			}else{ 
				if(($file != ".") AND ($file != "..")){ 
					unlink("$directory/$file");
				}
			}
		}
		$mydir->close();
		rmdir($directory);
	}
	
	function get_expand_name($file)
	{
		$nameArray = explode(".", $file);
		if(count($nameArray) == 1){
			return false;
		}
		return $nameArray[count($nameArray) - 1];
	}
	
	function get_rand_name($oFileName)
	{
		$nFileName = date("ymdHis") . mt_rand(1, 10);
		$oFileNameArray = explode(".", $oFileName);
		if(count($oFileNameArray) < 2){
			return $nFileName;
		}
		return  $nFileName . '.' . $oFileNameArray[count($oFileNameArray)-1];
	}
	
	function traverse($directory, $callback, $type = "file")
	{
		if(!function_exists($callback)){
			return false;
		}
		$mydir = dir($directory); 
		while(false !== ($file = $mydir->read())){
			if((is_dir("{$directory}/{$file}")) && ($file != ".") && ($file != "..")){
				if($type == "dir" || $type == "all"){
					$callback("{$directory}/{$file}", "dir");
				}
				File::traverse("{$directory}/{$file}", $callback, $type);
			}else{
				if(($type == "file" || $type == "all") && ($file != ".") && ($file != "..")){
					$callback("{$directory}/{$file}", "file");
				}
			}
		}
		$mydir->close();
		return true;
	}
	
}//end class

?>