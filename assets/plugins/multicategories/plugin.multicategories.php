<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die();
$e = &$modx->event;
if ($e->name == 'OnDocFormRender') {
    include_once(MODX_BASE_PATH . 'assets/plugins/multicategories/lib/plugin.class.php');
    global $modx_lang_attribute;
    $plugin = new \MultiCategories\Plugin($modx, $modx_lang_attribute);
    $output = $plugin->render();
    if ($output) $e->output($output);
}
if ($e->name == 'OnDocFormSave') {
    include_once(MODX_BASE_PATH . 'assets/plugins/multicategories/lib/model.php');
    $data = new \MultiCategories\Model($modx);
    $categories = array();
    if (!empty($_POST['__multicategories']) && is_scalar($_POST['__multicategories'])) {
        $categories = explode(',', $_POST['__multicategories']);
    }
    $data->save($id, $categories);
}
if ($e->name == 'OnEmptyTrash') {
    if (empty($ids)) return;
    $where = implode(',', $ids);
    $modx->db->delete($modx->getFullTableName('site_content_categories'), "`doc` IN ({$where}) OR `category` IN ({$where})");
}
