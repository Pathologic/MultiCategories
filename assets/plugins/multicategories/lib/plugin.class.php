<?php namespace MultiCategories;

include_once(MODX_BASE_PATH . 'assets/lib/SimpleTab/plugin.class.php');
use \SimpleTab\Plugin as SimplePlugin;
/**
 * Class sgPlugin
 * @package SimpleGallery
 */
class Plugin extends SimplePlugin
{
    public $pluginName = 'MultiCategories';
    public $tpl = 'assets/plugins/multicategories/tpl/multicategories.tpl';
    public $jsListDefault = 'assets/plugins/multicategories/js/scripts.json';
    public $jsListCustom = 'assets/plugins/multicategories/js/custom.json';
    public $cssListDefault = 'assets/plugins/multicategories/css/styles.json';
    public $cssListCustom = 'assets/plugins/multicategories/css/custom.json';

    /**
     * @return array
     */
    public function getTplPlaceholders()
    {
        include_once(MODX_BASE_PATH . 'assets/plugins/multicategories/lib/model.php');
        $data = new Model($this->modx);
        $ph = array(
            'lang'         => $this->lang_attribute,
            'categories'   => implode(',', $data->getCategories($this->params['id'])),
            'url'          => $this->modx->config['site_url'] . 'assets/plugins/multicategories/ajax.php',
            'site_url'     => $this->modx->config['site_url'],
            'manager_url'  => MODX_MANAGER_URL,
        );

        return array_merge($this->params, $ph);
    }

    /**
     * @return bool
     */
    public function checkTable()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function createTable()
    {
        include_once (MODX_BASE_PATH . 'assets/plugins/multicategories/lib/model.php');
        $data = new Model($this->modx);
        $data->createTable();

        return true;
    }
}
