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

class MPSLSlide extends MPSLChildEntity
{
    protected $is_preview = false;

    public function __construct($id = 0, $settings = null, $parent_id = 0, $order = null, $is_preview = false)
    {
        $this->name = 'slide';
        $this->is_preview = (bool)$is_preview;

        if ($this->is_preview) {
            $id = mpsl_get_db()->getPreviewId($id); // $this->db = null at the moment
            $parent_id = 0;
        }

        parent::__construct($id, $settings, $parent_id, $order);
    }

    public function makePreview()
    {
        if (!$this->is_preview) {
            $original_id = $this->id;
            $this->parent_id = 0;
            // Get preview ID or create new preview slide
            $preview_id = $this->db->getPreviewId($this->id);
            if ($preview_id) {
                $this->id = $preview_id;
            } else {
                // Preview slide not found; create the new one
                $this->add();
                // Add new record to "mpsl_slide_preview" table
                $this->db->insert(
                    MPSLDb::PREVIEW_SLIDE_TABLE,
                    array(MPSLDb::SLIDE_ID => $this->id, 'id_original' => $original_id)
                );
            }
            // Now it's preview slide
            $this->is_preview = true;
        }
    }

    public function deletePreview()
    {
        $query = $this->db->createQuery();
        $query->select(MPSLDb::PREVIEW_SLIDE_ID)
              ->select(MPSLDb::SLIDE_ID)
              ->from(MPSLDb::PREVIEW_SLIDE_TABLE)
              ->whereIs('id_original', $this->id);

        $data = $this->db->queryRow($query);

        if ($data) {
            // Delete old preview slide
            $preview_id = $data[MPSLDb::SLIDE_ID];
            $preview_slide = new MPSLSlide($preview_id);
            $preview_slide->loadItems();
            $preview_slide->delete();
            // Delete preview information record
            $record_id = $data[MPSLDb::PREVIEW_SLIDE_ID];
            $this->db->delete(MPSLDb::PREVIEW_SLIDE_TABLE, $record_id);
        }
    }

    /**
     * @param int $load_depth Value <i>"0"</i> means do not load subitems
     * (layers for slides, for example), <i>"-1"</i> - load full hierarchy.
     */
    public function loadItems($load_depth = -1)
    {
        if ($this->items_loaded) {
            return;
        }

        $query = $this->db->createQuery();
        $query->selectAll()
              ->from(MPSLDb::LAYER_TABLE)
              ->whereIs(MPSLDb::PARENT_ID, $this->id)
              ->orderBy('`order`');
        $layers = $this->db->queryResults($query);

        foreach ($layers as $layer) {
            // Create slide object
            $id = (int)$layer[MPSLDb::LAYER_ID];
            $order = (int)$layer['order'];
            $item = new MPSLLayer($id, null, $this->id, $order);
            // Add new item to slide
            $this->addItemAt($item, $order);
            // Maybe load subitems
            if ($load_depth != 0) {
                $item->loadItems($load_depth - 1);
            }
        }

        $this->items_loaded = true;
    }

    /**
     * Duplicates the entity and all its child items.
     */
    public function duplicate()
    {
        $old_title = $this->getTitle();
        $new_title = sprintf('Duplicate of %s', $old_title);
        $this->setTitle($new_title);

        $duplicated = parent::duplicate();

        if (!$duplicated) {
            $this->setTitle($old_title);
        }

        return $duplicated;
    }

    public function delete()
    {
        $this->deletePreview();
        return parent::delete();
    }

    public function getTitle()
    {
        return $this->settings->getOption('title', '');
    }

    public function setTitle($title)
    {
        $this->settings->updateOption('title', $title);
    }

    public function getStatus()
    {
        return $this->settings->getOption('status', 'published');
    }

    public function setStatus($status)
    {
        mpsl_provide_occurrence($status, array('published', 'draft'));
        $this->settings->updateOption('status', $status);
    }

    public function isHidden()
    {
        $status = $this->settings->getOption('status', 'published');

        if ($status == 'draft') {
            return true;
        }

        $current_time = mpsl_current_time();
        $date_from = $this->settings->getOption('date_from', '');
        if ($date_from) {
            $time_from = strtotime($date_from);
            if ($time_from !== false && $time_from !== -1 && $time_from > $current_time) {
                return true;
            }
        }

        $date_until = $this->settings->getOption('date_until', '');
        if ($date_until) {
            $time_until = strtotime($date_until);
            if ($time_until !== false && $time_until !== -1 && $time_until < $current_time) {
                return true;
            }
        }

        // Otherwise - not hidden
        return false;
    }

    public function getPresetClasses()
    {
        $classes = array();
        foreach ($this->items as $layer) {
            $preset = $layer->getSettingsObject()->getOption('preset');
            if ($preset && $preset !== 'private') {
                $classes[] = $preset;
            }
        }
        return array_unique($classes);
    }

    protected function optionsToSanitize()
    {
        return array('title', 'link_title');
    }

    public function getTableData()
    {
        $title = $this->getTitle();
        $status = $this->getStatus();
        $need_logged_in = (bool)$this->settings->getOption('need_logged_in');
        $date_from = $this->settings->getOption('date_from');
        $date_until = $this->settings->getOption('date_until');

        $data = array(
            'id' => $this->id,
            'title' => $title,
            'status' => $status,
            'need_logged_in' => $need_logged_in,
            'date_from' => (empty($date_from) ? '-' : $date_from),
            'date_until' => (empty($date_until) ? '-' : $date_until)
        );

        return $data;
    }
}
