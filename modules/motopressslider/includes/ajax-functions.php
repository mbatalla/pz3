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

/*
 * Functions:
 *     create_slider
 *     update_slider
 *     duplicate_slider
 *     delete_slider
 *     create_slide
 *     update_slide
 *     duplicate_slide
 *     delete_slide
 *     update_slides_order
 *     check_alias_exists
 *     get_youtube_thumbnail
 *     get_vimeo_thumbnail
 *     fetch_media
 *     fetch_product_media
 *     upload_media
 *     delete_media
 *     products_preview
 */

function mpsl_create_slider()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    // Get options
    $options = mpsl_get_unstripped_value('options');
    if ($options !== false) {
        $options = mpsl_json_decode_assoc($options);
    } else {
        $options = array();
    }
    if (empty($options)) {
        mpsl_ajax_error($module->l('Options are not set.'));
    }

    // Get type
    $type = Tools::getValue('type', 'custom');

    // Get title and alias
    $title = (isset($options['main']['title']) ? $options['main']['title'] : null);
    $alias = (isset($options['main']['alias']) ? $options['main']['alias'] : null);
    if (empty($title) || empty($alias)) {
        mpsl_ajax_error($module->l('Title or alias is not set.'));
    }

    // Maybe create slider
    $aliases = MPSLSlider::getAliases();
    if (!mpsl_in($alias, $aliases)) {
        // The alias is unique - can create new slider with it
        $slider = new MPSLSlider(0, null, $type);
        $slider->settings->updateOptions($options, true);
        $id = $slider->add();
        if ($id) {
            // Create template slide for product slider
            if ($type == 'product') {
                $slide_title = $module->l('Template Slide');
                $template_slide = new MPSLSlide(0, array('title' => $slide_title), $slider->id, 1);
                $id = $template_slide->add(); // For product slider we must return the ID of template slide
            }
            // Then update hooked sliders list
            mpsl_hook_slider($alias, $options['appearance']['display_on']);
            // Success operation
            mpsl_ajax_success($module->l('Created new slider.'), array('id' => $id));
        } else {
            // $slider->add() failed
            mpsl_ajax_error(Tools::stripslashes($module->l('Database error. Can\'t push new slider to database.')));
        }

    } else {
        // The alias is not unique - can't create new slider with it
        $unique_alias = MPSLSlider::uniqueAlias($alias);
        mpsl_ajax_error(
            sprintf(
                Tools::stripslashes(
                    $module->l('The alias "%1$s" already exists. Alias must be unique. For example: "%2$s".')
                ),
                $alias,
                $unique_alias
            )
        );
    }
}

function mpsl_update_slider()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    // Get the ID
    $id = (int)Tools::getValue('id', 0);
    if (!$id) {
        mpsl_ajax_error($module->l('Slider ID is not set.'));
    }

    // Get "is_preview"
    $is_preview = Tools::getValue('is_preview', 'false');
    $is_preview = ($is_preview === 'true' ? true : false);

    // Get options
    $options = mpsl_get_unstripped_value('options');
    if ($options !== false) {
        $options = mpsl_json_decode_assoc($options);
    } else {
        $options = array();
    }
    if (empty($options)) {
        mpsl_ajax_error($module->l('Options are not set.'));
    }

    // Get title and alias
    $title = (isset($options['main']['title']) ? $options['main']['title'] : null);
    $alias = (isset($options['main']['alias']) ? $options['main']['alias'] : null);
    if (empty($title) || empty($alias)) {
        mpsl_ajax_error($module->l('Title or alias is not set.'));
    }

    // Create slider object
    $slider = new MPSLSlider($id);

    $old_alias = $slider->getAlias();
    $aliases = MPSLSlider::getAliases();

    // Maybe update the slider
    // if (is preview || alias was not changed || alias was changed but is unique) {
    if ($is_preview || $alias == $old_alias || !mpsl_in($alias, $aliases)) {
        $slider->setTitle($title);
        $slider->setAlias($alias);
        $slider->settings->updateOptions($options, true);
        $updated = $slider->update();
        if ($updated) {
            // Update hooked sliders list
            mpsl_hook_slider($alias, $options['appearance']['display_on']);
            // Success operation
            mpsl_ajax_success($module->l('Slider updated.'), array('id' => $slider->id));
        } else {
            mpsl_ajax_error($module->l('Failed to update slider in database.'));
        }

    } else {
        $unique_alias = MPSLSlider::uniqueAlias($alias);
        mpsl_ajax_error(
            sprintf(
                $module->l('This alias already exists. Alias must be unique, for example: "%s".'),
                $unique_alias
            )
        );
    }
}

function mpsl_duplicate_slider()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $id = (int)Tools::getValue('id', 0);
    if (!$id) {
        mpsl_ajax_error($module->l('Slider ID is not set.'));
    }

    $slider = new MPSLSlider($id);
    $slider->loadItems();
    $slider->duplicate();

    mpsl_ajax_success($module->l('Slider duplicated. Reloading table...'));
}

function mpsl_delete_slider()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $id = (int)Tools::getValue('id', 0);
    if (!$id) {
        mpsl_ajax_error($module->l('Slider ID is not set.'));
    }

    $slider = new MPSLSlider($id);
    $slider->loadItems();
    $alias = $slider->getAlias();

    // Get attached images
    // $attachemnt_ids = $slider->getAttachments();

    // Delete slider
    $slider->delete();

    // Delete attachments after
    // Attachment::deleteAttachments($attachemnt_ids);

    // Delete slider hooks
    $module->unhookSlider($alias);

    mpsl_ajax_success(sprintf($module->l('Slider %d deleted.'), $id));
}

function mpsl_create_slide()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();
    $db = $module->db;

    $slider_id = (int)Tools::getValue('slider_id', 0);
    $next_slide_no = (int)$db->lastId(MPSLDb::SLIDE_TABLE);
    $next_slide_no += 1;
    $title = sprintf($module->l('Slide %d'), $next_slide_no);

    $slide = new MPSLSlide(0, array('title' => $title), $slider_id);
    // $order = $slide->nextOrder();
    // $title = sprintf($module->l('Slide %d'), $order);
    // $slide->setOrder($order);
    // $slide->setTitle($title);
    $slide->setOrder($slide->nextOrder());
    $slide->add();

    // Maybe update the title
    if ($slide->id != $next_slide_no) {
        $title = sprintf($module->l('Slide %d'), $slide->id);
        $slide->settings->updateOption('title', $title);
        $slide->update();
    }

    mpsl_ajax_success($module->l('Slide created.'), array('id' => $slide->id));
}

function mpsl_update_slide()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    // Get slide's ID
    $slide_id = Tools::getValue('id', 0);
    if ($slide_id == 0) {
        mpsl_ajax_error($module->l('Slide ID is not set.'));
    }

    // Get other POST data
    $options = mpsl_get_unstripped_value('options', '{}');
    $options = mpsl_json_decode_assoc($options);
    $layers = mpsl_get_unstripped_value('layers', '{[]}');
    $layers = mpsl_json_decode_assoc($layers);
    $presets = mpsl_get_unstripped_value('presets', '{}');
    $presets = mpsl_json_decode_assoc($presets);
    $last_preset_id = Tools::getValue('last_preset_id', 0);
    $last_private_id = Tools::getValue('last_private_preset_id', 0);
    $is_preview = Tools::getValue('preview', false);
    $is_preview = ($is_preview === 'false' ? false : (bool)$is_preview);

    // Set custom presets and IDs
    $presets_obj = MPSLPresets::getInstance($is_preview);
    if ($last_preset_id) {
        $presets_obj->setLastPresetId($last_preset_id);
    }
    if ($last_private_id) {
        $presets_obj->setLastPrivateId($last_private_id);
    }
    $presets_obj->setCustomPresets($presets);

    $slide = new MPSLSlide($slide_id);
    if ($is_preview) {
        $slide->makePreview();
    }
    $slide->loadItems();

    $errors = array();

    // Try to update slide options
    if (!empty($options)) {
        $slide->settings->updateOptions($options, true);
        $updated = $slide->update();
        if (!$updated) {
            $errors[] = $module->l('Failed to update slide options.');
        }
    }

    // Merge layers
    $merger = new MPSLLayerMerger();
    $merged = $merger->merge($slide, $layers);
    if (!$merged) {
        $errors[] = $module->l('Failed to update the layers.');
    }

    // Update presets and private styles (after slide and layers)
    $presets_obj->setPreview($is_preview); // Update "is_previw" field after LayerMarger
    $presets_obj->update();
    $presets_obj->updatePrivateStyles();

    // If not preview then remove preview slide
    if (!$is_preview && count($errors) == 0) {
        $slide->deletePreview();
    }

    if (count($errors) == 0) {
        mpsl_ajax_success($module->l('Slide updated.'));
    } else {
        $message = implode(' ', $errors);
        mpsl_ajax_error($message);
    }
}

function mpsl_duplicate_slide()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $id = (int)Tools::getValue('id', 0);
    if (!$id) {
        mpsl_ajax_error($module->l('Slide ID is not set.'));
    }

    $slide = new MPSLSlide($id);
    $slide->loadItems();
    $slide->duplicate();

    mpsl_ajax_success($module->l('Slide duplicated. Reloading table...'));
}

function mpsl_delete_slide()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $id = (int)Tools::getValue('id', 0);
    if (!$id) {
        mpsl_ajax_error($module->l('Slide ID is not set.'));
    }

    $slide = new MPSLSlide($id);
    $slide->loadItems();
    $slide->delete();

    mpsl_ajax_success($module->l('Slide deleted.'));
}

function mpsl_update_slides_order()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();
    $db = mpsl_get_db();

    $orders = Tools::getValue('order', array());
    $orders_count = count($orders);

    for ($i = 0; $i < $orders_count; $i += 1) {
        $slide_id = $orders[$i];
        $order = $i + 1;
        $db->update(MPSLDb::SLIDE_TABLE, $slide_id, array('order' => $order));
    }

    mpsl_ajax_success($module->l('Slides reordered.'));
}

function mpsl_check_alias_exists()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $alias = Tools::getValue('alias');
    $aliases = MPSLSlider::getAliases();

    $data = array(
        'is_exist' => in_array($alias, $aliases),
        'aliases' => $aliases
    );

    if ($data['is_exist']) {
        $message = sprintf(Tools::stripslashes($module->l('Alias "%s" already exists.')), $alias);
    } else {
        $message = sprintf(Tools::stripslashes($module->l('Alias "%s" is not exist.')), $alias);
    }

    mpsl_ajax_success($message, $data);
}

function mpsl_get_youtube_thumbnail()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $id = Tools::getValue('src', null);
    if ($id) {
        $youtube_data_api = MPSLYoutubeDataApi::getInstance();
        $thumbnail = $youtube_data_api->getThumbnail($id);
        if ($thumbnail === false) {
            $thumbnail = '';
        }
        mpsl_ajax_success($module->l('Thumbnail loaded.'), array('result' => $thumbnail));
    } else {
        mpsl_ajax_error($module->l('YouTube video source not set.'));
    }
}

function mpsl_get_vimeo_thumbnail()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $id = Tools::getValue('src', null);
    if ($id) {
        $vimeo_oembed_api = MPSLVimeoOEmbedApi::getInstance();
        $thumbnail = $vimeo_oembed_api->getThumbnail($id);
        if ($thumbnail === false) {
            $thumbnail = '';
        }
        mpsl_ajax_success($module->l('Thumbnail loaded.'), array('result' => $thumbnail));
    } else {
        mpsl_ajax_error($module->l('Vimeo video source not set.'));
    }
}

function mpsl_fetch_media()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();
    $db = mpsl_get_db();
    $id = Tools::getValue('id', null);

    if (is_null($id)) {
        $ids = $db->getAttachmentIds();
    } else {
        $ids = (array)$id;
    }

    $list = array();
    foreach ($ids as $id) {
        $upload = new MPSLAttachment($id);
        $list[$id] = $upload->getInfo();
    }

    mpsl_ajax_success(
        sprintf($module->l('Loaded information about attachment(-s) %s.'), implode(', ', $ids)),
        array('list' => $list)
    );
}

function mpsl_fetch_product_media()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();
    $id = Tools::getValue('id', null);
    if (is_null($id)) {
        mpsl_ajax_error($module->l('Slider ID is not set.'));
    }

    $slider = new MPSLSlider($id);
    $product_options = $slider->settings->getGroupOptions('product');
    $products = MPSLProductQuery::queryProducts($product_options, true);

    $list = array();
    foreach ($products as $product_id => $product) {
        $data = MPSLAttachment::getCoverInfo($product);
        $list[$product_id] = $data;
    }

    $formats = array();
    foreach ($list as $data) {
        $formats[$data['id']] = $data['format'];
    }

    mpsl_ajax_success(
        $module->l('Loaded information about product images.'),
        array('list' => $list, 'formats' => $formats)
    );
}

function mpsl_upload_media()
{
    $module = mpsl_get_module();

    // Check for errors first
    if (!isset($_FILES['uploadImage'])) {
        if (isset($_FILES['file'])) {
            mpsl_ajax_fatal_message($module->l('Upload Error: wrong upload field name - file.'));
        } else {
            mpsl_ajax_fatal_message($module->l('Upload Error: the image is not set/found. Maybe it is too big.'));
        }
    }

    if ($_FILES['uploadImage']['error'] > 0) {
        mpsl_ajax_fatal_message(
            sprintf(
                mpsl_translate_php_upload_error($_FILES['uploadImage']['error']),
                $_FILES['uploadImage']['error']
            )
        );
    }

    // Check the MIME type
    if (!preg_match('/image\/.+/', $_FILES['uploadImage']['type'])) {
        mpsl_ajax_fatal_message($module->l('Upload Error: uploaded file is not an image.'));
    }

    // All seems to be OK
    $upload_errors = array();
    $upload = new MPSLAttachment();
    $upload->load($_FILES['uploadImage'], $upload_errors);
    if (empty($upload_errors)) {
        $upload_info = $upload->getInfo();
        mpsl_ajax_success($module->l('Image uploaded.'), $upload_info);
    } else {
        $message = implode(' ', $upload_errors);
        mpsl_ajax_fatal_message($message);
    }
}

function mpsl_delete_media()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $id = Tools::getValue('id', 0);

    if (!$id) {
        mpsl_ajax_error($module->l('The image is not set.'));
    }

    $attachment = new MPSLAttachment($id);
    $deleted = $attachment->delete();

    if ($deleted) {
        mpsl_ajax_success($module->l('Image deleted.'));
    } else {
        mpsl_ajax_error($module->l('Failed to delete the image.'));
    }
}

function mpsl_products_preview()
{
    mpsl_verify_ajax_nonce();

    $module = mpsl_get_module();

    $options = mpsl_get_unstripped_value('options');
    $options = mpsl_json_decode_assoc($options);

    $products = MPSLProductQuery::queryProducts($options, true);
    $count = count($products);

    $html = mpsl_fetch_smarty('admin/products-preview.tpl', array('products' => $products));

    mpsl_ajax_success(
        $module->l('Products loaded.'),
        array('products' => $products, 'count' => $count, 'html' => $html)
    );
}
