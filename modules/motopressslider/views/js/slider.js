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
    /*
     * Objects:
     *     (MPSL.FormController - in controls.js)
     *         MPSL.SliderFormController
     *     MPSL.ProductsPreview
     */

    MPSL.SliderFormController = MPSL.FormController.extend(
        {},
        {
            option_controllers: {},
            preview_panel: null,
            preview_control: null,
            tabs_control: null,

            init: function(element, options) {
                this._super(element, options);
                var self = this;

                // Add shortcode changer
                this.changeShortcode();
                this.option_controllers['alias'].on('change', function () {
                    self.changeShortcode();
                });

                this.preview_panel = $('#mpsl-product-preview');

                // Init tabs
                this.tabs_control = new MPSL.Tabs($('#mpsl-slider-settings-tabs .tabs-navigation a'), {
                    'active': MPSL.Vars.page.tab,
                    'on_change': function (tab, panel) {
                        MPSL.CodeMirrorControl.refreshEditors(panel);
                        // Show/hide products preview
                        var id = tab.attr('id');
                        if (id == 'slider_product') {
                            self.preview_panel.show();
                        } else {
                            self.preview_panel.hide();
                        }
                    }
                });

                // Create products preview
                var posts_preview = $('#mpsl-product-preview');
                if (posts_preview.length) {
                    this.preview_control = new MPSL.ProductsPreview(posts_preview);
                }
            },

            changeShortcode: function () {
                var alias = this.option_controllers['alias'].getValue();
                var shortcode = '[' + MPSL.Vars.settings.shortcode_name + ' ' + alias + ']';
                this.option_controllers['shortcode'].setValue(shortcode);
            },

            disableEditing: function () {
                this.element.find('#update_slider, #create_slider, #edit_slides').attr('disabled', 'disabled');
            },

            enableEditing: function () {
                this.element.find('#update_slider, #create_slider, #edit_slides').removeAttr('disabled', 'disabled');
            },

            '#update_slider click': function (element) {
                this.disableEditing();

                var options_ok = this.verifyOptions();
                if (options_ok) {
                    var options = this.getGroupedOptions();

                    var data = {
                        'action': 'mpslUpdateSlider',
                        'nonce': MPSL.Vars.nonces.update_slider,
                        'options': JSON.stringify(options)
                    };
                    // Add "id" for already created slider
                    if (MPSL.Vars.page.id) {
                        data.id = MPSL.Vars.page.id;
                    }

                    var self = this;
                    $.ajax({
                        'type': 'POST',
                        'url': MPSL.Vars.ajax_url,
                        'data': data,
                        'dataType': 'JSON',

                        'success': function(response) {
                            self.enableEditing();
                            MPSL.Functions.showMessage(response.message, response.status);
                        },

                        'error': function (jqXHR) {
                            self.enableEditing();
                            var error = $.parseJSON(jqXHR.responseText);
                            if (error.is_debug) {
                                MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                            }
                            console.error(error);
                        }
                    });

                } else {
                    this.enableEditing();
                }
            },

            '#create_slider click': function (element) {
                this.disableEditing();

                var options_ok = this.verifyOptions();
                if (options_ok) {
                    var options = this.getGroupedOptions(),
                        type = this.option_controllers['type'].getValue();

                    var data = {
                        'action': 'mpslCreateSlider',
                        'nonce': MPSL.Vars.nonces.create_slider,
                        'options': JSON.stringify(options),
                        'type': type
                    };

                    var self = this;
                    $.ajax({
                        'type': 'POST',
                        'url': MPSL.Vars.ajax_url,
                        'data': data,
                        'dataType': 'JSON',

                        'success': function (response) {
                            MPSL.Functions.showMessage(response.message, response.status);
                            if (response.result) {
                                var current_url = window.location.href;
                                var new_url = MPSL.Functions.removeParamFromUrl(current_url, 'type');
                                if (type === 'custom') {
                                    new_url = MPSL.Functions.addParamsToUrl(new_url, {'view': 'slides', 'id': response.id});
                                } else {
                                    new_url = MPSL.Functions.addParamsToUrl(new_url, {'view': 'slide', 'id': response.id});
                                }
                                window.location.href = new_url;
                            } else {
                                self.enableEditing();
                            }
                        },

                        'error': function (jqXHR) {
                            self.enableEditing();
                            var error = $.parseJSON(jqXHR.responseText);
                            if (error.is_debug) {
                                MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                            }
                            console.error(error);
                        }
                    });

                } else {
                    this.enableEditing();
                }
            } // Create button click
        }
    );

    MPSL.ProductsPreview = can.Control.extend(
        {},
        {
            form_controller: null,
            table_body: null,
            counter_badge: null,

            init: function (element, options) {
                this.form_controller = $('.mpsl-slider-settings-wrapper').control();
                var products_table = $('#mpsl-product-preview-table');
                this.table_body = products_table.find('tbody');
                this.counter_badge = $('#mpsl-product-preview .panel-heading .badge');
                this.getProducts();
            },

            getProducts: function () {
                var self = this,
                    options = this.form_controller.getGroupedOptions(),
                    data = {
                        'action': 'mpslProductsPreview',
                        'nonce': MPSL.Vars.nonces.products_preview,
                        'options': JSON.stringify(options['product'])
                    };

                $.ajax({
                    'type': 'POST',
                    'url': MPSL.Vars.ajax_url,
                    'data': data,
                    'dataType': 'JSON',

                    'success': function (response) {
                        self.table_body.empty();
                        self.counter_badge.html('0');
                        if (response.result) {
                            self.table_body.append(response.html);
                            self.counter_badge.html(response.count);
                        }
                    },

                    'error': function (jqXHR) {
                        var error = $.parseJSON(jqXHR.responseText);
                        if (error.is_debug) {
                            MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                        }
                        console.error(error);
                    }
                });
            },

            '#get_products click' : function (event) {
                this.getProducts();
            }
        }
    );

    new MPSL.SliderFormController($('.mpsl-slider-settings-wrapper'));
});
