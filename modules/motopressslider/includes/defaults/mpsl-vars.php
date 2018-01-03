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

$module = mpsl_get_module();
$current_url = mpsl_shopinfo('current_url');
$admin_url = mpsl_shopinfo('admin_url');

return array(
    'Vars' => array(
        'ajax_url' => $module->ajax_url,
        'current_url' => $current_url,
        'admin_url' => $admin_url,
        'img_url' => $module->uploads_url,
        'product_img_url' => _PS_BASE_URL_ . _THEME_PROD_DIR_,
        'cover_formats' => array(),
        'settings' => array(
            'shortcode_name' => 'mpsl',
            'alt_prefix' => 'mpsl-',
            'core_path' => $module->core_url,
            'core_version' => MotoPressSlider::CORE_VERSION,
            'max_upload_size' => MPSLAttachment::getMaxSize()
        ),
        'page' => array(),
        'layer' => array(),
        'preset' => array(),
        'nonces' => array(
            'create_slider' => mpsl_create_nonce('ajax_mpslCreateSlider'),
            'update_slider' => mpsl_create_nonce('ajax_mpslUpdateSlider'),
            'duplicate_slider' => mpsl_create_nonce('ajax_mpslDuplicateSlider'),
            'delete_slider' => mpsl_create_nonce('ajax_mpslDeleteSlider'),
            'create_slide' => mpsl_create_nonce('ajax_mpslCreateSlide'),
            'update_slide' => mpsl_create_nonce('ajax_mpslUpdateSlide'),
            'duplicate_slide' => mpsl_create_nonce('ajax_mpslDuplicateSlide'),
            'delete_slide' => mpsl_create_nonce('ajax_mpslDeleteSlide'),
            'update_slides_order' => mpsl_create_nonce('ajax_mpslUpdateSlidesOrder'),
            'check_alias_exists' => mpsl_create_nonce('ajax_mpslCheckAliasExists'),
            'get_youtube_thumbnail' => mpsl_create_nonce('ajax_mpslGetYoutubeThumbnail'),
            'get_vimeo_thumbnail' => mpsl_create_nonce('ajax_mpslGetVimeoThumbnail'),
            'fetch_media' => mpsl_create_nonce('ajax_mpslFetchMedia'),
            'fetch_product_media' => mpsl_create_nonce('ajax_mpslFetchProductMedia'),
            'delete_media' => mpsl_create_nonce('ajax_mpslDeleteMedia'),
            'products_preview' => mpsl_create_nonce('ajax_mpslProductsPreview')
        ),
        'lang' => array(
            'test' => $module->l('test'),
            'empty_input_error' => $module->l('%s require non empty value.'),
            'ajax_result_not_found' => $module->l('In the AJAX response undisclosed result field.'),
            'validate_digitals_only' => $module->l('%s must content digitals only.'),
            'validate_less_min' => $module->l('%s could not be less then %d'),
            'validate_greater_max' => $module->l('%s could not be greater then %d'),
            'alias_not_valid' => $module->l('Alias not valid, use letters, underscores and hyphens only.'),
            'alias_already_exists' => $module->l('This alias already exists. Alias must be unique.'),
            'validate_date_format' => $module->l('%s invalid date format. Use datepicker.'),
            'validate_day' => $module->l('%s invalid value for day: %day.'),
            'validate_month' => $module->l('%s invalid value for month: %month.'),
            'validate_year' => $module->l('%s invalid value for year: %year must be between %min and %max.'),
            'validate_hour' => $module->l('%s invalid value for hour: %hour.'),
            'validate_minute' => $module->l('%s invalid value for minute: %minute.'),
            'delete' => $module->l('Delete'),
            'cancel' => $module->l('Cancel'),
            'choose' => $module->l('Choose'),
            'none' => $module->l('None'),
            'slider_want_delete_single' => $module->l('Do you really want to delete slider with ID %d?'),
            'slide_want_delete_single' => $module->l('Do you really want to delete slide with ID %d?'),
            'layer_want_delete_all' => $module->l('Do you really want to delete all the layers?'),
            'import_export_dialog_title' => sprintf($module->l('%s Import and Export'), $module->displayName),
            'preview_dialog_title' => $module->l('Preview slider'),
            'no_sliders_selected_to_export' => $module->l('No sliders selected to export.'),
            'style_editor_dialog_title' => $module->l('Style Editor'),
            'layer_preset_delete' => $module->l('Do you really want to delete preset "%s"?'),
            'layer_preset_rename' => $module->l('Rename preset'),
            'layer_preset_enter_name' => $module->l('Please enter name for new preset'),
            'layer_preset_not_selected' => $module->l('No preset selected'),
            'layer_preset_private_name' => $module->l('Element Style'),
            'layer_preset_default_name' => $module->l('New preset'),
            'media_want_delete_single' => $module->l('Do you really want to delete the image %s?'),
            'media_uploader_msg' => $module->l('To upload please drop images here or click and select images'),
            'media_to_big' => $module->l('Upload error: the file is too big. Max upload size is {{maxFilesize}}MiB.'),
            'more' => $module->l('More'),
            'add_to_cart' => $module->l('Add to cart'),
            'animation_modal' => $module->l('Transition Editor')
        )
    )
);
