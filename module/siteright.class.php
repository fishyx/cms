<?php
  loadClass('Dba');
  class Siteright extends Dba{
      var $_tableName = 'right';
      function buildRights(){
    if(!defined('SI_ADMIN')){
        define('SI_ADMIN', str_replace('\\','/',SI_ROOT . 'admin/'));
    }
    $dir = opendir(SI_ADMIN);
    while (($file = readdir($dir)) !== false)
      {
        if (!preg_match('/^\.+$/', $file)) {
            if(is_dir($file)) {
                $dirs[] = $file;
            } else {
                $files[] = $file;
            }
        }
      }
      closedir($dir);
    foreach($files as $filename){
        if(preg_match('/^.+\.ctrl.php$/i',$filename)){
            $ctrls[] = $filename;
        }else{
            $other[] = $filename;
        }
    }
    $ext = '.ctrl.php';
    foreach($ctrls as $file){
        $fp = fopen(SI_ADMIN . $file,'r');
        if($fp){
        $buffer = ''; $i = 0;
        while(!feof($fp)){
            $buffer .= stream_get_line($fp,30);
        }
        fclose($fp);
        $item = array();
    if(preg_match('#\*\s*actions\{\{(.+?)\}\}#s', $buffer, $r)){
        // * actions{{ }}
        $action = array();
        if(preg_match_all('/doc\s*:\s*action\s*:\s*(.+?)\s*=\s*(.+)/', $r[1], $d)){
            // doc : action : name = value
            foreach ($d[1] as $i => $k) {
                $action[$k] = trim($d[2][$i]);
            }
        }
        $ctrl = str_replace($ext, '', $file);
        if(preg_match('/doc\s*:\s*title\s*:\s*(.+)/', $r[1], $t)){
            // doc : title : value
            $title = trim($t[1]);
        }else{
            $title = $ctrl;
        }
        
        if(preg_match('/doc\s*:\s*group\s*:\s*(.+)/', $r[1], $t)){
            // doc : group : value
            $group = trim($t[1]);
        }else{
            $group = 'default';
        }
        $item['title'] = $title;
        $item['group'] = $group;
        $item['action'] = $action;
        $actions[$ctrl] = $item;
    }
    }
    }
    foreach($actions as $ctrl =>$action){

    $item = array();
    $item['action'] = $ctrl;
    $item['name'] = $action['title'];
    $item['groups'] = $action['groups'];
    $item['enable'] = 1;
    if(preg_match('#\[(.+?)\]#', $item['name'], $r)){
        $item['name'] = $r[1];
    }
    $rightId = $this->getRow("And action = '$ctrl'");
    if(!$rightId){
        $flag = $this->add($item);
    }else{
        $flag = $this->update($item,"And id = {$rightId['id']}");
    }
      }
      if($flag){
          return true;
      }else{
          return false;
      }
  }
  }
?>
