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
 *     mpsl_add_query_args
 *     mpsl_current_url
 *     mpsl_get_url_data
 *     mpsl_is_ssl
 *     mpsl_parse_query_args
 *     mpsl_remove_query_args
 *     mpsl_url
 */

function mpsl_add_query_args($url, $args, $skip_nulls = false)
{
    $additional_args = array();
    foreach ($args as $key => $value) {
        if (!is_null($value)) {
            $additional_args[] = $key . '=' . $value;
        } elseif (!$skip_nulls) {
            $additional_args[] = $key;
        }
    }
    if (!empty($additional_args)) {
        if (!mpsl_strinstr('?', $url)) {
            return $url . '?' . implode('&', $additional_args);
        } else {
            return $url . '&' . implode('&', $additional_args);
        }
    } else {
        return $url;
    }
}

function mpsl_current_url()
{
    return mpsl_shopinfo('current_url');
}

function mpsl_get_url_data($key = 'all')
{
    // Maybe URL data was already created?
    $module = mpsl_get_module();
    $urlinfo = $module->urlinfo;
    if (!empty($urlinfo)) {
        // Yes. Return the data
        return (array_key_exists($key, $urlinfo) ? $urlinfo[$key] : $urlinfo );
    }
    // Nope. Create new data

    // For https://www.pstest.com/sub1/admin123/index.php?controller=X&token=Y
    // $domain = www.pstest.com
    $domain = $_SERVER['HTTP_HOST'];
    // $base = https://www.pstest.com/sub1/
    $base = Tools::getHttpHost(true) . __PS_BASE_URI__;
    $base = mpsl_slash($base);
    // $basedir = sub1/
    $basedir = mpsl_unslash_left(__PS_BASE_URI__);
    // $request = admin123/index.php?controller=X&token=Y
    $request = str_replace($basedir, '', $_SERVER['REQUEST_URI']);
    $request = mpsl_unslash_left($request);
    // $script = index.php?controller=X&token=Y
    $script = mpsl_preg_match('/[^\/]*(?:\?.*$|$)/', $request, '');
    // $page = index.php
    $page = mpsl_preg_match('/[^\?]+/', $script, '');
    // $dir = admin123/
    $dir = str_replace($script, '', $request);
    // $path = https://www.pstest.com/sub1/admin123/
    $path = $base . $dir;
    // $url = https://www.pstest.com/sub1/admin123/index.php?controller=X&token=Y
    $url = $base . $dir . $script;

    // Save new URL data
    $urlinfo = array(
        'domain'  => $domain,
        'base'    => $base,
        'basedir' => $basedir,
        'request' => $request,
        'script'  => $script,
        'page'    => $page,
        'dir'     => $dir,
        'path'    => $path,
        'url'     => $url
    );
    $module->urlinfo = $urlinfo;

    if (array_key_exists($key, $urlinfo)) {
        return $urlinfo[$key];
    } else {
        return $urlinfo;
    }
}

function mpsl_is_ssl()
{
    if (isset($_SERVER['HTTPS'])) {
        if (Tools::strtolower($_SERVER['HTTPS']) == 'on') {
            return true;
        }
        if ($_SERVER['HTTPS'] == '1') {
            return true;
        }
    } elseif (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == '443')) {
        return true;
    }
    return false;
}

function mpsl_parse_query_args($url, $skip_nulls = false)
{
    $args_string = parse_url($url, PHP_URL_QUERY);
    $args_pairs = explode('&', $args_string);

    $args = array();
    foreach ($args_pairs as $pair) {
        if (mpsl_strinstr('=', $pair)) {
            list($name, $value) = explode('=', $pair);
            $args[$name] = $value;
        } elseif (!$skip_nulls) {
            $args[$pair] = null;
        }
    }

    return $args;
}

function mpsl_remove_query_args($url, $args, $skip_nulls = false)
{
    $parsed = mpsl_parse_query_args($url, $skip_nulls);
    foreach ($parsed as $arg) {
        if (array_key_exists($arg, $args)) {
            unset($parsed[$arg]);
        }
    }
    return $parsed;
}

/**
 * Generates an URL to local module's file or folder.
 */
function mpsl_url($subpath)
{
    $module_url = mpsl_shopinfo('module_url');
    $url = $module_url . mpsl_unslash_left($subpath);
    return $url;
}
