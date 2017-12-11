<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_ . 'tmlookbookhomepage/classes/TMLookBookHomePageBlocks.php');

class Tmlookbookhomepage extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tmlookbookhomepage';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Template Monster';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('TM Look Book Home Page');
        $this->description = $this->l('Show look book on home page');

        $this->parent_module_name = 'tmlookbook';
        $this->id_shop = $this->context->shop->id;
        $this->default_list_params = $this->getDefaultListParams();
        $this->default_form_params = $this->getDefaultFormParams();

        $this->languages = Language::getLanguages(false);

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHome') &&
            $this->registerHook('displayTopColumn') &&
            $this->createAjaxController();
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall() &&
            $this->removeAjaxContoller();
    }

    protected function getDefaultFormParams()
    {
        return array(
            'module' => $this,
            'default_form_language' => $this->context->language->id,
            'allow_employee_form_lang' => Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0),
            'identifier' => $this->identifier,
        );
    }

    protected function getDefaultListParams()
    {
        return array(
            'shopLinkType' => '',
            'simple_header' => false,
            'identifier' => 'id',
            'actions' => array(
                'edit',
                'delete'
            ),
            'show_toolbar' => false,
            'module' => $this,
        );
    }

    public function getErrors()
    {
        $this->context->controller->errors = $this->_errors;
    }

    public function getConfirmations()
    {
        $this->context->controller->confirmations = $this->_confirmations;
    }

    protected function getWarnings()
    {
        $this->context->controller->warnings = $this->warning;
    }

    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;

        if (is_array($this->languages)) {
            foreach ($this->languages as $language) {
                $tab->name[$language['id_lang']] = 'tmlookbookhomepage';
            }
        }

        $tab->class_name = 'AdminTMLookBookHomePage';
        $tab->module = $this->name;
        $tab->id_parent = -1;

        return (bool)$tab->add();
    }

    private function removeAjaxContoller()
    {
        if ($tab_id = (int)Tab::getIdFromClassName('AdminTMLookBookHomePage')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }

        return true;
    }

    public function getContent()
    {
        $content = $this->renderContent();
        $this->getErrors();
        $this->getConfirmations();
        $this->getWarnings();

        return $content;
    }

    protected function renderContent()
    {
        if ($this->checkParentModule()) {
            if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
                $this->_errors = $this->l('You cannot add/edit elements from a "All Shops" or a "Group Shop" context');
                return false;
            } elseif ($this->id_shop != Tools::getValue('id_shop')) {
                $token = Tools::getAdminTokenLite('AdminModules');
                $current_index =  AdminController::$currentIndex;
                Tools::redirectAdmin($current_index .'&configure='.$this->name .'&token='. $token . '&shopselected&id_shop='.$this->id_shop);
            } elseif (Tools::isSubmit('addhook')) {
                return $this->renderForm();
            } elseif (Tools::isSubmit('savehook')) {
                $this->saveBlock();

                return $this->renderHookTopColumnList() . $this->renderHookHomeList();
            } elseif (Tools::isSubmit('deletehookHome') || Tools::isSubmit('deletehookTopColumn')) {
                $this->deleteBlock();

                return $this->renderHookTopColumnList() . $this->renderHookHomeList();
            } elseif (Tools::isSubmit('updatehookHome') || Tools::isSubmit('updatehookTopColumn')) {
                return $this->renderForm();
            } elseif (Tools::isSubmit('statushookHome') || Tools::isSubmit('statushookTopColumn')) {
                $this->updateBlock();

                return $this->renderHookTopColumnList() . $this->renderHookHomeList();
            } else {
                return $this->renderHookTopColumnList() . $this->renderHookHomeList();
            }
        }

        return false;
    }

    protected function renderHookHomeList()
    {
        $fields_values = $this->getConfigListValues('hookhome');
        $configs = $this->getConfigList();
        $params = array(
            'table' => 'hookHome',
            'title' => $this->l('Hook Home'),
            'listTotal' => count($fields_values),
            'token' => Tools::getAdminTokenLite('AdminModules'),
            'currentIndex' => AdminController::$currentIndex . '&configure=' . $this->name . '&id_shop=' . $this->id_shop,
            'toolbar_btn' => array(
                'new' => array(
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&addhook&hook=hookhome&token=' . Tools::getAdminTokenLite('AdminModules') . '&id_shop=' . $this->id_shop,
                    'desc' => $this->l('Add new')
                )
            )
        );

        $helper = $this->createHelperListObject($params);

        return $helper->generateList($fields_values, $configs);
    }

    protected function renderHookTopColumnList()
    {
        $fields_values = $this->getConfigListValues('hooktopcolumn');
        $configs = $this->getConfigList();
        $params = array(
            'table' => 'hookTopColumn',
            'title' => $this->l('Hook Top Column'),
            'listTotal' => count($fields_values),
            'token' => Tools::getAdminTokenLite('AdminModules'),
            'currentIndex' => AdminController::$currentIndex . '&configure=' . $this->name . '&id_shop=' . $this->id_shop,
            'toolbar_btn' => array(
                'new' => array(
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&addhook&hook=hooktopcolumn&token=' . Tools::getAdminTokenLite('AdminModules') . '&id_shop=' . $this->id_shop,
                    'desc' => $this->l('Add new')
                )
            )
        );

        $helper = $this->createHelperListObject($params);

        return $helper->generateList($fields_values, $configs);
    }

    protected function getConfigList()
    {
        return array(
            'id'    => array(
                'title' => ($this->l('Page id')),
                'type'  => 'text',
                'search' => false,
                'orderby' => false,
                'class' => 'hidden'
            ),
            'image' => array(
                'title' => ($this->l('Image')),
                'search' => false,
                'orderby' => false,
                'type' => 'image'
            ),
            'name'    => array(
                'title' => ($this->l('Name')),
                'type'  => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'type'    => array(
                'title' => ($this->l('Type')),
                'type'  => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'sort_order' => array(
                'title' => $this->l('Position'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
                'class' => 'pointer dragHandle'
            ),
            'active'  => array(
                'title'  => $this->l('Status'),
                'type'   => 'bool',
                'align'  => 'center',
                'active' => 'status',
                'search' => false,
                'orderby' => false,
            ),
        );
    }

    protected function getConfigListValues($type)
    {
        if ($blocks = TMLookBookHomePageBlocks::getAllBlocks($type, $this->id_shop)) {
            return $blocks;
        }

        return array();
    }


    protected function renderForm()
    {
        $fields_values = $this->getConfigFormValues();
        $configs = $this->getConfigForm();
        $params = array(
            'token' => Tools::getAdminTokenLite('AdminModules'),
            'currentIndex' => AdminController::$currentIndex
                . '&configure=' . $this->name . '&savehook' . '&id_shop=' . $this->id_shop,
            'tpl_vars' => array(
                'fields_value' => $fields_values,
                'languages'    => $this->context->controller->getLanguages(),
                'id_language'  => $this->context->language->id,
            )
        );

        $helper = $this->createHelperFormObject($params);

        return $helper->generateForm(array($configs));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend'  => array(
                    'title' => ((int)Tools::getValue('id_tab')
                        ? $this->l('Update block')
                        : $this->l('Add block')),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'id',
                        'class' => 'hidden'
                    ),
                    array(
                        'type'    => 'switch',
                        'label'   => $this->l('Status:'),
                        'name'    => 'active',
                        'is_bool' => true,
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 9,
                        'label' => $this->l('Type:'),
                        'type' => 'select',
                        'name' => 'type',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_type' => 1,
                                    'name' => $this->l('Page'),
                                ),
                                array(
                                    'id_type' => 2,
                                    'name' => $this->l('Page inner')
                                )
                            ),
                            'id' => 'id_type',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'label' => $this->l('Page:'),
                        'type' => 'select',
                        'name' => 'id_page',
                        'options' => array(
                            'query' => $this->getAllPages(),
                            'id' => 'id_page',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'sort_order',
                        'class' => 'hidden'
                    ),
                    array(
                        'col'   => 2,
                        'type'  => 'text',
                        'name'  => 'hook',
                        'class' => 'hidden'
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                    'type'  => 'submit',
                    'name'  => 'savetab'
                ),
                'buttons' => array(
                    array(
                        'href'  => AdminController::$currentIndex.'&configure='.$this->name.'&viewpage&id_page='.Tools::getValue('id_page').'&token='.Tools::getAdminTokenLite('AdminModules').'&id_shop='.$this->id_shop,
                        'title' => $this->l('Cancle'),
                        'icon'  => 'process-icon-cancel'
                    )
                )
            )
        );
    }

    protected function getConfigFormValues()
    {
        $block = $this->createBlockObject();

        return array(
            'id' => Tools::getValue('id', $block->id),
            'id_page' => Tools::getValue('id_page', $block->id_page),
            'type' => Tools::getValue('type', $block->type),
            'hook' => Tools::getValue('hook', $block->hook_name),
            'active' => Tools::getValue('active', $block->active),
            'sort_order' => Tools::getValue('sort_order', $this->getBlockMaxSortOrder($block))
        );
    }

    protected function createBlockObject()
    {
        if ($id = Tools::getValue('id')) {
            return new TMLookBookHomePageBlocks($id);
        }

        return new TMLookBookHomePageBlocks();
    }

    protected function saveBlock()
    {
        $block = $this->createBlockObject();

        $block->active = Tools::getValue('active', $block->active);
        $block->hook_name = Tools::getValue('hook', $block->hook_name);
        $block->id_page = Tools::getValue('id_page', $block->id_page);
        $block->type = Tools::getValue('type', $block->type);
        $block->sort_order = Tools::getValue('sort_order', $this->getBlockMaxSortOrder($block));

        $block->save();
    }

    protected function deleteBlock()
    {
        $block = $this->createBlockObject();

        $block->delete();
    }

    protected function getBlockMaxSortOrder($block)
    {
        if (!$block->id) {
            $max_sort_order = $block->getMaxSortOrder(Tools::getValue('hook'));
            if (!is_numeric($max_sort_order[0]['sort_order'])) {
                $max_sort_order = 1;
            } else {
                $max_sort_order = $max_sort_order[0]['sort_order'] + 1;
            }

            return $max_sort_order;
        }

        return $block->sort_order;
    }

    protected function updateBlock()
    {
        $block = $this->createBlockObject();

        if ($block->toggleStatus()) {
            $this->_confirmations = $this->l('Block status update.');

            return true;
        }

        $this->_errors = $this->l('Can\'t update block status.');

        return false;
    }

    protected function checkParentModule()
    {
        if (!$this->checkModuleStatus($this->parent_module_name)) {
            return false;
        }

        return true;
    }

    protected function checkModuleStatus($module_name)
    {
        return $this->checkModuleInstall($module_name) && $this->checkModuleActive($module_name);
    }

    protected function checkModuleInstall($module_name)
    {
        if (!Module::isInstalled($module_name)) {
            $this->_errors[] = sprintf($this->l('Please install module "%s".'), $module_name);
            return false;
        }

        return true;
    }

    protected function checkModuleActive($module_name)
    {
        if (Module::getInstanceByName($module_name)->active == 0) {
            $this->warning[] = sprintf($this->l('Please enable module "%s"'), $module_name);
        }

        return true;
    }

    protected function createHelperListObject($params)
    {
        $helper_list = new HelperList();
        $params = array_merge($this->default_list_params, $params);

        foreach ($params as $key => $value) {
            $helper_list->$key = $value;
        }

        return $helper_list;
    }

    protected function createHelperFormObject($params)
    {
        $helper_form = new HelperForm();
        $params = array_merge($this->default_form_params, $params);

        foreach ($params as $key => $value) {
            $helper_form->$key = $value;
        }

        return $helper_form;
    }

    protected function getAllPages()
    {
        if ($this->checkParentModule()) {
            return TMLookBookCollections::getAllPages($this->id_shop);
        }

        return array();
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            Media::addJSDefL('tmmlhp_theme_url', $this->context->link->getAdminLink('AdminTMLookBookHomePage'));
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        Media::addJSDefL('tmml_page_name', 'index');
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    protected function initHook($hook)
    {
        $result = '';
        $blocks = TMLookBookHomePageBlocks::getAllBlocks($hook, $this->context->shop->id, true);

        foreach ($blocks as $block) {
            if ($block['type'] == 1) {
                $this->context->smarty->assign(array(
                    'pages' => array($block),
                    'tmlb_page_name' => $this->context->controller->php_self
                ));
                $result .= $this->display($this->_path, 'views/templates/front/lookbooks.tpl');
            } else {
                $lookbook = new Tmlookbook();
                $page = new TMLookBookCollections($block['id_page']);
                $tabs = TMLookBookTabs::getAllTabs($block['id_page'], true);
                foreach ($tabs as $key => $tab) {
                    $products = array();
                    $tabs[$key]['hotspots'] = TMLookBookHotSpots::getHotSpots($tab['id_tab']);
                    if (count($tabs[$key]['hotspots']) > 0) {
                        foreach ($tabs[$key]['hotspots'] as $hotspot_id => $hotspot) {
                            if ($hotspot['type'] == 1) {
                                $products = array_merge($products, $tabs[$key]['hotspots'][$hotspot_id]['product'] = $lookbook->getProductsById(array('0' => $hotspot['id_product'])));
                            }
                        }
                    }
                    $tabs[$key]['products'] = $products;
                }
                $this->context->smarty->assign(array(
                    'tabs' => $tabs,
                    'tm_page_name' => $page->name[$this->context->language->id]
                ));

                $result .= $this->display($this->_path, 'views/templates/front/pages_templates/'.$page->template.'.tpl');
            }
        }

        return $result;
    }

    public function hookDisplayHome()
    {
        if ($this->checkParentModule()) {
            return $this->initHook('hookHome');
        }

        return '';
    }

    public function hookDisplayTopColumn()
    {
        if ($this->checkParentModule() && $this->context->controller->php_self == 'index') {
            return $this->initHook('hookTopColumn');
        }

        return '';
    }
}
