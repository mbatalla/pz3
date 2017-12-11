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
 *     mpsl_get_context
 *     mpsl_get_cover
 *     mpsl_get_cover_id
 *     mpsl_get_cover_urls
 *     mpsl_get_db
 *     mpsl_get_image_types
 *     mpsl_get_module
 *     mpsl_get_preview_type
 *     mpsl_get_smarty
 *     mpsl_id_lang
 *     mpsl_id_lang_default
 *     mpsl_id_langs
 *     mpsl_id_shop
 *     mpsl_is_mobile
 */

/** @return Context */
function mpsl_get_context()
{
    return Context::getContext();
}

/**
 * @return array ["path", "width", "height"].
 */
function mpsl_get_cover($id_image)
{
    $cover = array(
        'file' => '',
        'format' => 'jpg',
        'url' => '',
        'width' => 0,
        'height' => 0
    );

    if ($id_image > 0) {
        $image = new Image($id_image);
        $file = $id_image . '.' . $image->image_format;
        $url = _PS_BASE_URL_ . _THEME_PROD_DIR_ . $image->getImgFolder() . $file;
        $image_size = getimagesize($url);
        // Update cover data
        $cover['file'] = $file;
        $cover['format'] = $image->image_format;
        $cover['url'] = $url;
        $cover['width'] = $image_size[0];
        $cover['height'] = $image_size[1];
    }

    return $cover;
}

function mpsl_get_cover_id($id_product)
{
    // Image::getCover($id_product) will return NULL
    $cover_data = Product::getCover($id_product);
    if (count($cover_data) > 0) {
        return (int)$cover_data['id_image'];
    } else {
        return 0;
    }
}

/**
 * @param array|string $sizes Optional. Cover types. In arrays does not uses
 * keys. For full image use "mpsl_fullsize" (MotoPressSlider::FULLSIZE).
 * Default: "mpsl_fullsize".
 * @return array ["small_default" => %URL%, "mpsl_fullsize" => %URL%, ...].
 */
function mpsl_get_cover_urls($id_image, $sizes = MotoPressSlider::FULLSIZE)
{
    $covers = array();
    if (!is_array($sizes)) {
        $sizes = (array)$sizes;
    }

    if ($id_image) {
        $image = new Image($id_image);
        $path = _PS_BASE_URL_ . _THEME_PROD_DIR_ . $image->getExistingImgPath();
        foreach ($sizes as $size) {
            if ($size == MotoPressSlider::FULLSIZE) {
                $file = $path . '.' . $image->image_format;
            } else {
                $file = $path . '-' . $size . '.' . $image->image_format;
            }
            $covers[$size] = $file;
        }
    } else {
        foreach ($sizes as $size) {
            $covers[$size] = '';
        }
    }

    return $covers;
}

/** @return MPSLDb */
function mpsl_get_db()
{
    $db = Context::getContext()->mpsldb;
    return $db;
}

/**
 * @return array Image types, like: "cart_default", "small_default",
 * "medium_default" etc (["name" => ..., "width" => ..., "height" => ..., ...]).
 */
function mpsl_get_image_types()
{
    return ImageType::getImagesTypes('products', true);
}

/** @return MotoPressSlider */
function mpsl_get_module()
{
    $module = Module::getInstanceByName('motopressslider');
    return $module;
}

function mpsl_get_preview_type()
{
    $side = (class_exists('MPSLAttachment') ? MPSLAttachment::PREVIEW_SIZE : 150);
    $preferred_size = $side*$side; // Square image

    $types = mpsl_get_image_types();

    $factors = array();
    foreach ($types as $data) {
        $name = $data['name'];
        $w = $data['width'];
        $h = $data['height'];
        // Calculate size factor
        $size = $w*$h;
        $size_factor = $preferred_size/$size;
        if ($size_factor > 1) {
            $size_factor = 1/$size_factor;
        }
        // Calculate proportions factor
        $prop_factor = $w/$h;
        if ($prop_factor > 1) {
            $prop_factor = 1/$prop_factor;
        }
        // Calculate general factor
        $factor = (int)($size_factor*$prop_factor*100); // Will get %
        $factors[$name] = $factor;
    }

    arsort($factors);
    $sorted_names = array_flip($factors);
    return reset($sorted_names);
}

function mpsl_get_smarty()
{
    $smarty = Context::getContext()->smarty;
    return $smarty;
}

/**
 * @return int Default language ID.
 */
function mpsl_id_lang()
{
    return Context::getContext()->language->id;
}

/**
 * @return int Language ID.
 */
function mpsl_id_lang_default()
{
    $default_language_id = (int)Configuration::get('PS_LANG_DEFAULT');
    return $default_language_id;
}

function mpsl_id_langs()
{
    $id_lang = mpsl_id_lang();
    $id_lang_default = mpsl_id_lang_default();
    $id_langs = array($id_lang_default, $id_lang);
    return $id_langs;
}

function mpsl_id_shop()
{
    $id_shop = Shop::getContextShopID();
    return $id_shop;
}

function mpsl_is_mobile()
{
    $is_mobile = (bool)Context::getContext()->getMobileDevice();
    return $is_mobile;
}
