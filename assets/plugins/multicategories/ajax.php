<?php
define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

include_once(__DIR__."/../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
if(!isset($_SESSION['mgrValidated'])){
    die();
}
$modx->invokeEvent('OnManagerPageInit',array('invokedBy'=>'MultiCategories'));
if (isset($modx->pluginCache['MultiCategoriesProps'])) {
	$modx->event->params = $modx->parseProperties($modx->pluginCache['MultiCategoriesProps'], 'MultiCategories', 'plugin');
} else {
	die();
}
$params = $modx->event->params;

$roles = isset($params['role']) ? explode(',',$params['role']) : false;
if ($roles && !in_array($_SESSION['mgrRole'], $roles)) die();

$mode = (isset($_REQUEST['mode']) && is_scalar($_REQUEST['mode'])) ? $_REQUEST['mode'] : null;
$out = null;
$controllerClass = isset($modx->event->params['controller']) ? $modx->event->params['controller'] : '';
if (empty($controllerClass) || !class_exists($controllerClass)) {
    require_once (MODX_BASE_PATH . 'assets/plugins/multicategories/lib/controller.php');
    $controllerClass = '\MultiCategories\Controller';
}
$controller = new $controllerClass($modx);
if($controller instanceof \MultiCategories\Controller){
	if (!empty($mode) && method_exists($controller, $mode)) {
		$out = call_user_func_array(array($controller, $mode), array());
	}else{
		$out = call_user_func_array(array($controller, 'load'), array());
	}
	$controller->callExit();
}
echo ($out = is_array($out) ? json_encode($out) : $out);
