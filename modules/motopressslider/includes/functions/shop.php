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
 *     mpsl_cache_get
 *     mpsl_cache_set
 *     mpsl_create_nonce
 *     mpsl_delete_option
 *     mpsl_delete_transient
 *     mpsl_get_categories
 *     mpsl_get_option
 *     mpsl_get_tags
 *     mpsl_get_transient
 *     mpsl_get_unstripped_value
 *     mpsl_is_admin_user
 *     mpsl_is_logged_in
 *     mpsl_set_transient
 *     mpsl_shopinfo
 *     mpsl_update_option
 *     mpsl_verify_nonce
 */

function mpsl_cache_get($key)
{
    return Cache::retrieve($key);
}

function mpsl_cache_set($key, $value)
{
    Cache::store($key, $value);
}

function mpsl_create_nonce($key)
{
    // Tools::getToken() is a good way to generate notices
    $context = Context::getContext();
    if ($context && isset($context->customer)) {
        $keyword = $context->customer->id . $context->customer->passwd . $key;
        return Tools::encrypt($keyword);
    } else {
        return Tools::encrypt($key);
    }
}

function mpsl_delete_option($key)
{
    return Configuration::deleteByName($key);
}

function mpsl_delete_transient($key)
{
    return Configuration::deleteByName($key);
}

function mpsl_get_categories()
{
    return Category::getCategories();
}

function mpsl_get_option($key, $default = false)
{
    $result = Configuration::get($key);
    if ($result !== false) {
        // If value is string, but default value is array, then the option value
        // was encoded from array to string and we need to decode it back
        if (is_string($result) && is_array($default)) {
            $result = mpsl_json_decode_assoc($result, $default);
        }
    } else {
        $result = $default;
    }
    return $result;
}

function mpsl_get_tags()
{
    $id_lang = mpsl_id_lang();

    $db = mpsl_get_db();
    $query = $db->createQuery();

    $query->select('id_tag', 'id')
          ->select('name')
          ->from('tag')
          ->whereIs('id_lang', $id_lang)
          ->orderBy('id_lang', 'DESC')
          ->orderBy('id_tag', 'ASC');

    $tags = $db->queryResults($query);
    return $tags;
}

function mpsl_get_transient($key, $default = false)
{
    $transient_value = Configuration::get($key);

    if ($transient_value !== false) {
        $transient_data = Tools::jsonDecode($transient_value, true);

        if ($transient_data) {
            $current_time = time();
            $expiration_time = (isset($transient_data['expire']) ? $transient_data['expire'] : $current_time + 1);

            if ($expiration_time == 'never' || $expiration_time > $current_time) {
                return (isset($transient_data['value']) ? $transient_data['value'] : $default);
            } else {
                mpsl_delete_transient($key);
                return $default;
            }
        }
    }

    // Otherwise...
    return $default;
}

if (!get_magic_quotes_gpc()) {
    /**
     * It's equal to function <b>Tools::getValue()</b> but does not uses the
     * <b>stripslashes</b> function on a result value (does not converts quotes
     * into code <i>&...;</i> or <i>&#...;</i>).
     */
    function mpsl_get_unstripped_value($key, $default_value = false)
    {
        if (!Tools::getIsset($key)) {
            return $default_value;
        }

        $all_values = Tools::getAllValues();
        $ret = $all_values[$key]; // It exists, we checked

        if (is_string($ret)) {
            return urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
        }

        return $ret;
    }
} else {
    /**
     * It's equal to function <b>Tools::getValue()</b> but does not uses the
     * <b>stripslashes</b> function on a result value (does not converts quotes
     * into code <i>&...;</i> or <i>&#...;</i>).
     */
    function mpsl_get_unstripped_value($key, $default_value = false)
    {
        if (!Tools::getIsset($key)) {
            return $default_value;
        }

        $all_values = Tools::getAllValues();
        $ret = $all_values[$key]; // It exists, we checked

        if (is_string($ret)) {
            $ret = Tools::stripslashes($ret); // Remove magic quotes
            return urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
        }

        return $ret;
    }
}

function mpsl_is_admin_user()
{
    $cookie = new Cookie('psAdmin');
    if ($cookie->id_employee) {
        return true;
    }
    return false;
}

function mpsl_is_logged_in()
{
    $is_logged_in = false;
    $cookie = new Cookie('psAdmin');
    if ($cookie && $cookie->id_employee) {
        $is_logged_in = true;
    }
    return $is_logged_in;
}

function mpsl_set_transient($key, $data, $expire = 0)
{
    if ($expire > 0) {
        $expiration_time = time() + $expire;
    } else {
        $expiration_time = 'never';
    }

    $transient_value = array(
        'value' => $data,
        'expire' => $expiration_time
    );
    $new_data = Tools::jsonEncode($transient_value);

    $response = Configuration::updateValue($key, $new_data);
    return $response;
}

/**
 * @param string $key Available keys: <b>base_url</b>, <b>admin_url</b>,
 * <b>module_url</b>, <b>current_url</b>, <b>url</b> (equals to "current_url")
 * and <b>name</b>.
 */
function mpsl_shopinfo($key = 'url')
{
    // Get existing shop information
    $module = mpsl_get_module();
    $shopinfo = $module->shopinfo;
    if (!empty($shopinfo)) {
        // Shop information already initialized. Just get the value
        if (array_key_exists($key, $shopinfo)) {
            return $shopinfo[$key];
        } else {
            return $shopinfo['url'];
        }
    }
    // Shop information not initialized. Create it

    // Get URL parts
    $parts = mpsl_get_url_data();

    $shop_name = mpsl_get_option('PS_SHOP_NAME', null);
    if (is_null($shop_name)) {
        $shop_name = $parts['domain'];
    }

    // Save new shop information
    $shopinfo = array(
        'base_url'    => $parts['base'],
        'admin_url'   => $parts['path'] . 'index.php',
        'module_url'  => $parts['base'] . 'modules/motopressslider/',
        'current_url' => $parts['url'],
        'url'         => $parts['url'],
        'name'        => $shop_name
    );
    $module->shopinfo = $shopinfo;

    $response = mpsl_get($shopinfo, $key, $shopinfo['url']);
    return $response;
}

function mpsl_update_option($key, $value)
{
    if (is_array($value)) {
        $value = Tools::jsonEncode($value);
    }
    $response = Configuration::updateValue($key, $value);
    return $response;
}

function mpsl_verify_nonce($nonce, $key)
{
    $good_nonce = mpsl_create_nonce($key);
    $is_good = ($good_nonce === $nonce);
    return $is_good;
}
