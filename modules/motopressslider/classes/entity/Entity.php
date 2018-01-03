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

abstract class MPSLEntity
{
    /**
     * @var MotoPressSlider Module instance.
     */
    protected $module = null;
    /**
     * @var MPSLDb Main module database instance.
     */
    protected $db = null;

    // Common fields
    protected $id = 0;
    protected $name = 'entity'; // Other variants: "slider", "slide", "layer"
    /** @var MPSLSettings Settings object. */
    protected $settings = null; // Settings object (see class Settings)
    protected $items = array(); // Slides or layers array
    protected $items_loaded = false;
    protected $orders = array(); // Format: array(%Item ID% => %Item Order%)
    protected $db_table = 'ps_mpsl_entity'; // Entity's table name in the database
    protected $db_key = 'id_entity'; // Entity's key field name of the table

    // Combine all requests to database (get options, order, parent ID etc) into
    // one request
    protected $loaded = false;
    protected $loaded_data = null;

    /**
     * @todo You need to change the value of the $this->name before calling the
     * parent __constructor() method.
     */
    public function __construct($id = 0, $settings = null)
    {
        $this->module = mpsl_get_module();
        $this->db = $this->module->db;

        $this->updateDbInformation($this->name);

        $this->id = $id;

        // Prepare settings
        if ($this->id) {
            $this->settings = $this->loadSettings($this->name);
            if (is_a($settings, 'MPSLSettings')) {
                $options = $settings->getOptions();
                $this->settings->updateOptions($options);
            } elseif (is_array($settings)) {
                $this->settings->updateOptions($settings);
            }
        } else {
            if (is_a($settings, 'MPSLSettings')) {
                $this->settings = $settings;
            } elseif (is_array($settings)) {
                $this->settings = $this->createSettings($this->name, $settings);
            } else {
                $this->settings = $this->createSettings($this->name);
            }
        }
    }

    protected function updateDbInformation($name)
    {
        $this->db_table = $this->db->prefix($name);
        $this->db_key = $this->db->tableKey($name);
    }

    abstract public function loadItems();

    /**
     * Generates the $data array that can be used to update existing or insert
     * new data into database.
     */
    public function gatherData()
    {
        $sanitize_options = $this->optionsToSanitize();
        $data = array(
            'options' => $this->settings->encode($sanitize_options)
        );
        return $data;
    }

    /**
     * Add new record to database.
     */
    public function add()
    {
        $data = $this->gatherData();
        $inserted = $this->db->insert($this->db_table, $data);

        if ($inserted) {
            $this->id = $this->db->lastId($this->db_table);
        } else {
            return false;
        }

        // Duplicate items if exists
        foreach ($this->items as $item) {
            $item->setParent($this->id);
            $item->add();
        }

        // Return new ID instead of "true"
        return $this->id;
    }

    /**
     * Update entity in database.
     */
    public function update($update_children = false)
    {
        if ($this->id == 0) {
            // It's a new item. Add it
            return $this->add();
        }

        $data = $this->gatherData();
        $updated = $this->db->update($this->db_table, $this->id, $data);

        if ($updated && $update_children) {
            foreach ($this->items as $item) {
                $updated = $updated && $item->update(true);
            }
        }

        return $updated;
    }

    /**
     * Duplicates the entity and all its child items.
     */
    public function duplicate()
    {
        return $this->add();
    }

    /**
     * Delete the entity from database.
     */
    public function delete()
    {
        if ($this->id == 0) {
            return true; // Nothing to delete
        }

        $deleted = $this->db->delete($this->db_table, $this->id);

        if ($deleted) {
            $item_ids = $this->getItemsIds();
            foreach ($item_ids as $item_id) {
                $this->deleteItem($item_id);
            }
            $this->reset();
            return true;
        } else {
            // Failed to delete the entity
            return false;
        }
    }

    protected function reset()
    {
        $this->id = 0;
        $this->settings->reset();
        $this->items = array();
        $this->orders = array();
    }

    public function addItem($item)
    {
        $next_index = count($this->items);
        $this->addItemAt($item, $next_index);
    }

    /**
     * Add item with a specified order.
     */
    public function addItemAt($item, $order)
    {
        // Add new item
        $this->items[$order] = $item;
        $this->orders[$item->id] = $order;
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * Converts all items into an array.
     */
    public function getItemsArray()
    {
        $data = array();
        foreach ($this->items as $item) {
            $data[] = $item->settings->getOptions();
        }
        return $data;
    }

    public function getItemsCount()
    {
        return count($this->items);
    }

    public function getItemsIds()
    {
        return array_keys($this->orders);
    }

    public function getOrderedItemsIds()
    {
        $orders = $this->orders;
        asort($orders);
        return array_keys($orders);
    }

    public function getItem($item_id)
    {
        $order = $this->getItemOrder($item_id);
        return $this->items[$order];
    }

    public function getFirstItem()
    {
        if (count($this->items) > 0) {
            return reset($this->items);
        } else {
            return null;
        }
    }

    /**
     * Alias of method getItemIndex().
     */
    public function getItemOrder($item_id)
    {
        return $this->orders[$item_id];
    }

    /**
     * Remove item from items list but does not delete it from database.
     */
    public function removeItem($item_id)
    {
        $order = $this->orders[$item_id];
        unset($this->items[$order]);
        unset($this->orders[$item_id]);
    }

    /**
     * Remove item from items list and also delete it from database.
     */
    public function deleteItem($item_id)
    {
        $item = $this->getItem($item_id);
        $deleted = $item->delete();
        if ($deleted) {
            $this->removeItem($item_id);
            return true;
        }
        return false;
    }

    /**
     * @return MPSLSettings Settings object.
     */
    public function getSettingsObject()
    {
        return $this->settings;
    }

    protected function createSettings($name, $default_options = array())
    {
        $settings_obj = new MPSLSettings($name . '.php');
        if (!empty($default_options)) {
            $settings_obj->updateOptions($default_options);
        }
        return $settings_obj;
    }

    protected function loadSettings($name)
    {
        // Load entity data from database
        $this->load();

        // Create settings object
        $settings_obj = $this->createSettings($name);

        // Update optins
        $json = $this->loaded_data['options'];
        $sanitize_options = $this->optionsToSanitize();
        $options = MPSLSettings::decode($json, $sanitize_options);
        $settings_obj->updateOptions($options);

        return $settings_obj;
    }

    protected function optionsToSanitize()
    {
        return array();
    }

    public function getSanitizedOptions()
    {
        $sanitize_options = $this->optionsToSanitize();
        $options = $this->settings->getSanitizedOptions($sanitize_options);
        return $options;
    }

    /**
     * Only $this->module, $this->db, $this->id, $this->db_tabe and
     * $this->db_key available at the moment.
     */
    protected function load()
    {
        if ($this->loaded) {
            // Already loaded
            return true;
        }

        // Not loaded yet. Load new data
        $query = $this->db->createQuery();
        $query->selectAll()
              ->from($this->db_table)
              ->whereIs($this->db_key, $this->id);
        $this->loaded_data = $this->db->queryRow($query);

        // If not loaded then just set default values
        if ($this->loaded_data) {
            // Data loaded
            $this->loaded = true;
        } else {
            // Data not loaded
            $this->loaded = false;
            // Set temporary default values
            $this->loaded_data = array(
                $this->db_key => $this->id,
                MPSLDb::PARENT_ID => 0,
                'order' => 0,
                'options' => ''
            );
        }

        return $this->loaded;
    }

    /**
     * Generates an array of used attachments.
     * @return array Array of attachment IDs.
     */
    public function getAttachments()
    {
        $attachments = array();
        return $attachments;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
