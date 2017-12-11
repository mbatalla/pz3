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

class MPSLImporterController extends ModuleAdminController
{
    public function __construct()
    {
        $this->controller_type = 'moduleadmin';
        $this->page_name = 'module-motopressslider-' . Dispatcher::getInstance()->getController();
        $this->bootstrap = true;
        parent::__construct();

        $this->module->ctrls->maybeRedirect();

        $this->setTemplate('importer.tpl');
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
        $is_imported = false;
        $log = null;
        if (isset($_FILES['import_file']) && isset($_FILES['import_file']['tmp_name'])) {
            $import_file = $_FILES['import_file']['tmp_name'];
            $use_auth = Tools::getValue('http_auth', 'off');
            $use_auth = ($use_auth == 'on' ? true : false);
            $auth_login = ($use_auth ? Tools::getValue('http_auth_login', null) : null);
            $auth_pass = ($use_auth ? Tools::getValue('http_auth_password', null) : null);

            $importer = new MPSLImporter();
            $is_imported = $importer->import($import_file, $auth_login, $auth_pass);
            $log = $importer->getLog();
        } else {
            $log = array($this->l('No import file found.'));
        }

        $this->context->smarty->assign('is_imported', $is_imported);
        $this->context->smarty->assign('log', $log);
        $this->context->smarty->assign('is_debug', MotoPressSlider::IS_DEBUG);

        parent::display();
    }
}
