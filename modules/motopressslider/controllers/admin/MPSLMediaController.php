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

class MPSLMediaController extends ModuleAdminController
{
    public function __construct()
    {
        $this->controller_type = 'moduleadmin';
        $this->page_name = 'module-motopressslider-' . Dispatcher::getInstance()->getController();
        $this->bootstrap = true;
        parent::__construct();

        $this->module->ctrls->maybeRedirect();

        $this->setTemplate('media.tpl');
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

    public function displayAjaxMpslFetchMedia()
    {
        mpsl_fetch_media();
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
        $vars = mpsl_read('includes/defaults/mpsl-vars.php');
        $this->module->addJsDef('MPSL', $vars);
    }

    public function display()
    {
        $this->context->smarty->assign('admin_templates', $this->module->templates_dir . 'admin');
        parent::display();
    }

    public function initFooter()
    {
        $vendor = $this->module->vendor_dir;
        $js = $this->module->js_dir;

        // Add vendor scripts and styles
        $this->addJquery();
        $this->addJqueryUI('ui.tabs');
        $this->addJqueryUI('ui.button');
        $this->addCSS($vendor . 'jqueryui/ui-smoothness/jquery-ui.css');
        $this->addJS($vendor . 'dropzone/js/dropzone.js');
        $this->addCSS($vendor . 'dropzone/css/dropzone.css');

        if (!MotoPressSlider::IS_DEBUG) {
            $this->addJS($vendor . 'canjs/can.custom.min.js');
        } else {
            $this->addJS($vendor . 'canjs/can.custom.js');
        }

        // Add MotoPress core scripts
        $this->addJS($js . 'functions.js');
        $this->addJS($js . 'tabs.js');
        $this->addJS($js . 'media-library.js');

        // Add main scripts
        $this->addJS($js . 'media.js');

        parent::initFooter();
    }
}
