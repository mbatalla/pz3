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

class MPSLSlidesController extends ModuleAdminController
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

        $this->setTemplate('slides.tpl');

        $this->page_header_toolbar_btn['mpsl-add-slide'] = array(
            'href' => '#',
            'icon' => 'process-icon-plus',
            'desc' => $this->l('Add Slide')
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
            // If the file with the template is located in the root directory
            $this->template = $this->module->module_dir . $template;
        } elseif (Tools::file_exists_cache($this->getTemplatePath() . $template)) {
            // Otherwise file in module_dir/views/templates/front/ should be used
            $this->template = $this->getTemplatePath() . $template;
        } else {
            // Template file is not found
        }
    }

    public function displayAjaxMpslCreateSlide()
    {
        mpsl_create_slide();
    }

    public function displayAjaxMpslDuplicateSlide()
    {
        mpsl_duplicate_slide();
    }

    public function displayAjaxMpslDeleteSlide()
    {
        mpsl_delete_slide();
    }

    public function displayAjaxMpslUpdateSlidesOrder()
    {
        mpsl_update_slides_order();
    }

    public function getTemplatePath()
    {
        return $this->module->templates_dir . 'admin/';
    }

    public function registerJsVars()
    {
        $slider_id = (int)Tools::getValue('id', 0);
        $slider = new MPSLSlider($slider_id);
        $slider->loadItems(1);

        // If the slider is product then redirect to slide edit page
        if ($slider->isProduct()) {
            $template_id = $slider->getTemplateId();
            $redirect_link = $this->module->ctrls->getControllerLink('MPSLSlide', array('id' => $template_id));
            Tools::redirect($redirect_link);
        }

        $this->data = array(
            'slider_id' => $slider_id,
            'slider' => $slider
        );

        $vars = mpsl_read('includes/defaults/mpsl-vars.php');
        $vars['Vars']['page'] = array_merge($vars['Vars']['page'], array('slider_id' => $slider_id));
        $this->module->addJsDef('MPSL', $vars);
    }

    public function display()
    {
        // extract($this->data) - no need to generate 2 errors in PrestaShop Validator
        $slider_id = $this->data['slider_id'];
        $slider = $this->data['slider'];

        $slides_data = array(
            'name' => 'slides',
            'columns' => array(
                'sortable' => array(
                    'title' => '',
                    'class' => 'fixed-width-xs center mpsl-slide-sort-handle',
                    'type' => 'sort'
                ),
                'id' => array(
                    'title' => $this->l('ID'),
                    'class' => 'fixed-width-xs center'
                ),
                'title' => array(
                    'title' => $this->l('Title')
                ),
                'status' => array(
                    'title' => $this->l('Status')
                ),
                'visible_for' => array(
                    'title' => $this->l('Visible for')
                ),
                'date_from' => array(
                    'title' => $this->l('Date From')
                ),
                'date_until' => array(
                    'title' => $this->l('Date Until')
                ),
                'action' => array(
                    'title' => 'Actions',
                    'class' => 'fixed-width-md center',
                    'type' => 'actions'
                )
            ),
            'actions' => array(
                array(
                    'title' => $this->l('Edit'),
                    'view' => 'Slide',
                    'id_name' => 'id',
                    'icon' => 'icon-pencil'
                ),
                array(
                    'title' => $this->l('Duplicate'),
                    'link' => '#',
                    'icon' => 'icon-copy',
                    'class' => 'mpsl-duplicate-slide-btn',
                    'atts' => array(
                        'data-mpsl-slide-id' => 'id'
                    )
                ),
                array(
                    'type' => 'divider'
                ),
                array(
                    'title' => $this->l('Delete'),
                    'link' => '#',
                    'icon' => 'icon-trash',
                    'class' => 'mpsl-delete-slide-btn',
                    'atts' => array(
                        'data-mpsl-slide-id' => 'id'
                    )
                )
            ),
            'rows' => array(),
            'push' => 'id'
        );

        // Generate rows for slides data
        $slides = $slider->getItems();
        foreach ($slides as $slide) {
            $slide_link = $this->module->ctrls->getControllerLink('MPSLSlide', array('id' => $slide->id));
            $row = $slide->getTableData();
            $row['title'] = '<a class="mpsl-slide-name" href="' . $slide_link . '">' . $row['title'] . '</a>';
            $row['visible_for'] = ($row['need_logged_in'] ? $this->l('logged-in') : $this->l('all'));
            $slides_data['rows'][$slide->id] = $row;
        }

        $page_heading_data = array(
            'title' => $this->l('Slides'),
            'count' => count($slides_data['rows']),
            'actions' => array(
                'slide-add' => array(
                    'title' => $this->l('Add'),
                    'link' => '#',
                    'icon' => 'process-icon-new'
                )
            )
        );

        $this->context->smarty->assign('slider_id', $slider_id);
        $this->context->smarty->assign('page_heading_data', $page_heading_data);
        $this->context->smarty->assign('slides_data', $slides_data);
        $this->context->smarty->assign('admin_templates', $this->module->templates_dir . 'admin');

        parent::display();
    }

    public function initFooter()
    {
        $vendor = $this->module->vendor_dir;
        $js = $this->module->js_dir;

        // Add vendor scripts and styles
        $this->addJquery();
        $this->addJqueryUI('ui.sortable');
        $this->addJqueryUI('ui.button');
        $this->addCSS($vendor . 'jqueryui/ui-smoothness/jquery-ui.css');

        if ($this->module->is_mobile) {
            $this->addJqueryUI('ui.touch');
        }

        if (!MotoPressSlider::IS_DEBUG) {
            $this->addJS($vendor . 'canjs/can.custom.min.js');
        } else {
            $this->addJS($vendor . 'canjs/can.custom.js');
        }

        // Add MotoPress core scripts
        $this->addJS($js . 'functions.js');

        // Add main scripts
        $this->addJS($js . 'slides.js');

        parent::initFooter();
    }
}
