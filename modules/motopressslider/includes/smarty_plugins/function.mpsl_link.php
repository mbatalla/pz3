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
 * File:     function.mpsl_link.php
 * Type:     function
 * Name:     mpsl_link
 * Purpose:  creates link to the controller page
 * -----------------------------------------------------------------------------
 */

/**
 * @param array $params
 */
function smarty_function_mpsl_link($params)
{
    $atts = array_merge(array(
        'view' => 'sliders',
        'id' => null,
        'append' => ''
    ), $params);

    $ctrls = new MPSLCtrls();
    $link = $ctrls->getLink($atts['view'], $atts['id'], $atts['append']);

    return $link;
}
