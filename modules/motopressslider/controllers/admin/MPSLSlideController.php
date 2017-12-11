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

class MPSLSlideController extends ModuleAdminController
{
    /** @var array Stores data that could be used in multiple methods. */
    protected $data = array();

    public function __construct()
    {
        $this->controller_type = 'moduleadmin';
        $this->page_name = 'module-motopressslider-' . Dispatcher::getInstance()->getController();
        $this->bootstrap = true;
        parent::__construct();

        $this->module->ctrls->maybeRedirect();

        $this->setTemplate('slide.tpl');
    }

    public function createTemplate($template_name)
    {
        if (file_exists($template_name)) {
            return $this->context->smarty->createTemplate($template_name, $this->context->smarty);
        } else {
            return parent::createTemplate($template_name);
        }
    }

    public function setTemplate($template)
    {
        if (Tools::file_exists_cache($this->module->module_dir . $template)) {
            // If the file with the template is located in the root directory
            $this->template = $this->module->module_dir . $template;
        } elseif (Tools::file_exists_cache($this->getTemplatePath() . $template)) {
            // Otherwise file in module_dir/views/templates/front/ should be used
            $this->template = $this->getTemplatePath() . $template;
        } else {
            // Template file is not found
        }
    }

    public function getTemplatePath()
    {
        return $this->module->templates_dir . 'admin/';
    }

    public function displayAjaxMpslUpdateSlide()
    {
        mpsl_update_slide();
    }

    public function displayAjaxMpslGetYoutubeThumbnail()
    {
        mpsl_get_youtube_thumbnail();
    }

    public function displayAjaxMpslGetVimeoThumbnail()
    {
        mpsl_get_vimeo_thumbnail();
    }

    public function displayAjaxMpslFetchMedia()
    {
        mpsl_fetch_media();
    }

    public function displayAjaxMpslFetchProductMedia()
    {
        mpsl_fetch_product_media();
    }

    public function displayAjaxMpslUploadMedia()
    {
        mpsl_upload_media();
    }

    public function displayAjaxMpslDeleteMedia()
    {
        mpsl_delete_media();
    }

    public function registerJsVars()
    {
        // Create slide object
        $slide_id = Tools::getValue('id', 0);
        $slide = new MPSLSlide($slide_id);
        $slide->loadItems();
        $order = $slide->getOrder();

        // Create slider object (without items)
        $slider_id = $slide->parent_id;
        $slider = new MPSLSlider($slider_id);
        $slider->addItemAt($slide, $order);

        // Add 'Product Image' variant into ['image']['options']['bg_image_type']['list']['product']
        // for template slides
        if ($slider->isProduct()) {
            $product_image_type = array('product' => $this->l('Product Image'));
            $slide->settings->extendListAttr('image', 'bg_image_type', $product_image_type);
        }

        // Create default layer object
        $layer = new MPSLLayer();

        // Get layers
        $ordered_layers = $slide->getOrderedItemsIds();
        $layers = $slide->getItemsArray();
        foreach ($layers as &$layer_array) {
            $layer_array['text'] = htmlspecialchars_decode($layer_array['text'], ENT_QUOTES);
        }

        $preview_controller = 'MPSLPreview';
        $preview_token = $this->module->ctrls->getControllerToken($preview_controller);

        $presets_obj = MPSLPresets::getInstance();

        $this->data = array(
            'slider' => $slider,
            'slide' => $slide,
            'layer' => $layer
        );

        $vars = mpsl_read('includes/defaults/mpsl-vars.php');
        $vars['Vars']['page'] = array_merge($vars['Vars']['page'], array(
            'slider_id' => $slider_id,
            'id' => $slide_id,
            'grouped_options' => $slide->settings->getSettings(true),
            'options' => $slide->settings->getSettings(),
            'preview_controller' => $preview_controller,
            'preview_token' => $preview_token
        ));
        $vars['Vars']['layer'] = array_merge($vars['Vars']['layer'], array(
            'list' => $layers,
            'ordered_list' => $ordered_layers,
            'grouped_options' => $layer->settings->getSettings(true),
            'options' => $layer->settings->getSettings(),
            'defaults' => $layer->settings->getOptions(),
            'white_space_class_prefix' => 'mpsl-white-space-'
        ));
        $vars['Vars']['preset'] = array_merge($vars['Vars']['preset'], array(
            'default_list' => $presets_obj->getDefaultPresets(),
            'list' => $presets_obj->getCustomPresets(),
            'grouped_options' => $presets_obj->getSettings(true),
            'options' => $presets_obj->getSettings(),
            'defaults' => $presets_obj->getDefaultPresetData(),
            'last_id' => $presets_obj->getLastPresetId(),
            'last_private_id' => $presets_obj->getLastPrivateId(),
            'class_prefix' => MPSLPresets::PREFIX, // "mpsl-preset-"
            'private_class_prefix' => MPSLPresets::PRIVATE_PREFIX, // "mpsl-private-preset-"
            'layer_class' => MPSLPresets::LAYER_CLASS, // "mpsl-layer"
            'layer_hover_class' => MPSLPresets::LAYER_HOVER_CLASS // "mpsl-layer-hover"
        ));
        $this->module->addJsDef('MPSL', $vars);
    }

    public function display()
    {
        // extract($this->data) - no need to generate 12 errors in PrestaShop Validator
        $slider = $this->data['slider'];
        $slide = $this->data['slide'];
        $layer = $this->data['layer'];

        // Data for settings forms
        $slide_settings = $slide->settings->getSettings(true);
        $layer_settings = $layer->settings->getSettings(true);
        $preset_settings = MPSLPresets::getInstance()->getSettings(true);

        // Next and previous slide ID
        $slides_ids = $this->module->db->getSlideIds($slider->id);
        $current = array_search($slide->id, $slides_ids);
        $prev_slide_id = ($current - 1 >= 0 ? $slides_ids[$current - 1] : end($slides_ids));
        $next_slide_id = ($current + 1 < count($slides_ids) ? $slides_ids[$current + 1] : reset($slides_ids));

        // Get layer variants
        $keep_variants = (!$slider->isProduct() ? 'custom' : array('custom', 'product'));
        $layer_variants = mpsl_read('includes/defaults/layer-variants.php');
        $layer_variants = mpsl_filter_variants($keep_variants, $layer_variants);

        // Prepare macroses button for "text" and "button_link" options
        $text_macroses = '';
        $button_link_macroses = '';
        if ($slider->isProduct()) {
            $smarty = new MPSLSmarty('admin/option-macroses.tpl');
            if (isset($layer_variants['html']['variants']['product'])) {
                $smarty->assign('title', $this->l('Add Macros'));
                $smarty->assign('variants', $layer_variants['html']['variants']['product']);
                $text_macroses = $smarty->fetch();
            }
            if (isset($layer_variants['button']['variants']['product'])) {
                $smarty->assign('title', $this->l('Set Macros'));
                $smarty->assign('variants', $layer_variants['button']['variants']['product']);
                $button_link_macroses = $smarty->fetch();
            }
        }

        // Get names of the "positioning" and "misc" options
        $positioning_options = array_keys($layer->settings->getGroupOptions('positioning'));
        $misc_options = array_keys($layer->settings->getGroupOptions('misc'));

        // Prepare "Slide duration ..." text
        $slider_delay = $slider->settings->getOption('slider_delay', 0);
        $slide_duration = sprintf($this->l('Slide duration (ms): %d'), $slider_delay);

        $layer_layout = array(
            'positioning' => array( // data
                'class' => 'col-md-3',
                'options' => array(
                    array( // set
                        'type' => 'alternate',
                        'list' => $positioning_options // All "positioning" options
                    )
                )
            ),
            'animation' => array( // data
                'class' => 'col-md-4',
                'options' => array(
                    array( // set
                        'type' => 'grouped',
                        'list' => array(
                            'start_animation',
                            'start_timing_function',
                            'start_duration',
                            'start_animation_group'
                        )
                    ),
                    array( // set
                        'type' => 'grouped',
                        'list' => array(
                            'end_animation',
                            'end_timing_function',
                            'end_duration',
                            'end_animation_group'
                        )
                    ),
                    array( // set
                        'type' => 'alternate',
                        'list' => array('start', 'end')
                    ),
                    array( // set
                        'type' => 'text',
                        'text' => $slide_duration
                    )
                )
            ),
            'misc' => array(
                'class' => 'col-md-5',
                'options' => array(
                    array( // data
                        'type' => 'alternate',
                        'list' => $misc_options, // All "misc" options
                        'prepend' => array( // set
                            'text' => $text_macroses,
                            'button_link' => $button_link_macroses
                        )
                    )
                )
            )
        );

        // Slider data
        $this->context->smarty->assign('slider', $slider);
        $this->context->smarty->assign('slider_id', $slider->id);
        $this->context->smarty->assign('is_product', $slider->isProduct());
        // Data for settings forms
        $this->context->smarty->assign('slide_settings', $slide_settings);
        $this->context->smarty->assign('layer_settings', $layer_settings);
        $this->context->smarty->assign('preset_settings', $preset_settings);
        // Navigation data
        $this->context->smarty->assign('prev_slide_id', $prev_slide_id);
        $this->context->smarty->assign('next_slide_id', $next_slide_id);
        // Variants and layout data
        $this->context->smarty->assign('layer_variants', $layer_variants);
        $this->context->smarty->assign('layer_layout', $layer_layout);
        // Other data
        $this->context->smarty->assign('admin_templates', $this->module->templates_dir . 'admin');

        parent::display();
    }

    public function initFooter()
    {
        $vendor = $this->module->vendor_dir;

        // Add vendor scripts and styles
        $this->addJqueryUI('ui.dialog');
        $this->addJqueryUI('ui.core');
        $this->addJqueryUI('ui.widget');
        $this->addJqueryUI('ui.mouse');
        $this->addJqueryUI('ui.draggable');
        $this->addJqueryUI('ui.droppable');
        $this->addJqueryUI('ui.resizable');
        $this->addJqueryUI('ui.sortable');
        $this->addJqueryUI('ui.tabs');
        $this->addJqueryUI('ui.datepicker');
        $this->addJqueryUI('ui.button');
        $this->addJS($vendor . 'codemirror/lib/codemirror.js');
        $this->addCSS($vendor . 'codemirror/lib/codemirror.css');
        $this->addJS($vendor . 'codemirror/mode/css/css.js');
        $this->addCSS($vendor . 'spectrum/spectrum.css');
        $this->addJS($vendor . 'spectrum/spectrum.js');
        $this->addCSS($vendor . 'jqueryui/ui-smoothness/jquery-ui.css');
        $this->addJS($vendor . 'dropzone/js/dropzone.js');
        $this->addCSS($vendor . 'dropzone/css/dropzone.css');

        if (!MotoPressSlider::IS_DEBUG) {
            $this->addJS($vendor . 'canjs/can.custom.min.js');
            if ($this->module->is_mobile) {
                $this->addJS($vendor . 'jqueryui/jquery-ui-touch/jquery.ui.touch-punch.min.js');
            }
        } else {
            $this->addJS($vendor . 'canjs/can.custom.js');
            if ($this->module->is_mobile) {
                $this->addJS($vendor . 'jqueryui/jquery-ui-touch/jquery.ui.touch-punch.js');
            }
        }

        // Add MotoPress core scripts
        $this->module->addCore();
        $this->addCSS($this->module->css_dir . 'slide.css');
        $this->module->addFooterScript('functions.js');
        $this->module->addFooterScript('tabs.js');
        $this->module->addFooterScript('media-library.js');
        $this->module->addFooterScript('controls.js');

        // Add main scripts
        $this->module->addFooterScript('slide.js');

        parent::initFooter();
    }
}
