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
 * File:     function.mpsl_input.php
 * Type:     function
 * Name:     mpsl_input
 * Purpose:  creates link to the controller page
 * -----------------------------------------------------------------------------
 */

/**
 * @param array $params
 */
function smarty_function_mpsl_input($params)
{
    $atts = array_merge(array(
        'settings' => null,
        'type' => 'default',
        'prepend' => ''
    ), $params);

    $settings = $atts['settings'];
    $prepend = $atts['prepend'];

    if (is_null($settings)) {
        return '';
    }

    $form = MPSLForm::getInstance();
    $input = '';
    switch ($atts['type']) {
        case 'default':
            $input = $form->createControl($settings, $prepend);
            break;
        case 'alternate':
            $input = $form->createAlternateControl($settings, $prepend);
            break;
        case 'grouped':
            $input = $form->createControlGroup($settings);
            break;
    }

    return $input;
}
