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

class MPSLPreviewController extends ModuleAdminController
{
    public function __construct()
    {
        $this->controller_type = 'moduleadmin';
        $this->page_name = 'module-motopressslider-' . Dispatcher::getInstance()->getController();
        $this->bootstrap = true;
        parent::__construct();

        $this->module->ctrls->maybeRedirect();

        $this->setTemplate('preview.tpl');
        $this->module->is_preview_page = true;
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

    public function registerJsVars()
    {
        $this->module->addJsDef('MPSL', array());
    }

    public function display()
    {
        $is_slide_preview = false;

        $preview_type = Tools::getValue('type', 'slider');
        $slider_id = Tools::getValue('slider_id', 0);
        $slide_id = Tools::getValue('slide_id', 0);
        $slider = new MPSLSlider($slider_id);
        $slider->loadItems();
        $start_slide = 1;

        if ($preview_type == 'slide') {
            $is_slide_preview = true;
            // Set preview slide
            $preview_slide = new MPSLSlide($slide_id, null, $slider_id, null, true);
            $preview_slide->loadItems();
            $preview_order = $preview_slide->getOrder();
            $slider->addItemAt($preview_slide, $preview_order);
            // Start from preview slide
            $start_slide = $preview_order;
            // Update some settings for slide preview only
            $slider->settings->updateOption('enable_timer', false);
        }

        // Update slider settings
        $slider->settings->updateOption('visible_from', '');
        $slider->settings->updateOption('visible_till', '');
        $slider->settings->updateOption('start_slide', $start_slide);

        $this->context->smarty->assign('slider', $slider);
        $this->context->smarty->assign('is_slide_preview', $is_slide_preview);

        parent::display();
    }

    public function initFooter()
    {
        $this->module->addCore();
        parent::initFooter();
    }
}
