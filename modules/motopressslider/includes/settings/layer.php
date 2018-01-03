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

$image_sizes = array(MotoPressSlider::FULLSIZE => $module->l('Full size'));
$ps_image_types = mpsl_get_image_types();
foreach ($ps_image_types as $type) {
    $label = $type['name'];
    $label = preg_replace('/[\W_]+/', ' ', $label);
    $label = Tools::ucfirst($label);
    $label .= ' (' . $type['width'] . 'px*' . $type['height'] . 'px)';
    $image_sizes[$type['name']] = $label;
}

$layer_options = array(
    'positioning' => array(
        'title' => $module->l('Positioning'),
        'icon' => null,
        'description' => '',
        'class' => 'col-md-3',
        'options' => array(
            'type' => array(
                'type' => 'select',
                'default' => 'html',
                'list' => array(
                    'html' => 'html',
                    'image' => 'image',
                    'button' => 'button',
                    'video' => 'video'
                ),
                'hidden' => true
            ),
            'private_styles' => array(
                'type' => 'multiple',
                'default' => array() // JSON
            ),
            'align' => array(
                'type' => 'align_table',
                'default' => array(
                    'vert' => 'middle',
                    'hor' => 'center'
                ),
                'options' => array(
                    'vert_align' => array(
                        'type' => 'hidden',
                        'default' => 'middle'
                    ),
                    'hor_align' => array(
                        'type' => 'hidden',
                        'default' => 'center'
                    ),
                    'offset_x' => array(
                        'type' => 'number',
                        'default' => 0,
                        'label2' => $module->l('X')
                    ),
                    'offset_y' => array(
                        'type' => 'number',
                        'default' => 0,
                        'label2' => $module->l('Y')
                    )
                )
            ),
            'width' => array(
                'type' => 'number',
                'label2' => $module->l('width'),
                'default' => '',
                'min' => 1,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            ),
            'html_width' => array(
                'type' => 'number',
                'label2' => $module->l('width'),
                'default' => '',
                'min' => 1,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'html'
                )
            ),
            'white-space' => array(
                'type' => 'select',
                'label' => $module->l('Whitespace'),
                'default' => 'normal',
                'list' => array(
                    'normal' => $module->l('Normal'),
                    'nowrap' => $module->l('No-wrap')
                ),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'html'
                )
            ),
            'video_width' => array(
                'type' => 'number',
                'label2' => $module->l('width'),
                'default' => 1,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_height' => array(
                'type' => 'number',
                'label2' => $module->l('height'),
                'default' => 1,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            )
        )
    ),
    'animation' => array(
        'title' => $module->l('Animation'),
        'icon' => null,
        'description' => '',
        'class' => 'col-md-4',
        'options' => array(
            'start_animation' => array(
                'type' => 'select',
                'label' => $module->l('Start animation'),
                'default' => 'fadeIn',
                'list' => array(
                    'bounceIn' => $module->l('bounceIn'),
                    'bounceInDown' => $module->l('bounceInDown'),
                    'bounceInLeft' => $module->l('bounceInLeft'),
                    'bounceInRight' => $module->l('bounceInRight'),
                    'bounceInUp' => $module->l('bounceInUp'),
                    'fadeIn' => $module->l('fadeIn'),
                    'fadeInDown' => $module->l('fadeInDown'),
                    'fadeInDownBig' => $module->l('fadeInDownBig'),
                    'fadeInLeft' => $module->l('fadeInLeft'),
                    'fadeInLeftBig' => $module->l('fadeInLeftBig'),
                    'fadeInRight' => $module->l('fadeInRight'),
                    'fadeInRightBig' => $module->l('fadeInRightBig'),
                    'fadeInUp' => $module->l('fadeInUp'),
                    'fadeInUpBig' => $module->l('fadeInUpBig'),
                    'flip' => $module->l('flip'),
                    'flipInX' => $module->l('flipInX'),
                    'flipInY' => $module->l('flipInY'),
                    'lightSpeedIn' => $module->l('lightSpeedIn'),
                    'rotateIn' => $module->l('rotateIn'),
                    'rotateInDownLeft' => $module->l('rotateInDownLeft'),
                    'rotateInDownRight' => $module->l('rotateInDownRight'),
                    'rotateInUpLeft' => $module->l('rotateInUpLeft'),
                    'rotateInUpRight' => $module->l('rotateInUpRight'),
                    'rollIn' => $module->l('rollIn'),
                    'zoomIn' => $module->l('zoomIn'),
                    'zoomInDown' => $module->l('zoomInDown'),
                    'zoomInLeft' => $module->l('zoomInLeft'),
                    'zoomInRight' => $module->l('zoomInRight'),
                    'zoomInUp' => $module->l('zoomInUp')
                )
            ),
            'start_timing_function' => array(
                'type' => 'select',
                'default' => 'linear',
                'list' => array(
                    'linear' => $module->l('linear'),
                    'ease' => $module->l('ease'),
                    'easeIn' => $module->l('easeIn'),
                    'easeInOut' => $module->l('easeInOut'),
                    'easeInQuad' => $module->l('easeInQuad'),
                    'easeInCubic' => $module->l('easeInCubic'),
                    'easeInQuart' => $module->l('easeInQuart'),
                    'easeInQuint' => $module->l('easeInQuint'),
                    'easeInSine' => $module->l('easeInSine'),
                    'easeInExpo' => $module->l('easeInExpo'),
                    'easeInCirc' => $module->l('easeInCirc'),
                    'easeInBack' => $module->l('easeInBack'),
                    'easeOutQuad' => $module->l('easeOutQuad'),
                    'easeOutCubic' => $module->l('easeOutCubic'),
                    'easeOutQuart' => $module->l('easeOutQuart'),
                    'easeOutQuint' => $module->l('easeOutQuint'),
                    'easeOutSine' => $module->l('easeOutSine'),
                    'easeOutExpo' => $module->l('easeOutExpo'),
                    'easeOutCirc' => $module->l('easeOutCirc'),
                    'easeOutBack' => $module->l('easeOutBack'),
                    'easeInOutQuad' => $module->l('easeInOutQuad'),
                    'easeInOutCubic' => $module->l('easeInOutCubic'),
                    'easeInOutQuart' => $module->l('easeInOutQuart'),
                    'easeInOutQuint' => $module->l('easeInOutQuint'),
                    'easeInOutSine' => $module->l('easeInOutSine'),
                    'easeInOutExpo' => $module->l('easeInOutExpo'),
                    'easeInOutCirc' => $module->l('easeInOutCirc'),
                    'easeInOutBack' => $module->l('easeInOutBack'),
                )
            ),
            'start_duration' => array(
                'type' => 'number',
                'default' => 1000,
                'min' => 0
            ),
            'start_animation_group' => array(
                'type' => 'animation_control',
                'default' => false,
                'button_label' => $module->l('Edit'),
                'button_id' => 'start_animation_btn',
                'animation_type' => 'start',
                'skip' => true,
                'skipChild' => true,
                'options' => array(
                    'start_duration_clone' => null, // Init later (see below)
                    'start_timing_function_clone' => null, // Init later (see below)
                    'start_animation_clone' => null, // Init later (see below)
                ),
            ),
            'end_animation' => array(
                'type' => 'select',
                'label' => $module->l('End animation'),
                'default' => 'auto',
                'list' => array(
                    'auto' => $module->l('auto'),
                    'bounceOut' => $module->l('bounceOut'),
                    'bounceOutDown' => $module->l('bounceOutDown'),
                    'bounceOutLeft' => $module->l('bounceOutLeft'),
                    'bounceOutRight' => $module->l('bounceOutRight'),
                    'bounceOutUp' => $module->l('bounceOutUp'),
                    'fadeOut' => $module->l('fadeOut'),
                    'fadeOutDown' => $module->l('fadeOutDown'),
                    'fadeOutDownBig' => $module->l('fadeOutDownBig'),
                    'fadeOutLeft' => $module->l('fadeOutLeft'),
                    'fadeOutLeftBig' => $module->l('fadeOutLeftBig'),
                    'fadeOutRight' => $module->l('fadeOutRight'),
                    'fadeOutUp' => $module->l('fadeOutUp'),
                    'fadeOutUpBig' => $module->l('fadeOutUpBig'),
                    'flip' => $module->l('flip'),
                    'flipOutX' => $module->l('flipOutX'),
                    'flipOutY' => $module->l('flipOutY'),
                    'lightSpeedOut' => $module->l('lightSpeedOut'),
                    'rotateOut' => $module->l('rotateOut'),
                    'rotateOutDownLeft' => $module->l('rotateOutDownLeft'),
                    'rotateOutDownRight' => $module->l('rotateOutDownRight'),
                    'rotateOutUpLeft' => $module->l('rotateOutUpLeft'),
                    'rotateOutUpRight' => $module->l('rotateOutUpRight'),
                    'rollOut' => $module->l('rollOut'),
                    'zoomOut' => $module->l('zoomOut'),
                    'zoomOutDown' => $module->l('zoomOutDown'),
                    'zoomOutLeft' => $module->l('zoomOutLeft'),
                    'zoomOutRight' => $module->l('zoomOutRight'),
                    'zoomOutUp' => $module->l('zoomOutUp')
                )
            ),
            'end_timing_function' => array(
                'type' => 'select',
                'default' => 'linear',
                'list' => array(
                    'linear' => $module->l('linear'),
                    'ease' => $module->l('ease'),
                    'easeIn' => $module->l('easeIn'),
                    'easeInOut' => $module->l('easeInOut'),
                    'easeInQuad' => $module->l('easeInQuad'),
                    'easeInCubic' => $module->l('easeInCubic'),
                    'easeInQuart' => $module->l('easeInQuart'),
                    'easeInQuint' => $module->l('easeInQuint'),
                    'easeInSine' => $module->l('easeInSine'),
                    'easeInExpo' => $module->l('easeInExpo'),
                    'easeInCirc' => $module->l('easeInCirc'),
                    'easeInBack' => $module->l('easeInBack'),
                    'easeOutQuad' => $module->l('easeOutQuad'),
                    'easeOutCubic' => $module->l('easeOutCubic'),
                    'easeOutQuart' => $module->l('easeOutQuart'),
                    'easeOutQuint' => $module->l('easeOutQuint'),
                    'easeOutSine' => $module->l('easeOutSine'),
                    'easeOutExpo' => $module->l('easeOutExpo'),
                    'easeOutCirc' => $module->l('easeOutCirc'),
                    'easeOutBack' => $module->l('easeOutBack'),
                    'easeInOutQuad' => $module->l('easeInOutQuad'),
                    'easeInOutCubic' => $module->l('easeInOutCubic'),
                    'easeInOutQuart' => $module->l('easeInOutQuart'),
                    'easeInOutQuint' => $module->l('easeInOutQuint'),
                    'easeInOutSine' => $module->l('easeInOutSine'),
                    'easeInOutExpo' => $module->l('easeInOutExpo'),
                    'easeInOutCirc' => $module->l('easeInOutCirc'),
                    'easeInOutBack' => $module->l('easeInOutBack'),
                )
            ),
            'end_duration' => array(
                'type' => 'number',
                'default' => 1000,
                'min' => 0
            ),
            'end_animation_group' => array(
                'type' => 'animation_control',
                'default' => false,
                'button_label' => $module->l('Edit'),
                'button_id' => 'end_animation_btn',
                'animation_type' => 'end',
                'skip' => true,
                'skipChild' => true,
                'options' => array(
                    'end_duration_clone' => null, // Init later (see below)
                    'end_timing_function_clone' => null, // Init later (see below)
                    'end_animation_clone' => null, // Init later (see below)
                ),
            ),
            'start' => array(
                'type' => 'number',
                'label2' => $module->l('Display at (ms)'),
                'default' => 1000,
                'min' => 0
            ),
            'end' => array(
                'type' => 'number',
                'label2' => $module->l('Hide at (ms)'),
                'default' => 0,
                'min' => 0
            )
        )
    ),
    'misc' => array(
        'title' => $module->l('Misc'),
        'icon' => null,
        'description' => '',
        'class' => 'col-md-5',
        'options' => array(
            'text' => array(
                'type' => 'textarea',
                'label' => $module->l('Text/HTML'),
                'default' => $module->l('Lorem ipsum'),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'html'
                )
            ),
            'button_text' => array(
                'type' => 'text',
                'label' => $module->l('Button text'),
                'default' => $module->l('Button'),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'button'
                )
            ),
            'button_link' => array(
                'type' => 'text',
                'label' => $module->l('Link'),
                'default' => '#',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'button'
                )
            ),
            'button_target' => array(
                'type' => 'switcher',
                'label' => $module->l('Open in new window'),
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'button'
                )
            ),
            'image_id' => array(
                'type' => 'library_image',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                ),
                'helpers' => array('image_url'),
                'button_label' => $module->l('Select Image'),
                'select_label' => $module->l('Select Image')
            ),
            'image_type' => array(
                'type' => 'select',
                'default' => 'library',
                'list' => array(
                    'library' => 'library',
                    'product' => 'product'
                ),
                'hidden' => true
            ),
            'image_size' => array(
                'type' => 'size_select',
                'label' => $module->l('Product image size'),
                'default' => MotoPressSlider::FULLSIZE,
                'list' => $image_sizes,
                'dependency' => array(
                    'parameter' => 'image_type',
                    'value' => 'product'
                )
            ),
            'image_url' => array(
                'type' => 'hidden',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                ),
            ),
            'image_link' => array(
                'type' => 'text',
                'label' => $module->l('Link'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            ),
            'image_target' => array(
                'type' => 'switcher',
                'label' => $module->l('Open in new window'),
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            ),
            'video_type' => array(
                'type' => 'button_group',
                'default' => 'youtube',
                'list' => array(
                    'youtube' => $module->l('Youtube'),
                    'vimeo' => $module->l('Vimeo'),
                    'html' => $module->l('Media Library')
                ),
                'button_size' => 'large',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'youtube_src' => array(
                'type' => 'text',
                'default' => '',
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'youtube'
                )
            ),
            'vimeo_src' => array(
                'type' => 'text',
                'default'=> '',
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'vimeo'
                )
            ),
            'video_src_mp4' => array(
                'type' => 'text',
                'default' => '',
                'label' => $module->l('Source MP4'),
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'video_src_webm' => array(
                'type' => 'text',
                'default' => '',
                'label' => $module->l('Source WEBM'),
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'video_src_ogg' => array(
                'type' => 'text',
                'default' => '',
                'label' => $module->l('Source OGG'),
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'video_preview_image' => array(
                'type' => 'text',
                'default' => '',
                'label' => $module->l('Preview Image URL'),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_autoplay' => array(
                'type' => 'checkbox',
                'label' => $module->l('Autoplay'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_loop' => array(
                'type' => 'checkbox',
                'label' => $module->l('Loop'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_mute' => array(
                'type' => 'checkbox',
                'label' => $module->l('Mute'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'video_html_hide_controls' => array(
                'type' => 'checkbox',
                'label' => $module->l('Hide Controls'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'html'
                )
            ),
            'video_youtube_hide_controls' => array(
                'type' => 'checkbox',
                'label' => $module->l('Hide controls'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'video_type',
                    'value' => 'youtube'
                )
            ),
            'video_disable_mobile' => array(
                'type' => 'checkbox',
                'label' => $module->l('Disable mobile'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'video'
                )
            ),
            'preset' => array(
                'type' => 'style_editor',
                'label' => $module->l('Style'),
                'edit_label' => $module->l('Edit'),
                'remove_label' => $module->l('Remove'),
                'helpers' => array('private_styles'),
                'class' => 'mpsl-option-wrapper-preset',
                'default' => '',
            ),
            'private_preset_class' => array(
                'type' => 'hidden',
                'default' => ''
            ),
            'classes' => array(
                'type' => 'text',
                'label' => $module->l('Custom classes'),
                'default' => ''
            ),
            'image_link_classes' => array(
                'type' => 'text',
                'label' => $module->l('Link custom classes'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'image'
                )
            )
        )
    )
);

$merge_clones = array(
    'start_animation_group' => array(
        'start_duration' => array(
            'label' => $module->l('Duration:'),
            'helpers' => array('start_duration')
        ),
        'start_timing_function' => array(
            'label' => $module->l('Ease function:'),
            'helpers' => array('start_timing_function')
        ),
        'start_animation' => array(
            'type' => 'select_list',
            'label' => '',
            'helpers' => array('start_animation')
        )
    ),
    'end_animation_group' => array(
        'end_duration' => array(
            'label' => $module->l('Duration:'),
            'helpers' => array('end_duration')
        ),
        'end_timing_function' => array(
            'label' => $module->l('Ease function:'),
            'helpers' => array('end_timing_function')
        ),
        'end_animation' => array(
            'type' => 'select_list',
            'label' => '',
            'helpers' => array('end_animation')
        )
    )
);

foreach ($merge_clones as $group => $options) {
    $type = $layer_options['animation']['options'][$group]['animation_type'];
    foreach ($options as $option_name => $merge_atts) {
        $clone_name = $option_name . '_clone'; // "start_duration_clone", "end_animation_clone" etc.
        $clone_option = $layer_options['animation']['options'][$option_name];
        $clone_option = array_merge($clone_option, $merge_atts);
        $layer_options['animation']['options'][$group]['options'][$clone_name] = $clone_option;
    }
}

return $layer_options;
