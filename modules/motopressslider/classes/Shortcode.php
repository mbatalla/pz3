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

class MPSLShortcode
{
    /** @var MotoPressSlider */
    private $module = null;

    private $is_edit_mode = false;
    private $is_slide_preview = false;
    private $is_ajax = false;
    private $is_product = false;
    private $has_visible_slides = false;

    /** @var MPSLSlider */
    private $slider = null;
    private $slider_id = 0;
    private $slider_is_empty = false; // Slider is empty when it ID is 0 or when
                                      // it has no slides

    private $products = array(); // Only for Product Slider

    /** @var MPSLPresets */
    private $presets_obj = null;

    /**
     * @param mixed $slider Slider object, integer ID or string alias name.
     */
    public function __construct($slider, $is_edit_mode = false, $is_slide_preview = false)
    {
        $this->module = mpsl_get_module();

        // Create slider
        if (is_a($slider, 'MPSLSlider')) {
            $this->slider = $slider;
            $this->slider_id = $this->slider->id;
        } else {
            if (is_numeric($slider)) {
                // $slider is a slider ID
                $this->slider_id = (int)$slider;
            } else {
                // $slider is a slider alias
                $this->slider_id = MPSLSlider::aliasToId($slider);
            }
            $this->slider = new MPSLSlider($this->slider_id);
        }

        if ($this->slider_id <= 0) {
            $this->slider_is_empty = true;
        } else {
            $this->slider->loadItems();
        }

        $this->is_edit_mode = $is_edit_mode;
        $this->is_slide_preview = $is_slide_preview;
        $this->is_ajax = mpsl_is_ajax();
        $this->is_product = $this->slider->isProduct();

        $this->presets_obj = MPSLPresets::getInstance();

        // Load products and create product slides for Product Slider
        if (!$this->is_edit_mode && $this->is_product && !$this->slider_is_empty) {
            $this->loadProducts();
        }
    }

    public function doShortcode()
    {
        if ($this->slider_is_empty) {
            return '';
        }

        $slider = $this->slider;
        $slider_options = $slider->settings->getOptions();
        $custom_css = trim($slider_options['custom_styles']);
        $height = $slider_options['height'];
        $slider_id = 'motoslider_wrapper' . uniqid();
        $slider_style = (!$this->is_edit_mode ? '' : 'width: ' . $slider_options['width'] . 'px;');
        $slides = $slider->getItems();
        $slider_fonts = array();
        $private_presets = array();

        $this->has_visible_slides = false;
        $logged_in = mpsl_is_logged_in();

        ob_start();

        if (!empty($custom_css)) {
            echo '<style type="text/css">' . $custom_css . '</style>';
        }

        // Slider wrapper
        echo '<div id="' . $slider_id . '" class="motoslider_wrapper" style="' . $slider_style . '">';
        echo '<div data-motoslider style="height: ' . $height . 'px; max-height: ' . $height . 'px;"></div>';

        // Motoslider
        echo '<div class="motoslider">';
        echo '<div id="settings" ' . $this->getSliderData($slider) . '></div>';

        // Slides
        echo '<div id="slides">';
        foreach ($slides as $slide) {
            // Skip hidden and logged-in slides on frontend (but not in edit
            // mode or on preview)
            $is_hidden = $slide->isHidden();
            $need_logged_in = $slide->settings->getOption('need_logged_in');
            if ($is_hidden && !$this->is_edit_mode && !$this->is_slide_preview) {
                continue;
            } elseif ($need_logged_in && !$logged_in) {
                // If !$logged_in you can't see edit mode or preview
                continue;
            } else {
                $this->has_visible_slides = true;
            }

            // Get slide fonts and atts
            $slide_fonts = $slide->settings->getOption('fonts', array());
            $slider_fonts = array_merge_recursive($slider_fonts, $slide_fonts);
            $slide_atts = $this->getSlideData($slide, $slider_options);

            // Slide
            echo '<div class="slide" ' . $slide_atts['slide'] . '>';

            // Slide background
            echo '<div class="slide_bg">';
            if (isset($slide_atts['bgcolor'])) {
                echo '<div ' . $slide_atts['bgcolor'] . '></div>'; // Slide background color
            }
            if (isset($slide_atts['bgimage'])) {
                echo '<div ' . $slide_atts['bgimage'] . '></div>'; // Slide background image
            }
            if (isset($slide_atts['bgvideo'])) {
                echo '<div ' . $slide_atts['bgvideo'] . '></div>'; // Slide background video
            }
            // Slide background
            echo '</div>';

            // Layers
            echo '<div class="layers">';
            $layers = $slide->getItems();
            foreach ($layers as $layer) {
                $type = $layer->getType();
                $data = $this->getLayerData($layer, $private_presets);
                // Layer
                if ($type == 'image') {
                    echo '<div class="layer" ' . $data['atts'] . '>';
                        echo '<img ' . $data['img_atts'] . ' />';
                    echo '</div>';
                } else {
                    echo '<div class="layer" ' . $data['atts'] . '>';
                    echo $data['content'];
                    echo '</div>';
                }
            }
            // Layers
            echo '</div>';

            // Slide
            echo '</div>';
        }
        // Slides
        echo '</div>';

        // Motoslider
        echo '</div>';

        // Slider wrapper
        echo '</div>';

        $this->pushFonts($slider_id, $slider_fonts);
        $this->pushPresets($slider_id, $private_presets);
        $this->pushHeightFix($slider_id, $slider_options);

        $shortcode = ob_get_clean();

        if ($this->has_visible_slides) {
            // Maybe add some additional wrappers
            $hook = $this->module->current_hook;
            if (in_array($hook, array('displayTop', 'displayTopColumn', 'displayFooter'))) {
                $shortcode = '<div class="sf-contener clearfix col-lg-12">' . $shortcode . '</div>';
            }
            return $shortcode;
        } else {
            return '';
        }
    }

    /**
     * @param array $layer Layer options.
     */
    private function doMacros($macros, &$layer, &$product)
    {
        $content = '';

        switch ($layer['type']) {
            case 'html':
                switch ($macros) {
                    case '%mpsl-name%':
                        $content = $product['name'];
                        break;
                    case '%mpsl-short-description%':
                        $content = $product['short_description'];
                        break;
                    case '%mpsl-description%':
                        $content = $product['description'];
                        break;
                    case '%mpsl-tags%':
                        $content = $product['tags'];
                        break;
                    case '%mpsl-price-no-tax%':
                        $content = $product['price_without_tax2'];
                        break;
                    case '%mpsl-price%':
                        $content = $product['price_with_tax2'];
                        break;
                    case '%mpsl-categories%':
                        $content = $product['categories'];
                        break;
                    case '%mpsl-quantity%':
                        $content = $product['quantity'];
                        break;
                    case '%mpsl-in-stock%':
                        $content = $product['in_stock'];
                        break;
                    case '%mpsl-product-url%':
                        $content = $product['product_url'];
                        break;
                    case '%mpsl-product-image%':
                        $cover_key = 'cover_url_' . MotoPressSlider::FULLSIZE;
                        $content = '<img src="' . $product[$cover_key] . '" alt="' . $product['name'] . '" />';
                        break;
                    case '%mpsl-product-image-url%':
                        $cover_key = 'cover_url_' . MotoPressSlider::FULLSIZE;
                        $content = $product[$cover_key];
                        break;
                }
                break;

            case 'image':
                switch ($macros) {
                    case '%mpsl-product-image%':
                    case '%mpsl-product-image-url%':
                        $cover_key = 'cover_url_' . $layer['image_size'];
                        $content = $product[$cover_key];
                        break;
                }
                break;

            case 'button':
                switch ($macros) {
                    case '%mpsl-product%':
                    case '%mpsl-product-url%':
                        $content = $product['product_url'];
                        break;
                    case '%mpsl-add-to-cart%':
                    case '%mpsl-add-to-cart-url%':
                        $content = $product['cart_url'];
                        break;
                }
                break;
        } // switch ($layer['type'])

        return $content;
    }

    /**
     * @param MPSLSlider $slider
     */
    private function getSliderData($slider)
    {
        $options = $slider->settings->getOptions();

        $raw = array(
            'data-full-window-width' => ($options['full_width'] && !$this->is_edit_mode ? 'true' : 'false'),
            'data-full-height' => ($options['full_height'] && !$this->is_edit_mode ? 'true' : 'false'),
            'data-full-height-offset' => $options['full_height_offset'] . $options['full_height_units'],
            'data-full-height-offset-container' => $options['full_height_container'],
            'data-full-size-grid' => ($options['full_size_grid'] && !$this->is_edit_mode ? 'true' : 'false'),
            'data-timer' => ($options['enable_timer'] && $slider->getItemsCount() > 1 ? 'true' : 'false'),
            'data-timer-delay' => $options['slider_delay'],
            'data-hover-timer' => ($options['hover_timer'] ? 'true' : 'false'),
            'data-counter' => ($options['counter'] && !$this->is_edit_mode ? 'true' : 'false'),
            'data-slider-layout' => 'auto',
            'data-grid-width' => $options['width'],
            'data-grid-height' => $options['height'],
            'data-timer-reverse' => ($options['timer_reverse'] ? 'true' : 'false'),
            'data-arrows-show' => ($options['arrows_show'] ? 'true' : 'false'),
            'data-thumbnails-show' => ($options['thumbnails_show'] ? 'true' : 'false'),
            'data-slideshow-timer-show' => ($options['slideshow_timer_show'] ? 'true' : 'false'),
            'data-slideshow-ppb-show' => ($options['slideshow_ppb_show'] ? 'true' : 'false'),
            'data-controls-hide-on-leave' => ($options['controls_hide_on_leave'] ? 'true' : 'false'),
            'data-swipe' => ($options['swipe'] ? 'true' : 'false'),
            'data-delay-init' => ($options['delay_init'] ? $options['delay_init'] : 0),
            'data-scroll-init' => ($options['scroll_init'] ? 'true' : 'false'),
            'data-start-slide' => ($options['start_slide'] ? $options['start_slide'] : 1),
            'data-visible-from' => $options['visible_from'],
            'data-visible-till' => $options['visible_till'],
            'data-custom-class' => trim($options['custom_class'])
        );

        if ($options['full_width'] && !$this->is_edit_mode) {
            $raw['data-custom-class'] .= ' ms_fullwidth_fix';
        }

        if ($this->is_edit_mode) {
            $raw['data-edit-mode'] = 'true';
        }

        $data = mpsl_implode_assoc('" ', $raw, '="');
        return $data;
    }

    /**
     * @param MPSLSlide $slide
     */
    private function getSlideData($slide, &$slider_options)
    {
        $options = $slide->settings->getOptions();

        $raw = array(
            'slide' => array(
                'data-class' => $options['slide_classes'],
                'data-id' => $slide->id,
                'data-animation' => '',
                'data-fade-animation' => '',
                'data-duration' => '',
                'data-easing' => ''
            )
        );

        // Update animation options
        if (!empty($slider_options['slider_animation'])) {
            $raw['slide']['data-animation'] = $slider_options['slider_animation'];
        }
        if (!empty($slider_options['slider_animation'])) {
            $raw['slide']['data-fade-animation'] = $slider_options['slider_animation'];
        }
        if (!empty($slider_options['slider_duration'])) {
            $raw['slide']['data-duration'] = $slider_options['slider_duration'];
        }
        if (!empty($slider_options['slider_easing'])) {
            $raw['slide']['data-easing'] = $slider_options['slider_easing'];
        }

        // Link "data-" attributes
        if (!$this->is_edit_mode && !empty($options['link'])) {
            $raw['slide']['data-link'] = $options['link'];
            $raw['slide']['data-link-target'] = '_self';
            if ($options['link_blank'] === true || $this->module->is_preview_page) {
                $raw['slide']['data-link-target'] = '_blank';
            }
            if (!empty($options['link_id'])) {
                $raw['slide']['data-link-id'] = $options['link_id'];
            }
            if (!empty($options['link_class'])) {
                $raw['slide']['data-link-class'] = $options['link_class'];
            }
            if (!empty($options['link_rel'])) {
                $raw['slide']['data-link-rel'] = $options['link_rel'];
            }
            if (!empty($options['link_title'])) {
                $raw['slide']['data-link-title'] = $options['link_title'];
            }
        }

        // Get background color
        if (!empty($options['bg_color_type'])) {
            if ($options['bg_color_type'] === 'color' && !empty($options['bg_color'])) {
                // Background color
                $raw['bgcolor'] = array(
                    'data-type' => $options['bg_color_type'],
                    'data-color' => $options['bg_color']
                );
            } elseif ($options['bg_color_type'] === 'gradient') {
                // Background gradient
                if (!empty($options['bg_grad_color_1']) || !empty($options['bg_grad_color_2'])) {
                    $raw['bgcolor'] = array(
                        'data-type' => $options['bg_color_type'],
                        'data-color-initial' => 'transparent',
                        'data-color-final' => 'transparent',
                        'data-position' => '0deg'
                    );
                    if (!empty($options['bg_grad_color_1'])) {
                        $raw['bgcolor']['data-color-initial'] = $options['bg_grad_color_1'];
                    }
                    if (!empty($options['bg_grad_color_2'])) {
                        $raw['bgcolor']['data-color-final'] = $options['bg_grad_color_2'];
                    }
                    if ($options['bg_grad_angle']) {
                        $raw['bgcolor']['data-position'] = $options['bg_grad_angle'] . 'deg';
                    }
                }
            }
        }

        // Get background image
        if (!empty($options['bg_image_type'])) {
            $raw['bgimage'] = array(
                'data-type' => 'image',
                'data-fit' => $options['bg_fit']
            );
            if ($options['bg_fit'] === 'percentage') {
                $raw['bgimage']['data-fit-x'] = $options['bg_fit_x'];
                $raw['bgimage']['data-fit-y'] = $options['bg_fit_y'];
            }
            $raw['bgimage']['data-position'] = $options['bg_position'];
            if ($options['bg_position'] === 'percentage') {
                $raw['bgimage']['data-position-x'] = $options['bg_position_x'];
                $raw['bgimage']['data-position-y'] = $options['bg_position_y'];
            }
            $raw['bgimage']['data-repeat'] = $options['bg_repeat'];
            // Background Image Media Library
            if ($options['bg_image_type'] === 'library' && !empty($options['bg_image_id'])) {
                $image_src = mpsl_get_image_src($options['bg_image_id']);
                if ($image_src !== false) {
                    $raw['bgimage']['data-src'] = $image_src;
                }
            }
            // Background Image External/Product
            if (in_array($options['bg_image_type'], array('external', 'product'))
                && !empty($options['bg_image_url'])
            ) {
                $raw['bgimage']['data-src'] = $options['bg_image_url'];
            }
            // Check for "data-src": no src, no background image
            if (!isset($raw['bgimage']['data-src'])) {
                unset($raw['bgimage']);
            }
        }

        // Get background video
        if (!$this->is_edit_mode) {
            if (!empty($options['bg_video_src_mp4'])
                || !empty($options['bg_video_src_webm'])
                || !empty($options['bg_video_src_ogg'])
            ) {
                $raw['bgvideo'] = array(
                    'data-type' => 'video',
                    'data-src-mp4' => trim($options['bg_video_src_mp4']),
                    'data-src-webm' => trim($options['bg_video_src_webm']),
                    'data-src-ogg' => trim($options['bg_video_src_ogg']),
                    'data-loop' => ($options['bg_video_loop'] ? 'true' : 'false'),
                    'data-mute' => ($options['bg_video_mute'] ? 'true' : 'false'),
                    'data-fillmode' => $options['bg_video_fillmode'],
                    'data-cover' => ($options['bg_video_cover'] ? 'true' : 'false'),
                    'data-cover-type' => $options['bg_video_cover_type'],
                    'data-autoplay' => 'true'
                );
            }
        }

        $data = array();
        foreach ($raw as $key => $values) {
            $data[$key] = mpsl_implode_assoc('" ', $values, '="');
        }
        return $data;
    }

    private function getLayerData($layer, &$private_presets)
    {
        $options = $layer->settings->getOptions();
        $type = $options['type'];

        $layer_preset = $options['preset'];
        if ($layer_preset) {
            if ($layer_preset == 'private') {
                $layer_preset = $options['private_preset_class'];
                if ($this->is_edit_mode && $layer_preset) {
                    $private_presets[$layer_preset] = $options['private_styles'];
                }
            }
        }
        $layer_preset = 'mpsl-layer ' . $layer_preset;

        $raw = array(
            'atts' => array(
                'data-id' => $layer->id,
                'data-type' => $type
            ),
            'img_atts' => array(),
            'content' => ''
        );

        switch ($type) {

            case 'html':
                if ($options['white-space']) {
                    $raw['atts']['data-white-space'] = $options['white-space'];
                }
                $raw['content'] = $options['text'];
                break;

            case 'image':
                if ($options['image_type'] != 'product') {
                    $image_src = mpsl_get_image_src($options['image_id']);
                } else {
                    // Got product image
                    $image_src = $options['image_url'];
                }

                $image_link = trim($options['image_link']);
                $image_link_classes = trim($options['image_link_classes']);
                if ($image_link) {
                    $image_link_classes .= ' ' . $layer_preset;
                    $layer_preset = '';
                }

                if ($image_src) {
                    $raw['img_atts']['src'] = $image_src;
                }
                $raw['img_atts']['width'] = $options['width'];
                $raw['atts']['data-width'] = $options['width'];
                $raw['atts']['data-link'] = $options['image_link'];
                $raw['atts']['data-target'] = ($options['image_target'] === true ? '_blank' : '_self');
                $raw['atts']['data-link-class'] = $image_link_classes;
                break;

            case 'button':
                $raw['atts']['data-link'] = $options['button_link'];
                $raw['atts']['data-target'] = ($options['button_target'] === true ? '_blank' : '_self');

                $raw['content'] = $options['button_text'];
                break;

            case 'video':
                $options['video_preview_image'] = trim($options['video_preview_image']);
                $video_type = $options['video_type'];
                switch ($video_type) {
                    case 'html':
                        $raw['atts']['data-src-mp4'] = trim($options['video_src_mp4']);
                        $raw['atts']['data-src-webm'] = trim($options['video_src_webm']);
                        $raw['atts']['data-src-ogv'] = trim($options['video_src_ogg']);
                        $raw['atts']['data-controls'] = ($options['video_html_hide_controls'] ? 'false' : 'true');
                        break;

                    case 'youtube':
                        $raw['atts']['data-type'] = 'youtube';
                        $raw['atts']['data-src'] = $options['youtube_src'];
                        $raw['atts']['data-controls'] = ($options['video_youtube_hide_controls'] ? 'false' : 'true');

                        if (empty($options['video_preview_image'])) {
                            $youtube_api = MPSLYoutubeDataApi::getInstance();
                            $youtube_id = $youtube_api->getIdByUrl($options['youtube_src']);
                            $youtube_thumbnail = $youtube_api->getThumbnail($youtube_id);
                            if ($youtube_thumbnail !== false) {
                                $options['video_preview_image'] = $youtube_thumbnail;
                            }
                        }
                        break;

                    case 'vimeo':
                        $raw['atts']['data-type'] = 'vimeo';
                        $raw['atts']['data-src'] = $options['vimeo_src'];

                        if (empty($options['video_preview_image'])) {
                            $vimeo_api = MPSLVimeoOEmbedApi::getInstance();
                            $vimeo_id = $vimeo_api->getIdByUrl($options['vimeo_src']);
                            $vimeo_thumbnail = $vimeo_api->getThumbnail($vimeo_id);
                            if ($vimeo_thumbnail !== false) {
                                $options['video_preview_image'] = $vimeo_thumbnail;
                            }
                        }
                        break;
                } // switch $video_type

                if (!empty($options['video_width'])) {
                    $raw['atts']['data-width'] = $options['video_width'];
                }
                if (!empty($options['video_height'])) {
                    $raw['atts']['data-height'] = $options['video_height'];
                }
                $raw['atts']['data-poster'] = $options['video_preview_image'];
                $raw['atts']['data-autoplay'] = ($options['video_autoplay'] ? 'true' : 'false');
                $raw['atts']['data-loop'] = ($options['video_loop'] ? 'true' : 'false');
                $raw['atts']['data-mute'] = ($options['video_mute'] ? 'true' : 'false');
                $raw['atts']['data-disable-mobile'] = ($options['video_disable_mobile'] ? 'true' : 'false');
                break; // case 'video'

        } // switch $type

        // Print other not type-dependent attributes
        $raw['atts']['data-align-horizontal'] = $options['hor_align'];
        $raw['atts']['data-align-vertical'] = $options['vert_align'];
        $raw['atts']['data-offset-x'] = $options['offset_x'];
        $raw['atts']['data-offset-y'] = $options['offset_y'];
        if (!empty($options['html_width'])) {
            $raw['atts']['data-width'] = $options['html_width'];
        }

        // Animation, timing and easing
        $raw['atts']['data-animation'] = $options['start_animation'];
        $raw['atts']['data-timing-function'] = $options['start_timing_function'];
        $raw['atts']['data-duration'] = $options['start_duration'];
        $raw['atts']['data-leave-animation'] = $options['end_animation'];
        $raw['atts']['data-leave-timing-function'] = $options['end_timing_function'];
        $raw['atts']['data-leave-duration'] = $options['end_duration'];
        $raw['atts']['data-delay'] = $options['start'];

        if ($options['end']) {
            $raw['atts']['data-leave-delay'] = $options['end'];
        }

        $raw['atts']['data-class'] = trim($options['classes'] . ' ' . $layer_preset);
        $raw['atts']['data-resizable'] = '1';
        $raw['atts']['data-dont-change-position'] = '';
        $raw['atts']['data-hide-width'] = '';

        $data = array(
            'atts' => mpsl_implode_assoc('" ', $raw['atts'], '="'),
            'img_atts' => mpsl_implode_assoc('" ', $raw['img_atts'], '="'),
            'content' => $raw['content']
        );
        return $data;
    }

    private function loadProducts()
    {
        // Create product slides...
        $slider = $this->slider;
        $template_slide = $slider->getFirstItem();
        $template_layers = $template_slide->getItemsArray();
        $slide_options = $template_slide->settings->getOptions();

        // Collect used product image types
        $image_sizes = $this->collectImageTypes($slide_options, $template_layers);

        // Load products data
        $product_options = $this->slider->settings->getGroupOptions('product');
        $product_options['image_types'] = $image_sizes;
        $this->products = MPSLProductQuery::queryProducts($product_options);

        $link_slides = (bool)$product_options['link_slides'];
        $link_blank = (bool)$product_options['link_blank'];

        // Remove template slide from slider
        $slider->removeItem($template_slide->id);

        // Add new slides and layers to slider
        $slides_added = 0;
        foreach ($this->products as $product) {
            // Create new slide
            $slide_order = $slides_added + 1;
            $slide = new MPSLSlide(0, $slide_options, $this->slider_id, $slide_order);
            // Maybe link slide to product
            if ($link_slides) {
                $slide->settings->updateOption('link', $product['product_url']);
                if ($link_blank) {
                    $slide->settings->updateOption('link_blank', true);
                }
            }
            // Maybe set product image as background
            $bg_type = $slide->settings->getOption('bg_image_type');
            if ($bg_type == 'product') {
                $cover_key = 'cover_url_' . MotoPressSlider::FULLSIZE;
                $slide->settings->updateOption('bg_image_url', $product[$cover_key]);
            }

            // Add layers
            // Convert all macroses in template layers
            $layers = $this->translateLayers($template_layers, $product);
            // Create layers
            foreach ($layers as $layer_order => &$layer_options) {
                // Add new layer to slide
                $layer = new MPSLLayer(0, $layer_options, $slide->id, $layer_order);
                $slide->addItemAt($layer, $layer_order);
            }
            // Add new slides to slider
            $slider->addItemAt($slide, $slide_order);
            $slides_added += 1;
        }

        if ($slides_added == 0) {
            $this->slider_is_empty = true;
        }
    }

    private function collectImageTypes($slide_options, $layers)
    {
        $image_sizes = array();
        // Slide images
        if ($slide_options['bg_image_type'] == 'product') {
            $image_sizes[] = MotoPressSlider::FULLSIZE;
        }
        // Layer images
        foreach ($layers as $layer) {
            if ($layer['type'] == 'image' && $layer['image_type'] == 'product') {
                $image_sizes[] = $layer['image_size'];
            }
        }
        $image_sizes = array_unique($image_sizes);
        return $image_sizes;
    }

    /**
     * Translate all macroses into content.
     * @param array $layers An array of layers (in array format). See
     * MPSLEntity::getItemsArray().
     */
    private function translateLayers($layers, &$product)
    {
        // Handle product images
        foreach ($layers as $order => $layer) {
            if ($layer['image_type'] == 'product') {
                // Got product image; just replace it's "image_url" to macros
                $layers[$order]['image_url'] = '%mpsl-product-image-url%';
            }
        }

        // Get all macroses
        $slide_macroses = $this->gatherSlideMacroses($layers);

        foreach ($slide_macroses as $order => $data) {
            // Get layer
            $layer = &$layers[$order];

            // extract($data) - no need to generate 4 errors in PrestaShop Validator
            $option = $data['option'];
            $value = $data['value'];
            $macroses = $data['macroses'];

            // Do macroses
            $contents = array();
            foreach ($macroses as $macros) {
                $contents[] = $this->doMacros($macros, $layer, $product);
            }
            $content = mpsl_str_substitute($macroses, $contents, $value);
            $content = trim($content);
            $layer[$option] = $content;
        }

        return $layers;
    }

    /**
     * @param array $layers An array of layers (in array format). See
     * MPSLEntity::getItemsArray().
     * @return array Macros data in format:
     *     array(
     *         %index of the layer with macroses% => array(
     *             'option' => option name that contains macroses
     *             'value' => option value
     *             'macroses' => array of macros names (full, with "%" - "%mpsl-price%")
     *         ),
     *         ...
     *     )
     */
    private function gatherSlideMacroses($layers)
    {
        $slide_macroses = array();

        foreach ($layers as $order => $layer) {
            $type = $layer['type'];

            // Get option name that can contain macroses
            switch ($type) {
                case 'html':
                    $option = 'text';
                    break;
                case 'image':
                    $option = 'image_url';
                    break;
                case 'button':
                    $option = 'button_link';
                    break;
                default:
                    continue; // Other layer types can't contain macroses, so
                              // just skip them
            }

            $value = $layer[$option];
            // Macroses with "%": "%mpsl-price%", "%mpsl-product-url%" etc.
            $macroses = mpsl_preg_match_all('/%[\w-]+%/', $value, array());

            if (!empty($macroses)) {
                // Add new data
                $data = array(
                    'option' => $option,
                    'value' => $value,
                    'macroses' => $macroses
                );
                $slide_macroses[$order] = $data;
            }
            // Otherwise no need to do anything with the value; skip the layer
        }

        return $slide_macroses;
    }

    private function pushFonts($slider_id, $slider_fonts)
    {
        // Get all presets fonts (except private)
        $all_fonts = ($this->is_edit_mode ? $this->presets_obj->getFonts() : array());

        // Merge fonts and make unique each of them
        $slider_fonts = array_merge_recursive($slider_fonts, $all_fonts);
        $slider_fonts = $this->presets_obj->fontsUnique($slider_fonts);

        // Format fonts
        $fonts_url = array();
        foreach ($slider_fonts as $font_name => &$font_data) {
            // Unique variants
            $font_data['variants'] = array_unique($font_data['variants']);
            $fonts_url_item = $font_name;
            if (count($font_data['variants'])) {
                $fonts_url_item .= ':' . implode(',', $font_data['variants']);
            }
            $fonts_url[] = urlencode($fonts_url_item);
        }

        // Convert URLs to string format
        if (count($fonts_url)) {
            $fonts_url = sprintf('https://fonts.googleapis.com/css?family=%s', implode('|', $fonts_url));
        } else {
            $fonts_url = '';
        }

        if ($this->is_edit_mode) {
            $this->module->gfonts_url = $fonts_url;
        } elseif ($fonts_url) {
            echo '<p class="motopress-hide-script mpsl-hide-script">';
            echo '<script type="text/javascript" id="mpsl-slider-fonts-load-' . $slider_id . '">';

            echo 'var font = document.createElement("link");';
            echo 'font.rel = "stylesheet";';
            echo 'font.type = "text/css";';
            echo 'font.className = "mpsl-fonts-link";';
            echo 'font.href = "' . $fonts_url . '";';
            echo 'document.getElementsByTagName("head")[0].appendChild(font);';

            echo '</script>';
            echo '</p>';
        }
    }

    private function pushPresets($slider_id, $private_presets)
    {
        if ($this->is_ajax) {
            echo '<p class="motopress-hide-script mpsl-hide-script">';
            echo '<script type="text/javascript" id="mpsl-init-slider-' . $slider_id . '">';

            echo 'jQuery(document).ready(function ($) {';
            echo 'MPSLManager.initSlider($("#' . $slider_id . '")[0]);';
            echo '});';

            echo '</script>';
            echo '</p>';
        } else {
            if ($this->is_edit_mode) {
                $this->module->default_presets = $this->presets_obj->compile(
                    $this->presets_obj->getDefaultPresets(),
                    false,
                    true
                );
                $this->module->custom_presets = $this->presets_obj->compile(
                    $this->presets_obj->getCustomPresets(),
                    false,
                    true
                );
                $this->module->private_presets = $this->presets_obj->compile(
                    $private_presets,
                    true,
                    true
                );
            }
        }
    }

    private function pushHeightFix($slider_id, &$slider_options)
    {
        echo '<p class="motopress-hide-script mpsl-hide-script">';
        echo '<script type="text/javascript" id="mpsl-fix-height-' . $slider_id . '">';

        echo 'var aspect = ' . ($slider_options['height']/$slider_options['width']) . ';';
        echo 'var sliderWrapper = document.getElementById("' . $slider_id . '");';
        echo 'var outerWidth = sliderWrapper.offsetWidth;';
        echo 'var curHeight = outerWidth*aspect;';
        echo 'sliderWrapper.querySelector("[data-motoslider]").height = curHeight + "px";';

        echo '</script>';
        echo '</p>';
    }
}
