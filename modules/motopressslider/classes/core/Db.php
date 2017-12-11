<?php
/**
 * 2014-2016 MotoPress Slider
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPLv2)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl2.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade MotoPress Slider to newer
 * versions in the future.
 *
 * @author    MotoPress <marketing@getmotopress.com>
 * @copyright 2014-2016 MotoPress
 * @license   http://www.gnu.org/licenses/gpl2.html GNU General Public License (GPLv2)
 */

class MPSLDb
{
    const DB_ENGINE = _MYSQL_ENGINE_;
    const DB_PREFIX = _DB_PREFIX_; // "ps_"
    const TABLE_PREFIX = MotoPressSlider::PREFIX; // "mpsl_"

    const SLIDER_TABLE = 'mpsl_slider';
    const SLIDER_ID = 'id_slider';

    const SLIDE_TABLE = 'mpsl_slide';
    const SLIDE_ID = 'id_slide';

    const PREVIEW_SLIDE_TABLE = 'mpsl_slide_preview';
    const PREVIEW_SLIDE_ID = 'id_slide_preview';

    const LAYER_TABLE = 'mpsl_layer';
    const LAYER_ID = 'id_layer';

    const ATTACHMENT_TABLE = 'mpsl_attachment';
    const ATTACHMENT_ID = 'id_attachment';

    const PARENT_ID = 'id_parent';

    /**
     * @var MotoPressSlider MotoPress Slider object.
     */
    protected $module = null;

    /**
     * @var DbCore PrestaShop database object.
     */
    private $db = null;

    // Default values for PrestaShop database object.
    private $use_cache = true;
    private $add_prefix = true;
    private $null_values = false;
    private $return_array = true;

    public function __construct($module, $db, $use_cache)
    {
        $this->module = $module;
        $this->db = $db;
        $this->use_cache = $use_cache;
    }

    /**
     * Executes a query.
     * @param string|DbQuery $sql
     * @return bool
     */
    public function execute($sql)
    {
        return $this->db->execute($sql, $this->use_cache);
    }

    /**
     * Executes return the result of $sql as array.
     * @param string|DbQuery $sql Query to execute
     * @return array|false|null|mysqli_result|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public function executeS($sql)
    {
        return $this->db->executeS($sql, $this->return_array, $this->use_cache);
    }

    /**
     * @return bool Success of the operation.
     */
    public function createTable($table, $fields, $primary_key = null)
    {
        $table = $this->prefix($table, true);

        if (is_array($fields)) {
            // Prepare the primary key
            if (!is_null($primary_key)) {
                if (is_array($primary_key)) {
                    $primary_key = implode('`, `', $primary_key);
                }
            } else {
                // Primary key not set; then just get the first field name
                $primary_key = mpsl_first_key($fields);
            }
            $primary_key = "`{$primary_key}`";

            // Prepare fields
            foreach ($fields as $name => $type) {
                $fields[$name] = "`{$name}` {$type}";
            }
            $fields = implode(', ', $fields);

            // Prepare SQL
            $sql = "CREATE TABLE IF NOT EXISTS `{$table}` ({$fields}, "
                 . "PRIMARY KEY ({$primary_key})) ENGINE=%s DEFAULT "
                 . "CHARSET=utf8 AUTO_INCREMENT=1;";
            $sql = sprintf($sql, self::DB_ENGINE);

            $created = (bool)$this->execute($sql);
            return $created;
        } // if $fields is array
    }

    /**
     * @return bool Success of the operation.
     */
    public function dropTable($table)
    {
        $table = $this->prefix($table, true);
        $sql = "DROP TABLE IF EXISTS `{$table}`;";

        $dropped = (bool)$this->execute($sql);
        return $dropped;
    }

    /**
     * @return bool Success of the operation.
     */
    public function dropTables($tables)
    {
        $all_dropped = true;
        foreach ($tables as $table) {
            $dropped = $this->dropTable($table);
            $all_dropped = $all_dropped && $dropped;
        }
        return $all_dropped;
    }

    /**
     * @param DbQuery|MPSLDbQuery $query Query object.
     */
    public function query($query)
    {
        return $this->db->execute($query, $this->use_cache);
    }

    /**
     * Get a single value.
     * @param DbQuery|MPSLDbQuery $query Query object.
     */
    public function queryValue($query)
    {
        return $this->db->getValue($query, $this->use_cache);
    }

    /**
     * Returns an array of values (for a single column) or just first values
     * from result data.
     * @param DbQuery|MPSLDbQuery $query Query object.
     */
    public function queryValues($query)
    {
        $data = $this->executeS($query);
        if (empty($data)) {
            // No data found
            return array();
        } else {
            // There is a single column in the $data
            $result = array();
            foreach ($data as $values) {
                $result[] = reset($values);
            }
            return $result;
        }
    }

    /**
     * Get a single table row/record.
     * @param DbQuery|MPSLDbQuery $query Query object.
     */
    public function queryRow($query)
    {
        return $this->db->getRow($query, $this->use_cache);
    }

    /**
     * Always returns an array of arrays of column data.
     * @param DbQuery|MPSLDbQuery $query Query object.
     */
    public function queryResults($query)
    {
        $data = $this->executeS($query);

        if (empty($data)) {
            // No data found
            return array();
        } else {
            // There are multiple columns in the $data
            return $data;
        }
    }

    /**
     * Similar to method <b>queryResults()</b>, but also groups data by key
     * field and converts data like
     *     key_field   field2   ...
     *     10          a
     *     10          b
     *     20          c
     *     20          d
     *     ...
     * into
     *     array(
     *         10 => [a, b],
     *         20 => [c, d],
     *         ...
     *     )
     * or [when fields count > 2] into:
     *     array(
     *         10 => [ [a, ...], [b, ...] ],
     *         20 => [ [c, ...], [d, ...] ],
     *         ...
     *     )
     */
    public function query1n($query, $key_field, $distinct = true)
    {
        // Get results
        $results = $this->queryResults($query);
        $data = array();

        // Convert results
        if (!empty($results)) {
            // Can't generate data without key field
            if (array_key_exists($key_field, $results[0])) {
                // 1 key field value and 1 or more (multiple) other values
                $is_multiple = (count($results[0]) > 2);
                // Group results by key field
                foreach ($results as $result) {
                    // Prepare $data entry and remove key value from $result
                    $key_value = $result[$key_field];
                    if (!array_key_exists($key_value, $data)) {
                        $data[$key_value] = array();
                    }
                    unset($result[$key_field]);
                    // Add data
                    if (!$is_multiple) {
                        // Add single value
                        $second_value = reset($result);
                        $data[$key_value][] = $second_value;
                    } else {
                        // Add an array
                        $data[$key_value][] = $result;
                    }
                }
                // Remove duplicates
                if ($distinct && !$is_multiple) {
                    foreach ($data as $id => $values) {
                        $data[$id] = array_unique($values);
                    }
                }
            }
        } // if $results not empty

        return $data;
    }

    /**
     * @return bool Success of the operation.
     */
    public function insert($table, $data)
    {
        $inserted = $this->db->insert(
            $table,
            $data,
            $this->null_values,
            $this->use_cache,
            Db::INSERT,
            $this->add_prefix
        );
        return $inserted;
    }

    public function select($table, $id)
    {
        $key_field = $this->tableKey($table);

        $query = $this->createQuery();
        $query->selectAll()
              ->from($table)
              ->whereIs($key_field, $id);

        $data = $this->queryRow($query);
        return $data;
    }

    /**
     * @deprecated since version 1.2.1
     */
    public function selectJoin($left_table, $right_table, $id, $join_type = 'INNER', $where = null)
    {
        $join_type = Tools::strtoupper($join_type);
        $left_field = 'l.' . bqSQL($this->tableKey($left_table));
        $right_field = 'r.' . bqSQL($this->tableKey($right_table));
        $on = $left_field . ' = ' . $right_field;
        $join = $join_type . ' JOIN ' . bqSQL($right_table) . ' r ON ' . $on;

        $query = $this->createQuery();
        $query->selectAll()
              ->from($left_table, 'l')
              ->join($join)
              ->whereIs($left_field, $id);
        if (!is_null($where)) {
            $query->where(pSQL($where));
        }

        $data = $this->queryResults($query);
        return $data;
    }

    /**
     * @return bool Success of the operation.
     */
    public function update($table, $id, $data, $limit = 0)
    {
        $key_field = $this->tableKey($table);
        $where = "`{$key_field}` = {$id}";

        $updated = $this->db->update(
            $table,
            $data,
            $where,
            $limit,
            $this->null_values,
            $this->use_cache,
            $this->add_prefix
        );

        return $updated;
    }

    /**
     * @param type $where Inline WHERE conditions.
     * @return bool Success of the operation.
     */
    public function delete($table, $id, $limit = 0)
    {
        $key_field = $this->tableKey($table);
        $where = "`{$key_field}` = {$id}";

        $deleted = $this->db->delete(
            $table,
            $where,
            $limit,
            $this->use_cache,
            $this->add_prefix
        );

        return $deleted;
    }

    public function exists($table, $id)
    {
        $key_field = $this->tableKey($table);

        $query = $this->createQuery();
        $query->selectCount()
              ->from($table)
              ->whereIs($key_field, $id);

        $count = (int)$this->queryValue($query);
        return ($count > 0 ? true : false);
    }

    public function lastId($table)
    {
        $table_key = $this->tableKey($table);

        $query = $this->createQuery();
        $query->selectMax($table_key)
              ->from($table);
        $last_id = $this->queryValue($query);

        return $last_id;
    }

    public function parentId($table, $id)
    {
        $table_key = $this->tableKey($table);

        $query = $this->createQuery();
        $query->select(self::PARENT_ID)
              ->from($table)
              ->whereIs($table_key, $id);
        $parent_id = $this->queryValue($query);

        return $parent_id;
    }

    /**
     * @return MPSLDbQuery
     */
    public function createQuery()
    {
        return new MPSLDbQuery();
    }

    /**
     * Generates field name of a primary key by table name.
     */
    public function tableKey($table)
    {
        $table_key = 'id_' . $this->unprefix($table);
        return $table_key;
    }

    /**
     * Add "ps_" and "mpsl_" prefixes to table name if not exists (not actual
     * "ps_", it uses _DB_PREFIX_ constant).
     */
    public function prefix($table, $forse_db_prefix = false)
    {
        if (Tools::strpos($table, self::TABLE_PREFIX) !== 0) {
            // "mpsl_" not found or not at the beginning
            $table = self::TABLE_PREFIX . $table;
        }
        if ((!$this->add_prefix || $forse_db_prefix) && Tools::strpos($table, self::DB_PREFIX) !== 0) {
            // "ps_" not found or not at the beginning
            $table = self::DB_PREFIX . $table;
        }
        return $table;
    }

    /**
     * Removes all prefixes from table name: "ps_" and "mpsl_" (not actual
     * "ps_", it uses _DB_PREFIX_ constant).
     */
    public function unprefix($table)
    {
        $table = preg_replace('/^' . self::DB_PREFIX . '/', '', $table);
        $table = preg_replace('/^' . self::TABLE_PREFIX . '/', '', $table);
        return $table;
    }

    public function lastError()
    {
        return $this->db->getMsgError();
    }

    /**
     * Searches for all slides that uses a specified attachment.
     * @return int How many slides uses than attachment.
     */
    public function getAttachedSlidesCount($attachment_id)
    {
        $query = $this->createQuery();
        $where = 'options LIKE  \'%"bg_image_id":"' . (int)$attachment_id . '"%\'';
        $query->selectCount()
              ->from(self::SLIDE_TABLE)
              ->where($where);
        return $this->queryValue($query);
    }

    /**
     * Searches for all layers that uses a specified attachment.
     * @return int How many layers uses than attachment.
     */
    public function getAttachedLayersCount($attachment_id)
    {
        $query = $this->createQuery();
        $where = 'options LIKE  \'%"image_id":"' . (int)$attachment_id . '"%\'';
        $query->selectCount()
              ->from(self::LAYER_TABLE)
              ->where($where);
        return $this->queryValue($query);
    }

    /**
     * @return array Slider data without options: array(%ID%, %title%, %name%).
     */
    public function getSlidersLight()
    {
        $query = $this->createQuery();
        $query->select(self::SLIDER_ID, 'id')
              ->select('title', 'name')
              ->select('alias')
              ->from(self::SLIDER_TABLE);
        $sliders = $this->queryResults($query);

        // Decode special characters in slider titles
        foreach ($sliders as &$slider) {
            $slider['name'] = htmlspecialchars_decode($slider['name']);
        }

        return $sliders;
    }

    public function getSliders()
    {
        $query = $this->createQuery();
        $query->selectAll()
              ->from(self::SLIDER_TABLE);
        $rows = $this->queryResults($query);

        $sliders = array();

        foreach ($rows as $row) {
            $id = (int)$row[self::SLIDER_ID];

            // Prepare the raw data
            $title = htmlspecialchars_decode($row['title'], ENT_QUOTES);
            $alias = $row['alias'];
            $options = mpsl_json_decode_assoc($row['options'], array(
                'type' => 'custom',
                'visible_from' => '',
                'visible_till' => ''
            ));
            $visible_from = (!empty($options['visible_from']) ? $options['visible_from'] . 'px' : '-');
            $visible_till = (!empty($options['visible_till']) ? $options['visible_till'] . 'px' : '-');
            $hooks = $this->module->getSliderHooks($alias);
            $appearance_link = $this->module->ctrls->getControllerLink(
                'MPSLSlider',
                array('id' => $id, 'tab' => 'appearance')
            );
            $hooks_html = array();

            if (!empty($hooks)) {
                $is_first = true;
                foreach ($hooks as $hook => $label) {
                    $label = Tools::strtolower($label);
                    if ($is_first) {
                        $label = Tools::ucfirst($label);
                        $is_first = false;
                    }
                    $hooks_html[] = '<span class="mpsl-hook" title="' . $hook . '">' . $label . '</span>';
                }
            } else {
                $hooks_html[] = $this->module->l('None');
            }

            // Prepare the final data
            $type = $options['type'];
            $name = "{$title} ({$alias})";
            $shortcode = MPSLSlider::aliasToShortcode($alias);
            $display_on = implode(', ', $hooks_html);
            $display_on .= ' (<a href="' . $appearance_link . '">' . $this->module->l('edit') . '</a>)';
            $visible = "{$visible_from} / {$visible_till}";
            $template_id = ($type == 'custom' ? 0 : (int)$this->getTemplateId($id));

            // Add new slider record
            $slider = array(
                'type' => $type,
                'id' => $id,
                'name' => $name,
                'shortcode' => $shortcode,
                'display_on' => $display_on,
                'visible' => $visible,
                'template_id' => $template_id
            );
            $sliders[] = $slider;
        } // For each $row

        return $sliders;
    }

    public function getTemplateId($product_slider_id)
    {
        $query = $this->createQuery();
        $query->select(self::SLIDE_ID)
              ->from(self::SLIDE_TABLE)
              ->whereIs(self::PARENT_ID, $product_slider_id);
        $template_id = (int)$this->queryValue($query);
        return $template_id;
    }

    public function getShortcodes()
    {
        $query = $this->createQuery();
        $query->select('title')
              ->select('alias')
              ->from(self::SLIDER_TABLE);

        $rows = $this->queryResults($query);

        $shortcodes = array();
        foreach ($rows as $row) {
            $shortcode = MPSLSlider::aliasToShortcode($row['alias']);
            $shortcodes[] = array(
                'name' => $row['title'],
                'shortcode' => $shortcode
            );
        }

        return $shortcodes;
    }

    /**
     * Get attachment IDs for specified offset ($start) and count.
     * @return array Array of attachment IDs.
     */
    public function getAttachmentIds($start = 0, $count = 0)
    {
        $query = $this->createQuery();
        $query->select(self::ATTACHMENT_ID)
              ->from(self::ATTACHMENT_TABLE)
              ->limit($count, $start)
              ->orderBy(self::ATTACHMENT_ID, 'DESC');

        $data = $this->queryValues($query);
        return $data;
    }

    public function getSlideIds($slider_id)
    {
        $query = $this->createQuery();
        $query->select(self::SLIDE_ID)
              ->from(self::SLIDE_TABLE)
              ->whereIs(self::PARENT_ID, $slider_id);

        $ids = $this->queryValues($query);
        return $ids;
    }

    public function getPreviewId($slide_id)
    {
        $query = $this->createQuery();
        $query->select(self::SLIDE_ID)
              ->from(self::PREVIEW_SLIDE_TABLE)
              ->whereIs('id_original', $slide_id);
        $id = $this->queryValue($query);
        return $id;
    }

    public function getPreviewsData()
    {
        $query = $this->createQuery();
        $query->select(self::SLIDE_ID)
              ->select('id_original')
              ->from(self::PREVIEW_SLIDE_TABLE);

        $data = $this->queryResults($query);

        $previews = array();
        if ($data) {
            foreach ($data as $row) {
                $preview_id = $row[self::SLIDE_ID];
                $original_id = $row['id_original'];
                $previews[$preview_id] = $original_id;
            }
        }

        return $previews;
    }
}
