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
        "api"         => 1,
        "addWhereList" => 'c.isfolder = 1',
        "parents"     => 0,
        "hideSubMenus" => 1,
        "selectFields" => 'id,isfolder,parent,pagetitle,menutitle'
    );

    /**
     * constructor.
     * @param \DocumentParser $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->modx = $modx;
        $this->data = new Model($modx);


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
        $this->dlParams['parents'] = $id;
        $this->dlParams['prepare'] = function(array $data = array()) use ($openIds) {
            $data['text'] = $data['title'];
            $data['state'] = $data['isfolder'] == 1 && !isset($data['children']) ? 'closed' : 'open';
            if (in_array($data['id'], $openIds)) $data['checked'] = true;

            return $data;
        };
        $out = json_decode($this->modx->runSnippet('DLMenu', $this->dlParams), true);

        return $out[0];
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
