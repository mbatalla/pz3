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

class MPSLExporter
{
    /** @var MotoPressSlider */
    private $module = null;
    private $presets_obj = null;

    public function __construct($module = null, $presets_obj = null)
    {
        if (!is_null($module) && is_a($module, 'MotoPressSlider')) {
            $this->module = $module;
        } else {
            $this->module = mpsl_get_module();
        }
        if (!is_null($presets_obj)) {
            $this->presets_obj = $presets_obj;
        } else {
            $this->presets_obj = MPSLPresets::getInstance();
        }
    }

    /**
     * @param array|int $ids
     * @return void
     */
    public function export($ids)
    {
        if (empty($ids)) {
            return;
        }

        $ids = (array)$ids;
        $sliders = array();
        $internal_resources = array();
        $preset_classes = array();

        // Create all sliders
        foreach ($ids as $id) {
            $sliders[] = new MPSLSlider($id);
        }

        // Generate export file name
        if (count($ids) == 1) {
            $data_name = $sliders[0]->getAlias();
        } else {
            $data_name = 'sliders';
        }
        $shop_name = mpsl_shopinfo('name');
        $shop_name = preg_replace('/[^\w]+/', '', $shop_name);
        $date = date('m-d-Y');
        $export_file = "{$shop_name}-{$data_name}-data-{$date}.json";

        // 1/5. Prepare export info
        $export_data = array(
            'info' => array(
                'mpsl-ver' => $this->module->version,
                'base-upload' => $this->module->uploads_url
            )
        );

        // 2/5. Export sliders
        foreach ($sliders as $slider) {
            $export_data['sliders'][$slider->id] = $this->exportSlider($slider, $internal_resources, $preset_classes);
        }

        // 3/5. Export images
        $export_data['uploads'] = $internal_resources;

        // 4/5. Export custom presets
        $presets = array();
        if (count($preset_classes) > 0) {
            $presets = $this->presets_obj->getCustomPresets();
            foreach (array_keys($presets) as $preset_class) {
                if (!in_array($preset_class, $preset_classes)) {
                    unset($presets[$preset_class]);
                }
            }
        }
        $export_data['presets'] = $presets;

        // 5/5. Encode export data
        $export_data = Tools::jsonEncode($export_data);

        // Return the export file
        header("Content-Type: application/force-download; charset=utf8");
        header("Content-Disposition: attachment; filename={$export_file}");
        exit($export_data);
    }

    /**
     * @param MPSLSlider $slider
     * @param array $internal_resources
     * @param array $preset_classes
     * @return array
     */
    private function exportSlider($slider, &$internal_resources, &$preset_classes)
    {
        // Export settings
        $slider_options = $slider->getSanitizedOptions();

        // Export slides and preset classes
        $slider->loadItems();
        $slides = $slider->getItems();
        $slides_data = array();
        foreach ($slides as $slide) {
            // Export slide and layers
            $slides_data[] = $this->exportSlide($slide, $internal_resources);
            // Export presets
            $slide_preset_classes = $slide->getPresetClasses();
            $preset_classes = array_merge($preset_classes, $slide_preset_classes);
        }
        $preset_classes = array_unique($preset_classes);

        $export_data = array(
            'options' => $slider_options,
            'slides' => $slides_data
        );
        return $export_data;
    }

    /**
     * @param MPSLSlide $slide
     * @param array $internal_resources
     * @return array
     */
    private function exportSlide($slide, &$internal_resources = array())
    {
        // Export settings
        $slide_settings = $slide->settings->getSettings(true);
        $slide_options = $this->exportSettings($slide_settings, $internal_resources);

        // Export layers
        $layers = $slide->getItems();
        $layers_data = array();
        foreach ($layers as $layer) {
            $layers_data[] = $this->exportLayer($layer, $internal_resources);
        }

        $export_data = array(
            'options' => $slide_options,
            'layers' => $layers_data
        );
        return $export_data;
    }

    /**
     * @param MPSLLayer $layer
     * @param array $internal_resources
     * @return array
     */
    private function exportLayer($layer, &$internal_resources)
    {
        $export_data = $layer->getSanitizedOptions();

        foreach ($export_data as $option => $value) {
            switch ($option) {
                case 'image_id':
                    $export_data[$option] = $this->exportImage($value, $internal_resources);
                    break;
                case 'private_styles':
                    $export_data[$option] = $this->presets_obj->clearPreset($export_data[$option]);
                    break;
            }

            // Remove URLs that have no usage
            if (mpsl_strinstr('image_url', $option)) {
                $export_data[$option] = '';
            }
        }

        return $export_data;
    }

    private function exportSettings($grouped_settings, &$internal_resources)
    {
        $export_data = array();

        foreach ($grouped_settings as $group) {
            foreach ($group['options'] as $name => $option) {

                if ($option['type'] === 'library_image' && !empty($option['value'])) {
                    $export_data[$name] = $this->exportImage($option['value'], $internal_resources);
                } else {
                    // Remove URLs that have no usage
                    $export_data[$name] = (!mpsl_strinstr('image_url', $name) ? $option['value'] : '');
                }

            }
        }

        return $export_data;
    }

    private function exportImage($value, &$internal_resources)
    {
        $export_data = $value;

        if (!empty($value)) {
            if (!array_key_exists($value, $internal_resources)) {
                // New internal resource found
                $internal_resources[$value] = array(
                    'value' => mpsl_get_image_src($value)
                );
            }
            $export_data = array(
                'need_update' => true,
                'old_value' => $value
            );
        }

        return $export_data;
    }
}
