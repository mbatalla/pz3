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

$module = mpsl_get_module();

return array(
    'custom' => array(
        'label' => $module->l('Custom Slider'),
        'id' => 'mpsl-custom-slider',
        'slider_type' => 'custom',
        'description' => $module->l('create unlimited slides with images, text, buttons and videos'),
        'selected' => true
    ),
    'product' => array(
        'label' => $module->l('Product Slider'),
        'id' => 'mpsl-product-slider',
        'slider_type' => 'product',
        'description' => $module->l('create from products of your shop'),
        'selected' => false
    )
);
