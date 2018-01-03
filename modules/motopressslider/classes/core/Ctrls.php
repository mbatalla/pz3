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

class MPSLCtrls
{
    private static $instance = null;

    private $module = null;
    private $context = null;

    public function __construct($module = null)
    {
        $this->module = $module;
        $this->context = Context::getContext();
    }

    public function getController()
    {
        return Tools::getValue('controller', 'undefined');
    }

    public function getControllerToken($controller)
    {
        return Tools::getAdminTokenLite($controller);
    }

    public function getControllerLink($controller, $args = array())
    {
        $token = $this->getControllerToken($controller);

        // Can't use $this->module->context->link->getAdminLink() - link is null
        $url = mpsl_shopinfo('admin_url');
        $url .= '?controller=' . $controller;
        $url .= '&token=' . $token;

        if (!empty($args)) {
            $url = mpsl_add_query_args($url, $args);
        }

        return $url;
    }

    public function getLink($view, $id = null, $append = '')
    {
        $view = Tools::strtolower($view);
        $controller = 'MPSL' . Tools::ucfirst($view);
        $args = array();

        if (!is_null($id)) {
            $args['id'] = $id;
        }

        $link = $this->getControllerLink($controller, $args);
        $link .= $append;
        return $link;
    }

    public function maybeRedirect()
    {
        // If "view" argument can be found in the URL then maybe we need to
        // redirect to a new page/controller
        $view = Tools::getValue('view', false);
        if ($view) {
            // "view" argument found in the URL
            $required_controller = 'MPSL' . Tools::ucfirst($view);
            $current_url = mpsl_shopinfo('current_url');
            $args = mpsl_parse_query_args($current_url);

            if (!isset($args['controller']) || $args['controller'] !== $required_controller) {
                // Redirect to required controller
                $args = mpsl_array_adiff(array('controller', 'token', 'view'), $args);
                $redirect_link = $this->getControllerLink($required_controller, $args);
                Tools::redirect($redirect_link);
            }
        }

        // If the module is inactive then redirect user to dashboard
        if (!$this->module->active) {
            Tools::redirect('index');
        }
    }

    public function isMpslController()
    {
        $controller = $this->getController();
        $is_mpsl = preg_match('/^MPSL.*/i', $controller);
        return (bool)$is_mpsl;
    }

    public function getAjaxUrl()
    {
        $ajax_url = '';
        $controller = $this->getController();
        if ($controller != 'undefined' && $this->isMpslController()) {
            $ajax_url = $this->getControllerLink($controller, array('ajax' => null));
        } else {
            $ajax_url = $this->module->module_url . 'ajax.php';
            $ajax_url = mpsl_add_query_args($ajax_url, array('ajax' => null));
        }
        return $ajax_url;
    }

    private function createTab($class, $name, $parent_id)
    {
        $default_lang = mpsl_id_lang_default();

        $tab = new Tab();
        $tab->name[$default_lang] = $name;
        $tab->class_name = $class;
        $tab->id_parent = $parent_id;
        $tab->module = $this->module->name;

        $added = $tab->add();

        if ($added) {
            // Retur new tab ID
            $tab_id = Tab::getIdFromClassName($class);
            return $tab_id;
        } else {
            return false;
        }
    }

    public function install()
    {
        $controllers = mpsl_read('includes/defaults/controllers.php');
        $created = true;

        // Convert all entities to single format
        foreach ($controllers as $class => $data) {
            if (!is_array($data)) {
                $controllers[$class] = array('label' => $data);
            }
            $controllers[$class]['subpages'] = array();
        }

        // Add subpages
        foreach ($controllers as $class => $data) {
            // If parent class defined
            if (isset($data['parent'])) {
                // Get parent class
                $parent = $data['parent'];
                // Add first page (will be the "index" page for parent menu
                // item)
                if (empty($controllers[$parent]['subpages'])) {
                    $controllers[$parent]['subpages'][$parent] = $controllers[$parent]['label'];
                }
                // Add child menu item
                $controllers[$parent]['subpages'][$class] = $data['label'];
                // Delete menu item from $controllers, the item will be created
                // as a subpage of a parent menu
                unset($controllers[$class]);
            }
        }

        // Create all menus and menu items
        foreach ($controllers as $class => $data) {
            // Add menus
            if (isset($data['parent_id'])) {
                $id = $this->createTab($class, $data['label'], $data['parent_id']);
            } else {
                $id = $this->createTab($class, $data['label'], -1);
            }
            $created = ($created && (bool)$id);
            // Add submenus
            foreach ($data['subpages'] as $subclass => $sublabel) {
                $subid = $this->createTab($subclass, $sublabel, $id);
                $created = ($created && (bool)$subid);
            }
        }

        return $created;
    }

    public function uninstall()
    {
        if (is_null($this->module)) {
            $this->module = mpsl_get_module();
        }

        // Uninstall Tabs
        $module_tabs = Tab::getCollectionFromModule($this->module->name);
        if (!empty($module_tabs)) {
            foreach ($module_tabs as $module_tab) {
                $module_tab->delete();
            }
        }

        return true;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
