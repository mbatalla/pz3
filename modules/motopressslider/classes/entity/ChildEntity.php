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

abstract class MPSLChildEntity extends MPSLEntity
{
    protected $parent_id = 0;
    protected $order = 1;

    /**
     * @todo You need to change the value of the $this->name before calling the
     * parent __constructor() method.
     */
    public function __construct($id = 0, $settings = null, $parent_id = 0, $order = null)
    {
        parent::__construct($id, $settings);

        // Prepare parent ID
        if ($parent_id) {
            $this->parent_id = (int)$parent_id;
        } elseif ($this->id) {
            $this->loadParentId();
        }

        if (!is_null($order)) {
            $this->order = $order;
        } elseif ($this->id) {
            $this->loadOrder();
        }
    }

    protected function loadParentId()
    {
        $this->load();
        $this->parent_id = $this->loaded_data[MPSLDb::PARENT_ID];
    }

    protected function loadOrder()
    {
        $this->load();
        $this->order = $this->loaded_data['order'];
    }

    // abstract protected function loadItems(); - from class Entity

    /**
     * Generates the $data array that can be used to update existing or insert
     * new data into database.
     */
    public function gatherData()
    {
        $data = array(
            MPSLDb::PARENT_ID => $this->parent_id,
            'order' => $this->order
        );
        $data = array_merge($data, parent::gatherData());
        return $data;
    }

    /**
     * Duplicates the entity and all its child items.
     */
    public function duplicate()
    {
        $old_order = $this->order;
        $new_order = $this->nextOrder();
        $this->order = $new_order;

        $duplicated = parent::duplicate();

        if (!$duplicated) {
            $this->order = $old_order;
        }

        return $duplicated;
    }

    public function setParent($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function nextOrder()
    {
        if (!$this->parent_id) {
            return 1;
        }
        $query = $this->db->createQuery();
        $query->selectMax('order')
              ->from($this->db_table)
              ->whereIs(MPSLDb::PARENT_ID, $this->parent_id);
        $max_order = (int)$this->db->queryValue($query);
        return ($max_order + 1);
    }
}
