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

class MPSLLayerMerger
{
    /**
     * @param MPSLSlide $slide
     * @param array $layers
     */
    public function merge($slide, $layers)
    {
        if (is_null($layers)) {
            return false;
        }

        $this->updateOrders($layers);
        $this->updatePresets($slide, $layers);
        $succeed = $this->mergeObjectAndArray($slide, $layers);
        return $succeed;
    }

    private function updateOrders(&$layers)
    {
        $order = 1;
        foreach ($layers as &$layer) {
            $layer['order'] = $order;
            $order += 1;
        }
    }

    private function updatePresets($slide, &$layers)
    {
        // Get presets list
        $presets_obj = MPSLPresets::getInstance();
        $presets = $presets_obj->getAllPresets();

        // Update layers
        $fonts = array(); // Also generate fonts array for the slide
        foreach ($layers as $no => $layer) {
            $preset_class = $layer['preset'];
            if ($preset_class) {
                $styles = null;
                if ($preset_class === 'private') {
                    $styles = $layer['private_styles'];
                } elseif (array_key_exists($preset_class, $presets)) {
                    $styles = $presets[$preset_class];
                }
                if (!is_null($styles)) {
                    $fonts = array_merge_recursive($fonts, $presets_obj->getFontsFromPreset($styles));
                }
            }
            $layers[$no]['private_styles'] = $presets_obj->clearPreset($layer['private_styles']);
        } // For each layer

        // Set used fonts
        $fonts = $presets_obj->fontsUnique($fonts);
        $slide_fonts = $slide->settings->getOption('fonts');
        if ($slide_fonts !== $fonts) {
            $slide->settings->updateOption('fonts', $fonts);
            $slide->update();
        }
    }

    private function mergeObjectAndArray($slide, &$layers)
    {
        $succeed = true;

        $items = $slide->getItems();
        $info = $this->getInfo($items, $layers);
        $counts = $info['counts'];
        $positions = $info['positions'];

        foreach ($counts as $type => $count) {
            $pos = $positions[$type];
            $succeed = $succeed && $this->create($layers, $slide->id, $count, $pos);
            $succeed = $succeed && $this->update($items, $layers, $count, $pos);
            $succeed = $succeed && $this->delete($items, $count, $pos);
        }

        return $succeed;
    }

    private function create(&$layers, $parent_id, $count, $pos)
    {
        $succeed = true;
        for ($i = $count['before']; $i < $count['after']; $i++) {
            $layer_no = $pos['after'][$i];

            $item = new MPSLLayer();
            $layer = $layers[$layer_no];

            $item->settings->updateOptions($layer);
            // "layer_order" in table "mpsl_layers" starts from "1"
            $item->setOrder($layer['order']);
            $item->setParent($parent_id);

            $succeed = $succeed && $item->add();
        }
        return $succeed;
    }

    private function update($items, &$layers, $count, $pos)
    {
        $succeed = true;
        $to_update = min($count['before'], $count['after']);
        for ($i = 0; $i < $to_update; $i++) {
            $item_no = $pos['before'][$i];
            $layer_no = $pos['after'][$i];

            $item = $items[$item_no];
            $layer = $layers[$layer_no];

            $item->settings->updateOptions($layer);
            // "layer_order" in table "mpsl_layers" starts from "1"
            $item->setOrder($layer['order']);

            $succeed = $succeed && $item->update();
        }
        return $succeed;
    }

    private function delete($items, $count, $pos)
    {
        $succeed = true;
        for ($i = $count['after']; $i < $count['before']; $i++) {
            $item_no = $pos['before'][$i];

            $item = $items[$item_no];

            $succeed = $succeed && $item->delete();
        }
        return $succeed;
    }

    private function getInfo($items, &$layers)
    {
        $counts = array();
        $positions = array(); // Layer order in array

        $this->count($items, 'before', $counts, $positions);
        $this->count($layers, 'after', $counts, $positions);

        $info = array(
            'counts' => $counts,
            'positions' => $positions
        );
        return $info;
    }

    private function count($items, $context, &$counts, &$positions)
    {
        foreach ($items as $no => $item) {
            $type = $this->getType($item);
            if (!array_key_exists($type, $counts)) {
                $counts[$type] = array('before' => 0, 'after' => 0);
                $positions[$type] = array(
                    'before' => array(),
                    'after' => array()
                );
            }
            $counts[$type][$context] += 1;
            $positions[$type][$context][] = $no;
        }
    }

    private function getType($item)
    {
        if (is_array($item)) {
            return (isset($item['type']) ? $item['type'] : 'html');
        } else {
            return $item->getType();
        }
    }
}
