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

class MotoPressSlider extends Module
{
    const CORE_VERSION = '1.3.3';
    const CANJS_VERSION = '2.3.22';
    const CODEMIRROR_VERSION = '3.12';
    const SPECTRUM_VERSION = '1.7.1';
    const IS_DEBUG = _PS_MODE_DEV_;
    const PREFIX = 'mpsl_';
    const SECONDS_IN_DAY = 86400;
    const FULLSIZE = 'mpsl_fullsize'; // See also MPSL.FULLSIZE in functions.js

    // Main vars
    /** @var MPSLDb Main database object. */
    private $db = null;
    /** @var MPSLCtrls Controllers manager. */
    private $ctrls = null;
    /** @var array Shop information. (See function mpsl_shopinfo()) */
    public $shopinfo = array();
    /** @var array Data about URL. (See function mpsl_get_url_data()) */
    public $urlinfo = array();
    /** @var boolean */
    private $core_loaded = false;
    /** @var boolean */
    private $module_loaded = false;
    /** @var boolean */
    private $vars_loaded = false;
    /** @var array */
    private $display_hooks = array();
    /** @var array Variables that must be added to JavaScript. */
    private $js_def = array();
    /** @var MPSLDoubleArray Hook to alias "double" array. */
    private $hooked_sliders = null;
    /** @var string */
    private $current_hook = 'none';
    /** @var boolean */
    private $is_mobile = false;
    /** @var boolean */
    private $is_16 = true;
    /** @var array */
    private $footer_scripts = array();

    // Controller data
    private $controller = 'undefined';
    private $is_mpsl_controller = false;

    // Other vars
    private $base_url = ''; // "http://your-site/"
    private $module_dir = ''; // ".../modules/motopressslider/"
    private $module_url = ''; // "http://your-site/modules/motopressslider/"
    private $ajax_url = ''; // "http://your-site/modules/motopressslider/ajax.php"
    private $css_dir = ''; // ".../motopressslider/views/css/"
    private $js_dir = ''; // ".../motopressslider/views/js/"
    private $js_subdir = ''; // "/modules/motopressslider/views/js/" - for footer scripts
    private $core_css_dir = ''; // ".../motopressslider/vendor/motoslider_core/styles/"
    private $core_js_dir = ''; // ".../motopressslider/vendor/motoslider_core/scripts/"
    private $core_css_url = ''; // "http://.../modules/motopressslider/vendor/motoslider_core/styles/"
    private $core_url = ''; // "http://.../modules/motopressslider/vendor/motoslider_core/"
    private $vendor_dir = ''; // ".../motopressslider/vendor/"
    private $templates_dir = ''; // ".../motopressslider/views/templates/"
    private $img_dir = ''; // ".../motopressslider/views/img/"
    private $img_url = ''; // "http://your-site/modules/motopressslider/views/img/"
    private $uploads_dir = ''; // ".../img/motopressslider/"
    private $uploads_url = ''; // "http://your-site/img/motopressslider/"

    // Sharing fields
    public $is_preview_page = false;
    // Admin sharing
    public $default_presets = array();
    public $custom_presets = array();
    public $private_presets = array();
    public $gfonts_url = '';

    public function __construct()
    {
        $this->name = 'motopressslider';
        $this->tab = 'front_office_features';
        $this->version = '1.2.9';
        $this->author = 'MotoPress';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.9');
        $this->module_key = '3126a4df675dcc9bf87edec95f9bb2f4';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('MotoPress Slider');
        $this->description =
            $this->l('Responsive Slider for your shop with beautiful slideshows, layers, effects and animations.');

        $this->is_mobile = $this->context->getMobileDevice();
        $this->is_16 = version_compare(_PS_VERSION_, '1.6', '>=');

        // Create DB object
        require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/core/Db.php');
        $this->db = new MPSLDb($this, Db::getInstance(), !self::IS_DEBUG);

        // Create Ctrls object
        require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/core/Ctrls.php');
        $this->ctrls = new MPSLCtrls($this);
        $this->controller = $this->ctrls->getController();
        $this->is_mpsl_controller = $this->ctrls->isMpslController();

        // Add custom objects to Context
        $this->context->mpsl = $this;
        $this->context->mpsldb = $this->db;

        // Prepare hook names that would be used to show the slider. But module
        // uses also "displayMotoSlider", "displayBackOfficeHeader" and
        // "displayBackOfficeFooter"
        $this->display_hooks = array(
            'common' => array(
                'displayHeader' => $this->l('Header'),
                'displayTop' => $this->l('Page top'),
                'displayHome' => $this->l('Main page'),
                'displayFooterProduct' => $this->l('Product page'),
                'displayFooter' => $this->l('Footer')
            ),
            '1.6' => array(
                'displayBanner' => $this->l('Banner'),
                'displayTopColumn' => $this->l('Top column'),
                'displayHomeTabContent' => $this->l('Home tab'),
                'displayProductTab' => $this->l('Product tab')
            )
        );

        $this->module_dir = _PS_MODULE_DIR_ . $this->name . '/';
        $this->css_dir = $this->module_dir . 'views/css/';
        $this->js_dir = $this->module_dir . 'views/js/';
        $this->js_subdir = '/modules/' . $this->name . '/views/js/';
        $this->core_css_dir = $this->module_dir . 'vendor/motoslider_core/styles/';
        $this->core_js_dir = $this->module_dir . 'vendor/motoslider_core/scripts/';
        $this->templates_dir = $this->module_dir . 'views/templates/';
        $this->img_dir = $this->module_dir . 'views/img/';

        require_once(_PS_MODULE_DIR_ . 'motopressslider/includes/functions.php');
    }

    private function onDisplayHook($hook)
    {
        $this->current_hook = $hook;
        $output = '';
        $aliases = $this->getHookedSliders($hook);
        if (!empty($aliases)) {
            $this->addCore();
            foreach ($aliases as $alias) {
                $output .= motoSlider($alias);
            }
        }
        return $output;
    }

    public function hookDisplayMotoSlider($params)
    {
        $alias = '';

        // Try to get "alias" or "slider" parameter from $params
        if (isset($params['alias'])) {
            $alias = $params['alias'];
        } elseif (isset($params['slider'])) {
            $alias = $params['slider'];
        }

        // Display slider
        if (!empty($alias)) {
            $this->addCore();
            return motoSlider($alias);
        } else {
            return '';
        }
    }

    public function hookDisplayHeader()
    {
        $this->current_hook = 'displayFooter';
        $this->loadVars();

        if ($this->hasHookedSliders()) {
            $this->addCore();
        } else {
            // Because MotoPressSlider::loadModule() wasn't called yet
            include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Presets.php');
        }

        $this->prepareHeaderVars();

        $output = $this->onDisplayHook('displayHeader');
        $output .= $this->display(__FILE__, 'header.tpl');
        return $output;
    }

    public function hookDisplayTop()
    {
        return $this->onDisplayHook('displayTop');
    }

    public function hookDisplayHome()
    {
        return $this->onDisplayHook('displayHome');
    }

    public function hookDisplayFooterProduct()
    {
        return $this->onDisplayHook('displayFooterProduct');
    }

    public function hookDisplayFooter()
    {
        $this->current_hook = 'displayFooter';
        $this->prepareFooterVars();

        $id_cms = Tools::getValue('id_cms');
        $id_product = Tools::getValue('id_product');
        if ($this->controller == 'cms' && $id_cms != '') {
            $cms = $this->context->smarty->getVariable('cms');
            $cms = $cms->value;
            if (isset($cms->content) && !empty($cms->content)) {
                $cms->content = $this->doShortcode($cms->content);
            }
        }
        if ($this->controller == 'product' && $id_product != '') {
            $product = $this->context->smarty->getVariable('product');
            $product = $product->value;
            $this->product = $product;
            $product->description = $this->doShortcode($product->description);
        }

        $output = $this->onDisplayHook('displayFooter');
        $output .= $this->display(__FILE__, 'footer.tpl');
        return $output;
    }

    public function hookDisplayBanner()
    {
        return $this->onDisplayHook('displayBanner');
    }

    public function hookDisplayTopColumn()
    {
        return $this->onDisplayHook('displayTopColumn');
    }

    public function hookDisplayHomeTabContent()
    {
        return $this->onDisplayHook('displayHomeTabContent');
    }

    public function hookDisplayProductTab()
    {
        return $this->onDisplayHook('displayProductTab');
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->addStyle('back-office.css');
        if ($this->is_mpsl_controller) {
            $this->addStyle('mpsl.css');

            // Load all PHP files and prepare all module vars
            $this->loadModule();

            // Add JS and Smarty variables
            $this->prepareHeaderVars();
            $this->context->controller->registerJsVars();

            Media::addJsDef($this->js_def);

            // Display header template
            return $this->display(__FILE__, 'header.tpl');
        } else {
            return '';
        }
    }

    public function hookDisplayBackOfficeFooter()
    {
        if ($this->is_mpsl_controller) {
            $this->prepareFooterVars();
            // Display footer template
            return $this->display(__FILE__, 'footer.tpl');
        } else {
            return '';
        }
    }

    public function addCore()
    {
        if (!$this->core_loaded) {
            $this->loadModule();
            // Load styles
            $this->addStyle('theme.css');
            $this->addStyle('slide.css');
            // Load scripts
            $this->context->controller->addJquery();
            $this->addCoreScript('vendor.js');
            $this->addCoreScript('motoslider.js');
            // No need to load again
            $this->core_loaded = true;
        }
    }

    public function doShortcode($content)
    {
        // Load slider scripts and styles
        $this->addCore();

        // Get shortcodes
        $regex = '/\[mpsl\s+([-_a-zA-Z0-9]+)[^\]]*\]/';
        $matched = preg_match_all($regex, $content, $matches);

        // Fix possible errors
        if (!$matched) {
            // Get rid of tags in shortcode text. For example:
            // [mpsl <span>sampleslider</span>] will become [mpsl sampleslider]
            if (Tools::strpos($content, '[mpsl') !== false) {
                $matched2 = preg_match_all('/\[mpsl[^\]]+\]/', $content, $matches2);
                if ($matched2) {
                    // Fix shortcodes
                    foreach ($matches2[0] as $shortcode) {
                        $fixed_shortcode = preg_replace('/<[^>]+>/', '', $shortcode);
                        $content = str_replace($shortcode, $fixed_shortcode, $content);
                    }
                    // Try to search again
                    $matched = preg_match_all($regex, $content, $matches);
                }
            } // If "[mpsl" found in text
        } // If shortcodes not found

        // Do shortcode
        if ($matched) {
            foreach ($matches[1] as $no => $alias) {
                $shortcode = $matches[0][$no];
                if (Tools::strpos($content, $shortcode) !== false) {
                    $slider = motoSlider($alias);
                    $content = str_replace($shortcode, $slider, $content);
                }
            }
        } else {
            // Just remove all "[mpsl]"
            $content = preg_replace('/\[mpsl[^\]]*\]/', '', $content);
        }

        return $content;
    }

    private function prepareHeaderVars()
    {
        // Add JS variables
        $this->addJsDef('MPSLCore', array(
            'path' => $this->core_url,
            'version' => self::CORE_VERSION
        ));
        $this->addJsDef('MPSLSettings', array(
            'ajax_url' => $this->ajax_url,
            'uploads_url' => $this->uploads_url,
            'previews_url' => $this->uploads_url . 'previews/'
        ));

        // Assign Smarty vars
        $this->context->smarty->assign('ajax_url', $this->ajax_url);
        $this->context->smarty->assign('is_debug', self::IS_DEBUG);
        $this->context->smarty->assign('is_mobile', $this->is_mobile);
        $this->context->smarty->assign('templates_dir', $this->templates_dir);
        $this->context->smarty->assign('preset_styles', MPSLPresets::getAllCss());
        $this->context->smarty->assign('mpsl_core_css', $this->core_css_url . 'motoslider.css');

        // Add shortcodes list for CMS TinyMCE plugin
        // if ($this->controller == 'AdminCmsContent') {
        //     $this->smarty->assign('MPSLShortcodes', $this->db->getShortcodes());
        // }
    }

    private function prepareFooterVars()
    {
        $this->smarty->assign('footer_scripts', $this->footer_scripts);
        $this->smarty->assign('g_fonts_url', $this->gfonts_url);
        $this->smarty->assign('default_presets', $this->default_presets);
        $this->smarty->assign('custom_presets', $this->custom_presets);
        $this->smarty->assign('private_presets', $this->private_presets);
    }

    public function addStyle($file)
    {
        $path = $this->css_dir . $file;
        $this->context->controller->addCSS($path);
    }

    public function addScript($file)
    {
        $path = $this->js_dir . $file;
        $this->context->controller->addJS($path);
    }

    public function addCoreStyle($file)
    {
        $path = $this->core_css_dir . $file;
        $this->context->controller->addCSS($path);
    }

    public function addCoreScript($file)
    {
        $path = $this->core_js_dir . $file;
        $this->context->controller->addJS($path);
    }

    public function addFooterScript($name)
    {
        $path = $this->js_subdir . $name;
        $this->footer_scripts[] = $path;
    }

    public function addJsDef($name, $value)
    {
        $this->js_def[$name] = $value;
    }

    public function hookSlider($hook, $alias)
    {
        $this->hooked_sliders->add($hook, $alias);
    }

    public function unhookSlider($alias)
    {
        $this->hooked_sliders->removeReverse($alias);
    }

    public function hasHookedSliders()
    {
        return $this->hooked_sliders->hasItems();
    }

    public function getSliderHooks($alias)
    {
        $hooks = $this->hooked_sliders->getReverse($alias);
        $display_hooks = $this->getDisplayHooks();
        foreach (array_keys($hooks) as $name) {
            $label = $display_hooks[$name];
            $hooks[$name] = $label;
        }
        return $hooks;
    }

    public function getHookedSliders($hook)
    {
        return $this->hooked_sliders->getDirect($hook);
    }

    public function updateHookedSliders()
    {
        mpsl_update_option('mpsl_hook_to_alias', $this->hooked_sliders->getDirectArray());
        mpsl_update_option('mpsl_alias_to_hook', $this->hooked_sliders->getReverseArray());
    }

    public function getDisplayHooks()
    {
        if ($this->is_16) {
            $hooks = array_merge($this->display_hooks['common'], $this->display_hooks['1.6']);
        } else {
            $hooks = $this->display_hooks['common'];
        }
        return $hooks;
    }

    public function loadModule()
    {
        if (!$this->module_loaded) {
            // Get entities and related files
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Attachment.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Entity.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/ChildEntity.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Layer.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/LayerMerger.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Presets.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Settings.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Slide.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Slider.php');

            // Get API classes
            include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/api/VimeoOEmbedApi.php');
            include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/api/YoutubeDataApi.php');

            // Get other classes
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/DbQuery.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/Form.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/ImageResizer.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/ProductQuery.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/Shortcode.php');
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/Smarty.php');
            if ($this->is_mpsl_controller) {
                require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/Exporter.php');
                require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/Importer.php');
            }

            // Include AJAX functions for AJAX calls
            if ($this->context->controller->ajax) {
                require_once(_PS_MODULE_DIR_ . 'motopressslider/includes/ajax-functions.php');
            }

            // Load Smarty plugins
            $this->loadSmartyPlugins();

            $this->module_loaded = true;
        }
        $this->loadVars();
    }

    private function loadVars()
    {
        if (!$this->vars_loaded) {
            // Load hooked sliders
            require_once(_PS_MODULE_DIR_ . 'motopressslider/classes/core/DoubleArray.php');
            $this->hooked_sliders = new MPSLDoubleArray();
            $hook_to_alias = mpsl_get_option('mpsl_hook_to_alias', array());
            $alias_to_hook = mpsl_get_option('mpsl_alias_to_hook', array());
            $this->hooked_sliders->mergeDirect($hook_to_alias);
            $this->hooked_sliders->mergeReverse($alias_to_hook);
            // Prepare other vars
            $this->base_url = mpsl_shopinfo('base_url');
            $this->module_url = mpsl_shopinfo('module_url');
            $this->ajax_url = $this->ctrls->getAjaxUrl();
            $this->js_subdir = mpsl_unslash_left($this->js_subdir);
            $this->js_subdir = mpsl_get_url_data('basedir') . $this->js_subdir;
            $this->js_subdir = mpsl_slash_left($this->js_subdir);
            $this->core_css_url = $this->module_url . 'vendor/motoslider_core/styles/';
            $this->core_url = $this->module_url . 'vendor/motoslider_core/';
            $this->vendor_dir = $this->module_dir . 'vendor/';
            $this->img_url = $this->module_url . 'views/img/';
            $this->uploads_dir = _PS_IMG_DIR_ . $this->name . '/';
            $this->uploads_url = $this->base_url . 'img/' . $this->name . '/';
            if (!file_exists($this->uploads_dir)) {
                // Have no rights to create module folder in _PS_IMG_DIR_
                $this->uploads_dir = $this->img_dir . 'uploads/';
                $this->uploads_url = $this->img_url . 'uploads/';
            }

            $this->vars_loaded = true;
        }
    }

    public function loadSmartyPlugins()
    {
        require_once(_PS_MODULE_DIR_ . 'motopressslider/includes/smarty_plugins/function.motoslider.php');
        require_once(_PS_MODULE_DIR_ . 'motopressslider/includes/smarty_plugins/function.mpsl_link.php');
        require_once(_PS_MODULE_DIR_ . 'motopressslider/includes/smarty_plugins/function.mpsl_input.php');
    }

    public function install()
    {
        include_once(_PS_MODULE_DIR_ . 'motopressslider/includes/functions.php');
        include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/core/Ctrls.php');
        include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Attachment.php');
        include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Settings.php');
        include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Presets.php');

        $installed = false;

        if (parent::install()
            && $this->registerHook('displayMotoSlider')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('displayBackOfficeFooter')
        ) {
            $installed = true;

            // Register hooks
            $hooks = array_keys($this->getDisplayHooks());
            foreach ($hooks as $hook) {
                $installed = $installed && $this->registerHook($hook);
            }

            // Add controllers
            $installed = $installed && $this->ctrls->install();

            // Create tables
            $installed = $installed && $this->createTables();

            // Register Smarty plugins
            $this->loadSmartyPlugins();
            $this->context->smarty->registerPlugin('function', 'motoslider', 'smarty_function_motoslider');
            $this->context->smarty->registerPlugin('function', 'mpsl_link', 'smarty_function_mpsl_link');
            $this->context->smarty->registerPlugin('function', 'mpsl_input', 'smarty_function_mpsl_input');

            // Try to create folder /img/motopressslider/
            MPSLAttachment::createCustomStorage();

            // Save timezone offset
            $timezone = Configuration::get('PS_TIMEZONE', 'Europe/London');
            $time_offset = mpsl_get_timezone_offset($timezone);
            mpsl_set_transient('mpsl_gmt_offset', $time_offset, self::SECONDS_IN_DAY);

            // Compile default presets
            MPSLPresets::getInstance()->update();
            MPSLPresets::getInstance(true)->update(); // Presets for preview
        }

        return $installed;
    }

    public function uninstall()
    {
        include_once(_PS_MODULE_DIR_ . 'motopressslider/includes/functions.php');
        include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/core/Ctrls.php');
        include_once(_PS_MODULE_DIR_ . 'motopressslider/classes/entity/Attachment.php');

        $uninstalled = false;

        if (parent::uninstall()) {
            $uninstalled = true;

            // Delete tables
            $uninstalled = $uninstalled && $this->deleteTables();

            // Delete controllers
            $this->ctrls->uninstall();

            // Delete hook options
            mpsl_delete_option('mpsl_hook_to_alias');
            mpsl_delete_option('mpsl_alias_to_hook');
            // Delete Presets options
            mpsl_delete_option('mpsl_last_preset_id');
            mpsl_delete_option('mpsl_last_private_preset_id');
            mpsl_delete_option('mpsl_custom_presets_json');
            mpsl_delete_option('mpsl_default_css');
            mpsl_delete_option('mpsl_custom_css');
            mpsl_delete_option('mpsl_preview_default_css');
            mpsl_delete_option('mpsl_preview_custom_css');
            mpsl_delete_option('mpsl_preview_private_css');
            mpsl_delete_option('mpsl_private_css');
            // Delete transients
            mpsl_delete_option('mpsl_gmt_offset');
            // Delete cached options
            mpsl_delete_option('mpsl_gfonts');
            // Delete YouTube and Vimeo thumbnail URLs
            Db::getInstance()->delete('configuration', "name LIKE '%mpsl-youtube-img-%'");
            Db::getInstance()->delete('configuration', "name LIKE '%mpsl-vimeo-img-%'");

            // Delete folder /img/motopressslider/
            MPSLAttachment::deleteCustomStorage();

            // Unregister Smarty plugins
            $this->context->smarty->unregisterPlugin('function', 'motoslider');
            $this->context->smarty->unregisterPlugin('function', 'mpsl_link');
            $this->context->smarty->unregisterPlugin('function', 'mpsl_input');
        }

        return $uninstalled;
    }

    private function createTables()
    {
        $created = true;

        // Create "mpsl_attachment" table
        $created = $created && $this->db->createTable(
            MPSLDb::ATTACHMENT_TABLE,
            array(
                MPSLDb::ATTACHMENT_ID => 'int(10) NOT NULL AUTO_INCREMENT',
                'file' => 'VARCHAR(40) NOT NULL',
                'file_name' => 'VARCHAR(128) NOT NULL',
                'file_size' => 'bigint(10) NOT NULL',
                'mime' => 'VARCHAR(128) NOT NULL'
            )
        );

        // Create "mpsl_slider" table
        $created = $created && $this->db->createTable(
            MPSLDb::SLIDER_TABLE,
            array(
                MPSLDb::SLIDER_ID => 'int(10) NOT NULL AUTO_INCREMENT',
                'title' => 'tinytext NOT NULL',
                'alias' => 'tinytext NOT NULL',
                'options' => 'text NOT NULL'
            )
        );

        // Create "mpsl_slide" table
        $created = $created && $this->db->createTable(
            MPSLDb::SLIDE_TABLE,
            array(
                MPSLDb::SLIDE_ID => 'int(10) NOT NULL AUTO_INCREMENT',
                MPSLDb::PARENT_ID => 'int(10) NOT NULL',
                'order' => 'int(11) NOT NULL',
                'options' => 'text NOT NULL'
            )
        );

        // Create "mpsl_slide_preview" table
        $created = $created && $this->db->createTable(
            MPSLDb::PREVIEW_SLIDE_TABLE,
            array(
                MPSLDb::PREVIEW_SLIDE_ID => 'int(10) NOT NULL AUTO_INCREMENT',
                MPSLDb::SLIDE_ID => 'int(10) NOT NULL',
                'id_original' => 'int(10) NOT NULL'
            )
        );

        // Create "mpsl_layer" table
        $created = $created && $this->db->createTable(
            MPSLDb::LAYER_TABLE,
            array(
                MPSLDb::LAYER_ID => 'int(10) NOT NULL AUTO_INCREMENT',
                MPSLDb::PARENT_ID => 'int(10) NOT NULL',
                'order' => 'int(11) NOT NULL',
                'options' => 'text NOT NULL'
            )
        );

        return $created;
    }

    private function deleteTables()
    {
        $dropped = $this->db->dropTables(array(
            MPSLDb::SLIDER_TABLE,
            MPSLDb::SLIDE_TABLE,
            MPSLDb::PREVIEW_SLIDE_TABLE,
            MPSLDb::LAYER_TABLE,
            MPSLDb::ATTACHMENT_TABLE
        ));
        return $dropped;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}
