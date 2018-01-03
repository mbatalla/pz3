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

(function ($, MPSL) {
    MPSL.MediaManager = can.Control.extend(
        {
            images: {
                'status': 0, // 0 - not loaded, 1 - loaded, 2 - loading
                'list': {}
            },
            product_images: {
                'status': 0, // 0 - not loaded, 1 - loaded, 2 - loading
                'list': {}
            },

            libraries: {},

            fetch: function (images, caller, action, nonce) {
                if (images['status'] > 0) {
                    return;
                }

                images['status'] = 2;

                var data = {
                    'action': action,
                    'nonce': nonce
                };
                if (caller == 'fetchProducts') {
                    data['id'] = MPSL.Vars.page.slider_id;
                }

                $.ajax({
                    'type': 'GET',
                    'url': MPSL.Vars.ajax_url,
                    'data': data,
                    'dataType': 'JSON',

                    'success': function (response) {
                        if (response.result) {
                            $.extend(images['list'], response.list);
                            if (caller == 'fetchProducts') {
                                MPSL.Vars.cover_formats = response.formats;
                            }
                            MPSL.MediaManager.notifyLibraries(caller, response.list);
                            images['status'] = 1;
                        } else {
                            images['status'] = 0;
                        }
                    },

                    'error': function (jqXHR) {
                        images['status'] = 0;
                        var error = $.parseJSON(jqXHR.responseText);
                        console.error(error);
                    }
                }); // $.ajax()
            },

            fetchProducts: function () {
                MPSL.MediaManager.fetch(
                    MPSL.MediaManager.product_images,    // Images storage object
                    'fetchProducts',                     // Caller
                    'mpslFetchProductMedia',             // AJAX action
                    MPSL.Vars.nonces.fetch_product_media // AJAX action nonce
                );
            },

            fetchImages: function () {
                MPSL.MediaManager.fetch(
                    MPSL.MediaManager.images,    // Images storage object
                    'fetchImages',               // Caller
                    'mpslFetchMedia',            // AJAX action
                    MPSL.Vars.nonces.fetch_media // AJAX action nonce
                );
            },

            addImage: function (id, data) {
                var media = {};
                media[id] = data;

                $.extend(MPSL.MediaManager.images['list'], media);

                MPSL.MediaManager.notifyLibraries('addImage', data);
            },

            removeImage: function (id) {
                if (id in MPSL.MediaManager.images['list']) {
                    delete MPSL.MediaManager.images['list'][id];
                }

                MPSL.MediaManager.notifyLibraries('removeImage', id);
            },

            notifyLibraries: function (action, data) {
                $.each(MPSL.MediaManager.libraries, function (index, library) {
                    library.onAction(action, data);
                });
            },

            hasLibrary: function (id) {
                return (id in MPSL.MediaManager.libraries);
            },

            addLibrary: function (id, library) {
                MPSL.MediaManager.libraries[id] = library;
            }
        },
        {}
    ); // End of MPSL.MediaManager

    MPSL.MediaLibrary = can.Control.extend(
        {
            defaults: {
                'id': 'mpsl-media-library',
                'is_frame': true,
                'is_selectable': true
            },

            create: function (options) {
                // Set default values for non-existent options
                options = $.extend({}, MPSL.MediaLibrary.defaults, options);

                // Create or get the media library object
                var frame = null;
                if (!MPSL.MediaManager.hasLibrary(options.id)) {
                    // The library not created yet
                    var element = $('#' + options.id);
                    // Create new Media Library
                    frame = new MPSL.MediaLibrary(element, options);
                    MPSL.MediaManager.addLibrary(options.id, frame);
                } else {
                    frame = MPSL.MediaManager.libraries[options.id];
                }

                return frame;
            }
        },
        {
            element: null, // .mpsl-media-library
            tabs: null, // MPSL.Tabs object
            images_wrapper: null, // .mpsl-media-images, wrapper for all media items (.mpsl-media-item), controls and .mpsl-no-records block
            no_images: null, // .mpsl-media-images .mpsl-no-media-found
            products_wrapper: null, // .mpsl-media-products, wrapper for all product media items (.mpsl-media-item), controls and .mpsl-no-records block
            no_products: null, // .mpsl-media-products .mpsl-no-media-found
            controls: null, // {"wrapper": .mpsl-media-controls, "close": .button-close, "select": .button-select}
            back_button: null, // "Back To Library" button, .mpsl-back-to-library
            form: null, // Dropzone form to upload images, .mpsl-media-form

            options: null,

            selection: {
                selected: false,
                is_product: false,
                type: 'library',
                element: null,
                atts: null
            },

            is_product: false,
            library_tab_index: 0,
            skip_events: 0,

            init: function (element, options) {
                this.element = element;
                this.options = options; // Already have default options, see MPSL.MediaLibrary.create()
                this.is_product = this.element.hasClass('product-library');

                if (this.is_product) {
                    this.library_tab_index = 1; // 0 - "Product Image", 1 - "Library", see /views/templates/admin/media-library.tpl
                }

                // Hide frame
                if (this.options.is_frame) {
                    this.element.hide();
                }

                MPSL.Preloader.start(this.element);

                // Init elements
                this.tabs = new MPSL.Tabs(this.element.find('.mpsl-tabs-nav a'), {'active': this.library_tab_index});
                this.images_wrapper = this.element.find('.mpsl-media-images');
                this.no_images = this.images_wrapper.find('.mpsl-no-records');
                if (this.is_product) {
                    this.products_wrapper = this.element.find('.mpsl-media-products');
                    this.no_products = this.products_wrapper.find('.mpsl-no-records');
                }
                this.controls = {};
                this.controls['wrapper'] = this.element.find('.mpsl-media-controls');
                this.controls['close'] = this.controls['wrapper'].find('.button-close');
                this.controls['select'] = this.controls['wrapper'].find('.button-select');
                this.back_button = this.element.find('.button-back');
                this.form = this.element.find('.mpsl-media-form');

                this.initControls();
                this.initDropzone();
                this.initEventHandlers();

                // Load images
                if (this.is_product) {
                    if (MPSL.MediaManager.product_images['status'] == 0) {
                        MPSL.MediaManager.fetchProducts();
                    } else if (MPSL.MediaManager.product_images['status'] == 1) {
                        this.loadProducts();
                    } // else - it's loading, just wait for event "fetchProducts"
                }
                if (MPSL.MediaManager.images['status'] == 0) {
                    MPSL.MediaManager.fetchImages();
                } else if (MPSL.MediaManager.images['status'] == 1) {
                    this.loadImages();
                } // else - it's loading, just wait for event "fetchImages"
            },

            initControls: function () {
                // If there are no buttons to show then hide whole control panels
                if (!this.options.is_selectable && !this.options.is_frame) {
                    this.controls['wrapper'].hide();
                } else {
                    // Hide all "Close" buttons in non-frame media library
                    if (!this.options.is_frame) {
                        this.controls['close'].hide();
                    }
                    // If not selectable then hide all "Select" buttons
                    if (!this.options.is_selectable) {
                        this.controls['select'].hide();
                    }
                }
            },

            initDropzone: function () {
                var form_id = this.form.attr('id');

                var dropzone_id = '';
                var words = form_id.split(/[\W_]/);
                if (words) {
                    $.each(words, function (index, value) {
                        if (index > 0) {
                            value = MPSL.Functions.ucfirst(value);
                        }
                        dropzone_id += value;
                    });
                } else {
                    dropzone_id = form_id;
                }

//                var Dropzone = require("../../vendor/dropzone/js/dropzone.js");
                Dropzone.options[dropzone_id] = {
                    'parallelUploads': 1,
                    'paramName': 'uploadImage',
                    'maxFilesize': MPSL.Vars.settings.max_upload_size,
                    'dictDefaultMessage': MPSL.Vars.lang['media_uploader_msg'],
                    'dictFileTooBig': MPSL.Vars.lang['media_to_big'],
                    init: function() {
                        this.on('success', function (file, response) {
                            var data = JSON.parse(response);
                            MPSL.MediaManager.addImage(data.id, data);
                        });
                    }
                };
            },

            initEventHandlers: function () {
                var self = this;

                // "Back To Library" button
                this.back_button.on('click', function (event) {
                    self.tabs.selectTab(self.library_tab_index);
                });

                // "Select Image" button
                this.controls['close'].on('click', function (event) {
                    self.closeLibrary();
                });

                // "Select Image" button
                this.controls['select'].on('click', function (event) {
                    can.trigger(self.element, 'select');
                    if (self.options.is_frame) {
                        self.closeLibrary();
                    }
                });

                // Background block
                this.element.find('.mpsl-media-back').on('click', function (event) {
                    self.closeLibrary();
                });
            },

            onAction: function (action, data) {
                if (this.skip_events > 0) {
                    this.skip_events -= 1;
                } else {
                    switch (action) {
                        case 'fetchProducts': this.loadProducts(); break;
                        case 'fetchImages': this.loadImages(); break;
                        case 'addImage': this.addImage(data); break;
                        case 'removeImage': this.removeImage(data); break;
                    }
                }
            },

            loadProducts: function() {
                if (this.is_product) {
                    var alert = this.products_wrapper.find('.alert');
                    var self = this;
                    $.each(MPSL.MediaManager.product_images['list'], function (index, data) {
                        $(data['html']).insertAfter(alert);
                        self.no_products.hide();
                    });
                }
            },

            loadImages: function() {
                var self = this;
                $.each(MPSL.MediaManager.images['list'], function (index, data) {
                    self.addImage(data);
                });
                this.checkImagesCount();

                MPSL.Preloader.stop(this.element);
            },

            addImage: function (data) {
                this.images_wrapper.prepend(data['html']);
                this.no_images.hide();
            },

            removeImage: function (id) {
                var element = this.images_wrapper.find('.mpsl-media-item-' + id);
                if (element.hasClass('active')) {
                    this.deselectItem();
                }
                element.remove();
                this.checkImagesCount();
            },

            deleteImage: function (element) {
                var id = element.data('id');

                var self = this;
                $.ajax({
                    'type': 'POST',
                    'url': MPSL.Vars.ajax_url,
                    'data': {
                        'action': 'mpslDeleteMedia',
                        'nonce': MPSL.Vars.nonces.delete_media,
                        'id': id
                    },
                    'dataType': 'JSON',

                    'success': function (response) {
                        MPSL.Functions.showMessage(response.message, response.status);
                        if (response.result) {
                            self.skip_events += 1;
                            MPSL.MediaManager.removeImage(id);
                            self.removeImage(id);
                        }
                    },

                    'error': function (jqXHR) {
                        var error = $.parseJSON(jqXHR.responseText);
                        if (error.is_debug) {
                            MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                        }
                        console.error(error);
                    }
                }); // $.ajax()
            },

            checkImagesCount: function () {
                if (this.images_wrapper.find('.mpsl-media-item').length == 0) {
                    this.no_images.show();
                } else {
                    this.no_images.hide();
                }
            },

            getSelection: function () {
                return this.selection;
            },

            deselectItem: function () {
                if (this.selection.selected) {
                    // Disable "Select Image" button
                    this.controls['select'].attr('disabled', 'disabled');
                    // Remove class "active" from last selected element
                    this.selection.element.removeClass('active');
                    // Reset selection
                    this.selection.element = null;
                    this.selection.atts = null;
                    this.selection.type = 'library';
                    this.selection.is_product = false;
                    this.selection.selected = false;
                }
            },

            selectItem: function (id, type) {
                var is_product = (type == 'product');

                var element;
                if (!is_product) {
                    element = this.images_wrapper.find('.mpsl-media-item-' + id);
                } else {
                    element = this.products_wrapper.find('.mpsl-media-item-' + id);
                }

                this.deselectItem();

                var file = element.data('file');
                var url = (!is_product ? MPSL.Functions.imgUrl(file) : MPSL.Functions.productImgUrl(id));
                var width = element.data('width');
                var height = element.data('height');

                this.selection.element = element;
                this.selection.atts = {
                    id: id,
                    // TODO: Rewrite media libraries.
                    src: url, // Media library in slide.js uses "src" parameter
                    url: url, // Media library in controls.js uses "url" parameter
                    width: width,
                    height: height
                };
                this.selection.is_product = is_product;
                this.selection.type = type;
                this.selection.selected = true;

                element.addClass('active');
                this.controls['select'].removeAttr('disabled');
            },

            openProductTab: function () {
                this.tabs.selectTab(0);
            },

            openLibraryTab: function () {
                this.tabs.selectTab(this.library_tab_index);
            },

            openAppropriateTab: function () {
                if (this.selection.is_product) {
                    this.openProductTab();
                } else {
                    this.openLibraryTab();
                }
            },

            openLibrary: function () {
                can.trigger(this.element, 'open');
                this.element.show();
            },

            closeLibrary: function () {
                can.trigger(this.element, 'close');
                this.element.hide();
            },

            '.mpsl-media-delete click': function (event) {
                var element = $(event[0].parentNode);
                this.deleteImage(element);
                return false;
            },

            '.mpsl-media-item click': function (event) {
                if (this.options.is_selectable) {
                    var element = $(event[0]);
                    if( !element.hasClass('active') ) {
                        // Select new item
                        var id = parseInt(element.data('id'));
                        var type = element.data('type');
                        this.selectItem(id, type);
                    } else {
                        // Deselect the item
                        this.deselectItem();
                    }
                }

                return false;
            }
        }
    ); // End of MPSL.MediaLibrary
})(jQuery, MPSL);
