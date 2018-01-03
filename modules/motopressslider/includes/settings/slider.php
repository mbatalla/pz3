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

$full_size_grid_desc =
    $module->l('Makes grid stretch to parent container. But you still need to set grid width and height');

// Prepare "display_on" list for option ['appearance']['options']['display_on']
$display_on = array();
$hooks = $module->display_hooks;
if ($module->is_16) {
    $supported_hooks = array_merge($hooks['common'], $hooks['1.6']); // %Hook% => %label%
} else {
    $supported_hooks = $hooks['common']; // %Hook% => %label%
}
foreach ($supported_hooks as $hook => $label) {
    $display_on[$hook] = "{$label} ({$hook})";
}

// Prepare "categories" list for option ['product']['options']['categories']
$categories = array($module->l('All categories'));
$supported_categories = mpsl_get_categories();
foreach ($supported_categories as $id_parent => $category_ids) {
    foreach ($category_ids as $id_category => $category) {
        $category = $category['infos'];
        $categories[$id_category] = $category['name'];
    }
}
asort($categories, SORT_STRING);

// Prepare "tags" list for option ['product']['options']['tags']
$tags = array($module->l('All tags'));
$supported_tags = mpsl_get_tags();
foreach ($supported_tags as $tag) {
    $tags[$tag['id']] = $tag['name'];
}
asort($tags, SORT_STRING);

return array(
    'main' => array(
        'title' => $module->l('Slider Settings'),
        'icon' => 'icon-edit',
        'description' => '',
        'options' => array(
            'type' => array(
                'type' => 'hidden',
                'label' => $module->l('Type'),
                'default' => 'custom',
                'list' => array(
                    'custom' => $module->l('Custom'),
                    'product' => $module->l('Product')
                ),
                'required' => true
            ),
            'title' => array(
                'type' => 'text',
                'label' => $module->l('Slider title'),
                'description' => $module->l('The title of the slider. Example: Slider1'),
                'default' => $module->l('New Slider'),
                'disabled' => false,
                'required' => true,
            ),
            'alias' => array(
                'type' => 'alias',
                'label' => $module->l('Slider alias'),
                'alias' => 'shortcode',
                'description' => $module->l('The unique alias that will be used in shortcodes. Example: slider1'),
                'default' => '',
                'disabled' => false,
                'required' => true,
            ),
            'shortcode' => array(
                'type' => 'shortcode',
                'label' => $module->l('Slider shortcode'),
                'description' => 'Copy this shortocode and paste to your page.',
                'default' => '',
                'readonly' => true,
            ),
            'full_width' => array(
                'type' => 'switcher',
                'label' => $module->l('Force full width'),
                'description' => $module->l('Enable this option to make this slider full-width / wide-screen'),
                'default' => false
            ),
            'full_height' => array(
                'type' => 'switcher',
                'label' => $module->l('Force full height'),
                'description' => $module->l('Enable this option to make this slider full-height'),
                'default' => false,
            ),
            'full_height_offset' => array(
                'type' => 'number',
                'label' => $module->l('Full height increment:'),
                'description' => $module->l('Slider height will be increased or decreased to this value'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'full_height',
                    'value' => true,
                )
            ),
            'full_height_units' => array(
                'type' => 'select',
                'label' => $module->l('Increment units:'),
                'default' => 'px',
                'list' => array(
                    'px' => $module->l('Pixels (px)'),
                    '%' => $module->l('Percents (%)'),
                ),
                'dependency' => array(
                    'parameter' => 'full_height',
                    'value' => true,
                )
            ),
            'full_height_container' => array(
                'type' => 'text',
                'label' => $module->l('Offset by container:'),
                'description' =>
                    $module->l('The height will be decreased with the height of these elements. Enter CSS Selector'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'full_height',
                    'value' => true,
                )
            ),
            'full_size_grid' => array(
                'type' => 'switcher',
                'label' => $module->l('Force full size grid'),
                'description' => $full_size_grid_desc,
                'default' => false
            ),
            'width' => array(
                'type' => 'number',
                'label' => $module->l('Layers grid size'),
                'label2' => $module->l('width'),
                'description' => $module->l('Initial width and height of the layers'),
                'default' => 960,
                'min' => 0
            ),
            'height' => array(
                'type' => 'number',
                'label2' => $module->l('height'),
                'description' => '',
                'default' => 350,
                'min' => 0
            ),
            'enable_timer' => array(
                'type' => 'switcher',
                'label' => $module->l('Enable slideshow'),
                'default' => true
            ),
            'slider_delay' => array(
                'type' => 'text',
                'label' => $module->l('Slideshow delay'),
                'description' => $module->l('The time one slide stays on the screen in milliseconds'),
                'default' => 7000
            ),
            'slider_animation' => array(
                'type' => 'select',
                'label' => $module->l('Slideshow animation'),
                'default' => 'msSlide',
                'list' => array(
                    'msSlide' => $module->l('Slide'),
                    'msSlideFade' => $module->l('Fade'),
                    'msSlideUpDown' => $module->l('Slide Down'),
                )
            ),
            'slider_duration' => array(
                'type' => 'text',
                'label' => $module->l('Slideshow duration'),
                'description' => $module->l('Animation duration in milliseconds'),
                'default' => 2000
            ),
            'slider_easing' => array(
                'type' => 'select',
                'label' => $module->l('Slideshow easings'),
                'default' => 'easeOutCirc',
                'list' => array(
                    'linear' => $module->l('linear'),
                    'ease' => $module->l('ease'),
                    'easeIn' => $module->l('easeIn'),
                    'easeOut' => $module->l('easeOut'),
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
                ),
                'description2' => '<a href="https://jqueryui.com/easing/" target="_blank">'
                                  . $module->l('Easing examples') . '</a>'
            ),
            'start_slide' => array(
                'type' => 'number',
                'label' => $module->l('Start with slide'),
                'description' => $module->l('Slide index in the list of slides'),
                'default' => 1
            ),
        )
    ),
    'controls' => array(
        'title' => $module->l('Controls'),
        'icon' => 'icon-keyboard',
        'description' => '',
        'options' => array(
            'arrows_show' => array(
                'type' => 'switcher',
                'label' => $module->l('Show arrows'),
                'default' => true
            ),
            'thumbnails_show' => array(
                'type' => 'switcher',
                'label' => $module->l('Show bullets'),
                'default' => true
            ),
            'slideshow_timer_show' => array(
                'type' => 'switcher',
                'label' => $module->l('Show slideshow timer'),
                'default' => true
            ),
            'slideshow_ppb_show' => array(
                'type' => 'switcher',
                'label' => $module->l('Show slideshow play/pause button'),
                'default' => true
            ),
            'controls_hide_on_leave' => array(
                'type' => 'switcher',
                'label' => $module->l('Hide controls when mouse leaves slider'),
                'default' => false
            ),
            'hover_timer' => array(
                'type' => 'switcher',
                'label' => $module->l('Pause on hover'),
                'description' => $module->l('Pause slideshow when hover the slider'),
                'default' => false
            ),
            'timer_reverse' => array(
                'type' => 'switcher',
                'label' => $module->l('Reverse order of the slides'),
                'description' => $module->l('Animate slides in the reverse order'),
                'default' => false
            ),
            'counter' => array(
                'type' => 'switcher',
                'label' => $module->l('Show counter'),
                'description' => $module->l('Displays the number of slides'),
                'default' => false
            ),
            'swipe' => array(
                'type' => 'switcher',
                'label' => $module->l('Enable swipe'),
                'description' => $module->l('Turn on swipe on desktop'),
                'default' => true
            ),
        )
    ),
    'appearance' => array(
        'title' => $module->l('Appearance'),
        'icon' => 'icon-eye',
        'description' => '',
        'options' => array(
            'display_on' => array(
                'type' => 'checkbox',
                'label' => $module->l('Display on'),
                'default' => '',
                'list' => $display_on
            ),
            'visible_from' => array(
                'type' => 'number',
                'label' => $module->l('Visible'),
                'label2' => $module->l('from'),
                'unit' => 'px',
                'default' => '',
                'min' => 0,
            ),
            'visible_till' => array(
                'type' => 'number',
                'label2' => $module->l('till'),
                'unit' => 'px',
                'default' => '',
                'min' => 0,
            ),
            'presets' => array(
                'type' => 'action_group',
                'label' => '',
                'label2' => $module->l('presets:'),
                'default' => '',
                'list' => array(
                    'phone' => $module->l('Phone'),
                    'tablet' => $module->l('Tablet'),
                    'desktop' => $module->l('Desktop')
                ),
                'actions' => array(
                    'phone' => array(
                        'visible_from' => '',
                        'visible_till' => 767
                    ),
                    'tablet' => array(
                        'visible_from' => 768,
                        'visible_till' => 991
                    ),
                    'desktop' => array(
                        'visible_from' => 992,
                        'visible_till' => ''
                    )
                )
            ),
            'delay_init' => array(
                'type' => 'text',
                'label' => $module->l('Initialization delay'),
                'label2' => '',
                'default' => 0
            ),
            'scroll_init' => array(
                'type' => 'switcher',
                'label' => $module->l('Initialize slider on scroll'),
                'label2' => '',
                'default' => false
            ),
            'custom_class' => array(
                'type' => 'text',
                'label' => $module->l('Slider custom class name'),
                'label2' => '',
                'default' => ''
            ),
            'custom_styles' => array(
                'type' => 'codemirror',
                'mode' => 'css',
                'label' => $module->l('Slider custom styles'),
                'default' => ''
            ),
        )
    ),
    'product' => array(
        'title' => $module->l('Content'),
        'icon' => 'icon-copy',
        'description' => '',
        'options' => array(
            'link_slides' => array(
                'type' => 'switcher',
                'label' => $module->l('Link slides to product page'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'link_blank' => array(
                'type' => 'switcher',
                'label' => $module->l('Open in new window'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'link_slides',
                    'value' => true,
                )
            ),
            'categories' => array(
                'type' => 'select',
                'label' => $module->l('Categories'),
                'label2' => '',
                'multiple' => true,
                'default' => 0,
                'list' => $categories,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'tags' => array(
                'type' => 'select',
                'label' => $module->l('Tags'),
                'label2' => '',
                'multiple' => true,
                'default' => 0,
                'list' => $tags,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'in_stock' => array(
                'type' => 'checkbox',
                'label' => $module->l('Only display in-stock products'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'on_sale_only' => array(
                'type' => 'checkbox',
                'label' => $module->l('Only display on sale products'),
                'default' => false,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'exclude_ids' => array(
                'type' => 'text',
                'label' => $module->l('Exclude products'),
                'description' => $module->l('Product IDs separated by comma'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'include_ids' => array(
                'type' => 'text',
                'label' => $module->l('Include products'),
                'description' => $module->l('Product IDs separated by comma'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'count' => array(
                'type' => 'number',
                'label' => $module->l('Products number'),
                'default' => 10,
                'min' => 0,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'offset' => array(
                'type' => 'number',
                'label' => $module->l('Number of first results to skip (offset)'),
                'default' => 0,
                'min' => 0,
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'order_by' => array(
                'type' => 'select',
                'label' => $module->l('Order by'),
                'default' => 'date',
                'list' => array(
                    'id' => $module->l('ID'),
                    'title' => $module->l('Title'),
                    'date' => $module->l('Date'),
                    'date_modified' => $module->l('Date Modified')
                ),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            ),
            'order' => array(
                'type' => 'select',
                'label' => $module->l('Order direction'),
                'default' => 'DESC',
                'list' => array(
                    'DESC' => $module->l('Descending'),
                    'ASC' => $module->l('Ascending'),
                ),
                'dependency' => array(
                    'parameter' => 'type',
                    'value' => 'product',
                )
            )
        )
    )
);
