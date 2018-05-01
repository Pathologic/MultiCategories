<?php namespace MultiCategories;

/**
 * Class Model
 * @package MultiCategories
 */
class Model {
    /** @var \DocumentParser $modx */
    protected $modx = null;
    protected $table = 'site_content_categories';

    /**
     * Model constructor.
     * @param \DocumentParser $modx
     */
    public function __construct(\DocumentParser $modx)
    {
        $this->modx = $modx;
        $this->table = $modx->getFullTableName($this->table);
    }

    /**
     * @param $doc
     * @param array $categories
     * @return bool
     */
    public function save($doc, $categories = array()) {
        $result = false;
        $doc = (int)$doc;
        if ($doc) {
            $q = $this->modx->db->query("SELECT `parent` FROM {$this->modx->getFullTableName('site_content')} WHERE `id`={$doc}");
            $parent = $this->modx->db->getValue($q);
            $existed = $this->getCategories($doc);
            $new = $this->cleanIDs($categories);
            $delete = array_diff($existed, $new);
            if (!empty($delete)) {
                $delete = implode(',', $delete);
                $this->modx->db->query("DELETE FROM {$this->table} WHERE `doc`={$doc} AND `category` IN ({$delete})");
            }
            if (!empty($new)) {
                $sql = "INSERT IGNORE INTO {$this->table} (`doc`, `category`) VALUES ";
                $values = array();
                foreach ($new as $category) {
                    if (!$category || $category == $parent) continue;
                    $values[] = "({$doc},{$category})";
                }
                if ($values) {
                    $sql .= implode(',', $values);
                    $this->modx->db->query($sql);
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @param int $doc
     * @return array
     */
    public function getCategories($doc) {
        $categories = array();
        $doc = (int)$doc;
        if ($doc) {
            $q = $this->modx->db->query("SELECT `category` FROM {$this->table} WHERE `doc`={$doc}");
            if ($this->modx->db->getRecordCount($q)) {
                $categories = $this->modx->db->getColumn('category', $q);
            }
        }

        return $categories;
    }

    /**
     * @param $ids
     * @return bool
     */
    public function remove($ids) {
        $result = false;

        return $result;
    }

    public function createTable() {
        $sql = <<< OUT
CREATE TABLE IF NOT EXISTS {$this->table} (
`doc` int(10) NOT NULL,
`category` int(10) NOT NULL,
UNIQUE KEY `link` (`doc`,`category`) USING BTREE,
KEY `doc` (`doc`),
KEY `category` (`category`)
) ENGINE=MyISAM;
OUT;
        $this->modx->db->query($sql);
    }

    public function cleanIDs($IDs, $sep = ',', $ignore = array())
    {
        $out = array();
        if (!is_array($IDs)) {
            if (is_scalar($IDs)) {
                $IDs = explode($sep, $IDs);
            } else {
                $IDs = array();
                throw new Exception('Invalid IDs list <pre>' . print_r($IDs, 1) . '</pre>');
            }
        }
        foreach ($IDs as $item) {
            $item = trim($item);
            if (is_scalar($item) && (int)$item >= 0) { //Fix 0xfffffffff
                if (!empty($ignore) && in_array((int)$item, $ignore, true)) {
                    $this->log[] = 'Ignore id ' . (int)$item;
                } else {
                    $out[] = (int)$item;
                }
            }
        }
        $out = array_unique($out);

        return $out;
    }
}
