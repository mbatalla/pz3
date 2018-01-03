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

/*
 * Functions:
 *     motoSlider
 *     mpsl_current_time
 *     mpsl_current_timezone_offset
 *     mpsl_filter_variants
 *     mpsl_get
 *     mpsl_get_timezone_offset
 *     mpsl_get_useragent
 *     mpsl_is_mpce_editor
 *     mpsl_strinstr
 *     mpsl_todebug
 *     mpsl_translate_php_upload_error
 */

/**
 * Examples:
 *     echo motoSlider(new MPSLSlider(...));
 *     echo motoSlider(5);
 *     echo motoSlider('sample-slider');
 * @param mixed $slider MPSLSlider object, integer slider ID or string slider
 * alias name.
 */
function motoSlider($slider, $is_edit_mode = false, $is_slide_preview = false)
{
    $slider_shortcode = new MPSLShortcode($slider, $is_edit_mode, $is_slide_preview);
    return $slider_shortcode->doShortcode();
}

function mpsl_current_time()
{
    $offset = mpsl_current_timezone_offset();
    $current_time = time() + $offset;
    return $current_time;
}

function mpsl_current_timezone_offset()
{
    $offset = mpsl_get_transient('mpsl_gmt_offset', false);
    if ($offset === false) {
        $timezone = Configuration::get('PS_TIMEZONE', 'Europe/London');
        $offset = mpsl_get_timezone_offset($timezone);
        mpsl_set_transient('mpsl_gmt_offset', $offset, MotoPressSlider::SECONDS_IN_DAY);
    }
    return $offset;
}

function mpsl_filter_variants($keep, $variants)
{
    if (!is_array($keep)) {
        $keep = (array)$keep;
    }

    foreach ($variants as $name => $data) {
        if (isset($data['variants'])) {
            // Delete all variant groups, keep only what was defined in $keep
            // argument
            $groups = array_keys($data['variants']);
            $delete_groups = array_diff($groups, $keep);
            foreach ($delete_groups as $group) {
                unset($variants[$name]['variants'][$group]);
            }
            // Delete all variant groups if it's only 1 (or 0) variant left
            $delete_all_groups = true;
            $variants_left = 0;
            foreach ($variants[$name]['variants'] as $group => $items) {
                $variants_left += count($items);
                if ($variants_left > 1) {
                    $delete_all_groups = false;
                    break;
                }
            }
            // Delete al variant groups if required
            if ($delete_all_groups) {
                unset($variants[$name]['variants']);
            }
        }
    }

    return $variants;
}

/**
 * Safe way to get the value of the variable than you don't sure it's set.
 * @param mixed $target Any var that you don't sure it's set.
 * @return mixed $target's value or $default value, if $target is not set.
 */
function mpsl_get(&$haystack, $needle, $default = false)
{
    if (array_key_exists($needle, $haystack)) {
        return $haystack[$needle];
    } else {
        return $default;
    }
}

/**
 * @author <dan@authenticdesign.net>
 * @link http://php.net/manual/en/function.timezone-offset-get.php#83356
 */
function mpsl_get_timezone_offset($remote_tz, $origin_tz = null)
{
    if (is_null($origin_tz)) {
        $origin_tz = date_default_timezone_get();
        if (!is_string($origin_tz)) {
            return 0; // An UTC timestamp was returned - bail out!
        }
    }

    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);

    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);

    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    return $offset;
}

function mpsl_get_useragent()
{
    $base_url = mpsl_shopinfo('base_url');
    $useragent = 'PrestaShop/' . _PS_VERSION_ . '; ' . $base_url;
    return $useragent;
}

/**
 * Update hooked sliders list.
 */
function mpsl_hook_slider($alias, $hooks)
{
    $module = mpsl_get_module();
    // Remove old hooks
    $module->unhookSlider($alias);
    // Add new hooks
    if (!empty($hooks) && is_array($hooks)) {
        foreach ($hooks as $hook) {
            $module->hookSlider($hook, $alias);
        }
    }
    // Update slider hooks in database
    $module->updateHookedSliders();
}

function mpsl_is_mpce_editor()
{
    $is_mpce_editor = Tools::getValue('motopress-ce', false);
    if (!$is_mpce_editor) {
        $action = Tools::getValue('action', 'undefined');
        if ($action == 'motopress_ce_render_shortcode') {
            $is_mpce_editor = true;
        }
    }
    return $is_mpce_editor;
}

function mpsl_strinstr($needle, $haystack)
{
    return (Tools::strpos($haystack, $needle) !== false ? true : false);
}

function mpsl_todebug()
{
    $context = Context::getContext();
    $log_file = _PS_MODULE_DIR_ . 'motopressslider/debug.log';
    // Get arguments
    $argc = func_num_args();
    $args = func_get_args();

    // Clear log file for a first time
    if (!isset($context->debug_started) || !$context->debug_started) {
        @file_put_contents($log_file, '');
        $context->debug_started = true;
    }

    if ($argc == 0) {
        // Just add new line
        @file_put_contents($log_file, PHP_EOL, FILE_APPEND);
    } else {
        // Create the message
        $message = '';
        $argn = 1;
        foreach ($args as $arg) {
            if (is_string($arg)) {
                // Show text
                $message .= $arg;
            } else {
                // Show another format of var
                ob_start();
                    var_dump($arg);
                $message_part = ob_get_clean();
                $message .= str_replace(PHP_EOL, '', $message_part);
            }
            // Add space between all words
            if ($argn < $argc) {
                $message = trim($message) . ' ';
            }
            $argn += 1;
        }

        // Add "new line" character(-s)
        $message .= PHP_EOL;

        // Flush the message
        @file_put_contents($log_file, $message, FILE_APPEND);
    } // If args count >= 1
}

function mpsl_translate_php_upload_error($error_code)
{
    $module = mpsl_get_module();
    $message = '';
    switch ($error_code) {
        case 1:
            $message = $module->l('File exceeds the upload_max_filesize directive in php.ini.');
            break;
        case 2:
            $message = $module->l('File exceeds the MAX_FILE_SIZE directive (in HTML form).');
            break;
        case 3:
            $message = $module->l('The uploaded file was only partially uploaded.');
            break;
        case 4:
            $message = $module->l('No file was uploaded.');
            break;
        case 6:
            $message = $module->l('Missing a temporary folder.');
            break;
        case 7:
            $message = $module->l('Failed to write file to disk.');
            break;
        case 8:
            $message = $module->l('An unknown PHP extension stopped the file upload.');
            break;
        default:
            $message = sprintf($module->l('Unrecognizable PHP upload error. Error code: %d.'), $error_code);
            break;
    }
    return $message;
}
