<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') return;
$e = &$modx->event;
if ($e->name == 'OnDocFormRender') {
    include_once(MODX_BASE_PATH . 'assets/plugins/multicategories/lib/plugin.class.php');
    $plugin = new \MultiCategories\Plugin($modx, $modx->getConfig('lang_code'));
    $output = $plugin->render();
    if ($output) $modx->event->addOutput($output);
}
if ($e->name == 'OnDocFormSave' && $modx->isBackend() && isset($_POST['__multicategories'])) {
    include_once(MODX_BASE_PATH . 'assets/plugins/multicategories/lib/model.php');
    $data = new \MultiCategories\Model($modx);
    $categories = !empty($_POST['__multicategories']) && is_scalar($_POST['__multicategories']) ? explode(',', $_POST['__multicategories']) : array();
    $data->save($id, $categories);
}
if ($e->name == 'OnEmptyTrash') {
    if (empty($ids)) return;
    include_once (MODX_BASE_PATH . 'assets/plugins/multicategories/lib/model.php');
    $data = new \MultiCategories\Model($modx);
    $data->remove($ids);
}
