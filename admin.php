<?php
define('IN_ADMIN', true);
define('DEBUG', false);
define('DEBUG_TRACK', false);
require '_init.inc.php';
require SI_INC . 'session.inc.php';
define('LANG', $lang);
if(LANG == 'en'){
    define('TABLE_PRE_LNG', 'en_');
}
require SI_INC . 'cnfdb.class.php';
require SI_INC . 'comm.func.php';
require SI_INC . 'admin.func.php';
require SI_INC . 'file_static.php';
require SI_INC . 'html/tableEditor.php';
require SI_INC . 'html/element.php';
require SI_INC . 'controller.class.php';
require SI_INC . 'controllerAdmin.class.php';
require SI_INC . 'view.php';
require SI_INC . 'viewRenderPHP.php';
require SI_INC . 'app.func.php';
 
$_REQUEST = format($_REQUEST, array(), _ALLOWHTML);
$_POST = format($_POST, null, _ALLOWHTML);
if(!defined('SI_ADMIN')){
    define('SI_ADMIN', SI_ROOT . 'admin/');
}
/**
 * Enter description here...
 *
 */
class Admin{
    /**
     * front app entry
     *
     */
    function main(){
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $router = new CtrlRoute();
        $params = $router->route($request);
        /**
        * put your comment there...
        * 
        * @var Adminuser
        */
        $site = getDTable('adminuser');
        if(isset($params->ctrl) && in_array($params->ctrl, array('login', 'logout'))){
            $params->action = $params->ctrl;
            $params->ctrl = 'Adminuser';
        }elseif(!$site->checkLogin()){
            $params->ctrl = 'Adminuser';
            $params->action = 'login';
        }elseif($site->checkRight($params->ctrl)){
            $params->ctrl = 'Adminuser';
            $params->action = 'offside';
        }

        if($params->ctrl != 'Adminuser'){
            $userInfo = $site->getLoginedUser();
            $response->set('Navs', $site->getUserMenus());
        } 
        $dispatch = new CtrlDispatchAdmin($params);
        $dispatch->dispatch($params, $response); 
        $viewRender = new ViewRenderAdmin($response);
        $viewRender->setTplPath(SI_ADMIN . 'views/');
        $viewRender->render();
    }
    
    function getRequest(){
        // request url
        $request = (object)format($_REQUEST);
        $request->url = gav($_GET, 'q');
       // $request->url = format($request->url);
        return $request;
    }
    
    function getResponse(){
        $response = new View();
        $response->setCharset('utf-8');
        $response->setLayout('_layout');
        $response->setErrTpl('_err');
        $response->set('_CSS', SI_ROOT_WEB . 'admin/css/');
        $response->set('_IMG', SI_ROOT_WEB . 'admin/images/');
        $response->set('php_self', $_SERVER['PHP_SELF']);
        $response->set('formAction', '?');
        return $response;
    }
}

class ViewRenderAdmin extends ViewRenderPHP {
    function fileNotFound($file){
        echo 'Error:' . $file . ' not found <br />';
        exit;
    }
}

/**
 * main();
 */
$cnf = array();
$cnf['defCtrl'] = 'index';
$cnf['defAction'] = 'index';
Configs::set($cnf);
$evt = new Admin();
$evt->main();

?>
