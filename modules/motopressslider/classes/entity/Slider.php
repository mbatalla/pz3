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

class MPSLSlider extends MPSLEntity
{
    public function __construct($id = 0, $settings = null, $type = null)
    {
        $this->name = 'slider';

        parent::__construct($id, $settings);

        // Set basic title and alias
        if ($this->id <= 0) {
            if (!is_null($type)) {
                $this->setType($type);
            }

            $is_product = $this->isProduct();
            if ($is_product) {
                $title = $this->module->l('New Product Slider');
            } else {
                $title = $this->module->l('New Slider');
            }
            $alias = ($is_product ? 'new-product-slider' : 'new-slider');
            $suffix = '';
            $alias = self::uniqueAlias($alias, $suffix);
            $title .= $suffix;
            $this->setTitle($title);
            $this->setAlias($alias);
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
              ->from(MPSLDb::SLIDE_TABLE)
              ->whereIs(MPSLDb::PARENT_ID, $this->id)
              ->orderBy('`order`', 'ASC');
        $slides = $this->db->queryResults($query);

        foreach ($slides as $slide) {
            // Create slide object
            $id = (int)$slide[MPSLDb::SLIDE_ID];
            $order = (int)$slide['order'];
            $item = new MPSLSlide($id, null, $this->id, $order);
            // Add new item to slider
            $this->addItemAt($item, $order);
            // Maybe load subitems
            if ($load_depth != 0) {
                $item->loadItems($load_depth - 1);
            }
        }

        $this->items_loaded = true;
    }

    /**
     * Generates the $data array that can be used to update existing or insert
     * new data into database.
     */
    public function gatherData()
    {
        $data = array(
            'title' => htmlspecialchars($this->getTitle(), ENT_QUOTES),
            'alias' => $this->getAlias()
        );
        $data = array_merge($data, parent::gatherData());
        return $data;
    }

    /**
     * Duplicates the entity and all its child items.
     */
    public function duplicate()
    {
        $old_title = $this->getTitle();
        $new_title = sprintf('Duplicate of %s', $old_title);
        $this->setTitle($new_title);

        $old_alias = $this->getAlias();
        $new_alias = self::uniqueAlias($old_alias);
        $this->setAlias($new_alias);

        $duplicated = parent::duplicate();

        if (!$duplicated) {
            $this->setTitle($old_title);
            $this->setAlias($old_alias);
        }

        return $duplicated;
    }

    public function delete()
    {
        $alias = $this->getAlias();
        $deleted = parent::delete();
        // Unhook slider from all hooks
        if ($deleted) {
            $this->module->unhookSlider($alias);
            $this->module->updateHookedSliders();
        }
        return $deleted;
    }

    public function isProduct()
    {
        $type = $this->settings->getOption('type', 'custom');
        $is_product = ($type == 'product');
        return $is_product;
    }

    public function getType()
    {
        return $this->settings->getOption('type', 'custom');
    }

    public function setType($type)
    {
        $type = mpsl_provide_occurrence($type, array('custom', 'product'));
        $this->settings->updateOption('type', $type);
    }

    public function getAlias()
    {
        return $this->settings->getOption('alias', '');
    }

    public function setAlias($alias)
    {
        $this->settings->updateOption('alias', $alias);
    }

    public function getTitle()
    {
        return $this->settings->getOption('title', '');
    }

    public function setTitle($title)
    {
        $this->settings->updateOption('title', $title);
    }

    public function setPreviewSlide($slide_id)
    {
        $preview_slide = new MPSLSlide($slide_id, null, $this->id);
        $this->addItemAt($preview_slide, $preview_slide->getOrder());
    }

    /**
     * For product sliders only. Otherwise it just return the first slide ID.
     */
    public function getTemplateId()
    {
        if (count($this->items) > 0) {
            $template_item = reset($this->items);
            return $template_item->id;
        } else {
            return 0;
        }
    }

    protected function optionsToSanitize()
    {
        return array('title');
    }

    public static function uniqueAlias($template = 'slider', &$suffix = '')
    {
        $alias = self::validateAlias($template);
        $aliases = self::getAliases();

        $unique_alias = $alias;
        $first_suffix = 2;

        // Search for number suffix in alias name
        $number_found = preg_match('/\d+$/', $alias, $matches);
        if ($number_found) {
            $first_suffix = (int)$matches[0] + 1; // Increase suffix number by 1
            // Remove "-N"/"_N" from the end of the alias name
            $alias = preg_replace('/[-_]*\d+$/', '', $alias);
            $unique_alias = $alias;
        }

        for ($i = $first_suffix; in_array($unique_alias, $aliases); $i += 1) {
            $unique_alias = "{$alias}-{$i}";
            $suffix = " {$i}";
        }

        return $unique_alias;
    }

    public static function aliasToShortcode($alias)
    {
        $shortcode = "[mpsl {$alias}]";
        return $shortcode;
    }

    public static function aliasToId($alias)
    {
        $db = mpsl_get_db();
        $query = $db->createQuery();
        $query->select(MPSLDb::SLIDER_ID)
              ->from(MPSLDb::SLIDER_TABLE)
              ->where('alias = \'' . pSQL($alias) . '\'');
        $id = (int)$db->queryValue($query);
        return $id;
    }

    public static function validateAlias($alias)
    {
        $alias_pattern = "/^[-_a-zA-Z0-9]+$/";
        $mild_pattern = "/[-_a-zA-Z0-9]+/";

        if (mpsl_preg_check($alias_pattern, $alias)) {
            return $alias;
        } else {
            $valid_alias_part = mpsl_preg_match($mild_pattern, $alias, 'slider');
            return $valid_alias_part;
        }
    }

    public static function getAliases()
    {
        $db = mpsl_get_db();
        $query = $db->createQuery();
        $query->select('alias')
              ->from(MPSLDb::SLIDER_TABLE);
        $aliases = $db->queryValues($query);
        return $aliases;
    }
}
