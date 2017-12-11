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
 *     mpsl_array_adiff
 *     mpsl_array_unset
 *     mpsl_count
 *     mpsl_first_key
 *     mpsl_implode_assoc
 *     mpsl_in
 *     mpsl_json_decode_assoc
 *     mpsl_provide_occurrence
 */

/**
 * Similar with function <b>array_diff_key</b> but as the first argument accepts
 * the array of keys that must differ.
 * @param array $needle An array of keys that must differ from $haystack array.
 */
function mpsl_array_adiff($needle, $haystack)
{
    $needle = array_flip($needle);
    $diff = array_diff_key($haystack, $needle);
    return $diff;
}

function mpsl_array_unset($key, &$array)
{
    if (array_key_exists($key, $array)) {
        unset($array[$key]);
    }
}

function mpsl_count($var)
{
    if (is_array($var)) {
        return count($var);
    } else {
        return 1;
    }
}

function mpsl_first_key($array)
{
    $keys = array_keys($array);
    $first_key = reset($keys);
    return $first_key;
}

function mpsl_implode_assoc($glue, $array, $glue_inner)
{
    foreach ($array as $key => &$item) {
        $item = $key . $glue_inner . $item;
    }
    $output = implode($glue, $array);
    $output .= trim($glue);
    return $output;
}

/**
 * @param array|mixed $haystack An array of items or another var.
 * @return boolean 1) In a case when $haystack is an array: returns TRUE if
 * $needle is an item of $haystack; 2) in a case when $haystack is not an array:
 * returns TRUE if $needle and $haystack are equal. Otherwise returns FALSE.
 */
function mpsl_in($needle, $haystack)
{
    if (!is_array($haystack)) {
        return ($needle === $haystack);
    } else {
        return in_array($needle, $haystack);
    }
}

function mpsl_json_decode_assoc($data, $default = array())
{
    $result = Tools::jsonDecode($data, true);
    if (!empty($result)) {
        if (is_array($default)) {
            $result = array_merge($default, $result);
        }
    } else {
        $result = $default;
    }
    return $result;
}

/**
 * Guarantees that the return value will be the item of $haystack.
 * @param mixed $needle
 * @param array $haystack
 * @return mixed $needle value if $needle is an item of $haystack; otherwise
 * will return the first value of $haystack.
 */
function mpsl_provide_occurrence($needle, $haystack)
{
    if (in_array($needle, $haystack)) {
        return $needle;
    } else {
        return reset($haystack);
    }
}
