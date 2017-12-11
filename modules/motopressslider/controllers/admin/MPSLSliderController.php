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

class MPSLSliderController extends ModuleAdminController
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

        $this->setTemplate('slider.tpl');
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

    public function displayAjaxMpslCreateSlider()
    {
        mpsl_create_slider();
    }

    public function displayAjaxMpslUpdateSlider()
    {
        mpsl_update_slider();
    }

    public function displayAjaxMpslProductsPreview()
    {
        mpsl_products_preview();
    }

    public function registerJsVars()
    {
        $slider_type = Tools::getValue('type', 'custom');
        $slider_id = (int)Tools::getValue('id', 0);
        $slider = new MPSLSlider($slider_id, null, $slider_type);
        $is_product = $slider->isProduct();
        $is_new_slider = ($slider_id > 0 ? false : true);
        $settings = $slider->settings->getSettings(true);
        $options = $slider->settings->getSettings();
        $tab = Tools::getValue('tab', 0);

        if (!$is_new_slider) {
            // Get real slider type
            $slider_type = $slider->getType();
        }

        // Get active tab number
        if (is_numeric($tab)) {
            // 0 <= $tab < count($settings)
            $tab = (int)$tab;
            if ($tab < 0 || $tab >= count($settings)) {
                $tab = 0;
            }
        } else {
            // Get tab number by tab name
            if (array_key_exists($tab, $settings)) {
                $tab = array_search($tab, array_keys($settings));
            } else {
                // Name not found
                $tab = 0;
            }
        }

        // Do not show product settings for custom sliders
        if (!$is_product) {
            unset($settings['product']);
        }

        $this->data = array(
            'slider' => $slider,
            'slider_id' => $slider_id,
            'is_product' => $is_product,
            'is_new_slider' => $is_new_slider
        );

        $vars = mpsl_read('includes/defaults/mpsl-vars.php');
        $vars['Vars']['page'] = array_merge($vars['Vars']['page'], array(
            'id' => $slider_id,
            'title' => $slider->getTitle(),
            'alias' => $slider->getAlias(),
            'grouped_options' => $settings,
            'options' => $options,
            'tab' => $tab
        ));
        $this->module->addJsDef('MPSL', $vars);
    }

    public function display()
    {
        // extract($this->data) - no need to generate 9 errors in PrestaShop Validator
        $slider = $this->data['slider'];
        $slider_id = $this->data['slider_id'];
        $is_product = $this->data['is_product'];
        $is_new_slider = $this->data['is_new_slider'];

        $settings = $slider->settings->getSettings(true);
        $template_id = 0;

        if (!$is_new_slider && $is_product) {
            // Get template ID
            $slider->loadItems(1);
            $template_id = $slider->getTemplateId();
        }

        // Do not show product settings for custom sliders
        if (!$is_product) {
            unset($settings['product']);
        }

        $this->context->smarty->assign('settings', $settings);
        $this->context->smarty->assign('slider_id', $slider_id);
        $this->context->smarty->assign('is_product', $is_product);
        $this->context->smarty->assign('is_new_slider', $is_new_slider);
        $this->context->smarty->assign('template_id', $template_id);
        $this->context->smarty->assign('admin_templates', $this->module->templates_dir . 'admin');

        parent::display();
    }

    public function initFooter()
    {
        $vendor = $this->module->vendor_dir;
        $js = $this->module->js_dir;

        // Add vendor scripts and styles
        $this->addJqueryUI('ui.dialog');
        $this->addJqueryUI('ui.core');
        $this->addJqueryUI('ui.widget');
        $this->addJqueryUI('ui.button');
        $this->addJS($vendor . 'codemirror/lib/codemirror.js');
        $this->addCSS($vendor . 'codemirror/lib/codemirror.css');
        $this->addJS($vendor . 'codemirror/mode/css/css.js');
        $this->addCSS($vendor . 'jqueryui/ui-smoothness/jquery-ui.css');

        if (!MotoPressSlider::IS_DEBUG) {
            $this->addJS($vendor . 'canjs/can.custom.min.js');
        } else {
            $this->addJS($vendor . 'canjs/can.custom.js');
        }

        // Add MotoPress core scripts
        $this->addJS($js . 'functions.js');
        $this->addJS($js . 'controls.js');
        $this->addJS($js . 'tabs.js');

        // Add main scripts
        $this->addJS($js . 'slider.js');

        parent::initFooter();
    }
}
