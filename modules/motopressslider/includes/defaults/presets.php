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
    'mpsl-btn-blue' => array(
        'settings' => array(
            'label' => $module->l('Button Blue'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-size' => '18',
            'padding-top' => '12',
            'padding-bottom' => '13',
            'padding-left' => '28',
            'padding-right' => '28',
            'border-radius' => '5',
            'color' => '#ffffff',
            'background-color' => '#20b9d5',
            'text-shadow' => '0px 1px 0px #06879f',
            'text_shadow_color' => '#06879f',
            'text_shadow_hor_len' => '0',
            'text_shadow_vert_len' => '1',
            'text_shadow_radius' => '0',
            'custom_styles' =>
                'text-decoration: none;'
                . '-webkit-box-shadow: 0px 2px 0px 0px #06879f;'
                . '-moz-box-shadow: 0px 2px 0px 0px #06879f;'
                . 'box-shadow: 0px 2px 0px 0px #06879f;'
        )
    ),

    'mpsl-btn-green' => array(
        'settings' => array(
            'label' => $module->l('Button Green'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-size' => '18',
            'padding-top' => '12',
            'padding-bottom' => '13',
            'padding-left' => '28',
            'padding-right' => '28',
            'border-radius' => '5',
            'color' => '#ffffff',
            'background-color' => '#58cf6e',
            'text-shadow' => '0px 1px 0px #17872d',
            'text_shadow_color' => '#17872d',
            'text_shadow_hor_len' => '0',
            'text_shadow_vert_len' => '1',
            'text_shadow_radius' => '0',
            'custom_styles' =>
                'text-decoration: none;'
                . '-webkit-box-shadow: 0px 2px 0px 0px #2ea044;'
                . '-moz-box-shadow: 0px 2px 0px 0px #2ea044;'
                . 'box-shadow: 0px 2px 0px 0px #2ea044;'
        )
    ),

    'mpsl-btn-red' => array(
        'settings' => array(
            'label' => $module->l('Button Red'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-size' => '18',
            'padding-top' => '12',
            'padding-bottom' => '13',
            'padding-left' => '28',
            'padding-right' => '28',
            'border-radius' => '5',
            'color' => '#ffffff',
            'background-color' => '#e75d4a',
            'text-shadow' => '0px 1px 0px #c03826',
            'text_shadow_color' => '#c03826',
            'text_shadow_hor_len' => '0',
            'text_shadow_vert_len' => '1',
            'text_shadow_radius' => '0',
            'custom_styles' =>
                'text-decoration: none;'
                . '-webkit-box-shadow: 0px 2px 0px 0px #cd3f2b;'
                . '-moz-box-shadow: 0px 2px 0px 0px #cd3f2b;'
                . 'box-shadow: 0px 2px 0px 0px #cd3f2b;'
        )
    ),

    'mpsl-txt-header-dark' => array(
        'settings' => array(
            'label' => $module->l('Header Dark'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-weight' => '300',
            'font-size' => '48',
            'color' => '#000000',
            'custom_styles' => 'letter-spacing: -0.025em;'
        )
    ),

    'mpsl-txt-header-white' => array(
        'settings' => array(
            'label' => $module->l('Header White'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-weight' => '300',
            'font-size' => '48',
            'color' => '#ffffff',
            'custom_styles' => 'letter-spacing: -0.025em;'
        )
    ),

    'mpsl-txt-sub-header-dark' => array(
        'settings' => array(
            'label' => $module->l('Sub-Header Dark'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-weight' => '300',
            'font-size' => '26',
            'background-color' => 'rgba(0, 0, 0, 0.6)',
            'color' => '#ffffff',
            'padding-top' => '14',
            'padding-right' => '14',
            'padding-bottom' => '14',
            'padding-left' => '14'
        )
    ),

    'mpsl-txt-sub-header-white' => array(
        'settings' => array(
            'label' => $module->l('Sub-Header White'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-weight' => '300',
            'font-size' => '26',
            'background-color' => 'rgba(255, 255, 255, 0.6)',
            'color' => '#000000',
            'padding-top' => '14',
            'padding-right' => '14',
            'padding-bottom' => '14',
            'padding-left' => '14'
        )
    ),

    'mpsl-txt-dark' => array(
        'settings' => array(
            'label' => $module->l('Text Dark'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-weight' => 'normal',
            'font-size' => '18',
            'line-height' => '30',
            'color' => '#000000',
            'text-shadow' => '0px 1px 0px rgba(255, 255, 255, 0.45)',
            'text_shadow_color' => 'rgba(255, 255, 255, 0.45)',
            'text_shadow_hor_len' => '0',
            'text_shadow_vert_len' => '1',
            'text_shadow_radius' => '0'
        )
    ),

    'mpsl-txt-white' => array(
        'settings' => array(
            'label' => $module->l('Text White'),
            'hover' => true
        ),
        'style' => array(
            'font-family' => 'Open Sans',
            'font-weight' => 'normal',
            'font-size' => '18',
            'line-height' => '30',
            'color' => '#ffffff',
            'text-shadow' => '0px 1px 0px rgba(0, 0, 0, 0.45)',
            'text_shadow_color' => 'rgba(0, 0, 0, 0.45)',
            'text_shadow_hor_len' => '0',
            'text_shadow_vert_len' => '1',
            'text_shadow_radius' => '0'
        )
    ),
);
