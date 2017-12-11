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

$fonts = array();
if (!mpsl_is_ajax() && mpsl_is_admin_user()) {
    $fonts = mpsl_cache_get('mpsl_gfonts');
    if (empty($fonts)) {
        $google_fonts = Tools::file_get_contents($module->vendor_dir . 'googlefonts/webfonts.json');
        $google_fonts = ($google_fonts ? mpsl_json_decode_assoc($google_fonts) : array());

        $fonts[''] = '-- ' . $module->l('SELECT') . ' --';

        if (!is_null($google_fonts) && isset($google_fonts['items'])) {
            foreach ($google_fonts['items'] as $gfont) {
                foreach ($gfont['variants'] as $key => $variant) {
                    if (Tools::strpos($variant, 'italic') !== false) {
                        unset($gfont['variants'][$key]);
                        continue;
                    }
                    $gfont['variants'][$key] = str_replace('regular', 'normal', $variant);
                }
                $fonts[$gfont['family']] = array(
                    'label' => $gfont['family'],
                    'atts' => array(
                        'data-variants' => $gfont['variants']
                    )
                );
            }
        }
        mpsl_cache_set('mpsl_gfonts', $fonts);
    }
}

return array(
    'font-typography' => array(
        'title' => $module->l('Font and typography'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'allow_style' => array(
                'type' => 'checkbox',
                'label' => $module->l('Enable mouse over styles'),
                'default' => true
            ),
            'background-color' => array(
                'type' => 'color_picker',
                'label' => $module->l('Background color:'),
                'default' => '',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'color' => array(
                'type' => 'color_picker',
                'label' => $module->l('Text color:'),
                'default' => '',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'font-size' => array(
                'type' => 'number',
                'label' => $module->l('Font size:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'font-family' => array(
                'type' => 'font_picker',
                'label' => $module->l('Font:'),
                'default' => '',
                'list' => $fonts,
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'font-weight' => array(
                'type' => 'select',
                'label' => $module->l('Weight:'),
                'default' => '',
                'helpers' => array('font-family'),
                'dynamicList' => array(
                    'parameter' => 'font-family',
                    'attr' => 'data-variants',
                ),
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'font-style' => array(
                'type' => 'select',
                'label' => $module->l('Font style:'),
                'default' => '',
                'list' => array(
                    '' => $module->l('Inherit'),
                    'italic' => $module->l('Italic')
                ),
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'letter-spacing' => array(
                'type' => 'number',
                'label' => $module->l('Letter spacing:'),
                'default' => '',
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'line-height' => array(
                'type' => 'number',
                'label' => $module->l('Line height:'),
                'default' => '', // normal
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'text-align' => array(
                'type' => 'select',
                'label' => $module->l('Text align:'),
                'default' => '',
                'list' => array(
                    '' => $module->l('Inherit'),
                    'left' => $module->l('Left'),
                    'center' => $module->l('Center'),
                    'right' => $module->l('Right'),
                    'justify' => $module->l('Justify')
                ),
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
    'text-shadow' => array(
        'title' => $module->l('Text Shadow'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'text-shadow' => array(
                'type' => 'text_shadow',
                'default' => '',
                'options' => array(
                    'text_shadow_color' => array(
                        'type' => 'color_picker',
                        'label' => $module->l('Color:'),
                        'default' => ''
                    ),
                    'text_shadow_hor_len' => array(
                        'type' => 'number',
                        'label' => $module->l('Horizontal Length:'),
                        'default' => '',
                        'unit' => 'px'
                    ),
                    'text_shadow_vert_len' => array(
                        'type' => 'number',
                        'label' => $module->l('Vertical Length:'),
                        'default' => '',
                        'unit' => 'px'
                    ),
                    'text_shadow_radius' => array(
                        'type' => 'number',
                        'label' => $module->l('Radius:'),
                        'default' => '',
                        'min' => 0,
                        'unit' => 'px'
                    )
                ),
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            )
        )
    ),
    'border' => array(
        'title' => $module->l('Border'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'border-style' => array(
                'type' => 'select',
                'label' => $module->l('Border Style:'),
                'default' => '',
                'list' => array(
                    'none' => $module->l('None'),
                    '' => $module->l('Inherit'),
                    'hidden' => $module->l('Hidden'),
                    'solid' => $module->l('Solid'),
                    'dotted' => $module->l('Dotted'),
                    'dashed' => $module->l('Dashed'),
                    'double' => $module->l('Double'),
                    'groove' => $module->l('Groove'),
                    'ridge' => $module->l('Ridge'),
                    'inset' => $module->l('Inset'),
                    'outset' => $module->l('Outset')
                ),
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-top-width' => array(
                'type' => 'number',
                'label' => $module->l('Top:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-right-width' => array(
                'type' => 'number',
                'label' => $module->l('Right:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-bottom-width' => array(
                'type' => 'number',
                'label' => $module->l('Bottom:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-left-width' => array(
                'type' => 'number',
                'label' => $module->l('Left:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-color' => array(
                'type' => 'color_picker',
                'label' => $module->l('Border Color:'),
                'default' => '',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'border-radius' => array(
                'type' => 'number',
                'label' => $module->l('Border Radius:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
    'padding' => array(
        'title' => $module->l('Padding'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'padding-top' => array(
                'type' => 'number',
                'label' => $module->l('Top:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'padding-right' => array(
                'type' => 'number',
                'label' => $module->l('Right:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'padding-bottom' => array(
                'type' => 'number',
                'label' => $module->l('Bottom:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
            'padding-left' => array(
                'type' => 'number',
                'label' => $module->l('Left:'),
                'default' => '',
                'min' => 0,
                'unit' => 'px',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
    'advanced-editor' => array(
        'title' => $module->l('Advanced Editor'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'custom_styles' => array(
                'type' => 'codemirror',
                'mode' => 'css',
                'label' => $module->l('Custom styles'),
                'default' => '',
                'disabled_dependency' => array(
                    'parameter' => 'allow_style',
                    'value' => true
                )
            ),
        )
    ),
);
