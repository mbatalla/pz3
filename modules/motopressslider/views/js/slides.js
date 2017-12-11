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
    MPSL.SlidesPageController = can.Control.extend(
        {},
        {
            slider_id : null,
            table: null,
            no_records_row: null,
            slides_counter: null,
            updating: false,

            init: function (element, options) {
                this._super(element, options);

                this.slider_id = MPSL.Vars.page.slider_id;
                this.table = this.element.find('table');
                this.no_records_row = this.table.find('tr.no-records-row');
                this.slides_counter = $('.slides-panel .badge');

                this.setSlidesSortable();

                var self = this;
                $('#page-header-desc-configuration-mpsl-add-slide').on('click', function (event) {
                    event.preventDefault();
                    self.addSlide(event.target);
                });
            },

            setSlidesSortable: function () {
                var self = this;
                this.table.sortable({
                    'items': 'tbody > tr',
                    'axis': 'y',
                    'containment': 'parent',
                    'cursor': 'move',
                    'handle': '.mpsl-slide-sort-handle',
                    'tolerance': 'pointer',
                    'helper': 'clone',
                    'start': function (e, ui) {
                        self.updating = false;
                        self.disableEditing();
                    },
                    'stop': function (e, ui) {
                        if (!self.updating) {
                            self.enableEditing();
                        }
                    },
                    'update': function (e, ui) {
                        self.updating = true;
                        self.updateSlidesOrder();
                    }
                });
            },

            disableEditing: function () {
                this.table.sortable('disable');
                this.element.find('.mpsl-delete-slide-btn, .mpsl-duplicate-slide-btn, #slide-add').attr('disabled', 'disabled');
            },

            enableEditing: function () {
                this.table.sortable('enable');
                this.element.find('.mpsl-delete-slide-btn, .mpsl-duplicate-slide-btn, #slide-add').removeAttr('disabled');
            },

            updateSlidesOrder: function () {
                var slide_rows = this.table.find('tbody > tr');
                var order = {};

                // Get new orders
                $.each(slide_rows, function (index, slide_row) {
                    order[index] = $(slide_row).attr('data-slides-id');
                });

                var data = {
                    'action': 'mpslUpdateSlidesOrder',
                    'nonce': MPSL.Vars.nonces.update_slides_order,
                    'order': order
                };

                var self = this;
                $.ajax({
                    'type': 'POST',
                    'url': MPSL.Vars.ajax_url,
                    'data': data,
                    'dataType': 'JSON',

                    'success': function (response) {
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
            },

            addSlide: function (element) {
                this.disableEditing();

                var data = {
                    'action': 'mpslCreateSlide',
                    'nonce': MPSL.Vars.nonces.create_slide,
                    'slider_id': this.slider_id
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
                            window.location.href = MPSL.Vars.current_url + '&view=slide&id=' + response.id;
                        }
                        self.enableEditing();
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
            },

            '#slide-add click': function (element) {
                this.addSlide(element);
            },

            '.mpsl-duplicate-slide-btn click': function (element) {
                this.disableEditing();

                var slide_id = element.attr('data-mpsl-slide-id');

                var data = {
                    'action': 'mpslDuplicateSlide',
                    'nonce': MPSL.Vars.nonces.duplicate_slide,
                    'id': slide_id
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
                            window.location.reload(true);
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
            },

            '.mpsl-delete-slide-btn click': function (element) {
                this.disableEditing();

                var slide_id = element.attr('data-mpsl-slide-id');
                var data = {
                    'action': 'mpslDeleteSlide',
                    'nonce': MPSL.Vars.nonces.delete_slide,
                    'id': slide_id
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
                            // Delete slide's row in table
                            element.closest('tr').remove();
                            // Hide the table (only if empty)
                            var slides_left = self.table.find('tbody > tr').length - 1;
                            self.slides_counter.html(slides_left);
                            if (slides_left == 0) {
                                self.no_records_row.show();
                            }
                        }
                        self.enableEditing();
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
            } // Delete button click
        }
    );

    new MPSL.SlidesPageController('.slides-panel');
});
