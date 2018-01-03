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
 * Smarty plugin
 * -----------------------------------------------------------------------------
 * File:     function.motoslider.php
 * Type:     function
 * Name:     motoslider
 * Purpose:  outputs basis markup of the slider
 * -----------------------------------------------------------------------------
 */

/**
 * @param array $params
 */
function smarty_function_motoslider($params)
{
    $atts = array_merge(array(
        'slider' => 0,
        'edit_mode' => false,
        'slide_preview' => false
    ), $params);

    if (empty($atts['slider'])) {
        return '';
    } else {
        $slider_html = motoSlider($atts['slider'], $atts['edit_mode'], $atts['slide_preview']);
        return $slider_html;
    }
}
