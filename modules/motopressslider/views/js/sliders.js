/*
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

jQuery(function ($) {

    var sliders_counter = $('.sliders-panel .badge');
    var sliders_table = $('#sliders-table');
    var no_records_row = sliders_table.find('tr.no-records-row');

    // Preview slider
    // ------------------------------------------------------------------

    var preview_wrapper = $('.mpsl-slider-preview'),
        preview_iframe = preview_wrapper.find('iframe'),
        preview_preloader = preview_wrapper.find('.mpsl-preloader'),
        preview_slider_id = null,
        resolution_buttons_wrapper = preview_wrapper.find('.mpsl-resolution-buttons-wrapper'),
        desktop_icon = preview_wrapper.find('.desktop');

    // Prepare basic part of preview URL (add "slider_id" later)
    var preview_url = MPSL.Vars.current_url;
    preview_url = MPSL.Functions.removeParamsFromUrl(preview_url, ['controller', 'token']);
    preview_url = MPSL.Functions.addParamsToUrl(preview_url, {
        'controller': MPSL.Vars.page.preview_controller,
        'token': MPSL.Vars.page.preview_token,
        'type': 'slider'
    });

    // Update preview_slider_id variable and open preview dialog
    sliders_table.on('click', '.mpsl-preview-slider-btn', function (event) {
        event.preventDefault();
        preview_slider_id = $(event.target).attr('data-mpsl-slider-id');
        preview_wrapper.dialog('open');
    });

    // Create preview dialog box and show preloader in the box
    preview_wrapper.dialog({
        resizable: false,
        draggable: false,
        autoOpen: false,
        modal: true,
        width: $(window).width() - 100,
        height: $(window).height() - 100,
        title: MPSL.Vars.lang['preview_dialog_title'],
        closeText: '',
        dialogClass: 'mpsl-preview-dialog',

        close: function () {
            var frame_doc = preview_iframe[0].contentDocument || preview_iframe[0].contentWindow.document;
            frame_doc.documentElement.innerHTML = '';
        },

        open: function () {
            // Show preloader
            preview_preloader.show();

            var full_preview_url = MPSL.Functions.addParamToUrl(preview_url, 'slider_id', preview_slider_id);

            preview_iframe.attr('src', full_preview_url);
            preview_iframe.width('100%');

            desktop_icon.siblings().removeClass('active');
            desktop_icon.addClass('active');
        },

        create: function () {
            resolution_buttons_wrapper.removeClass('hidden');
        }
    });

    // Hide preloader
    preview_iframe.on('load', function () {
        preview_preloader.hide();
    });

    // Init resolution sizes
    var resolution_buttons = [
        {type: "desktop", resolution: "100%"},
        {type: "tablet", resolution: "768px"},
        {type: "mobile", resolution: "480px"}
    ];
    resolution_buttons.forEach(function (item) {
        preview_wrapper.on('click', '.' + item.type, function () {
            preview_iframe.width(item.resolution);
            $(this).siblings().removeClass('active');
            $(this).addClass('active');

            // Temporary
            if (preview_iframe.length > 0) {
                var iframe_window = preview_iframe[0].contentWindow || preview_iframe[0].contentDocument;
                iframe_window.mpslResizePreview();
            }
        });
    });

    // Duplicate slider
    // ------------------------------------------------------------------

    sliders_table.on('click', '.mpsl-duplicate-slider-btn', function (event) {
        event.preventDefault();

        var self = $(this);

        // Disable duplicate button
        self.attr('disabled', 'disabled');

        var id = self.attr('data-mpsl-slider-id');

        var data = {
            'action': 'mpslDuplicateSlider',
            'nonce': MPSL.Vars.nonces.duplicate_slider,
            'id': id
        };

        $.ajax({
            'type': 'POST',
            'url': MPSL.Vars.ajax_url,
            'data': data,
            'dataType': 'JSON',

            'success': function (response) {
                MPSL.Functions.showMessage(response.message, response.status);
                if (response.result) {
                    // Reload page
                    window.location.reload(true);
                } else {
                    // Enable duplicate button
                    self.removeAttr('disabled');
                }
            },

            'error': function (jqXHR) {
                self.removeAttr('disabled'); // Enable duplicate button
                var error = $.parseJSON(jqXHR.responseText);
                if (error.is_debug) {
                    MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                }
                console.error(error);
            }
        });
    });

    // Delete slider
    // ------------------------------------------------------------------

    sliders_table.on('click', '.mpsl-delete-slider-btn', function (event) {
        event.preventDefault();

        var self = $(this);

        // Disable delete button
        self.attr('disabled', 'disabled');

        var id = self.attr('data-mpsl-slider-id');

        if (confirm(MPSL.Vars.lang['slider_want_delete_single'].replace('%d', id)) == false) {
            self.removeAttr('disabled'); // Enable delete button
            return true;
        }

        var data = {
            'action': 'mpslDeleteSlider',
            'nonce': MPSL.Vars.nonces.delete_slider,
            'id': id
        };

        $.ajax({
            'type': 'POST',
            'url': MPSL.Vars.ajax_url,
            'data': data,
            'dataType': 'JSON',

            'success': function (response) {
                MPSL.Functions.showMessage(response.message, response.status);
                if (response.result) {
                    // Delete slider's row in table
                    self.closest('tr').remove();
                    // Hide the table (only if empty)
                    var sliders_left = sliders_table.find('tbody > tr').length - 1;
                    sliders_counter.html(sliders_left);
                    if (sliders_left == 0) {
                        no_records_row.show();
                    }
                }
                 // Enable delete button
                self.removeAttr('disabled');
            },

            'error': function (jqXHR) {
                self.removeAttr('disabled'); // Enable delete button
                var error = $.parseJSON(jqXHR.responseText);
                if (error.is_debug) {
                    MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                }
                console.error(error);
            }
        });
    });

    // Slider presets ("Custom", "Product")
    // ------------------------------------------------------------------

    var slider_presets = $('#mpsl-slider-preset-wrapper');

    // Create presets dialog box
    slider_presets.dialog({
        resizable: false,
        draggable: false,
        autoOpen: false,
        modal: true,
        width: $(window).width()/4,
        height: $(window).height()/2,
        title: MPSL.Vars.lang['template_dialog_title'],
        closeText: '',
        dialogClass: 'mpsl-template-dialog',

        close: function (event, ui) {},

        open: function () {}
    });

    // Open presets dialog
    $('#slider-add, #page-header-desc-configuration-mpsl-add-slider').on('click', function (event) {
        event.preventDefault();
        slider_presets.dialog('open');
    });

    // Go to Create New Slider page
    $('#create-slider').on('click', function (event) {
        var checked_element = slider_presets.find('input[type="radio"]:checked');
        var slider_type = checked_element.data('type');

        var redirect_url = MPSL.Vars.admin_url;
        redirect_url = MPSL.Functions.addParamsToUrl(redirect_url, {
            'controller': MPSL.Vars.page.slider_controller,
            'token': MPSL.Vars.page.slider_token,
            'type': slider_type
        });

        window.location.href = redirect_url;
    });

    $('#cancel-creation').on('click', function () {
        slider_presets.dialog('close');
    });
});
