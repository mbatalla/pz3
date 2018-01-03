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

class MPSLSettings
{
    private $name = 'entity';

    private $settings_dir = '';
    private $settings_file = '';

    private $settings = array();
    private $options = array();

    // $groups and $child_groups helps to find options in the $settings array
    // [%OPTION_NAME% => %OPTION_GROUP_NAME%]
    private $groups = array();
    // [%OPTION_NAME% => ['group' => %OPT_GROUP_NAME%, 'option' => %OPT_NAME%]]
    private $child_groups = array(); // For nested options

    private $dependency_types = array('dependency', 'disabled_dependency');

    public function __construct($settings_file = '', $settings_dir = '')
    {
        if (!empty($settings_file)) {
            $this->setFile($settings_file, $settings_dir);
            // Load default settings
            $this->loadSettings();
        }
    }

    public function updateOption($name, $value)
    {
        // Update option
        $this->options[$name] = $value;
        // Get group information and update the settings
        $group = $this->getGroup($name);
        $this->updateSetting($group, $name, 'value', $value);
    }

    public function updateOptions($options, $is_grouped = false)
    {
        if (!$is_grouped) {
            foreach ($options as $option_name => $option_value) {
                $this->updateOption($option_name, $option_value);
            }
        } else {
            foreach ($options as &$options_array) {
                foreach ($options_array as $option_name => $option_value) {
                    $this->updateOption($option_name, $option_value);
                }
            }
        }
    }

    public function setOption($name, $value)
    {
        $this->updateOption($name, $value);
    }

    public function setOptions($options, $is_grouped = false)
    {
        $this->updateOptions($options, $is_grouped);
    }

    /**
     * @param string|array|NULL $group Group data from method <b>getGroupData()</b>.
     * @param string $param Which parameter of the option to update ("label",
     * "description", "default", "value" etc).
     * @see MPSLSetting::getGroupData()
     */
    public function updateSetting($group, $name, $param, $value)
    {
        // If $group is NULL then the setting does not exist and there is
        // nothing to update
        if (!is_null($group)) {
            if (!is_array($group)) {
                // Not a nested option
                $this->settings[$group]['options'][$name][$param] = $value;
            } else {
                // Is a nested option
                $this->settings[$group['group']]['options'][$group['option']]['options'][$name][$param] = $value;
            }
        }
    }

    public function updateSettings($settings)
    {
        foreach ($settings as $group => &$group_settings) {
            if (!isset($group_settings['product_hidden'])) {
                $group_settings['product_hidden'] = false;
            }
            foreach ($group_settings['options'] as $name => $option) {

                // Prepare some default parameters
                $settings[$group]['options'][$name] = $this->updateDefaults($name, $option);
                // Add option and it's group
                $this->prepareOption($name, $option['default'], $group);

                // Search for nested options (for example, see /includes/settings/layer.php)
                if (isset($option['options'])) {
                    foreach ($option['options'] as $subname => $suboption) {

                        // Prepare some default parameters
                        $settings[$group]['options'][$name]['options'][$subname] = $this->updateNestedDefaults(
                            $subname,
                            $suboption,
                            $settings[$group]['options'][$name]
                        );
                        // Add option and it's group data
                        $this->prepareOption(
                            $subname,
                            $suboption['default'],
                            array('group' => $group, 'option' => $name)
                        );

                    } // For each nested option
                } // If nested options found

            } // For each option
        } // For each group

        $this->settings = array_merge($this->settings, $settings);
    } // Function updateSettings

    private function prepareOption($name, $value, $group = null)
    {
        $this->options[$name] = $value;
        // Add group
        if (!is_null($group)) {
            if (is_string($group)) {
                $this->groups[$name] = $group;
            } else {
                // array('group' => ..., 'option' => ...) for nested option
                $this->child_groups[$name] = $group;
            }
        }
    }

    private function updateDefaults($name, $option)
    {
        // Set default value if not exists
        if (!isset($option['default'])) {
            $option['default'] = '';
        }

        // Set default values for non-existing parameters
        $defaults = array(
            'name' => $name,
            'value' => $option['default'],
            'unit' => '',
            'hidden' => false,
            'isChild' => false
        );
        $option = array_merge($defaults, $option);

        // Update dependencies
        foreach ($this->dependency_types as $dep) {
            if (array_key_exists($dep, $option)) {
                // Delete empty dependencies
                if (mpsl_count($option[$dep]) == 0) {
                    unset($option[$dep]);
                    continue;
                }
                // Check operator
                if (array_key_exists('operator', $option[$dep])) {
                    $option[$dep]['operator'] = Tools::strtoupper($option[$dep]['operator']);
                    // Replace "!IN" operator with "NOT IN"
                    if ($option[$dep]['operator'] == '!IN') {
                        $option[$dep]['operator'] = 'NOT IN';
                    }
                } else {
                    // Set default operator
                    $option[$dep]['operator'] = 'IN';
                }
                // Convert all values to array and sort it
                if (is_array($option[$dep]['value'])) {
                    sort($option[$dep]['value']);
                } else {
                    $option[$dep]['value'] = (array)$option[$dep]['value'];
                }
                // Convert all boolean values to numbers
                foreach ($option[$dep]['value'] as &$value) {
                    if (is_bool($value)) {
                        $value = (int)$value;
                    }
                }
            }
        } // foreach

        // Option settings updated
        return $option;
    }

    private function updateNestedDefaults($subname, $suboption, $option)
    {
        // Set default value if not exists
        if (!isset($suboption['default'])) {
            $suboption['default'] = '';
        }

        // Set default values for non-existing parameters
        $defaults = array(
            'name' => $subname,
            'value' => $suboption['default'],
            'unit' => '',
            'isChild' => true
        );
        $suboption = array_merge($defaults, $suboption);

        // Update dependencies
        foreach ($this->dependency_types as $dep) {
            if (!array_key_exists($dep, $suboption) && array_key_exists($dep, $option)) {
                $suboption[$dep] = $option[$dep];
            }
        }

        return $suboption;
    }

    public function getOption($name, $default = '')
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        } else {
            return $default;
        }
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getDefaultOptions()
    {
        $options = array();
        foreach (array_keys($this->options) as $name) {
            $group = $this->getGroup($name);
            $options[$name] = $this->getSetting($group, $name, 'default');
        }
        return $options;
    }

    public function getNonDefaultOptions()
    {
        $options = array();
        // Get all options and check with it's default values
        foreach ($this->options as $name => $value) {
            $group = $this->getGroup($name);
            $setting_value = $this->getSetting($group, $name, 'default');
            if ($value !== $setting_value) {
                $options[$name] = $value;
            }
        }
        return $options;
    }

    public function getSanitizedOptions($sanitize_options = array())
    {
        $options = $this->getNonDefaultOptions();
        $options = self::sanitize($options, $sanitize_options);
        return $options;
    }

    /**
     * Take options only from specified group.
     */
    public function getGroupOptions($group)
    {
        $options = array();
        if (array_key_exists($group, $this->settings)) {
            $option_names = array_keys($this->settings[$group]['options']);
            foreach ($option_names as $option) {
                $options[$option] = $this->options[$option];
            }
        }
        return $options;
    }

    /**
     * @param string|array|NULL $group Group data from method <b>getGroupData()</b>.
     * @param string $param Which parameter of the option to update ("label",
     * "description", "default", "value" etc).
     * @see MPSLSetting::getGroupData()
     */
    public function getSetting($group, $name, $param, $default = '')
    {
        // If $group is NULL then the setting does not exist
        if (!is_null($group)) {
            if (!is_array($group)) {
                // Not a nested option
                return $this->settings[$group]['options'][$name][$param];
            } else {
                // Is a nested option
                return $this->settings[$group['group']]['options'][$group['option']]['options'][$name][$param];
            }
        } else {
            return $default;
        }
    }

    public function getSettings($is_grouped = false)
    {
        if ($is_grouped) {
            return $this->settings;
        } else {
            $settings = array();
            foreach ($this->settings as &$group) {
                // Get options from all groups
                $settings = array_merge($settings, $group['options']);
                // Search for nested options
                foreach ($group['options'] as $option) {
                    if (array_key_exists('options', $option)) {
                        $settings = array_merge($settings, $option['options']);
                    }
                }
            }
            return $settings;
        }
    }

    public function hasOption($option)
    {
        return array_key_exists($option, $this->options);
    }

    public function extendListAttr($group, $option, $new_items)
    {
        $this->settings[$group]['options'][$option]['list'] =
            array_merge($this->settings[$group]['options'][$option]['list'], $new_items);
    }

    /**
     * Reads default settings from specified file.
     */
    public function readSettings()
    {
        $path = $this->settings_dir . $this->settings_file;
        if (file_exists($path)) {
            return include($path);
        } else {
            return array();
        }
    }

    private function loadSettings()
    {
        $default_settings = $this->readSettings();
        $this->updateSettings($default_settings);
    }

    public function setFile($settings_file, $settings_dir = '')
    {
        $this->settings_file = trim($settings_file);
        $this->name = mpsl_preg_match('/^\w+/', $this->settings_file);
        if (empty($settings_dir)) {
            $this->settings_dir = mpsl_dir('includes/settings/');
        } else {
            $this->settings_dir = mpsl_slash($settings_dir);
        }
    }

    public function getGroup($option_name)
    {
        $group = array();

        if (array_key_exists($option_name, $this->groups)) {
            // Option from main groups
            $group = $this->groups[$option_name]; // String
        } elseif (array_key_exists($option_name, $this->child_groups)) {
            // Option from one of the nested groups
            $group = $this->child_groups[$option_name]; // Array
        } else {
            // Option not found
            $group = null;
        }

        return $group;
    }

    public function reset()
    {
        $this->settings = array();
        $this->options = array();
        $this->groups = array();
        $this->loadSettings(); // Reload settings
    }

    public static function sanitize($options, $sanitize_options = array())
    {
        foreach ($sanitize_options as $option) {
            if (array_key_exists($option, $options)) {
                $options[$option] = htmlspecialchars($options[$option], ENT_QUOTES);
            }
        }
        return $options;
    }

    public function encode($sanitize_options = array())
    {
        $options = $this->getSanitizedOptions($sanitize_options);
        $json = Tools::jsonEncode($options);
        $json = addslashes($json);
        return $json;
    }

    /**
     * @return void Nothing.
     */
    public static function decode($json, $sanitized_options = array())
    {
        $json = str_replace(PHP_EOL, "\n", $json);
        $json = str_replace("\t", '    ', $json);
        $options = mpsl_json_decode_assoc($json);
        foreach ($sanitized_options as $option) {
            if (array_key_exists($option, $options)) {
                $options[$option] = htmlspecialchars_decode($options[$option], ENT_QUOTES);
            }
        }
        return $options;
    }
}
