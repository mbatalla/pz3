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

class MPSLSlidersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->controller_type = 'moduleadmin';
        $this->page_name = 'module-motopressslider-' . Dispatcher::getInstance()->getController();
        $this->bootstrap = true;
        parent::__construct();

        $this->module->ctrls->maybeRedirect();

        $this->setTemplate('sliders.tpl');

        $this->page_header_toolbar_btn['mpsl-add-slider'] = array(
            'href' => '#',
            'icon' => 'process-icon-plus',
            'desc' => $this->l('Add Slider')
        );
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
            // If the file with template is located in the root directory
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

    public function displayAjaxMpslDuplicateSlider()
    {
        mpsl_duplicate_slider();
    }

    public function displayAjaxMpslDeleteSlider()
    {
        mpsl_delete_slider();
    }

    public function registerJsVars()
    {
        $preview_controller = 'MPSLPreview';
        $preview_token = $this->module->ctrls->getControllerToken($preview_controller);
        $slider_controller = 'MPSLSlider';
        $slider_token = $this->module->ctrls->getControllerToken($slider_controller);

        $vars = mpsl_read('includes/defaults/mpsl-vars.php');
        $vars['Vars']['page'] = array_merge($vars['Vars']['page'], array(
            'preview_controller' => $preview_controller,
            'preview_token' => $preview_token,
            'slider_controller' => $slider_controller,
            'slider_token' => $slider_token,
            'magic_quotes_state' => get_magic_quotes_gpc()
        ));

        $this->module->addJsDef('MPSL', $vars);
    }

    public function display()
    {
        $page_heading_data = array(
            'title' => $this->l('Sliders'),
            'count' => 0, // Update when $sliders_data will be ready
            'actions' => array(
                'slider-add' => array(
                    'title' => $this->l('Add'),
                    'link' => '#',
                    'icon' => 'process-icon-new'
                ),
                'slider-import' => array(
                    'title' => $this->l('Import'),
                    'link' => $this->module->ctrls->getControllerLink('MPSLImpex'),
                    'icon' => 'process-icon-import'
                ),
                'slider-export' => array(
                    'title' => $this->l('Export'),
                    'link' => $this->module->ctrls->getControllerLink('MPSLImpex'),
                    'icon' => 'process-icon-export'
                )
            )
        );

        $sliders_data = array(
            'name' => 'sliders',
            'columns' => array(
                'id' => array(
                    'title' => $this->l('ID'),
                    'class' => 'fixed-width-xs center'
                ),
                'name' => array(
                    'title' => $this->l('Name')
                ),
                'shortcode' => array(
                    'title' => $this->l('Shortcode*')
                ),
                'display_on' => array(
                    'title' => $this->l('Display on')
                ),
                'visible' => array(
                    'title' => $this->l('Visible from/till')
                ),
                'action' => array(
                    'title' => $this->l('Actions'),
                    'class' => 'fixed-width-md center',
                    'type' => 'actions'
                )
            ),
            'actions' => array(
                array(
                    'title' => $this->l('Edit'),
                    'view' => 'Slides',
                    'id_name' => 'id',
                    'icon' => 'icon-pencil'
                ),
                array(
                    'title' => $this->l('Settings'),
                    'view' => 'Slider',
                    'id_name' => 'id',
                    'icon' => 'icon-gear'
                ),
                array(
                    'title' => $this->l('Preview'),
                    'link' => '#',
                    'icon' => 'icon-eye',
                    'class' => 'mpsl-preview-slider-btn',
                    'atts' => array(
                        'data-mpsl-slider-id' => 'id'
                    )
                ),
                array(
                    'title' => $this->l('Duplicate'),
                    'link' => '#',
                    'icon' => 'icon-copy',
                    'class' => 'mpsl-duplicate-slider-btn',
                    'atts' => array(
                        'data-mpsl-slider-id' => 'id'
                    )
                ),
                array(
                    'type' => 'divider'
                ),
                array(
                    'title' => $this->l('Delete'),
                    'link' => '#',
                    'icon' => 'icon-trash',
                    'class' => 'mpsl-delete-slider-btn',
                    'atts' => array(
                        'data-mpsl-slider-id' => 'id'
                    )
                )
            ),
            'rows' => $this->module->db->getSliders()
        );

        // Add link to each slider name (title)
        foreach ($sliders_data['rows'] as $no => $slider) {
            $slides_link = $this->module->ctrls->getControllerLink('MPSLSlides', array('id' => $slider['id']));
            $new_name = '<a href="' . $slides_link . '">' . $slider['name'] . '</a>';
            $sliders_data['rows'][$no]['name'] = $new_name;
        }

        // Update rows count
        $page_heading_data['count'] = count($sliders_data['rows']);

        // Get slider presets
        $slider_presets = mpsl_read('includes/defaults/slider-presets.php');

        $this->context->smarty->assign('page_heading_data', $page_heading_data);
        $this->context->smarty->assign('sliders_data', $sliders_data);
        $this->context->smarty->assign('slider_presets', $slider_presets);
        $this->context->smarty->assign('admin_templates', $this->module->templates_dir . 'admin');

        parent::display();
    }

    public function initFooter()
    {
        $vendor = $this->module->vendor_dir;
        $js = $this->module->js_dir;

        // Add vendor scripts and styles
        $this->addJquery();
        $this->addJqueryUI('ui.dialog');
        $this->addJqueryUI('ui.button');
        $this->addCSS($vendor . 'jqueryui/ui-smoothness/jquery-ui.css');

        if (!MotoPressSlider::IS_DEBUG) {
            $this->addJS($vendor . 'canjs/can.custom.min.js');
        } else {
            $this->addJS($vendor . 'canjs/can.custom.js');
        }

        // Add MotoPress core scripts
        $this->addJS($js . 'functions.js');

        // Add main scripts
        $this->addJS($js . 'sliders.js');

        parent::initFooter();
    }
}
