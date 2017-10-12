<?php

function &getUFM(){
	static $obj;
	if(!isset($obj)){
		require_once SI_INC . 'ufile_manager.php';
		$obj = new UFile(new UFPath());
	}
	return $obj;
}

function & getUFMPlug($ufile = null){
	require_once SI_INC . 'ufile_manager.php';
	$obj = new UFileManagerPlug($ufile);
	return $obj;
}

/**
 * Enter description here...
 *
 */
function printPageInfo(){
	$pager =& getPager();
	echo '<div class="pages">' .  $pager->getHtml() . '</div>';
}

function printErr($msg){
	if($msg){
		echo '<p class="err">' . $msg . '</p>';
	}
}
/**
* put your comment there...
* 
* @param mixed $header
* @param mixed $info
* @param mixed $err
* 
* @return HtmlForm
*/
function initedForm($header, $info, $err = ''){   
	$form =& getInstance('HtmlForm');
	$form->setErr($err);
	$form->setData($info);
	$form->setHeader($header);
	return $form;
}

/**
* put your comment there...
* 
* @param mixed $header
* @param mixed $list
* @param mixed $Ctrl
* @return HtmlTableEditor
*/
function initedEditor($header, $list, $Ctrl){
	$table =& getInstance('HtmlTable');
	$table->setWrapId('listEditor');
	$table->setHeader($header);
	$table->setShowStyle(false);
	$table->appendOrderString(aurl($Ctrl));
	$table->setData($list);
	
	$editor =& getInstance('HtmlTableEditor');
	$editor->setShowSelHtmlEl(true);
	$editor->setTable($table);
	$editor->setPostUrl(aurl($Ctrl, 'ajax'));
    
    $editor->setEditPattern('<a href="' . aurl($Ctrl, 'edit', 'id', '[id]') . '">编辑</a>');
    $editor->setDelPattern('<a class="del" href="' . aurl($Ctrl, 'del', 'id', '[id]') . '">删除</a>');
	return $editor;
}

function aurl(){
    $args = func_get_args();
    if(count($args) < 1){
        return '';
    }
    
    $ctrl = array_shift($args);
    $url = "?q={$ctrl}";
    
    if($args && $action = array_shift($args)){
        $url .= "-{$action}";
    }
    
    if($args)
        $url .= call_user_func_array('aurlParams', $args);
    
    return $url;
}

function aurlParams(){
    $args = func_get_args();
    $url = '';
    for($i=0, $j=count($args); $i < $j; $i++){
        $url .= '&' . $args[$i] . '=' . $args[++$i];
    }
    return $url;
}


/**
 * Enter description here...
 *
 * @param unknown_type $vars
 * @param unknown_type $sel
 */
 
function asmenu($vars, $sel){
	$html = '<div id="columnTtl">';
	foreach($vars as $key => $menu){
		$classname = $key != $sel ? ' class="normal"' : '';
		$html .= '<h3' . $classname . '><a href="' . $menu[1] . '">' . $menu[0] . '</a></h3>';
	}
	
	$html .= '<div class="cls"></div>';
	$html .= '</div>' . "\n";
	
	echo $html;
}

function insertJs($name, $echo = true){
	$fileMapping = array(
		'dateInput' => 'js/dateInput/dateInput.js',
		'majax' => 'js/majax.js',
        'ckfinder' => 'fcks/ckfinder/ckfinder.js',
        'jquery' => 'js/jquery/jquery.min.js',
	);
	if(!isset($fileMapping[$name])){
		return false;
	}
	
	static $loaded;
	if(!isset($loaded[$name])){
		$loaded[$name] = true;
		$js = '<script type="text/javascript" src="%s"></script>';
		$js = sprintf($js, $fileMapping[$name]);
		if($echo){
			echo $js . "\n";
		}
		return $js;
	}
	return '';
}

function printMsg($msg){
	if($msg){
		echo '<p class="err">' . $msg . '</p>';
	}
}

/*function getEditor($filedName = 'content', $value = '', 
    $width = "96%", $height = "500",    
    $skinId = 2, $basePath = "/fck/"
    ){
    
    $basePath = SI_ROOT_WEB . 'fck/';
    require_once(SI_ROOT . '/fck/fckeditor.php');
    $skins = array("default", "office2003", "silver");
    $skinPath = $basePath . 'editor/skins/' . $skins[$skinId] . '/';
    $oFCKeditor = new FCKeditor($filedName);
    $oFCKeditor->BasePath = $basePath;
    $oFCKeditor->Config['SkinPath'] = $skinPath;
    $oFCKeditor->Width = $width;
    $oFCKeditor->Height = $height;
    $oFCKeditor->Value = $value;
    $oFCKeditor->Create();
}*/

function getEditor($filedName = 'content', $value = ''
    ){
    
    
    require_once(SI_ROOT . '/fcks/ckeditor.php');
    $oFCKeditor = new CKEditor;
    $basePath = SI_ROOT_WEB . 'fcks/';
    $oFCKeditor->basePath = $basePath;
    $oFCKeditor->config['width'] = 600;
    $oFCKeditor->editor($filedName, $value);
}
function photoUpload($name, $src, $width = 200) {
	if(!$width){
		$width = 200;
	}
	$html .= HtmlElement::file($name);
	if($src){
		$html .= '<br/>' . image(array('src'=>$src, 'width'=>$width, 'none'=>1));
	}
	return $html;
}

?>
