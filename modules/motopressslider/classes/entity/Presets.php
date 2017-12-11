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

class MPSLPresets
{
    const PREFIX = 'mpsl-preset-';
    const PRIVATE_PREFIX = 'mpsl-private-preset-';
    const LAYER_CLASS = 'mpsl-layer';
    const LAYER_HOVER_CLASS = 'mpsl-layer-hover';

    private static $instance = null;

    private $is_preview = false;

    private $settings = null;

    private $default_preset_data = null; // array("style", "hover", "settings")

    private $default_presets = null;
    private $custom_presets = null;

    private $last_preset_id = 0;
    private $last_private_id = 0; // $last_private_preset_id

    public function __construct($is_preview = false)
    {
        $this->is_preview = $is_preview;

        // Create settings object
        $this->settings = new MPSLSettings('preset.php');

        $this->default_preset_data = $this->getDefaultPresetData();

        // Load default presets
        $this->default_presets = $this->loadDefaultPresets();
        // Load custom presets
        $this->custom_presets = $this->loadCustomPresets();

        // Get last IDs
        $this->last_preset_id = mpsl_get_option('mpsl_last_preset_id', 0);
        $this->last_private_id = mpsl_get_option('mpsl_last_private_preset_id', 0);
    }

    /**
     * @return MPSLPresets
     */
    public static function getInstance($is_preview = false)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($is_preview);
        } else {
            self::$instance->setPreview($is_preview);
        }
        return self::$instance;
    }

    public function setPreview($is_preview)
    {
        $this->is_preview = $is_preview;
    }

    public function update()
    {
        if (!$this->is_preview) {
            mpsl_update_option('mpsl_last_preset_id', $this->getLastPresetId());
            mpsl_update_option('mpsl_last_private_preset_id', $this->getLastPrivateId());

            $clean_custom_presets = $this->clearPresets($this->custom_presets);
            mpsl_update_option('mpsl_custom_presets_json', $clean_custom_presets);
        }

        $default_css = $this->compile($this->default_presets);
        $custom_css = $this->compile($this->custom_presets);

        if (!$this->is_preview) {
            mpsl_update_option('mpsl_default_css', $default_css);
            mpsl_update_option('mpsl_custom_css', $custom_css);
        } else {
            mpsl_update_option('mpsl_preview_default_css', $default_css);
            mpsl_update_option('mpsl_preview_custom_css', $custom_css);
        }

        return true;
    }

    public function compile($presets, $prepared = false, $separated = false)
    {
        if (!$prepared) {
            $presets = $this->fillPresets($presets);
        }

        $options = $this->settings->getSettings();
        $css = ''; // Return value when $separated == false
        $css_classes = array(); // Return value when $separated == true

        foreach ($presets as $class => $preset) {
            if (!$this->isValidPreset($preset)) {
                continue;
            }

            $types = array('style');
            if (!isset($preset['settings']['hover']) || $preset['settings']['hover']) {
                $types[] = 'hover';
            }

            foreach ($types as $type) {
                // Add cross-browser options
                foreach ($preset[$type] as $option_name => $option_value) {
                    if (!array_key_exists($option_name, $options)) {
                        continue;
                    }
                    switch ($option_name) {
                        case 'border-radius':
                            $options['-webkit-' . $option_name] = $options[$option_name];
                            $options['-moz-' . $option_name] = $options[$option_name];
                            unset($preset[$type][$option_name]);
                            $preset[$type]['-webkit-' . $option_name] = $option_value;
                            $preset[$type]['-moz-' . $option_name] = $option_value;
                            $preset[$type][$option_name] = $option_value;
                            break;
                    }
                } // For each preset option

                // ".mpsl-layer.%CLASS%"
                $css .= '.' . self::LAYER_CLASS . '.' . $class;
                // "#footer .mpsl-layer.%CLASS%"
                $css .= ', #footer .' . self::LAYER_CLASS . '.' . $class;
                if ($type === 'hover') {
                    // ".mpsl-layer-hover" or ":hover"
                    $css .= ($separated ? ('.' . self::LAYER_HOVER_CLASS) : ':hover');
                }

                $css .= '{';
                foreach ($preset[$type] as $option_name => $option_value) {
                    if (!array_key_exists($option_name, $options)) {
                        continue;
                    }
                    if ($options[$option_name]['isChild']) {
                        continue;
                    }
                    // Skip empty & helper options
                    if (!is_string($option_value)
                        || $option_value === ''
                        || in_array($option_name, array('allow_style', 'custom_styles'))
                    ) {
                        continue;
                    }
                    // Add unit
                    $css .= $option_name . ':' . trim($option_value);
                    if (is_numeric($option_value) && ($unit = $options[$option_name]['unit'])) {
                        $css .= $unit;
                    }
                    $css .= ';';
                } // For each preset option
                // Remove line breaks
                if (array_key_exists('custom_styles', $preset[$type])) {
                    $css .= preg_replace('/\s+/S', ' ', $preset[$type]['custom_styles']);
                }
                $css .= "}";
            } // For each type

            if ($separated) {
                $css_classes[$class] = $css;
                $css = '';
            }
        } // For each preset

        return ($separated ? $css_classes : $css);
    }

    public function clearPresets($presets = array())
    {
        if (!is_array($presets)) {
            return array();
        }

        foreach ($presets as $key => $preset) {
            $presets[$key] = $this->clearPreset($preset);
        }

        return $presets;
    }

    /**
     * Unsets the empty options.
     */
    public function clearPreset($preset)
    {
        if ($this->isValidPreset($preset)) {
            foreach (array('style', 'hover') as $mode) {
                foreach ($preset[$mode] as $option_key => $option_value) {
                    if ($option_value === '') {
                        unset($preset[$mode][$option_key]);
                    }
                }
            }
            if (isset($preset['settings']['label']) && !$preset['settings']['label']) {
                unset($preset['settings']['label']);
            }
            if (isset($preset['settings']['hover']) && $preset['settings']['hover']) {
                unset($preset['settings']['hover']);
            }
        }
        return $preset;
    }

    public function isValidPreset($preset)
    {
        return (
            !empty($preset)
            && is_array($preset)
            && isset($preset['settings']) && is_array($preset['settings'])
            && isset($preset['style']) && is_array($preset['style'])
            && isset($preset['hover']) && is_array($preset['hover'])
        );
    }

    public function loadDefaultPresets()
    {
        $default_presets = mpsl_read('includes/defaults/presets.php');
        $default_presets = $this->fillPresets($default_presets);
        return $default_presets;
    }

    public function loadCustomPresets()
    {
        $custom_presets = mpsl_get_option('mpsl_custom_presets_json', array());
        if (!empty($custom_presets)) {
            $custom_presets = $this->fillPresets($custom_presets);
            return $custom_presets;
        } else {
            return array();
        }
    }

    /**
     * Add missing fields from $default_preset_data.
     */
    public function fillPreset($preset)
    {
        if (!is_array($preset)) {
            $preset = array();
        }
        $preset = array_replace_recursive($this->default_preset_data, $preset);
        $preset['hover']['allow_style'] = $preset['settings']['hover'];
        return $preset;
    }

    /**
     * Add missing fields from $default_preset_data for each preset.
     */
    public function fillPresets($presets)
    {
        foreach ($presets as $preset_key => $preset) {
            $presets[$preset_key] = $this->fillPreset($preset);
        }
        return $presets;
    }

    public function override($presets, $is_single = false)
    {
        if (!empty($presets)) {
            if ($is_single) {
                $presets = $this->fillPreset($presets);
            } else {
                $presets = $this->fillPresets($presets);
            }
        }
        return $presets;
    }

    public function getDefaultPresetData(&$grouped_options = array())
    {
        if (empty($grouped_options)) {
            $default_options = $this->settings->getDefaultOptions();
        } else {
            $settings = new MPSLSettings();
            $settings->updateSettings($grouped_options);
            $default_options = $settings->getDefaultOptions();
        }

        $default_data = array(
            'style' => $default_options,
            'hover' => $default_options,
            'settings' => array(
                'label' => '',
                'hover' => true
            ),
        );

        return $default_data;
    }

    public function getDefaultPresetDataFromFile($settings_file_name)
    {
        if ($settings_file_name == 'preset.php') {
            return $this->getDefaultPresetData(array());
        }

        $settings = new MPSLSettings($settings_file_name);
        $default_options = $settings->getDefaultOptions();

        $default_data = array(
            'style' => $default_options,
            'hover' => $default_options,
            'settings' => array(
                'label' => '',
                'hover' => true
            ),
        );

        return $default_data;
    }

    public function getDefaultPresets()
    {
        return $this->default_presets;
    }

    public function getCustomPresets()
    {
        return $this->custom_presets;
    }

    public function getAllPresets()
    {
        $all_presets = array_merge(
            $this->default_presets,
            $this->custom_presets
        );
        return $all_presets;
    }

    public function setCustomPresets($presets)
    {
        $this->custom_presets = $presets;
    }

    public function getFontsFromPreset($preset)
    {
        $fonts = array();
        if (!$this->isValidPreset($preset)) {
            return $fonts;
        }

        $types = array('style');
        if (isset($preset['settings']['hover']) && $preset['settings']['hover']) {
            $types[] = 'hover';
        }

        foreach ($types as $type) {
            if (isset($preset[$type]['font-family']) && ($font_name = $preset[$type]['font-family'])) {
                if (!array_key_exists($font_name, $fonts)) {
                    $fonts[$font_name] = array('variants' => array());
                }
                $font_weight = $preset[$type]['font-weight'];
                if ($font_weight && !in_array($font_weight, $fonts[$font_name]['variants'])) {
                    // Normal
                    $font_weight = ($font_weight === 'normal' ? 'regular' : $font_weight);
                    $fonts[$font_name]['variants'][] = $font_weight;
                    // Italic
                    if (isset($preset[$type]['font-style']) && $preset[$type]['font-style'] === 'italic') {
                        if ($font_weight === 'regular') {
                            $font_weight = 'italic';
                        } else {
                            $font_weight = $font_weight . 'italic';
                        }
                        if (!in_array($font_weight, $fonts[$font_name]['variants'])) {
                            $fonts[$font_name]['variants'][] = $font_weight;
                        }
                    }
                }
            }
        } // For each preset type

        return $fonts;
    }

    public function getDefaultFonts()
    {
        $default_fonts = array();
        if (count($this->default_presets)) {
            foreach ($this->default_presets as $preset) {
                $default_fonts = array_merge_recursive($default_fonts, $this->getFontsFromPreset($preset));
            }
        }
        return $this->fontsUnique($default_fonts);
    }

    public function getCustomFonts()
    {
        $custom_fonts = array();
        if (count($this->custom_presets)) {
            foreach ($this->custom_presets as $preset) {
                $custom_fonts = array_merge_recursive($custom_fonts, $this->getFontsFromPreset($preset));
            }
        }
        return $this->fontsUnique($custom_fonts);
    }

    public function getFonts()
    {
        $fonts = array_merge_recursive(
            $this->getDefaultFonts(),
            $this->getCustomFonts()
        );
        return $this->fontsUnique($fonts);
    }

    public function updatePrivateStyles()
    {
        $db = mpsl_get_db();

        $previews = $db->getPreviewsData();
        $has_preview = array_flip($previews);

        // Get layers options
        $query = $db->createQuery();
        $query->select(MPSLDb::PARENT_ID)
              ->select('options')
              ->from(MPSLDb::LAYER_TABLE);
        $layers = $db->queryResults($query);

        $private_style_list = array();
        foreach ($layers as $data) {
            $slide_id = $data[MPSLDb::PARENT_ID];
            $options = $data['options'];

            if ($this->is_preview) {
                // Skip all slides that has a preview object
                if (array_key_exists($slide_id, $has_preview)) {
                    continue; // original
                }
            } else {
                // Skip layers from preview slides
                if (array_key_exists($slide_id, $previews)) {
                    continue; // preview
                }
            }

            $options = mpsl_json_decode_assoc($options);
            if ($options && isset($options['preset']) && $options['preset'] == 'private') {
                if (isset($options['private_preset_class']) && $options['private_preset_class']) {
                    $private_style_list[$options['private_preset_class']] = $options['private_styles'];
                }
            }
        }

        $css = $this->compile($private_style_list, true);
        if (is_string($css)) {
            if ($this->is_preview) {
                mpsl_update_option('mpsl_preview_private_css', $css);
            } else {
                mpsl_update_option('mpsl_private_css', $css);
            }
        }
    }

    public function addPresets($new_presets)
    {
        $new_classes = array();
        foreach ($new_presets as $pclass => $preset) {
            if ($this->isPresetClass($pclass)) {
                $last_preset_id = $this->incLastPresetId();
                $pclass_new = $this->presetClass($last_preset_id);
                $new_classes[$pclass] = $pclass_new;
                $this->custom_presets[$pclass_new] = $this->fillPreset($preset);
            }
        }
        return $new_classes;
    }

    public function getLastPresetId()
    {
        return $this->last_preset_id;
    }

    public function incLastPresetId()
    {
        $this->last_preset_id += 1;
        return $this->last_preset_id;
    }

    public function setLastPresetId($id)
    {
        if (is_numeric($id)) {
            $this->last_preset_id = (int)$id;
        }
    }

    public function getLastPrivateId()
    {
        return $this->last_private_id;
    }

    public function incLastPrivateId()
    {
        $this->last_private_id += 1;
        return $this->last_private_id;
    }

    public function setLastPrivateId($id)
    {
        if (is_numeric($id)) {
            $this->last_private_id = (int)$id;
        }
    }

    public function presetClass($id)
    {
        $class = self::PREFIX . $id;
        return $class;
    }

    public function privateClass($id)
    {
        $class = self::PRIVATE_PREFIX . $id;
        return $class;
    }

    public function isPresetClass($class)
    {
        $regex = '/^' . self::PREFIX . '[0-9]+$/';
        return (bool)preg_match($regex, $class);
    }

    public function isPrivateClass($class)
    {
        $regex = '/^' . self::PRIVATE_PREFIX . '[0-9]+$/';
        return (bool)preg_match($regex, $class);
    }

    public function getSettings($grouped = false)
    {
        return $this->settings->getSettings($grouped);
    }

    public function fontsUnique($fonts)
    {
        foreach ($fonts as $key => $value) {
            $fonts[$key]['variants'] = array_values(array_unique($value['variants']));
        }
        return $fonts;
    }

    public static function getDefaultCss()
    {
        if (mpsl_get_module()->is_preview_page) {
            $css = mpsl_get_option('mpsl_preview_default_css', '');
        } else {
            $css = mpsl_get_option('mpsl_default_css', '');
        }
        return mpsl_strip_tags($css);
    }

    public static function getCustomCss()
    {
        if (mpsl_get_module()->is_preview_page) {
            $css = mpsl_get_option('mpsl_preview_custom_css', '');
        } else {
            $css = mpsl_get_option('mpsl_custom_css', '');
        }
        return mpsl_strip_tags($css);
    }

    public static function getPrivateCss()
    {
        if (mpsl_get_module()->is_preview_page) {
            $css = mpsl_get_option('mpsl_preview_private_css', '');
        } else {
            $css = mpsl_get_option('mpsl_private_css', '');
        }
        return mpsl_strip_tags($css);
    }

    public static function getAllCss()
    {
        return self::getDefaultCss() . self::getCustomCss() . self::getPrivateCss();
    }
}
