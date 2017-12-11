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
    'main' => array(
        'title' => $module->l('General'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'product_hidden' => true,
        'options' => array(
            'fonts' => array(
                'type' => 'multiple',
                'default' => array(),
                'hidden' => true
            ),
            'title' => array(
                'type' => 'text',
                'label' => $module->l('Slide title'),
                'description' => $module->l('The title of the slide that will be shown in the slides list.'),
                'default' => 'Slide'
            ),
            'status' => array(
                'type' => 'button_group',
                'label' => $module->l('Status'),
                'description' => '',
                'default' => 'published',
                'button_size' => 'large',
                'list' => array(
                    'published' => $module->l('Published'),
                    'draft' => $module->l('Draft')
                )
            )
        )
    ),
    'color' => array(
        'title' => $module->l('Color'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'bg_color_type' => array(
                'type' => 'radio_group',
                'label' => $module->l('Background color type'),
                'default' => 'color',
                'list' => array(
                    'color' => $module->l('Color'),
                    'gradient' => $module->l('Gradient')
                )
            ),
            'bg_color' => array(
                'type' => 'color_picker',
                'label' => $module->l('Background color'),
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'color'
                )
            ),
            'bg_grad_color_2' => array(
                'type' => 'color_picker',
                'label' => $module->l('Gradient color 1'),
                'default' => 'black',
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'gradient'
                )
            ),
            'bg_grad_color_1' => array(
                'type' => 'color_picker',
                'label' => $module->l('Gradient color 2'),
                'default' => 'white',
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'gradient'
                )
            ),
            'bg_grad_angle' => array(
                'type' => 'number',
                'label' => $module->l('Gradient angle'),
                'default' => 0,
                'dependency' => array(
                    'parameter' => 'bg_color_type',
                    'value' => 'gradient'
                )
            )
        )
    ),
    'image' => array(
        'title' => $module->l('Image'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'bg_image_type' => array(
                'type' => 'select',
                'label' => $module->l('Background image'),
                'description' => '',
                'default' => 'library',
                'disabled' => false,
                'list' => array(
                    'library' => $module->l('Media Library'),
                    'external' => $module->l('External URL'),
                )
            ),
            'bg_image_id' => array(
                'type' => 'library_image',
                'default' => '',
                'label' => '',
                'button_label' => $module->l('Browse...'),
                'select_label' => $module->l('Insert image'),
                'can_remove' => true,
                'dependency' => array(
                    'parameter' => 'bg_image_type',
                    'value' => 'library'
                ),
                'helpers' => array('bg_internal_image_url')
            ),
            'bg_internal_image_url' => array(
                'type' => 'hidden',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'bg_image_type',
                    'value' => 'library'
                )
            ),
            'bg_image_url' => array(
                'type' => 'image_url',
                'label' => '',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'bg_image_type',
                    'value' => 'external'
                )
            ),
            'bg_fit' => array(
                'type' => 'select',
                'label' => $module->l('Size'),
                'description' => '',
                'default' => 'cover',
                'disabled' => false,
                'list' => array(
                    'cover' => $module->l('cover'),
                    'contain' => $module->l('contain'),
                    'percentage' => $module->l('(%, %)'),
                    'normal' => $module->l('normal')
                )
            ),
            'bg_fit_x' => array(
                'type' => 'number',
                'label' => $module->l('Fit X'),
                'default' => 100,
                'dependency' => array(
                    'parameter' => 'bg_fit',
                    'value' => 'percentage'
                )
            ),
            'bg_fit_y' => array(
                'type' => 'number',
                'label' => $module->l('Fit Y'),
                'default' => 100,
                'dependency' => array(
                    'parameter' => 'bg_fit',
                    'value' => 'percentage'
                )
            ),
            'bg_repeat' => array(
                'type' => 'select',
                'label' => $module->l('Repeat'),
                'description' => '',
                'default' => 'no-repeat',
                'disabled' => false,
                'list' => array(
                    'no-repeat' => $module->l('no-repeat'),
                    'repeat' => $module->l('repeat'),
                    'repeat-x' => $module->l('repeat-x'),
                    'repeat-y' => $module->l('repeat-y')
                )
            ),
            'bg_position' => array(
                'type' => 'select',
                'label' => $module->l('Position'),
                'description' => '',
                'default' => 'center center',
                'disabled' => false,
                'list' => array(
                    'center top' => $module->l('center top'),
                    'center bottom' => $module->l('center bottom'),
                    'center center' => $module->l('center center'),
                    'left top' => $module->l('left top'),
                    'left center' => $module->l('left center'),
                    'left bottom' => $module->l('left bottom'),
                    'right top' => $module->l('right top'),
                    'right center' => $module->l('right center'),
                    'right bottom' => $module->l('right bottom'),
                    'percentage' => $module->l('(x%, y%)')
                )
            ),
            'bg_position_x' => array(
                'type' => 'number',
                'label' => $module->l('Position X'),
                'default' => 0,
                'dependency' => array(
                    'parameter' => 'bg_position',
                    'value' => 'percentage'
                )
            ),
            'bg_position_y' => array(
                'type' => 'number',
                'label' => $module->l('Position Y'),
                'default' => 0,
                'dependency' => array(
                    'parameter' => 'bg_position',
                    'value' => 'percentage'
                )
            )
        )
    ),
    'video' => array(
        'title' => $module->l('Video'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            // Video BG Start
            'bg_video_src_mp4' => array(
                'type' => 'text',
                'default' => '',
                'label' => $module->l('Video source MP4')
            ),
            'bg_video_src_webm' => array(
                'type' => 'text',
                'default' => '',
                'label' => $module->l('Video source WEBM')
            ),
            'bg_video_src_ogg' => array(
                'type' => 'text',
                'default' => '',
                'label' => $module->l('Video source OGG')
            ),
            'bg_video_loop' => array(
                'type' => 'switcher',
                'default' => false,
                'label' => $module->l('Loop video')
            ),
            'bg_video_mute' => array(
                'type' => 'switcher',
                'default' => false,
                'label' => $module->l('Mute video')
            ),
            'bg_video_fillmode' => array(
                'type' => 'select',
                'default' => 'fill',
                'label' => $module->l('Video fillmode'),
                'list' => array(
                    'fill' => $module->l('Fill'),
                    'fit' => $module->l('Fit')
                )
            ),
            'bg_video_cover' => array(
                'type' => 'switcher',
                'default' => false,
                'label' => $module->l('Video cover')
            ),
            'bg_video_cover_type' => array(
                'type' => 'select',
                'default' => '',
                'label' => $module->l('Video cover type'),
                'list' => array(
                    '' => $module->l('None'),
                    '2x2-black' => $module->l('2 x 2 Black'),
                    '2x2-white' => $module->l('2 x 2 White'),
                    '3x3-black' => $module->l('3 x 3 Black'),
                    '3x3-white' => $module->l('3 x 3 White')
                ),
                'dependency' => array(
                    'parameter' => 'bg_video_cover',
                    'value' => true
                )
            )
            // Video BG End
        )
    ),
    'link' => array(
        'title' => $module->l('Link'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'link' => array(
                'type' => 'text',
                'label' => $module->l('Link this slide'),
                'default' => ''
            ),
            'link_blank' => array(
                'type' => 'switcher',
                'label' => $module->l('Open in new window'),
                'default' => false
            ),
            'link_id' => array(
                'type' => 'text',
                'label' => $module->l('Link ID'),
                'default' => ''
            ),
            'link_class' => array(
                'type' => 'text',
                'label' => $module->l('Link class'),
                'default' => ''
            ),
            'link_rel' => array(
                'type' => 'text',
                'label' => $module->l('Link rel'),
                'default' => ''
            ),
            'link_title' => array(
                'type' => 'text',
                'label' => $module->l('Link title'),
                'default' => ''
            )
        )
    ),
    'visibility' => array(
        'title' => $module->l('Visibility'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'product_hidden' => true,
        'options' => array(
            'need_logged_in' => array(
                'type' => 'switcher',
                'label' => $module->l('Only logged-in users can view this slide'),
                'default' => false
            ),
            'date_from' => array(
                'type' => 'datepicker',
                'label' => $module->l('Visible from'),
                'default' => '',
            ),
            'date_until' => array(
                'type' => 'datepicker',
                'label' => $module->l('Visible until'),
                'default' => '',
            )
        )
    ),
    'misc' => array(
        'title' => $module->l('Misc'),
        'icon' => null,
        'description' => '',
        'col' => '12',
        'options' => array(
            'slide_classes' => array(
                'type' => 'text',
                'label' => $module->l('Class name'),
                'default' => '',
            ),
            'slide_id' => array(
                'type' => 'text',
                'label' => $module->l('CSS ID'),
                'default' => '',
            )
        )
    )
);
