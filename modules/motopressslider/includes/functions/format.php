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
 *     mpsl_mysql2date
 *     mpsl_slash
 *     mpsl_slash_left
 *     mpsl_str_substitute
 *     mpsl_strip_tags
 *     mpsl_unslash
 *     mpsl_unslash_left
 */

function mpsl_mysql2date($date, $format)
{
    if (empty($date)) {
        return false;
    }

    if ($format == 'G') {
        return strtotime($date . ' +0000');
    }

    $i = strtotime($date);

    if ($format == 'U') {
        return $i;
    }

    return date($format, $i);
}

function mpsl_slash($path)
{
    $path = mpsl_unslash($path);
    $path .= '/';
    return $path;
}

function mpsl_slash_left($path)
{
    $path = mpsl_unslash_left($path);
    $path = '/' . $path;
    return $path;
}

/**
 * Replaces equal values by various replacements. Instead of function
 * str_replace() replaces each value of $search only once. So result of the code
 *     str_substitute( array('%name%', '%name%'), array('First', 'Second'), '%name% %name%' );
 * will be
 *     "First Second"
 * instead of str_replace's
 *     "First First"
 */
function mpsl_str_substitute($search, $replace, $text)
{
    // Replace both to arrays
    $search = (array)$search;
    $replace = (array)$replace;
    $count = count($search);
    // Make the length of $replace equal to $search
    if (count($replace) < $count) {
        for ($i = count($replace); $i < $count; $i += 1) {
            $replace[$i] = $replace[$i - 1];
        }
    }
    // Do replacement
    for ($i = 0; $i < $count; $i += 1) {
        $pattern = '/' . preg_quote($search[$i], '/') . '/';
        $text = preg_replace($pattern, $replace[$i], $text, 1);
    }
    return $text;
}

function mpsl_strip_tags($string, $remove_breaks = false)
{
    $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
    $string = strip_tags($string);

    if ($remove_breaks) {
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
    }

    return trim($string);
}

function mpsl_unslash($path)
{
    $path = rtrim($path, '/\\');
    return $path;
}

function mpsl_unslash_left($path)
{
    $path = ltrim($path, '/\\');
    return $path;
}
