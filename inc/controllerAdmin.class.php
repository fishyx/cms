<?php


class CtrlDispatchAdmin extends CtrlDispatch {
    var $ctrlPath = SI_ADMIN;
    function getCtrlName(){    
        return 'Ctrl' . ucfirst($this->params->ctrl) . 'Admin';
    }
}

/**
 * admin action
 */
class CtrlActionAdmin extends CtrlAction {
    
    var $_defOrderField;
    
    var $userid;
    
    /**
    * put your comment there...
    * 
    * @var Dba
    */
    var $mod;
    
    function __construct($action, $view){
        parent::CtrlAction($action, $view);
    }
    
    function ActionAjax(){
        $id = intval($_REQUEST['id']);
        $f = format($_REQUEST['field']);
        $v = $_REQUEST['value'];
        //$v = format($_REQUEST['value']);
        //$v = mb_convert_encoding($v, 'utf-8', 'gbk');
        if(!$id || !$f || ($f == 'title' && !$v)){
            exit('Iil args');
        }
        if(!$this->mod->updateField($f, $v, $id)){
            //print_r($this->mod->getErr());
            exit('update fail');
        }
        exit;
    }
    
    function ActionAdd(){
        $nextAc = 'insert';
        $this->view->setVar('info', $_POST);
        $this->view->setVar('nextAc', $nextAc);
        $this->setTpl('form');
        $this->setVar('Action', 'add');
    }
    
    function ActionEdit(){
        $nextAc = 'update';
        if(!$_POST){
            $id = intval($_REQUEST['id']);
            $info = $this->mod->getRow($id);
            $info['id'] = $id;
        }else{
            $info = $_POST;
        }
        $this->view->setVar('info', $info);
        $this->view->setVar('nextAc', $nextAc);
        $this->setTpl('form');
        $this->setVar('Action', 'edit');
    }
    
    function ActionInsert(){
        $_POST['cdate'] = date('Y-m-d H:i:s');
        $flag = $this->mod->add($_POST);
        
        if(!$flag){
            $err = $this->mod->getErr();
            track(array('err'=>$err, 'post'=>$_POST), 'Insert Error');
            
            $msg = implode(',', $err);
            $this->view->setVar('err', $err);
            $this->view->setVar('msg', '操作失败 ' . $msg);
            $this->ActionAdd();
            return ;
        }
        $this->view->setVar('msg', '操作成功');
        $this->ActionIndex();
    }
    
    function ActionUpdate(){
        if(isset($_POST['img_del']) && $_POST['img_del']){
            $_POST['img'] = '';
           $imgFile = $this->mod->getFiled('img', 1);
           if($imgFile){
               unlink($imgFile);
           }
        }
        $flag = $this->mod->update($_POST, intval($_REQUEST['id']));
               
        if(!$flag){
            $err = $this->mod->getErr();
            track(array('err'=>$err, 'post'=>$_POST), 'Update error');
            
            $this->view->setVar('err', $err);
            $this->view->setVar('msg', '操作失败');
            $this->ActionEdit();
            return ;
        }
        $this->view->setVar('msg', '操作成功');
       // $this->ActionIndex();
       redirect(aurl($this->getName()));
    }
    protected function getName(){
        return strtolower(preg_replace('/Ctrl(.+?)Admin/i', '$1', get_class($this)));
    }    
    function ActionDel(){
        if(!$this->mod->del(intval($_REQUEST['id']))){
            $msg = '操作失败';
        }else{
            $msg = '操作成功';
        }
        $this->view->setVar('msg', $msg);
        $this->ActionIndex();
    }
    
    function ActionIndex(){
        $cond = $this->getCond();
        $this->setSearch($cond);
        $this->setOrder($cond);
        
        $pager =& getPager();

        $list = $this->mod->getAll($pager, $cond);
        $this->_postDataList($list);
        
        $this->setV('list', $list);
        $this->setV('Action', 'index');
        $this->setTpl('list');
    }
    
    function &getCond(){
        return getCond();
    }
    
    function &setSearch(& $cond){
        $f = format($_REQUEST['f']);
        $v = format($_REQUEST['v']);
        if($f && $v){
            if($f == 'id'){
                $cond->eq($f, $v);
            }else{
                $cond->like($f, $v);
            }
        }
    }
    
    function setOrder(& $cond){
        $order = HtmlTable::getReqOrderFiled();
        if(!$order && $this->_defOrderField){
            $order = $this->_defOrderField;
        }
        if($order){
            $cond->orderby($order, HtmlTable::getReqOrderWay());
        }
    }
    
    function _postDataList(){}
    
    function setTpl(){       
        $this->view->setTpl(strtolower($this->Ctrl));
    }
    
}

/**
 *  category actions
 */
class CtrlActionCategory extends CtrlAction {
    
    var $op;
    var $tpl;
    /**
    * put your comment there...
    * 
    * @var CategoryApp
    */
    var $mod;
    var $ctrl;
    var $nameKey;
    
    function init(){
        die('rewirte this method');
    }

    function ActionIndex(){
        
        $catid = intval($_REQUEST['catid']);
        
        $htmlBuilder = $this->mod->getHtml();
        $htmlBuilder->setLinkParttern('<a href="' . $this->op . '&catid=[id]">[name]</a>');
        
        $this->setVar('cid', $catid);
        $this->setVar('cat', $this->mod);
        $this->setVar('path', $htmlBuilder->path($catid));
        
        $this->setVar('Action', 'index');
        $this->setTpl($this->tpl);
    }
    
    function ActionInsert(){
        $_POST = format($_POST);
        
        if($this->mod->insert($_POST)){
            $this->setVar('msg', '操作成功');
        }else{
            $this->setVar('msg', $this->mod->err);
        }
        
        $_REQUEST['catid'] = $_REQUEST['pid'];
        $this->init();
        $this->ActionIndex();
    }
    
    function ActionEdit(){
        $nextAc = 'update';
        if(!$_POST){
            $id = intval($_REQUEST['id']);
            $info = $this->mod->getRow($id);
            $info['id'] = $id;
        }else{
            $info = $_POST;
        }
        
        $this->setV('cat', $this->mod);
        $this->view->setVar('info', $info);
        $this->view->setVar('nextAc', $nextAc);
        $this->setTpl($this->tpl);
        $this->setVar('Action', 'edit');
    }
    
    function ActionUpdate(){
        $_POST = format($_POST);
        if($this->mod->update($_POST, intval($_REQUEST['id']))){
            $this->setVar('msg', '操作成功');
            
            $_REQUEST['catid'] = $_REQUEST['pid'];
            $this->init();
            $this->ActionIndex();
        }else{
            $this->setVar('msg', $this->mod->err);
            $this->ActionEdit();
        }
    }
    
    function ActionDel(){
        $cid = intval($_REQUEST['id']);
        if($this->mod->del($cid)){
            $this->setVar('msg', '操作成功');
        }else{
            $this->setVar('msg', $this->mod->err);
        }
        $this->init();
        $this->ActionIndex();
    }
    
    function ActionAjax(){
        $id = intval($_REQUEST['id']);
        $f = format($_REQUEST['f']);
        $v = format($_REQUEST['v']);
        
        if(!$id || !$f || ($f == $this->nameKey && !$v)){
            exit('invalid args');
        }
        
        $data = array($f=>$v);
        if(!$this->mod->update($data, $id)){
            exit($this->mod->err);
        }
        exit;
    }
}


?>
