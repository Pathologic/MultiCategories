<?php namespace MultiCategories;

include_once (MODX_BASE_PATH . 'assets/plugins/multicategories/lib/model.php');

/**
 * Class Controller
 * @package MultiCategories
 */
class Controller
{
    protected $modx = null;
    protected $data = null;
    public $output = '';
    public $isExit = false;

    public $dlParams = array(
        'addWhereList'   => 'c.isfolder = 1 AND c.deleted = 0',
        'parents'        => 0,
        'showParent'     => 1,
        'hideSubMenus'   => 1,
        'titleField'     => 'text',
        'selectFields'   => 'id,isfolder,parent,pagetitle,menutitle',
        'returnDLObject' => 1
    );

    /**
     * constructor.
     * @param \DocumentParser $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->modx = $modx;
        $this->data = new Model($modx);
        if (!empty($modx->event->params['parents'])) {
            $this->dlParams['parents'] = $modx->event->params['parents'];
        }
    }

    /**
     * @return mixed
     */
    public function load() {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $rid = isset($_POST['rid']) ? (int)$_POST['rid'] : 0;
        $openIds = $this->data->getCategories($rid);
        if (!$id && $openIds) {
            $this->dlParams['openIds'] = implode(',', $openIds);
        }
        if ($id) {
            $this->dlParams['parents'] = $id;
            $this->dlParams['showParent'] = 0;
        }
        $this->dlParams['prepare'] = function(array $data = array()) use ($openIds) {
            $data['state'] = $data['isfolder'] == 1 && !isset($data['children']) ? 'closed' : 'open';
            if (in_array($data['id'], $openIds)) $data['checked'] = true;
            $data['text'] = \APIHelpers::e($data['text']);
            
            return $data;
        };
        $dl = $this->modx->runSnippet('DLMenu', $this->dlParams);
        $out = [];
        foreach($dl->getMenu() as $menu) {
            $out = array_merge($out, $menu);
        }

        return $out;
    }

    /**
     *
     */
    public function callExit()
    {
        if ($this->isExit) {
            echo $this->output;
            exit;
        }
    }
}
