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
    if (typeof MPSL === 'undefined') {
        MPSL = {};
    }

    MPSL.Core = {
        /** @var SliderManager */
        SliderManager: {}
    };

    MPSL.Scope = {
        /** @var MPSL.Workground */
        workground: {},
        /** @var MPSL.LayerControls */
        layer_controls: {},
        /** @var MPSL.SlideSettingsForm */
        slide_settings: {},
        /** @var MPSL.LayerSettingsForm */
        layer_settings_form: {},
        /** @var MPSL.SlidePageController */
        slide_page_ctrl: {},
        /** @var MPSL.StyleEditorForm */
        style_editor_form: {},
        /** @var MPSL.StyleEditorController */
        style_editor_ctrl: {},
        /** @var MPSL.LayerPresetList */
        layer_preset_list: {},
        /** @var MPSL.StyleEditorModeSwitcher */
        style_model_switcher: {},
        /** @var MPSL.LayerStylePreview */
        layer_style_preview: {}
    };

    MPSL.Workground = can.Control.extend(
        {
            defaults: {},
            width: null,
            height: null,
            halfWidth: null,
            halfHeight: null
        },
        {
            layers: [],
            current_layer: null, // MPSL.%Type%Layer
            slider_wrapper: null,
            slider_bg_wrapper: null,
            slider_container: null,
            slider_element: null,
            slide: null,
            slide_bg: null,
            add_buttons: null,
            delete_buttons: null,
            slide_api: null,

            init: function (element, options) {
                MPSL.Scope.workground = this;

                new MPSL.LayerControls('.mpsl-layers-list-child-wrapper');

                this.slider_wrapper = element.children('.mpsl-slider-wrapper');
                this.slider_container = this.slider_wrapper.children('.motoslider_wrapper');
                this.add_buttons = element.find('.mpsl-layer-control-panel .mpsl-add-layer');
                this.delete_buttons = element.find('.mpsl-layer-control-panel [class*="mpsl-delete-"]');

                this.initControls();

                // Move preset styles to right place
                var coreCssLink = $('#mpsl-core-css');
                if (coreCssLink.length) {
                    $('#mpsl-preset-styles-wrapper').insertAfter(coreCssLink);
                }

                if (this.slider_container.length) {
                    var self = this;
                    var scrollbar_width = MPSL.Functions.getScrollbarWidth();
                    this.slider_container.one('MPSliderLoad', function () {
                        // MPSLManager - from motoslider_core
                        MPSL.Core.SliderManager = MPSLManager.getSliderManager(self.slider_container[0]);

                        self.slider_bg_wrapper = self.slider_wrapper.children('.mpsl-slide-bg-wrapper');
                        self.slider_element = self.slider_container.children('.ms_wrapper');
                        self.slide = self.slider_element.find('.ms_current_slide');
                        self.slide_api = MPSL.Core.SliderManager.getSlide(self.slide[0]);
                        self.slide_bg = self.slide.children('.ms_slide_wrapper');

                        MPSL.Workground.width = self.slider_element.outerWidth();
                        MPSL.Workground.height = self.slider_element.outerHeight();
                        MPSL.Workground.halfWidth = MPSL.Workground.width/2;
                        MPSL.Workground.halfHeight = MPSL.Workground.height/2;

                        self.slider_bg_wrapper.css({
                            'min-width': MPSL.Workground.width,
                            'height': MPSL.Workground.height
                        });
                        $('.mpsl-slide-border').css('width', MPSL.Workground.width);
                        self.slider_wrapper.css('max-height', MPSL.Workground.height + scrollbar_width);
                        self.slider_bg_wrapper.append(self.slide_bg.children().clone());

                        self.initLayers();

                        self.add_buttons.removeAttr('disabled');
                        self.delete_buttons.filter('.mpsl-delete-all-layers').removeAttr('disabled');

                        MPSL.Preloader.stop();
                    });
                } else {
                    MPSL.Preloader.stop();
                }
            },

            initControls: function () {
                // "Add Image" button
                var add_image_btn = this.add_buttons.filter('[data-type="image"]');
                new MPSL.AddImageMediaLibrary(add_image_btn, {id: 'mpsl-add-image-ml'});
            },

            updateSlideBg: function () {
                this.slider_bg_wrapper.html(this.slide.children('.ms_slide_wrapper').children().clone());
            },

            // TODO: Add "Duplicate Layer" button
            // duplicateLayer: function (layer) {
            //     ...
            // }

            '.mpsl-layer-control-panel .mpsl-add-layer:not([data-type="image"]) click': function (element) {
                // See also "click" function in MPSL.AddImageMediaLibrary -
                // it handles "click" event of "Add Image" button

                MPSL.Preloader.start($('.mpsl-slider-wrapper'));

                var type = element.attr('data-type');
                var macros = element.attr('data-macros');
                var layer_api = null;
                var layer_atts = {
                    'options': {}
                };

                switch (type) {
                    case 'html':
                        var text = MPSL.Vars.layer.defaults.text;
                        // Do macros thing first
                        if (macros) {
                            text = '%' + macros + '%';
                        }
                        // Set layer atts
                        layer_atts.options = {
                            'type': 'html',
                            'text': text,
                            'html_width': MPSL.Vars.layer.defaults.html_width
                        };
                        layer_api = MPSL.HtmlLayer.create(layer_atts);
                        break;

                    case 'button':
                        var button_text = MPSL.Vars.layer.defaults.button_text;
                        var button_link = MPSL.Vars.layer.defaults.button_link;
                        // Do macros thing first
                        if (macros) {
                            switch (macros) {
                                case 'mpsl-product-url': button_text = MPSL.Vars.lang['more']; break;
                                case 'mpsl-add-to-cart-url': button_text = MPSL.Vars.lang['add_to_cart']; break;
                            }
                            button_link = '%' + macros + '%';
                        }
                        // Set layer atts
                        layer_atts.options = {
                            'button_text': button_text,
                            'button_link': button_link,
                            'button_target': MPSL.Vars.layer.defaults.button_target
                        };
                        layer_api = MPSL.ButtonLayer.create(layer_atts);
                        break;

                    case 'video':
                        layer_atts.options = {
                            'video_type': 'youtube', // 'vimeo', 'html'
                            'youtube_src': 'https://www.youtube.com/watch?v=t0jFJmTDqno', // Vimeo source: http://vimeo.com/40558553
                            'youtube_thumbnail': 'https://i.ytimg.com/vi/t0jFJmTDqno/maxresdefault.jpg',
                            'video_preview_image': '',
                            'video_youtube_hide_controls': false,
                            'video_width': 427,
                            'video_height': 240
                        };
                        layer_api = MPSL.VideoLayer.create(layer_atts);
                        break;

                    case 'image':
                        // See "click" function in MPSL.AddImageMediaLibrary
                        break;
                } // switch (type)

                this.initNewLayer(layer_api, layer_atts);
            },

            initNewLayer: function (layer_api, layer_atts) {
                var result_deferred = $.Deferred();
                var result = result_deferred.promise();

                if (layer_api) {
                    var created_deferred = $.Deferred();
                    var created = created_deferred.promise();

                    if (layer_api.getScope().layer.created) {
                        created_deferred.resolve();
                    } else {
                        $(layer_api.getDomElement()).one('MPSLLayerCreate', created_deferred.resolve);
                    }

                    var self = this;
                    $.when(created).then(function () {
                        var position = MPSL.Layer.getCentralOffset();
                        var layer = self.initLayer($(layer_api.getDomElement()));
                        var options = $.extend({}, layer_atts.options, position);
                        layer.updatePosition(position);
                        layer.extendOptions(options);
                        layer.select();
                        layer.control.select();
                        layer.control.scrollToElement();
                        self.current = layer;

                        MPSL.Preloader.stop($('.mpsl-slider-wrapper'));
                        result_deferred.resolve(layer);
                    });
                } else {
                    result_deferred.reject();
                }

                return result;
            },

            '.mpsl-layer-control-panel .mpsl-delete-layer click': function () {
                this.removeCurrentLayer();
            },

            '.mpsl-layer-control-panel .mpsl-delete-all-layers click': function () {
                if (confirm(MPSL.Vars.lang['layer_want_delete_all'])) {
                    this.removeAllLayers();
                }
            },

            removeCurrentLayer: function () {
                if (this.current_layer) {
                    MPSL.Scope.layer_controls.remove(this.current_layer.control);
                    this.layers.splice(this.current_layer.getOrder(), 1);
                    this.resetOrder();
                    this.current_layer.api.remove();
                    this.current_layer = null;
                }
                this.onUnselect();
            },

            removeAllLayers: function () {
                MPSL.Scope.layer_controls.removeAll();
                $.each(this.layers, function () {
                    this.api.remove();
                });
                this.layers = [];
                this.current_layer = null;
                this.onUnselect();
            },

            initLayers: function () {
                var self = this;
                this.slide.find('.ms_layers_wrapper > .layers').each(function () {
                    self.initLayer($(this), false);
                });
            },

            initLayer: function (element, is_new) {
                var layer_api = MPSL.Core.SliderManager.getLayer(element[0]),
                    type = layer_api.getType(),
                    order = layer_api.getOrder(),
                    layer = null;

                var options = {
                    'order': order
                };

                if ( typeof type !== 'undefined' && type) {
                    switch (type) {
                        case 'html':
                            options.type = type;
                            layer = new MPSL.HtmlLayer(element, options, is_new);
                            break;

                        case 'button':
                            options.type = type;
                            layer = new MPSL.ButtonLayer(element, options, is_new);
                            break;

                        case 'image':
                            options.type = type;
                            layer = new MPSL.ImageLayer(element, options, is_new);
                            break;

                        case 'video':
                            options.type = 'video';
                            options.video_type = 'html';
                            layer = new MPSL.VideoLayer(element, options, is_new);
                            break;

                        case 'youtube':
                            options.type = 'video';
                            options.video_type = 'youtube';
                            layer = new MPSL.VideoLayer(element, options, is_new);
                            break;

                        case 'vimeo':
                            options.type = 'video';
                            options.video_type = 'vimeo';
                            layer = new MPSL.VideoLayer(element, options, is_new);
                            break;
                    } // switch (type)

                    layer.setWorkground(this);
                    layer.setAPI(layer_api);

                    // Add layer
                    var title = type + ' ' + order;
                    var atts = {};
                    MPSL.Scope.layer_controls.add(layer, order, title, atts);
                    this.layers.push(layer);
                }

                return layer;
            },

            setCurrentLayer: function (layer) {
                this.current_layer = layer;
            },

            getCurrentLayer: function () {
                return this.current_layer;
            },

            getLayersData: function () {
                return MPSL.Scope.layer_controls.getData();
            },

            onSelect: function (layer) {
                this.current_layer = layer;
                this.delete_buttons.filter('.mpsl-delete-layer').removeAttr('disabled');
            },

            onUnselect: function () {
                this.delete_buttons.filter('.mpsl-delete-layer').attr('disabled', 'disabled');
                this.current_layer = null;
            },

            resetOrder: function () {
                $.each(this.layers, function (order, layer) {
                    layer.setOrder(order);
                });
            },

            reorder: function () {
                // See MPSL.LayerControls.makeSortable => list_container.update
                var self = this;
                $.each(MPSL.Scope.layer_controls.controls, function (order, control) {
                    self.layers[order] = control.layer;
                    control.layer.setOrder(order);
                });
            },

            '.motoslider_wrapper > .ms_wrapper mousedown': function () {
                $.each(this.layers, function () {
                    this.unselect();
                });
                this.onUnselect();
            }
        }
    ); // End of MPSL.Workground

    MPSL.LayerControls = can.Control.extend(
        {
            defaults: {}
        },
        {
            controls: [],
            list_container: null,

            init: function (element, options) {
                MPSL.Scope.layer_controls = this;

                this.list_container = element.find('.mpsl-layers-table > tbody');
                this.makeSortable();
                new MPSL.LayerSettingsForm($('.mpsl-layer-settings-wrapper'), {'show_error_type' : 'internal'});
            },

            makeSortable: function () {
                var self = this;
                this.list_container.sortable({
                    handle: '.mpsl-sort-icon',
                    scroll: true,

                    helper: function (e, ui) {
                        ui.children().each(function () {
                            $(this).width($(this).width());
                        });
                        return ui;
                    },

                    'start': function (e, ui) {
                        var control = $(ui.item).control();
                        control.select();
                        control.layer.select();
                    },

                    'update': function () {
                        $(self.list_container.children('tr').get().reverse()).each(function (order, el) {
                            self.controls[order] = $(el).control();
                            self.controls[order].setOrder(order);
                        });
                        MPSL.Scope.workground.reorder();
                    }
                });
            },

            add: function (layer, order, title, atts) {
                if (typeof atts === 'undefined') {
                    atts = {};
                }

                var tr = $('<tr>');
                var td_drag_handle = $('<td class="fixed-width-xs default-table-cell-padding"><div class="mpsl-sort-icon"></div></td>');
                var td_title = $('<td class="default-table-cell-padding">' + title + '</td>');
                tr.append(td_drag_handle, td_title);

                this.list_container.prepend(tr);

                var control = new MPSL.LayerControl(tr);

                control.on('select' , function (event, layer_controller) {
                    MPSL.Scope.layer_settings_form.reinit(layer_controller);
                });
                control.on('unselect', function () {
                    MPSL.Scope.layer_settings_form.hide();
                });

                control.setOrder(order);
                control.linkLayer(layer);
                control.linkLayerControls(this);

                layer.linkControl(control);

                this.controls.push(control);
            },

            remove: function (control) {
                this.controls.splice(control.getOrder(), 1);
                this.resetOrder();
                control.element.remove();
            },

            removeAll: function () {
                $.each(this.controls, function () {
                    this.element.remove();
                });
                this.controls = [];
            },

            resetOrder: function () {
                $.each(this.controls, function (order, control) {
                    control.setOrder(order);
                });
            },

            getData: function () {
                var data = [];
                $.each(this.controls, function (order, control) {
                    data[control.getOrder()] = control.getData();
                });
                return data;
            },

            updateLayerLabel: function (order, type, number) {
                var row = $(this.list_container.children('tr').get().reverse()[order]),
                    label_cell = row.find('.mpsl-layer-label'),
                    old_label = label_cell.text(),
                    label_array = old_label.split(' ');

                label_array[0] = type;

                if (typeof number !== 'undefined') {
                    label_array[1] = number;
                }

                var new_label = label_array.join(' ');
                label_cell.text(new_label);
            }
        }
    ); // End of MPSL.LayerControls

    // LayerControl
    MPSL.LayerControl = can.Control.extend({
        defaults: {}
    }, {
        layer: null,
        order: null,
        layersControls: {},

        init: function (element, options) {},

        destroy: function() {
            this.unselect();
            can.Control.prototype.destroy.call(this);
        },

        'click': function(el) {
            this.select();
            this.layer.select();
        },

        select: function() {
            if (!this.element.hasClass('layer-control-selected')) {
                var siblings = this.element.siblings();
                siblings.removeClass('layer-control-selected');
                this.element.addClass('layer-control-selected');
                this.dispatch('select', [this]);
            }
        },

        unselect: function() {
            if (this.element.hasClass('layer-control-selected')) {
                this.element.removeClass('layer-control-selected');
                this.dispatch('unselect', [this]);
            }
        },

        linkLayer: function(layer) {
            this.layer = layer;
        },

        setOrder: function(order) {
            this.order = order;
        },
        getOrder: function() {
            return this.order;
        },
        getData: function(){
            return this.layer.options;
        },
        setOptions: function(options){
            if (options.hasOwnProperty('type') && options.type === 'video') {
                var videoType = options.video_type === 'html' ? 'video' : options.video_type;
                this.updateLabel(videoType);
            }
            this.layer.setOptions(options);
            this.layer.render();
        },
        updateLabel: function(type){
            this.layersControls.updateLayerLabel(this.getOrder(), type);
        },

        linkLayerControls: function(layersControls) {
            this.layersControls = layersControls;
        },

        scrollToElement: function() {
            this.layersControls.element.animate({
                'scrollTop': this.layersControls.element.scrollTop() + this.element.position().top - this.layersControls.element.position().top - this.layersControls.element.height() / 2 + this.element.height() / 2
            }, 100);
        }

    });
    can.extend(MPSL.LayerControl.prototype, can.event);


    // Layer
    MPSL.Layer = can.Control.extend({
        defaults: {},
        whiteSpaceClassPrefix: MPSL.Vars.layer.white_space_class_prefix,

        create: function() {},

        // TODO: Add "Duplicate Layer" button
        // duplicate: function (original_layer, override_options) {
        //     return MPSL.Core.SliderManager.duplicateLayer(original_layer.api, override_options);
        // },

        getCentralOffset: function() {
            return {
                vert_align: MPSL.Vars.layer.defaults.vert_align,
                hor_align: MPSL.Vars.layer.defaults.hor_align,
                offset_x: MPSL.Vars.layer.defaults.offset_x,
                offset_y: MPSL.Vars.layer.defaults.offset_y
            };
        },

        prepareOptionsForApi: function(options) {
            options = $.extend(true, {}, options);

            if (!options.hasOwnProperty('classes')) options.classes = '';
            options.classes = MPSL.Vars.preset.layer_class + ' ' + options.classes;

            return options;
        }

    }, {
        //type: null,
        order: null,
        options: {},
        prevOptions: {},
        control: null,
        object: null,
        presetOwnerObject: null,
        handle: null,
        container: null,
        api: null,
        type: null,
        workground: null,
        resizableOptions: {},
        draggableOptions: {},
        droppableOptions: {},
        resizable: false,
        isNew: false,
        onChangeData: {},
        storage : {
            youtubeThumbnails : {},
            vimeoThumbnails : {}
        },
        needReinitFlag: false,
        //uid: null,

        setup: function(element, options, isNew) {
            isNew = typeof(isNew) !== 'undefined' ? isNew : true;
            this.isNew = isNew;

            this._super(element, options);

            this.options = {};
            this.order = options.order;
            if (!isNew && typeof MPSL.Vars.layer.list[this.order] !== 'undefined') {
                var layerOptions = MPSL.Vars.layer.list[this.order];
                $.extend(this.options, layerOptions, options);
                //this.uid = this.options.uid;
            } else {
                $.extend(this.options, options);
                //this.uid = MPSL.Functions.uniqid();
            }
            if (!this.options.hasOwnProperty('private_preset_class') || typeof this.options.private_preset_class === 'undefined' || !this.options.private_preset_class) {
                this.options.private_preset_class = MPSL.LayerPresetList.privateClassPrefix + MPSL.LayerPresetList.getNextPrivateId();
            }
            this.prevOptions = this.options;
            this.object = element.children();
            this.presetOwnerObject = this.object;
            this.container = element.closest('.ms_wrapper');
        },

        resetObject: function(){
            this.object = this.element.children('.ms_layer');
            this.presetOwnerObject = this.object;
        },

        init: function (element, options) {
            this.api = MPSL.Core.SliderManager.getLayer(this.element[0]);
            this.type = this.api.getType();
            this.prepare();
            this.preventEvents();
            this.initWidgets();
            this.initEvents();
            this.element.on('MPSLLayerChange', this.proxy('onLayerChange'));
        },

        reinit: function(){
            this.resetObject();
            this.api = MPSL.Core.SliderManager.getLayer(this.element[0]);
            this.type = this.api.getType();
            this.object.addClass('mpsl-layer-child-fix');
            this.reprepare();
            this.preventEvents();
            /** @todo: Maybe unbind event */
            this.object.on('MPSLLayerChange', this.proxy('onLayerChange'));
            this.needReinitFlag = false;
        },

        needReinit: function(){
            this.needReinitFlag = true;
        },

        isNeedReinit: function(){
            return this.needReinitFlag;
        },

        prepare: function() {
            /*
            this.element.css({
                'left': this.object.position().left,
                'top': this.object.position().top
            });
            */

            /* Fix positions */
            var horSide, elementPosition = {'top': this.object.position().top};
            if (this.options.hor_align === 'right') {
                horSide = 'right';
                this.setLeftAuto();
            } else {
                horSide = 'left';
            }
            elementPosition[horSide] = parseFloat(this.object.css(horSide));
            this.element.css(elementPosition);
            /* End Fix positions */

            this.addFixClasses();
        },

        addFixClasses: function() {
            this.element.addClass('mpsl-layer-fix');
            this.object.addClass('mpsl-layer-child-fix');
        },

        addCustomFixClasses: function() {},

        reprepare: function() {
            this.addFixClasses();
            this.addCustomFixClasses();
        },

        initWidgets: function() {
            this.addHelpers();
            if (this.resizable) {
                this.makeResizable();
            }
            this.makeDraggable();
        },

        addHelpers: function() {
            this.element.append($('<div>', {
                'class': 'mpsl-layer-handle',
                'tabindex': 1
            }));
            this.handle = this.element.children('.mpsl-layer-handle');
        },

        makeDraggable: function() {
            var self = this,
                align,
                startElementWidth;

            var options = {
                scroll: false,
                grid: [1, 1],

                start: function() {
                    // Fix for jQuery UI 1.11.x (extra width & height)
                    if (self.type === 'html' && self.options.html_width === '') {
                        self.element.addClass('mpsl-editor-wh-auto');
                    }

                    startElementWidth = self.element.width();
                },
                stop: function(e, ui) {
                    // Fix for jQuery UI 1.11.x (extra width & height)
                    if (self.type === 'html' && self.options.html_width === '') {
                        self.element
                            .removeClass('mpsl-editor-wh-auto')
                            .css({'width': '', 'height': ''});
                    }

                    align = self.getOffsets();
                    MPSL.Scope.layer_settings_form.setOptions(align, true);
                },
                drag: function(e, ui) {
                    // Right pos fix
                    if (self.options.hor_align === 'right') {
                        //align = self.getOffsets(ui.position.left, ui.position.top, startElementWidth);
                        //self.element.css('right', align.offset_x);

                        self.element.css('right', Math.round(MPSL.Workground.width - ui.position.left - startElementWidth));
                    }
                }
            };

            this.mergeWidgetOprions(options, this.draggableOptions);
            this.element.draggable(options);
            this.afterMakeDraggable();
        },

        makeResizable: function() {
            var self = this;
            var options = {
                handles: 'n, e, s, w, se, sw, ne, nw',
                minWidth: 1,
                minHeight: 1,
                start: function(e, ui) {
                    self.resizeStart($(this), e, ui);
                },
                resize: function() {
                    //$(this).resizable('widget').trigger('mouseup');
                    //$(this).resizable('option', 'minWidth', $(this).width());
                    //return;
                    $(this).height(self.object.outerHeight(true));
                },
                stop: function(e, ui) {
                    self.resizeStop($(this), e, ui);
                }
            };
            this.mergeWidgetOprions(options, this.resizableOptions);

            this.element.resizable(options);
            this.afterMakeResizable();
        },

        afterMakeResizable: function() {},
        afterMakeDraggable: function() {},

        resizeStart: function(element, e, ui) {
            //element.resizable('option', 'minWidth', self.object.outerHeight(true) - self.object.height() + 1);
            var minWidth = this.object.outerWidth(true) - this.object.width() + 1;
            if (this.type === 'image') {
                minWidth += this.image.outerWidth(true) - this.image.width();
            }
            element.resizable('option', 'minWidth', minWidth);
        },

        resizeStop: function(element, e, ui) {
            //var width = $(this).children().width(),
            var layerType = this.type,
                width = this.getObjectWidth(),
                //layerOptions = this.getOffsets(ui.position.left, ui.position.top, width, element.height());
                layerOptions = this.getOffsets();

            if (layerType === 'html') {
                layerOptions.html_width = width;
            } else {
                layerOptions.width = width;
            }

            MPSL.Scope.layer_settings_form.setOptions(layerOptions, true);
            this.updateSize(width);
        },

        mergeWidgetOprions: function(original, custom) {
            //$.each(this.resizableOptions, function(key, value) {
            $.each(custom, function(key, value) {
                original[key] = value;
            });
            return original;
        },

        getObjectWidth: function() {
            return this.type === 'image' ? this.image.width() : this.object.width();
        },

        'mouseenter': function(el) {
            if (!this.element.hasClass('layer-selected')) {
                this.object.addClass(MPSL.Vars.preset.layer_hover_class);
                this.presetOwnerObject.addClass(MPSL.Vars.preset.layer_hover_class);
            }
        },

        ':not(.layer-selected) mouseleave': function() {
            if (!this.element.hasClass('layer-selected')) {
                this.object.removeClass(MPSL.Vars.preset.layer_hover_class);
                this.presetOwnerObject.removeClass(MPSL.Vars.preset.layer_hover_class);
            }
        },

        'mousedown': function(el, event) {
            event.stopPropagation();

            this.object.removeClass(MPSL.Vars.preset.layer_hover_class);
            this.presetOwnerObject.removeClass(MPSL.Vars.preset.layer_hover_class);
            this.select();
            this.control.select();
            this.control.scrollToElement();
        },

        select: function() {
            if (this.workground.current_layer) this.workground.current_layer.handle.blur();
            //this.handle.focus();
            this.focusWithoutScrolling();

            if (!this.element.hasClass('layer-selected')) {
                var siblings = this.element.siblings('.layers');
                siblings.removeClass('layer-selected');
                this.element.addClass('layer-selected');
                this.workground.onSelect(this);
            }
        },

        unselect: function() {
            if (this.workground.current_layer) this.workground.current_layer.handle.blur();

            if (this.element.hasClass('layer-selected')) {
                this.element.removeClass('layer-selected');
                this.control.unselect();
            }
        },

        linkControl: function(control) {
            this.control = control;
        },

        getOrder: function() {
            return this.order;
        },
        setOrder: function(order) {
            this.order = order;
            this.element.css('z-index', 100 + order);
        },

        preventEvents: function() {
            this.object.off('click');
        },

        onLayerChange: function() {
            if (this.isNeedReinit()) {
                this.reinit();
            }

            // After render
            if ($.inArray(this.type, ['html', 'image']) > -1) {
                this.updateSize(this.onChangeData.width);
            }
            if ($.inArray(this.type, ['video', 'vimeo', 'youtube']) > -1) {
                this.updateSize(this.onChangeData.width, this.onChangeData.height);
            }

            this.updatePosition(this.onChangeData.offset);

            // Update offset options on width/height change
            if (this.onChangeData.widthChanged) {
                MPSL.Scope.layer_settings_form.setOptions(this.getOffsets(), true);
            }

            MPSL.Preloader.stop($('.mpsl-slider-wrapper'));
        },

        setAPI: function(api) {
            this.api = api;
        },

        setWorkground: function(workground) {
            this.workground = workground;
        },

        setOptions: function(options){
            options.order = this.order;
            this.prevOptions = this.options;
            this.options = options;
        },

        extendOptions: function(options){
            $.extend(this.options, options);
        },

        render: function() {
            var self = this;
            MPSL.Preloader.start($('.mpsl-slider-wrapper'));

            var offset = {},
                width = null,
                height = null,
                widthChanged = false;

            /* Is width changed */
            if (this.options.hasOwnProperty('width') && this.object.width() != parseInt(this.options.width)) {
                widthChanged = true;
            }
            if (this.options.hasOwnProperty('html_width') && this.options.html_width !== '' && this.object.width() != parseInt(this.options.html_width)) {
                widthChanged = true;
            }
            if (this.options.hasOwnProperty('video_width') && this.object.width() != parseInt(this.options.video_width)
                || this.options.hasOwnProperty('video_height') && this.object.height() != parseInt(this.options.video_height) ) {
                widthChanged = true;
            }
            /* End Is width changed */

            var classOwner = 'classes';
            if (self.options.type === 'image') {
                if ($.trim(self.options.image_link)) {
                    classOwner = 'linkClasses';
                }
            }

            var options = {}, deferreds = [];

            $.each(this.options, function(name, value) {
                switch(name) {
                    case 'vert_align': case 'hor_align': case 'offset_x': case 'offset_y':
                        offset[name] = value;
                        break;
                    // HTML
                    case 'text':
                        if (self.prevOptions.text !== value) {
                            options['content'] = value;
                        }
                        break;
                    case 'html_width': width = value; break;

                    // Button
                    case 'button_text' : options['title'] = value; break;
                    //case 'button_link' : options['link'] = value; break;
                    //case 'button_target' : options['target'] = value; break;
                    case 'button_macros':
                        options['button_macros'] = value;
                        break;
                    case 'preset' :
                        var _value = (value === 'private') ? self.options.private_preset_class : value;

                        if (options.hasOwnProperty(classOwner) && options[classOwner].length) {
                            options[classOwner] += ' ' + _value;
                        } else {
                            options[classOwner] = _value;
                        }
                        break;
                    // Image
                    case 'width':
                        width = value;
                        //options['width'] = value;
                        break;
                    //case 'height' : options['height'] = value; break;
                    /*case 'image_id' :
                        if (value !== '') {
                            var attachment = wp.media.attachment(value);
                            var attachmentUrl = attachment.get('url');
                            // TODO: Wait for response
                            //attachmentUrl = (typeof(attachmentUrl) !== 'undefined') ? attachmentUrl : '';
                            options['src'] = attachmentUrl;
                        } else {
                            options['src'] = '';
                        }
                        break;*/
                    case 'image_url':
                        options['src'] = value;
                        break;
                    case 'image_link' : options['link'] = value; break;
                    //case 'image_target' : options['target'] = value; break;

                    // Video
                    case 'video_type':
                        if (value === 'youtube'){
                            options['type'] = 'youtube';
                        }
                        if (value === 'vimeo'){
                            options['type'] = 'vimeo';
                        }
                        if (value === 'html'){
                            options['type'] = 'video';
                        }
                        if (self.type !== options['type']) {
                            self.needReinit();
                        }
                        break;
//                    case 'video_id':
//                        options['sources'] = {};
//                        if (value !== '') {
//                            var attachment = wp.media.attachment(value);
//                            var type = attachment.get('subtype');
//                            type = (typeof(type) !== 'undefined') ? type : 'mp4';
//                            var attachmentUrl = attachment.get('url');
//                            switch(type) {
//                                case 'mp4': options['sources']['src_mp4'] = attachmentUrl; break;
//                                case 'webm': options['sources']['src_webm'] = attachmentUrl; break;
//                                case 'ogg' : options['sources']['src_ogg'] = attachmentUrl; break;
//                            }
//                        }
//                        break;
                    case 'video_src_mp4' :
                        if (!options.hasOwnProperty('sources')) { options['sources'] = {};}
                        options['sources']['mp4'] = $.trim(value);
                        break;
                    case 'video_src_webm' :
                        if (!options.hasOwnProperty('sources')) { options['sources'] = {};}
                        options['sources']['webm'] = $.trim(value);
                        break;
                    case 'video_src_ogg' :
                        if (!options.hasOwnProperty('sources')) { options['sources'] = {};}
                        options['sources']['ogg'] = $.trim(value);
                        break;
                    case 'youtube_src' :
                        options['src'] = value;
                        break;
                    case 'vimeo_src' :
                        options['src'] = value;
                        break;
                    case 'video_width':
                        width = value;
                        break;
                    case 'video_height':
                        height = value;
                        break;
                    case 'video_html_hide_controls':
                        options['controls'] = !value;
                        break;
                    case 'video_youtube_hide_controls':
                        options['controls'] = !value;
                        break;
                    case 'video_preview_image':
                        if (!$.trim(value).length){
                            switch (self.options.video_type) {
                                case 'html' :
                                    options['poster'] = '';
                                    break;

                                case 'youtube' :
                                    var trimedSrc = $.trim(self.options.youtube_src);
                                    if (self.storage.youtubeThumbnails.hasOwnProperty(trimedSrc)){
                                        options['poster'] = self.storage.youtubeThumbnails[trimedSrc];
                                    } else {
                                        var youtubeThumbnailDeferred = $.Deferred();
                                        deferreds.push(youtubeThumbnailDeferred.promise());
                                        var src_youtube = MPSL.Functions.getYoutubeId(self.options.youtube_src);

                                        $.ajax({
                                            'type': 'GET',
                                            'url': MPSL.Vars.ajax_url,
                                            'cache' : true,
                                            'data': {
                                                'action': 'mpslGetYoutubeThumbnail',
                                                'nonce': MPSL.Vars.nonces.get_youtube_thumbnail,
                                                'src': src_youtube
                                            },
                                            'success': function(data) {
                                                options['poster'] = data.result;
                                                self.storage.youtubeThumbnails[trimedSrc] = data.result;
                                                youtubeThumbnailDeferred.resolve();
                                            },
                                            'error': function(jqXHR) {
                                                var error = $.parseJSON(jqXHR.responseText);
                                                youtubeThumbnailDeferred.reject(error.message);
                                                if (error.is_debug) {
                                                    MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                                                }
                                                console.error(error);
                                            },
                                            'dataType': 'JSON'
                                        });
                                    }
                                    break;

                                case 'vimeo' :
                                    var trimedSrc = $.trim(self.options.vimeo_src);
                                    if (self.storage.vimeoThumbnails.hasOwnProperty(trimedSrc)){
                                        options['poster'] = self.storage.vimeoThumbnails[trimedSrc];
                                    } else {
                                        var vimeoThumbnailDeferred = $.Deferred();
                                        deferreds.push(vimeoThumbnailDeferred.promise());
                                        var src_vimeo = MPSL.Functions.getVimeoId(self.options.vimeo_src);

                                        $.ajax({
                                            'type': 'GET',
                                            'url': MPSL.Vars.ajax_url,
                                            'data': {
                                                'action': 'mpslGetVimeoThumbnail',
                                                'nonce': MPSL.Vars.nonces.get_vimeo_thumbnail,
                                                'src': src_vimeo
                                            },
                                            'success': function(data) {
                                                options['poster'] = data.result;
                                                self.storage.vimeoThumbnails[trimedSrc] = data.result;
                                                vimeoThumbnailDeferred.resolve();
                                            },
                                            'error': function(jqXHR) {
                                                var error = $.parseJSON(jqXHR.responseText);
                                                vimeoThumbnailDeferred.reject(error.message);
                                                if (error.is_debug) {
                                                    MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                                                }
                                                console.error(error);
                                            },
                                            'dataType': 'JSON'
                                        });
                                    }
                                    break;
                            }

                        } else {
                            options['poster'] = $.trim(value);
                        }
                        break;
                    // Universal
                    case 'classes' :
                        if (options.hasOwnProperty('classes') && options['classes'].length) {
                            options['classes'] += ' ' + value;
                        } else {
                            options['classes'] = value;
                        }
                        options['classes'] = $.trim(options['classes']);
                        break;
                    case 'image_link_classes' :
                        if (options.hasOwnProperty('linkClasses') && options['linkClasses'].length) {
                            options['linkClasses'] += ' ' + value;
                        } else {
                            options['linkClasses'] = value;
                        }
                        options['linkClasses'] = $.trim(options['linkClasses']);
                        break;

                    case 'white-space':
                        if (self.prevOptions['white-space'] !== value) options['whiteSpace'] = value;
                        break;
                }
            });
            options[classOwner] = MPSL.Vars.preset.layer_class + ' ' + options[classOwner];

            this.onChangeData = {
                'widthChanged': widthChanged,
                'offset': offset,
                'width': width,
                'height': height
            };

            // Before render
            if ($.inArray(this.type, ['button'/*, 'html'*/]) > -1) {
                this.element.css({
                    'width': '',
                    'height': ''
                });
            }

            // Render
            $.when.apply($, deferreds).then(function() {
                self.api.setOptions(options);
            }, function(rejectMessage){
                MPSL.Functions.showMessage(rejectMessage, MPSL.ERROR);
            });

        },

        getOffsets: function(_left, _top, _elWidth) {
            var wgVertCenter = MPSL.Workground.halfHeight,
                wgHorCenter = MPSL.Workground.halfWidth,
                left = typeof _left === 'undefined' ? this.element.position().left : _left,
                top = typeof _top === 'undefined' ? this.element.position().top : _top,
                elWidth = typeof _elWidth === 'undefined' ? this.element.outerWidth(true) : _elWidth,
                elHeight = this.element.outerHeight(true),
                elVertCenter = elHeight / 2,
                elHorCenter = elWidth / 2,
                align = {
                    'hor_align': this.options.hor_align,
                    'vert_align': this.options.vert_align
                };

            // --- Horizontal position ---
            if (this.options.hor_align === 'left') {
                align.offset_x = left;

            } else if (this.options.hor_align === 'center') {
                if (this.type === 'html' && this.options.html_width === '' && this.options['white-space'] === 'normal') {
                    this.addZeroLeftPos();
                    elWidth = typeof _elWidth === 'undefined' ? this.element.outerWidth(true) : _elWidth;
                    elHorCenter = elWidth / 2;
                    this.removeZeroLeftPos();
                }
                align.offset_x =  left + elHorCenter - wgHorCenter;

            } else {
                align.offset_x =  MPSL.Workground.width - left - elWidth;
            }

            // --- Vertical position ---
            if (this.options.vert_align === 'top') {
                align.offset_y = top;
            } else if (this.options.vert_align === 'middle') {
                align.offset_y =  top + elVertCenter - wgVertCenter;
            } else {
                align.offset_y =  MPSL.Workground.height - top - elHeight;
            }

            align.offset_x = Math.round(align.offset_x);
            align.offset_y = Math.round(align.offset_y);

            return align;
        },

        updatePosition: function(align) {
            align.offset_x = parseInt(align.offset_x);
            align.offset_y = parseInt(align.offset_y);

            var wgVertCenter = MPSL.Workground.halfHeight,
                wgHorCenter = MPSL.Workground.halfWidth,
                elWidth = this.element.outerWidth(true),
                elHorCenter = elWidth / 2,
                position = {'left': '', 'right': ''};

            // --- Horizontal position ---
            if (align.hor_align === 'left') {
                position.left = align.offset_x;

            } else if (align.hor_align === 'center') {
                if (this.type === 'html' && this.options.html_width === '' && this.options['white-space'] === 'normal') {
                    this.addZeroLeftPos();
                    elWidth = this.element.outerWidth(true);
                    elHorCenter = elWidth / 2;
                    this.removeZeroLeftPos();
                }
                position.left = wgHorCenter - elHorCenter + align.offset_x;

            } else {
                //position.left = MPSL.Workground.width - elWidth - align.offset_x;
                position.right = align.offset_x;
            }

            // --- Vertical position ---
            if (align.hor_align === 'right') {
                this.setLeftAuto();
            } else {
                this.setLeftCustom();
            }

            this.element.css(position);

            var elHeight = this.element.outerHeight(true),
                elVertCenter = elHeight / 2;

            position = {};
            if (align.vert_align === 'top') {
                position.top = align.offset_y;
            } else if (align.vert_align === 'middle') {
                position.top = wgVertCenter - elVertCenter + align.offset_y;
            } else {
                position.top = MPSL.Workground.height - elHeight - align.offset_y;
                //position.bottom = align.offset_y;
            }

            this.element.css(position);
        },

        /*
        whiteSpaceNoWrap: function() {
            //this.object.addClass('mpsl-white-space-nowrap');
            this.object.addClass('mpsl-editor-white-space-nowrap');
        },
        whiteSpaceNormal: function() {
            //this.object.removeClass('mpsl-white-space-nowrap');
            this.object.removeClass('mpsl-editor-white-space-nowrap');
        },
        */

        addZeroLeftPos: function() {
            this.element.addClass('mpsl-editor-layer-pos');
        },
        removeZeroLeftPos: function() {
            this.element.removeClass('mpsl-editor-layer-pos');
        },

        setLeftAuto: function() {
            this.element.addClass('mpsl-pos-left-auto');
        },
        setLeftCustom: function() {
            this.element.removeClass('mpsl-pos-left-auto');
        },

        initEvents: function() {
            var self = this, keyCode, pos, horSide, horStep;

            // Keydown
            this.handle.on('keydown', function(e) {
                keyCode = parseInt(e.keyCode);

                if ($.inArray(keyCode, [46, 8]) > -1) { // delete/backspace
                    e.preventDefault();
                    self.workground.removeCurrentLayer();

                } else if ($.inArray(keyCode, [37, 38, 39, 40]) > -1) { // arrows
                    e.preventDefault();

                    if (self.options.align.hor === 'right') {
                        horSide = 'right';
                        horStep = -1;
                    } else {
                        horSide = 'left';
                        horStep = 1;
                    }
                    pos = {'top': parseInt(self.element.css('top'))};
                    pos[horSide] = parseInt(self.element.css(horSide));

                    switch (keyCode) {
                        case 37: // left
                            self.element.css(horSide, pos[horSide] - horStep);
                            break;
                        case 38: // up
                            self.element.css('top', pos.top - 1);
                            break;
                        case 39: // right
                            self.element.css(horSide, pos[horSide] + horStep);
                            break;
                        case 40: // bottom
                            self.element.css('top', pos.top + 1);
                            break;
                    }
                    MPSL.Scope.layer_settings_form.setOptions(self.getOffsets(), true);
                }
            });
        },

        focusWithoutScrolling: function() {
            var x = window.scrollX, y = window.scrollY;
            this.handle.focus();
            window.scrollTo(x, y);
            this.workground.slider_wrapper.scrollTop(0);
        }

    });


    // Html
    MPSL.HtmlLayer = MPSL.Layer.extend({
        defaults: {},

        prepareOptionsForApi: function(options){
            options = this._super(options);

            var apiOptions = {
                'position' : {},
                'classes' : ''
            };
            if (options.hasOwnProperty('type')) apiOptions.type = options.type;
            if (options.hasOwnProperty('text')) apiOptions.content = options.text;
            if (options.hasOwnProperty('classes')) apiOptions.classes = options.classes;
            if (options.hasOwnProperty('html_width')) apiOptions.width = options.html_width;

            // position
            if (options.hasOwnProperty('vertical_position')){
                if (options.vertical_position === 'top') {
                    if (options.hasOwnProperty('top'))
                        apiOptions.position.offsetTop = options.top;
                }
                if (options.vertical_position === 'bottom') {
                    if (options.hasOwnProperty('bottom'))
                        apiOptions.position.offsetBottom = options.bottom;
                }
            }
            if (options.hasOwnProperty('horizontal_position')){
                if (options.horizontal_position === 'left'){
                    if (options.hasOwnProperty('left'))
                        apiOptions.position.offsetLeft = options.left;
                }
                if (options.horizontal_position === 'right') {
                    if (options.hasOwnProperty('right'))
                        apiOptions.position.offsetRight = options.right;
                }
            }
            return apiOptions;
        },

        create: function(attrs) {
            var apiOptions = MPSL.HtmlLayer.prepareOptionsForApi(attrs.options);
            return MPSL.Core.SliderManager.createLayer('html', apiOptions);
        }

    }, {
        resizable: true,
        resizableOptions: {
            handles: 'e, w',
            grid: [1, 1]
        },
        options: {},

        setup: function(element, options, isNew) {
            this._super(element, options, isNew);
        },

        init: function (element, options) {
            this._super(element, options);
        },
        prepare: function() {
            this._super();

            if (this.options.hasOwnProperty('html_width') && this.options.html_width !== '') {
                var rect = this.object[0].getBoundingClientRect();
                this.element.css('width', Math.round(rect.width));
                this.object.css('width', '');
            }
        },

        updateSize: function(width) {
            if (width === '') {
                this.element.css({
                    'width': '',
                    'height': ''
                });

            } else if (width !== null) {
                width = parseFloat(width);
                var indents = this.object.outerWidth(true) - this.object.width();
                this.element.css({
                    'width': width + indents + 'px',
                    'height': ''
                });
            }
        },

        resizeStart: function(element, e, ui) {
            this._super(element, e, ui);

            //MPSL.Preloader.disable();

            //var width = this.getObjectWidth();
            //MPSL.Scope.layer_settings_form.setOptions({'html_width': width});

            //this.object.addClass(MPSL.Layer.whiteSpaceClassPrefix + 'normal');

            //console.log('resizeStart');

            //MPSL.Preloader.enable();
        }
    });

    // Image
    MPSL.ImageLayer = MPSL.Layer.extend({

        prepareOptionsForApi: function(options) {
            options = this._super(options);

            var apiOptions = {
                'position': {},
                'classes': ''
            };

            if (options.hasOwnProperty('classes')) apiOptions.classes = options.classes;
            if (options.hasOwnProperty('type')) apiOptions.type = options.type;
            if (options.hasOwnProperty('width')) apiOptions.width = options.width;

            if (options.hasOwnProperty('vertical_position')){
                if (options.vertical_position === 'top') {
                    if (options.hasOwnProperty('top'))
                        apiOptions.position.offsetTop = options.top;
                }
                if (options.vertical_position === 'bottom') {
                    if (options.hasOwnProperty('bottom'))
                        apiOptions.position.offsetBottom = options.bottom;
                }
            }
            if (options.hasOwnProperty('horizontal_position')){
                if (options.horizontal_position === 'left'){
                    if (options.hasOwnProperty('left'))
                        apiOptions.position.offsetLeft = options.left;
                }
                if (options.horizontal_position === 'right') {
                    if (options.hasOwnProperty('right'))
                        apiOptions.position.offsetRight = options.right;
                }
            }
            return apiOptions;
        },

        create: function(attrs) {
            var apiOptions = MPSL.ImageLayer.prepareOptionsForApi(attrs.options);
            apiOptions.src = attrs.src;
            return MPSL.Core.SliderManager.createLayer('image', apiOptions);
        }

    }, {
        image: null,
        resizable: true,
        resizableOptions: {
            aspectRatio: true,
            handles: 'se, sw, ne, nw',
            grid: [1, 1]
        },

        init: function (element, options) {
            this.image = this.object.children();
            this.presetOwnerObject = this.image;

            this._super(element, options);
        },

        prepare: function() {
            this._super();

            var rect = this.object[0].getBoundingClientRect();
            this.element.css('width', Math.round(rect.width));
            this.addCustomFixClasses();
            //this.object.css('width', '100%');
        },

        addCustomFixClasses: function() {
            this.object.addClass('mpsl-image-layer-child-fix');
        },

        updateSize: function(width) {
            if (width !== null) {
                width = parseFloat(width);
                var indents = this.object.outerWidth(true) - this.object.width();
                indents += this.image.outerWidth(true) - this.image.width();
                this.element.css('width', width + indents + 'px');

                //this.element.css('height', this.object.outerHeight(true) + 'px');
                this.element.css('height', '');
            }
            // TODO: Remove this?
            this.object.css('width', '100%');
        }
    });

        // Video
    MPSL.VideoLayer = MPSL.Layer.extend({
        defaults: {},

        prepareOptionsForApi: function(options){
            options = this._super(options);

            //@todo prepare api options
            var apiOptions = {
                'position': {},
                'classes': ''
            };

            if (options.hasOwnProperty('video_type')) {
                switch(options.video_type) {
                    case 'youtube' :
                        //@todo change api option
                        apiOptions.type = 'youtube';
                        if (options.hasOwnProperty('youtube_src')){
                            apiOptions.src = options.youtube_src;
                        }
                        if (options.hasOwnProperty('video_youtube_hide_controls')){
                            apiOptions.controls = !options.video_youtube_hide_controls;
                        }
                        if (options.hasOwnProperty('video_preview_image') && options.video_preview_length){
                            apiOptions.poster = options.video_preview_image;
                        } else if (options.hasOwnProperty('youtube_thumbnail')){
                            apiOptions.poster = options.youtube_thumbnail;
                        } else {
                            apiOptions.poster = '';
                        }
                        break;
//                    case 'vimeo' :
//                        apiOptions.type = 'vimeo';
//                        if (options.hasOwnProperty('vimeo_src')){
//                            apiOptions.src = options.vimeo_src;
//                        }
//                        if (options.hasOwnProperty('video_preview_image')){
//                            apiOptions.poster = options.video_preview_image;
//                        }
//                        break;
//                    case 'html' :
//                        break;
                }
            }
            if (options.hasOwnProperty('video_width'))
                apiOptions.width = options.video_width;
            if (options.hasOwnProperty('video_height'))
                apiOptions.height = options.video_height;

            if (options.hasOwnProperty('classes')) apiOptions.classes = options.classes;

            if (options.hasOwnProperty('vertical_position')){
                if (options.vertical_position === 'top') {
                    if (options.hasOwnProperty('top'))
                        apiOptions.position.offsetTop = options.top;
                }
                if (options.vertical_position === 'bottom') {
                    if (options.hasOwnProperty('bottom'))
                        apiOptions.position.offsetBottom = options.bottom;
                }
            }
            if (options.hasOwnProperty('horizontal_position')){
                if (options.horizontal_position === 'left'){
                    if (options.hasOwnProperty('left'))
                        apiOptions.position.offsetLeft = options.left;
                }
                if (options.horizontal_position === 'right') {
                    if (options.hasOwnProperty('right'))
                        apiOptions.position.offsetRight = options.right;
                }
            }

            return apiOptions;
        },

        create: function(attrs) {
            var apiOptions = MPSL.VideoLayer.prepareOptionsForApi(attrs.options);
            return MPSL.Core.SliderManager.createLayer(apiOptions.type, apiOptions);
        }
    },{
        resizable: true,
        resizableOptions: {
            grid: [1, 1]
        },

        prepare: function() {
            this._super();

            var rect = this.object[0].getBoundingClientRect();
            this.element.css({
                'width': Math.round(rect.width),
                'height': Math.round(rect.height)
            });
            this.addCustomFixClasses();
        },

        addCustomFixClasses: function() {
            this.object.addClass('mpsl-video-layer-child-fix');
        },
//
        resizeStop: function(element, e, ui){
            var width = this.object.width(),
                height = this.object.height(),
                layerOptions = this.getOffsets();
            layerOptions.video_width = width;
            layerOptions.video_height = height;
            MPSL.Scope.layer_settings_form.setOptions(layerOptions, true);
            this.updateSize(width, height);
        },

        resize : function(element, e, ui){
            element.children('video, iframe').width(ui.size.width).height(ui.size.height);
        },

        updateSize: function(width, height) {
            var indents;

            if (width !== null) {
                width = parseFloat(width);
                indents = this.object.outerWidth(true) - this.object.width();
                this.element.css('width', width + indents + 'px');
            }
            if (height !== null){
                height = parseFloat(height);
                indents = this.object.outerHeight(true) - this.object.height();
                this.element.css('height', height + indents + 'px');
            } else {
//                this.element.css('height', this.object.outerHeight(true) + 'px');
//                this.element.css('height', '');
            }
            this.object.css('width', '100%');
            this.object.css('height', '100%');
        }
    });

    // Button
    MPSL.ButtonLayer = MPSL.Layer.extend({
        defaults: {},

        prepareOptionsForApi: function(options) {
            options = this._super(options);

            var apiOptions = {
                'position': {},
                'classes': ''
            };

            if (options.hasOwnProperty('type'))
                apiOptions.type = options.type;
            if (options.hasOwnProperty('button_text'))
                apiOptions.title = options.button_text
            if (options.hasOwnProperty('button_link'))
                apiOptions.link = options.button_link;
            if (options.hasOwnProperty('button_target'))
                apiOptions.target = options.button_target;

            if (options.hasOwnProperty('classes')) apiOptions.classes = options.classes;

            if (options.hasOwnProperty('vertical_position')){
                if (options.vertical_position === 'top') {
                    if (options.hasOwnProperty('top'))
                        apiOptions.position.offsetTop = options.top;
                }
                if (options.vertical_position === 'bottom') {
                    if (options.hasOwnProperty('bottom'))
                        apiOptions.position.offsetBottom = options.bottom;
                }
            }
            if (options.hasOwnProperty('horizontal_position')){
                if (options.horizontal_position === 'left'){
                    if (options.hasOwnProperty('left'))
                        apiOptions.position.offsetLeft = options.left;
                }
                if (options.horizontal_position === 'right') {
                    if (options.hasOwnProperty('right'))
                        apiOptions.position.offsetRight = options.right;
                }
            }
            return apiOptions;
        },

        create: function(attrs) {
            var apiOptions = MPSL.ButtonLayer.prepareOptionsForApi(attrs.options);
            return MPSL.Core.SliderManager.createLayer('button', apiOptions);
        }

    }, {
        resizable: false,
        init: function (element, options) {
            //MPSL.Layer.prototype.init.apply(this, arguments);
            this._super(element, options);
        },

        preventEvents: function() {
            MPSL.Layer.prototype.preventEvents.apply(this, arguments);
            this.object.attr('href', 'javascript:void(0)').attr('disabled', 'disabled');
        },

        afterMakeResizable: function() {
            this.element.resizable('disable').removeClass('ui-state-disabled');
        }

    });

    MPSL.LayerSettingsForm = MPSL.FormController.extend(
        {},
        {
            option_controllers: {},
            dependency: {},
            disabled_dependency: {},
            /** @var MPSL.LayerControl */
            layer_controller: null,
            vars_group: 'layer',
            tabs: null,

            init: function(element, settings) {
                this._super(element, settings);
                MPSL.Scope.layer_settings_form = this;

                this.tabs = new MPSL.Tabs(this.element.find('.tabs-navigation > a'));

                this.hide();
            },
            hide: function(){
                this.element.addClass('mpsl-hide-options');
            },
            show: function(){
                this.element.removeClass('mpsl-hide-options');
            },

            reinit: function (layer_controller) {
                this.layer_controller = layer_controller;

                this.resetOptions();

                var options = $.extend({}, MPSL.Vars.layer.defaults, this.layer_controller.layer.options);

                var self = this;
                $.each(options, function (option_name, value) {
                    if (option_name === 'order') {
                        return;
                    }
                    if (self.option_controllers.hasOwnProperty(option_name)) {
                        self.option_controllers[option_name].setValue(value, true);
                        self.showDependencedOptions(option_name);
                    }
                });

                this.showAllDependencies();

                if (this.layer_controller.layer.isNew) {
                    options = this.getOptions();
                    this.layer_controller.layer.setOptions(options);
                    this.layer_controller.layer.isNew = false;
                }

                this.show();
            },

            resetOptions: function () {
                var self = this;
                $.each(MPSL.Vars.layer.defaults, function (option_name, value) {
                    if (self.option_controllers.hasOwnProperty(option_name)) {
                        self.option_controllers[option_name].setValue(value, true);
                    }
                });
                this.showAllDependencies();
            },

            setOptions: function (options, silent) {
                if ($.isPlainObject(options)) {
                    var self = this;
                    $.each(options, function (option_name, value) {
                        if (self.option_controllers.hasOwnProperty(option_name)) {
                            self.option_controllers[option_name].setValue(value, true);
                            self.showDependencedOptions(option_name);
                        }
                    });
                }
                if (MPSL.Functions.isNot(silent)) {
                    this.render();
                } else {
                    this.layer_controller.layer.setOptions(this.getOptions());
                }
            },

            setType: function (type) {
                this.type = type;
            },

//            change: function () {
//                console.log('form change');
////                if (this.verifyOptions()) {
//                    var options = this.getOptions();
//                    this.layer_controller.setOptions(options);
////                }
//            },

            render: function () {
                if (this.verifyOptions()) {
                    var options = this.getOptions();
                    this.layer_controller.setOptions(options);
                }
            }
        }
    ); // End of MPSL.LayerSettingsForm

    MPSL.MediaLibraryController = can.Control.extend(
        {},
        {
            frame: null,

            init: function (element, options) {
                this.frame = MPSL.MediaLibrary.create(options);
                this.frame.on('open', this.proxy('onOpen'));
                this.frame.on('select', this.proxy('onSelect'));
                this.frame.on('close', this.proxy('onClose'));
            },

            onOpen: function () {
                this.frame.deselectItem();
            },

            onSelect: function () {
                var selection = this.frame.getSelection();
                var attributes = selection.atts;
                attributes['image_type'] = selection.type;
                return attributes;
            },

            onClose: function () {},

            'click': function (event) {
                var element = $(event[0]);
                var macros = element.data('macros');
                var is_product = (typeof macros !== 'undefined');

                if (is_product) {
                    this.frame.openProductTab();
                } else {
                    this.frame.openLibraryTab();
                }

                this.frame.openLibrary();
            }
        }
    ); // End of MPSL.MediaLibraryController

    MPSL.AddImageMediaLibrary = MPSL.MediaLibraryController.extend(
        {},
        {
            onSelect: function () {
                var slider_wrapper = $('.mpsl-slider-wrapper');
                var layers_list = $('.mpsl-layers-list-wrapper');

                MPSL.Preloader.start(slider_wrapper);
                MPSL.Preloader.start(layers_list);

                var attributes = this._super();
                attributes.options = {
                    'image_id': attributes.id
                    // Here will be fixed "width"
                };

                // Fix width
                var grid_width = MPSL.Workground.width,
                    grid_height = MPSL.Workground.height,
                    width = 0,
                    height = 0,
                    aspect_ratio = attributes.width/attributes.height,
                    division_factor = (grid_width >= attributes.width || grid_height >= attributes.height ? 1 : 2);

                if (grid_width >= grid_height) {
                    height = grid_height/division_factor;
                    width = height*aspect_ratio;
                } else {
                    width = grid_width/division_factor;
                }

                if (width > attributes.width) { // ?
                    width = attributes.width;
                }

                attributes.options.width = width;

                var layer_api = MPSL.ImageLayer.create(attributes);

                var created_deferred = $.Deferred();
                var created = created_deferred.promise();

                $.when(created).then(function () {
                    var position = MPSL.Layer.getCentralOffset();
                    var layer = MPSL.Scope.workground.initLayer($(layer_api.getDomElement()));
                    var options = $.extend({}, position, {
                        'width': layer.element.width(),
                        'image_id': attributes.id,
                        'image_type': attributes.image_type,
                        'image_url': attributes.url
                    });

                    layer.updatePosition(position);
                    layer.extendOptions(options);
                    layer.select();
                    layer.control.select();
                    layer.control.scrollToElement();
                    MPSL.Scope.workground.setCurrentLayer(layer);

                    MPSL.Preloader.stop(slider_wrapper);
                    MPSL.Preloader.stop(layers_list);
                });

                if (layer_api.getScope().layer.created) {
                    created_deferred.resolve();
                } else {
                    $(layer_api.getDomElement()).one('MPSLLayerCreate', created_deferred.resolve);
                }
            }
        }
    ); // End of MPSL.AddImageMediaLibrary

    MPSL.SlideSettingsForm = MPSL.FormController.extend(
        {},
        {
            workground: null,
            options: {},
            dependency: {},
            disabled_dependency: {},
            tabs: null,

            init: function (element, options) {
                this._super(element, options);

                MPSL.Scope.slide_settings = this;

                var tabs = $('#mpsl-slide-settings-tabs .tabs-navigation a');
                this.tabs = new MPSL.Tabs(tabs, {
                    active: tabs.filter(':visible:first').index(),
                    on_change: function (tab, panel) {
                        MPSL.CodeMirrorControl.refreshEditors(panel);
                    }
                });
            },

            setWorkground: function (workground) {
                this.workground = workground;
            },

            render: function (options) {
                MPSL.Preloader.start($('.mpsl-slider-wrapper'));

                var apiOptions = {
                    'general' : {},
                    'backgrounds' : {}
                };

                options = typeof(options) !== 'undefined' ? options : this.getOptions();

                if (options.hasOwnProperty('bg_color_type')) {
                    // BACKGROUND COLOR
                    // SINGLE COLOR
                    if (options.bg_color_type === 'color' && options.hasOwnProperty('bg_color') && options.bg_color.length) {
                        apiOptions.backgrounds.color = {};
                        apiOptions.backgrounds.color.color = options.bg_color;
                    }
                    // GRADIENT COLOR
                    if (options.bg_color_type === 'gradient'
                        &&  (   (options.hasOwnProperty('bg_grad_color_1')&& options.bg_grad_color_1.length)
                                ||(options.hasOwnProperty('bg_grad_color_2') && options.bg_grad_color_2.length)
                            )
                    ) {
                        apiOptions.backgrounds.gradient = {};
                        apiOptions.backgrounds.gradient.colorInitial = (options.hasOwnProperty('bg_grad_color_1') && options.bg_grad_color_1.length) ? options.bg_grad_color_1 : 'transparent';
                        apiOptions.backgrounds.gradient.colorFinal = (options.hasOwnProperty('bg_grad_color_2') && options.bg_grad_color_2.length) ? options.bg_grad_color_2 : 'transparent';
                        if (options.hasOwnProperty('bg_grad_angle')) {
                            apiOptions.backgrounds.gradient.position = (options.bg_grad_angle.length ? options.bg_grad_angle : '0') + 'deg';
                        }
                    }
                    // BACKGROUND IMAGE
                    if (options.hasOwnProperty('bg_image_type')
                        && ( (options.hasOwnProperty('bg_internal_image_url') && options.bg_internal_image_url.length)
                            || (options.hasOwnProperty('bg_image_url') && options.bg_image_url.length)
                        )
                    ) {
                        apiOptions.backgrounds.image = {};

                        // BACKGROUND IMAGE LIBRARY
                        if (options.bg_image_type === 'library' && options.bg_internal_image_url !== '') {
                            apiOptions.backgrounds.image.src = options.bg_internal_image_url;
                        }
                        // BACKGROUND IMAGE EXTERNAL
                        if (options.bg_image_type === 'external' && options.bg_image_url !== '') {
                            apiOptions.backgrounds.image.src = options.bg_image_url;
                        }
                        if (options.hasOwnProperty('bg_position')) {
                            apiOptions.backgrounds.image.position = options.bg_position;
                        }
                        if (options.hasOwnProperty('bg_position_x')) {
                            apiOptions.backgrounds.image.positionX = options.bg_position_x;
                        }
                        if (options.hasOwnProperty('bg_position_y')) {
                            apiOptions.backgrounds.image.positionY = options.bg_position_y;
                        }
                        if (options.hasOwnProperty('bg_repeat')) {
                            apiOptions.backgrounds.image.repeat = options.bg_repeat;
                        }
                        if (options.hasOwnProperty('bg_fit')) {
                            apiOptions.backgrounds.image.fit = options.bg_fit;
                        }
                        if (options.hasOwnProperty('bg_fit_x')) {
                            apiOptions.backgrounds.image.fitX = options.bg_fit_x;
                        }
                        if (options.hasOwnProperty('bg_fit_y')) {
                            apiOptions.backgrounds.image.fitY = options.bg_fit_y;
                        }

                    }

                    if (options.hasOwnProperty('slide_classes')) {
                        apiOptions.general.classes = $.trim(options.slide_classes);
                    }

                    if (options.hasOwnProperty('slide_id')) {
                        apiOptions.general.id = $.trim(options.slide_id);
                    }
                }

    //            console.log('api options' , apiOptions);
                this.workground.slide_api.setOptions(apiOptions);

                this.workground.updateSlideBg();

                MPSL.Preloader.stop($('.mpsl-slider-wrapper'));
            } // Function render()
        }
    );

    MPSL.SlidePageController = can.Control.extend({},{
        slideSettingsController : null,
        styleEditorController : null,
        presetList : null,
        workground : null,
        sliderPreviewWrapper : null,
        sliderPreviewIframe : null,
        previewForm : null,
        optionsInput : null,
        idInput : null,
        typeInput : null,
        nonceInput : null,
        preloader : null,
        resBtns : null,
        resolutionWrapper: null,


        init: function(element, options){
            MPSL.Scope.slide_page_ctrl = this;

            this.workground = new MPSL.Workground('#mpsl-workground', {});

            this.slideSettingsController = new MPSL.SlideSettingsForm('.mpsl-slide-settings-wrapper');
            this.slideSettingsController.setWorkground(this.workground);

            this.styleEditorController = new MPSL.StyleEditorController($('#mpsl-style-editor-modal'));
            this.styleEditorController.setWorkground(this.workground);

            this.sliderPreviewWrapper = $('.mpsl-slider-preview');
            this.preloader = this.sliderPreviewWrapper.find('.mpsl-preloader');
            this.sliderPreviewIframe = this.sliderPreviewWrapper.find('iframe');
            this.desktopIcon = this.sliderPreviewWrapper.find('.desktop');
            this.resolutionWrapper = this.sliderPreviewWrapper.find('.mpsl-resolution-buttons-wrapper');
            this.footerMessageWrapper = this.sliderPreviewWrapper.find('.mpsl-slider-preview-footer-message');
            var self = this;

            var previewUrl = MPSL.Vars.current_url;
            previewUrl = MPSL.Functions.removeParamsFromUrl(previewUrl, ['id', 'token', 'controller']);
            previewUrl = MPSL.Functions.addParamToUrl(previewUrl, 'controller', MPSL.Vars.page.preview_controller);
            previewUrl = MPSL.Functions.addParamToUrl(previewUrl, 'token', MPSL.Vars.page.preview_token);
            previewUrl = MPSL.Functions.addParamToUrl(previewUrl, 'slider_id', MPSL.Vars.page.slider_id);
            previewUrl = MPSL.Functions.addParamToUrl(previewUrl, 'slide_id', MPSL.Vars.page.id);
            previewUrl = MPSL.Functions.addParamToUrl(previewUrl, 'type', 'slide');

            this.sliderPreviewWrapper.dialog({
                resizable: false,
                draggable: false,
                autoOpen: false,
                modal: true,
                width: $(window).width() - 100,
                height: $(window).height() - 100,
                title: MPSL.Vars.lang['preview_dialog_title'],
                closeText: '',
                dialogClass: 'mpsl-preview-dialog',
                close: function() {
                    var frameDoc = self.sliderPreviewIframe[0].contentDocument || self.sliderPreviewIframe[0].contentWindow.document;
                    frameDoc.documentElement.innerHTML = "";
                },
                beforeClose: function() {
                    $('body').css('overflow', 'inherit');
                    self.footerMessageWrapper.addClass('hidden');
                },
                open: function() {
                    self.footerMessageWrapper.removeClass('hidden');
                    $('body').css('overflow', 'hidden');
                    self.preloader.show();
                    self.sliderPreviewIframe.attr('src', previewUrl);
                    self.sliderPreviewIframe.width('100%');
                    self.desktopIcon.siblings().removeClass('active');
                    self.desktopIcon.addClass('active');

                },
                create: function(){
                    self.resolutionWrapper.removeClass('hidden');
                }
            });

            this.resBtns = [
                {type: "desktop", resolution: "100%"},
                {type: "tablet", resolution: "768px"},
                {type: "mobile", resolution: "480px"}
            ];

            this.resBtns.forEach(function (e) {
                $('.' + e.type).on('click', function () {
                    self.sliderPreviewIframe.width(e.resolution);
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    // Temporary
                    if (self.sliderPreviewIframe.length > 0) {
                        var iframe_window = self.sliderPreviewIframe[0].contentWindow || self.sliderPreviewIframe[0].contentDocument;
                        iframe_window.mpslResizePreview();
                    }
                });
            });

            this.sliderPreviewIframe.on('load', function() {
                self.preloader.hide();
            });
        },

        disableBtns : function(){
            this.element.find('#save_slide, #delete_slide').attr('disabled', 'disabled');
        },

        enableBtns : function(){
            this.element.find('#save_slide, #delete_slide').removeAttr('disabled', 'disabled');
        },

        '#save_slide, #slider_preview click' : function(el, e) {
            var preview = $(el).attr('id') === 'slider_preview';

            if (!preview) this.disableBtns();

            var self = this,
                slideId = MPSL.Vars.page.id,
                slideData = this.slideSettingsController.getGroupedOptions(),
                layersData = this.workground.getLayersData(),
                /* mpslReplace */
                layerPresetsData = this.styleEditorController.getPresetsData(),
                lastPresetId = MPSL.LayerPresetList.getLastId(),
                lastPrivatePresetId = MPSL.LayerPresetList.getLastPrivateId(),
                /* endMpslReplace */
                data = {
                    'action': 'mpslUpdateSlide',
                    'nonce': MPSL.Vars.nonces.update_slide,
                    'options': JSON.stringify(slideData),
                    'layers': JSON.stringify(layersData),
                    /* mpslReplace */
                    'presets': JSON.stringify(layerPresetsData),
                    'last_preset_id': lastPresetId,
                    'last_private_preset_id': lastPrivatePresetId,
                    /* endMpslReplace */
                    'preview' : preview
                };

            //if (sliderId) data.id = sliderId;
            if (slideId) data.id = slideId;

            $.ajax({
                'type': 'POST',
                'url': MPSL.Vars.ajax_url,
                'data': data,
                'dataType': 'JSON',

                'success': function(response) {
                    if (preview) {
                        self.sliderPreviewWrapper.dialog('open');
                    } else {
                        MPSL.Functions.showMessage(response.message, response.status);
                        self.enableBtns();
                    }
                },

                'error': function(jqXHR) {
                    self.enableBtns();
                    var error = $.parseJSON(jqXHR.responseText);
                    if (error.is_debug) {
                        MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                    }
                    console.error(error);
                }
            });
        },

    });

    MPSL.StyleEditorForm = MPSL.FormController.extend({},{
        option_controllers: {},
        dependency: {},
        disabled_dependency: {},
        preset : null,
        vars_group: 'preset',
        style_model_switcher: {}, // StyleEditorModeSwitcher
        preview: {}, // LayerStylePreview
        presetList: {}, // LayerPresetList
        styleEditor: {}, // StyleEditorController
        allowStyleControl: {}, // Control
        styleMode: '',
        defaultStyleMode: 'style',

        init: function(element, settings) {
            this._super(element, settings);
            MPSL.Scope.style_editor_form = this;

            this.hide();

            this.allowStyleControl = this.getOptionController('allow_style');
            this.styleMode = this.defaultStyleMode;
            this.style_model_switcher = new MPSL.StyleEditorModeSwitcher($('#mpsl-style-mode-switcher'));
            this.preview = new MPSL.LayerStylePreview($('#mpsl-style-editor-preview-area'));
            can.bind.call(this.style_model_switcher, 'switch', this.proxy('styleModeChanged'));
        },

        hide: function(){
            this.element.addClass('mpsl-hide-options');
        },

        show: function(){
            this.element.removeClass('mpsl-hide-options');
            MPSL.Scope.style_editor_ctrl.fixDialog();
        },

        reinit: function(preset){
            var self = this;
            this.preset = preset;
            this.resetOptions();

            var options = $.extend(true, {}, MPSL.Vars.preset.defaults, this.preset.options);

            $.each(options[this.styleMode], function(optName, value) {
                if (self.option_controllers.hasOwnProperty(optName)) {
                    self.option_controllers[optName].setValue(value, true);
                    self.showDependencedOptions(optName);
                }
            });
            this.showAllDependencies();
            /*if (this.preset.isNew) {
                var options = this.getOptions();
                this.preset.setOptions(options);
                //this.preset.setOptions(options, false, true);
                //this.preset.setOptions(options, false, false);
                this.preset.isNew = false;
            }*/
            this.show();
        },

        resetOptions: function() {
            var self = this;
            $.each(MPSL.Vars.preset.defaults[this.styleMode], function(optName, value) { //TODO: Check
                if (self.option_controllers.hasOwnProperty(optName)) {
                    self.option_controllers[optName].setValue(value, true);
                }
            });
            this.showAllDependencies();
        },

        setOptions: function(options, silent){
            silent = typeof(silent) !== 'undefined' ? silent : false;
            var self = this;
            if ($.isPlainObject(options)) {
                $.each(options, function(optName, value){
                    if (self.option_controllers.hasOwnProperty(optName)) {
                        self.option_controllers[optName].setValue(value, true);
                        self.showDependencedOptions(optName);
                    }
                });
            }
            if (!silent) {
                this.render();
            } else {
                this.preset.setOptions(self.getOptions());
            }
        },

        render: function(){
            if (this.verifyOptions()) {
                //console.log('StyleEditorForm->render');
                var options = this.getOptions();
                this.preset.setOptions(options);
            }
        },

        styleModeChanged: function(control, mode) {
            this.setStyleMode(mode);

            (mode === 'style') ? this.allowStyleControl.disable() : this.allowStyleControl.enable();
            var selectedPreset = this.presetList.getSelected();
            if (selectedPreset) {
                if (selectedPreset.getType() !== MPSL.LayerPreset.DEFAULT) this.reinit(selectedPreset);
                selectedPreset.render();
            }
        },

        getStyleMode: function() {
            return this.styleMode;
        },

        setStyleMode: function(mode) {
            this.styleMode = $.inArray(mode, ['style', 'hover']) > -1 ? mode : this.defaultStyleMode;
        },

        resetStyleMode: function() {
            //this.styleMode = this.defaultStyleMode;
            this.style_model_switcher.setMode(this.defaultStyleMode);
        },

        linkPresetList: function(presetList) {
            this.presetList = presetList;
        },

        linkStyleEditor: function(StyleEditor) {
            this.styleEditor = StyleEditor;
        }

    });

    MPSL.StyleEditorController = can.Control.extend({}, {
        styleSettings: {}, // StyleEditorForm
        presetList: {}, // LayerPresetList
        workground: null,
        editorElement: null,
        editorWrapper: null,
        editorTabs: null,
        editorTabsNav: null,
        editorTabsContent: null,
        previewEl: null,
        linkedLayerControl: null,
        dialog: null,
        tabs: null,

        init: function(element, options) {
            MPSL.Scope.style_editor_ctrl = this;

            this.styleSettings = new MPSL.StyleEditorForm($('#mpsl-style-editor-settings-wrapper'), {'show_error_type' : 'internal'});
            this.styleSettings.linkStyleEditor(this);

            this.presetList = new MPSL.LayerPresetList('#mpsl-layer-preset-list-child-wrapper');
            this.presetList.linkStyleSettings(this.styleSettings);
            this.presetList.linkStyleEditor(this);
            this.styleSettings.linkPresetList(this.presetList);
            //this.presetList.setWorkground(this.workground);

            this.dialog = null;
            this.editorElement = $('#mpsl-style-editor-modal');
            this.editorWrapper = $('#mpsl-style-editor-wrapper');
            this.presetListChildWrapper = $('#mpsl-layer-preset-list-child-wrapper');
            this.editorTabs = this.editorElement.find('#mpsl-style-editor-settings-wrapper');
            this.editorTabsNav = this.editorTabs.find('.tabs-navigation');
            this.editorContentEl = this.editorElement.find('.mpsl-style-editor-content');
            this.editorFooterEl = this.editorElement.find('.mpsl-style-editor-footer');

            this.previewEl = $('#mpsl-style-editor-preview-area');
            this.switcherEl = $('#mpsl-style-mode-switcher');

            this.linkedLayerControl = null;

            //this.applyStyleBtn = this.editorElement.find('#mpsl-apply-style-btn');
            //this.setAsPresetBtn = this.editorElement.find('#mpsl-set-as-preset-btn');

            this.initEditor();
            //this.applyStyleBtn.on('click', this.proxy('applyStyle'));
            //this.setAsPresetBtn.on('click', this.proxy('createPreset'));
        },

        initEditor: function() {
            var self = this;
            /* mpslReplace var curPresetOptions = null; *//* endMpslReplace */

            this.editorElement.dialog({
                resizable: false,
                draggable: false,
                autoOpen: false,
                modal: true,
                appendTo: '#slide-wrapper',
                width: 1000,
                height: $(window).height() - 85, // Also change in functin fixDialog()
                title: MPSL.Vars.lang['style_editor_dialog_title'],
                closeText: '',
                dialogClass: 'mpsl-style-editor-dialog',
                position: { 'my': 'center', 'at': 'center', 'of': window },

                beforeClose: function() {
                    $('body').css('overflow', 'inherit');
                },

                close: function() {
                    /* mpslReplace
                    var name = self.linkedLayerControl.getValue(),
                        curPreset = self.presetList.getByName(name);
                    if (curPreset && curPresetOptions) curPreset.setOptions(curPresetOptions, true);
                    curPresetOptions = null;
                    */
                    // Save private styles
                    var privatePreset = self.presetList.getByName('private');
                    if (privatePreset) {
                        self.linkedLayerControl.helpers['private_styles'].setValue(privatePreset.getData());
                    }
                    /* endMpslReplace */

                    self.presetList.unselect();
                    self.styleSettings.resetStyleMode();
                },

                open: function() {
                    $('body').css('overflow', 'hidden');

                    self.fixDialog();

                    self.styleSettings.resetStyleMode();

                    var name = self.linkedLayerControl.getValue();
                    self.styleSettings.preview.reset();
                    self.loadPrivateOptions();
                    self.presetList.select(name, true);

                    /* mpslReplace
                    var curPreset = self.presetList.getByName(name);
                    if (curPreset) curPresetOptions = curPreset.getOptions({}, true);
                    *//* endMpslReplace */

                    MPSL.CodeMirrorControl.refreshEditors(self.tabs.getActivePanel());
                },

                create: function() {
                    var $uiDialog = $(this).parent('.ui-dialog');
                    if (!$uiDialog.parent('#slide-wrapper').length) $uiDialog.appendTo('#slide-wrapper');

                    self.dialog = $(this).data('ui-dialog');

                    // Refresh CodeMirror
                    self.tabs = new MPSL.Tabs(self.editorTabsNav.find('> a'), {
                        'on_change': function (tab, panel) {
                            MPSL.CodeMirrorControl.refreshEditors(panel);
                        }
                    });
                    self.editorTabsContent = self.tabs.getAllPanels();
                    $(window).on('resize', self.proxy('onWindowResize'));

                    // Render preset list
                    self.createPresetList();

                    can.trigger(self.element, 'create', [self]);
                }
            });
        },

        fixDialog: function() {
            // #mpsl-style-editor-modal = window - 85
            var dialogHeight = $(window).height() - 85;
            dialogHeight = Math.max(0, dialogHeight);
            this.editorElement.dialog('option', 'position', { 'my': 'center', 'at': 'center', 'of': window });
            this.editorElement.dialog('option', 'height', dialogHeight);

            // #mpsl-style-editor-content = #mpsl-style-editor-modal - #mpsl-style-editor-wrapper padding - .mpsl-style-editor-footer
            var contentHeight = this.editorElement.height() - parseInt(this.editorWrapper.css('padding-top')) - parseInt(this.editorWrapper.css('padding-bottom')) - this.editorFooterEl.outerHeight(true);
            contentHeight = Math.max(0, contentHeight);
            this.editorContentEl.css('height', contentHeight);

            // #mpsl-preset-settings-... = #mpsl-style-editor-content - #mpsl-style-editor-preview-area - #mpsl-style-mode-switcher - #mpsl-style-editor-settings-wrapper .tabs-navigation
            var settingsHeight = contentHeight - this.previewEl.outerHeight(true) - this.switcherEl.outerHeight(true) - this.editorTabsNav.outerHeight(true);
            settingsHeight = Math.max(0, settingsHeight);
            this.editorTabsContent.height(settingsHeight);

            // #mpsl-layer-preset-list-child-wrapper = #mpsl-style-editor-content
            this.presetListChildWrapper.height(contentHeight);
        },

        loadPrivateOptions: function() {
            var privateStyles = this.linkedLayerControl.helpers['private_styles'].getValue(),
                privateStylesCtrl = this.presetList.getByName('private');
            if (privateStylesCtrl) {
                privateStylesCtrl.setOptions(can.isEmptyObject(privateStyles) ? MPSL.LayerPreset.getDefaultsCopy() : privateStyles, true, true);
            }
        },

        createPresetList: function() {
            var self = this;

            // Generate private preset
            this.presetList.add('private', MPSL.LayerPreset.getDefaultsCopy(MPSL.Vars.lang['layer_preset_private_name']), MPSL.LayerPreset.PRIVATE);

            // Generate other presets

            $.each(MPSL.Vars.preset.list, function(name) {
                self.presetList.add(name, this);
            });

            // Generate default presets
            $.each(MPSL.Vars.preset.default_list, function(name) {
                self.presetList.add(name, this, MPSL.LayerPreset.DEFAULT);
            });
        },

        'onWindowResize': function() {
            if (this.editorElement.dialog('isOpen')) {
                this.fixDialog();
            }
        },

        /* mpslReplace */
        '#mpsl-save-as-layer-preset click': function() {
            var selectedPreset = this.presetList.getSelected();
            if (selectedPreset) {
                var label = prompt(MPSL.Vars.lang['layer_preset_enter_name'], selectedPreset.getLabel());
                if (label != null) {
                    var preset = this.presetList.duplicate(label);
                    if (preset) {
                        preset.select();
                    }
                }
            } else {
                MPSL.Functions.showMessage(MPSL.Vars.lang['layer_preset_not_selected'], MPSL.INFO);
            }
        },

        '#mpsl-create-layer-preset click': function() {
            var label = prompt(MPSL.Vars.lang['layer_preset_enter_name'], '');
            if (label != null) {
                if (!label) label = MPSL.Vars.lang['layer_preset_default_name'];
                var preset = this.presetList.addNew(label);
                if (preset) preset.select();
            }
        },

        '#mpsl-apply-layer-preset click': function() {
            var selectedPreset = this.presetList.getSelected();
            if (selectedPreset) {
                this.linkedLayerControl.setValue(selectedPreset.getName());

                can.trigger(this.presetList.element, 'presetApply', {
                    'name': selectedPreset.getName(),
                    'preset': selectedPreset
                });

                //NOTE: Moved to dialog.close
                //if (presetName === 'private') {
                //    this.linkedLayerControl.helpers['private_styles'].setValue(selectedPreset.getData());
                //}

                this.dialog.close();
            }
        },
        /* endMpslReplace */

        setWorkground: function(workground) {
            this.workground = workground;
        },

        /* mpslReplace */
        getPresetsData: function() {
            return this.presetList.getData();
        },
        /* endMpslReplace */

        linkControl: function(control) {
            this.linkedLayerControl = control;
        },

        /*---------------------*/

        disableBtns: function() {
            this.element.find('#save_slide, #delete_slide').attr('disabled', 'disabled');
        },
        enableBtns: function() {
            this.element.find('#save_slide, #delete_slide').removeAttr('disabled', 'disabled');
        }

    });

    MPSL.LayerPresetList = can.Control.extend({
        defaults: {},
        classPrefix: MPSL.Vars.preset.class_prefix,
        privateClassPrefix: MPSL.Vars.preset.private_class_prefix,

        getLastId: function() {
            return MPSL.Vars.preset.last_id;
        },
        getNextId: function() {
            this.incLastId();
            return MPSL.LayerPresetList.getLastId();
        },
        incLastId: function() {
            MPSL.Vars.preset.last_id ++;
        },

        getLastPrivateId: function() {
            return MPSL.Vars.preset.last_private_id;
        },
        getNextPrivateId: function() {
            MPSL.LayerPresetList.incLastPrivateId();
            return MPSL.LayerPresetList.getLastPrivateId();
        },
        incLastPrivateId: function() {
            MPSL.Vars.preset.last_private_id ++;
        }

    }, {
        //presets: [],
        list_container: null,
        workground: null,
        styleSettings: {}, // StyleEditorForm
        styleEditor: {}, // StyleEditorController

        init: function (element, options) {
            MPSL.Scope.layer_preset_list = this;

            //this.element.css('max-height', 250);
            this.list_container = element.find('.mpsl-layer-presets-table > tbody');

            can.bind.call(element, 'presetSelect', this.proxy('onPresetSelect'));
        },

        'onPresetSelect': function(e, pName) {
            var preset = this.getByName(pName);
            if (preset && preset.getType() === MPSL.LayerPreset.PRIVATE) {
                // TODO: Refactor. Move `loadPrivateOptions` to LayerPreset or LayerPresetList
                this.styleEditor.loadPrivateOptions();
                preset.select(true);
            }
        },

        add: function(name, options, type) {
            type = typeof type === 'undefined' ? MPSL.LayerPreset.CUSTOM : type;
            var self = this;

            var tr = $('<tr>').attr('data-name', name);
            if (type === MPSL.LayerPreset.PRIVATE) {
                tr.addClass('mpsl-layer-preset-private');
            } else if (type === MPSL.LayerPreset.DEFAULT) {
                tr.addClass('mpsl-layer-preset-default');
            }

            var tdTitle = $('<td>')
                .addClass('mpsl-layer-preset-label')
                .text(options.settings.label);

            var tdRemoveHandle = $('<td>')
                .attr('title', MPSL.Vars.lang['delete'])
                .text('');

            if ($.inArray(type, [MPSL.LayerPreset.PRIVATE, MPSL.LayerPreset.DEFAULT]) > -1) {
                tdRemoveHandle.addClass('mpsl-layer-preset-label');
            } else {
                tdRemoveHandle.addClass('mpsl-layer-preset-remove-handle');
            }

            tr.append(tdTitle, tdRemoveHandle);
            if (type === MPSL.LayerPreset.CUSTOM) {
                // TODO: Refactor. Wrap different types.
                this.list_container.children('.mpsl-layer-preset-private, :not(.mpsl-layer-preset-default)').eq(-1).after(tr);
            } else {
                this.list_container.append(tr);
            }

            var preset = new MPSL.LayerPreset(tr, options, {'name': name, 'type': type});
            preset.on('select', function(event, preset) {
                self.styleSettings.reinit(preset);
            });
            preset.on('unselect', function(){
                self.styleSettings.hide();
            });
            preset.linkPresetList(this);

            //this.styleSettings.linkedLayerControl
            //can.trigger(this, 'presetAdd', {
            can.trigger(this.element, 'presetAdd', {
                'name': name,
                'label': options.settings.label,
                'preset': preset,
                'isPrivate': type === MPSL.LayerPreset.PRIVATE
            });

            return preset;
        },

        addNew: function(label) {
            var name = MPSL.LayerPresetList.classPrefix + MPSL.LayerPresetList.getNextId();
            return this.add(name, MPSL.LayerPreset.getDefaultsCopy(label));
        },

        duplicate: function(newLabel, preset) {
            preset = typeof preset === 'undefined' ? this.getSelected() : preset;
            if (!preset) return false;

            var newOptions = preset.getOptions({'settings': {'label': newLabel}}, true),
                newName = MPSL.LayerPresetList.classPrefix + MPSL.LayerPresetList.getNextId();

            return this.add(newName, newOptions);
        },

        /**
        * @param {String | MPSL.LayerPreset} preset
        * */
        select: function(preset, hard) {
            if (typeof preset === 'undefined') return false;
            hard = typeof hard === 'undefined' ? false : hard;
            //preset = typeof preset === 'string' && preset ? this.getByName(preset) : preset;

            if (typeof preset === 'string') {
                preset = preset ? preset : 'private'; // Set private by default
                preset = this.getByName(preset);
            }
            if (!preset) preset = this.getByName('private');

            if (preset) {
                preset.select(hard);
                //return true;
                return preset.getName();
            } else {
                return false;
            }
        },

        /**
        * @param {String | MPSL.LayerPreset} [preset]
        * */
        unselect: function(preset) {
            preset = typeof preset === 'undefined' ? this.getSelected() : preset;
            preset = typeof preset === 'string' && preset ? this.getByName(preset) : preset;

            if (preset) {
                preset.unselect();
                return true;
            } else {
                return false;
            }
        },

        getSelected: function() {
            var selectedEl = this.list_container.children('.layer-preset-control-selected');
            return selectedEl.length ? this.list_container.children('.layer-preset-control-selected').control() : false;
        },

        getByName: function(name, returnElement) {
            returnElement = typeof returnElement === 'undefined' ? false : returnElement;

            var presetEl = this.list_container.children('[data-name="' + name + '"]');
            if (!presetEl.length) return false;

            return returnElement ? presetEl : presetEl.control();
        },

        //setWorkground: function(workground) {
         //   this.workground = workground;
        //},

        linkStyleEditor: function(styleEditor) {
            this.styleEditor = styleEditor;
        },

        linkStyleSettings: function(styleSettings) {
            this.styleSettings = styleSettings;
        },

        remove: function(control) {
            control.remove();
        },

        /* mpslReplace */
        getData: function() {
            var presets = {}, presetCtrl;

            this.list_container.children('tr').each(function() {
                presetCtrl = $(this).control();
                if (presetCtrl) {
                    //name = presetCtrl.getName();
                    //if (name !== 'private') { // Skip private styles

                    if ($.inArray(presetCtrl.getType(), [MPSL.LayerPreset.PRIVATE, MPSL.LayerPreset.DEFAULT]) === -1) { // Skip private & default styles
                        presets[presetCtrl.getName()] = presetCtrl.getData();
                    }
                }
            });

            return presets;
        },
        /* endMpslReplace */

        getStyleMode: function() {
            return this.styleSettings.getStyleMode();
        }
    });

    MPSL.LayerPreset = can.Control.extend({
        defaults: {},
        CUSTOM: 0,
        PRIVATE: 1,
        DEFAULT: 2,

        getDefaultsCopy: function(label) {
            var options = $.extend(true, {}, MPSL.Vars.preset.defaults);
            if (typeof label !== 'undefined') {
                options.settings.label = label;
            }
            return options;
        }

    }, {
        type: null,
        name: null,
        label: null,
        lableEl: null,
        removeHandle: null,
        presetList: {},

        setup: function(element, options, data/*, isNew*/) {
            //this.isNew = typeof(isNew) !== 'undefined' ? isNew : true;
            this._super(element, options);
            this.name = data.name;
            this.type = data.type;
            this.lableEl = element.children('.mpsl-layer-preset-label');
            //this.removeHandle = element.children('.mpsl-layer-preset-remove-handle');

            MPSL.Functions.disableSelection(this.lableEl);

            //if (!this.isNew) {
            //    var presetOptions = MPSL.Vars.preset.list[this.name];
                //$.extend(true, this.options, presetOptions, options);
            //} else {
                $.extend(true, this.options, options);
            //}
        },

        init: function (element, options) {},

        destroy: function() {
            this.unselect();
            can.Control.prototype.destroy.call(this);
        },

        // Select
        /* mpslReplace */
        '> .mpsl-layer-preset-label click': function() {
            this.select();
        },

        // Rename
        '> .mpsl-layer-preset-label dblclick': function(el, e) {
            //if (this.element.hasClass('mpsl-layer-preset-private') || this.element.hasClass('mpsl-layer-preset-default')) {
            if ($.inArray(this.type, [MPSL.LayerPreset.PRIVATE, MPSL.LayerPreset.DEFAULT]) > -1) {
                e.preventDefault();
                return;
            }

            var label = prompt(MPSL.Vars.lang['layer_preset_rename'], this.getLabel());
            if (label != null && label) {
                this.updateLabel(label);
            }
        },

        // Remove
        '> .mpsl-layer-preset-remove-handle click': function() {
            //if (this.element.hasClass('mpsl-layer-preset-private') || this.element.hasClass('mpsl-layer-preset-default')) {
            if ($.inArray(this.type, [MPSL.LayerPreset.PRIVATE, MPSL.LayerPreset.DEFAULT]) > -1) {
                e.preventDefault();
                return;
            }

            if (confirm(MPSL.Vars.lang['layer_preset_delete'].replace('%s', this.getLabel()))) {
                var privatePreset = null;

                if (this.isSelected()) {
                    this.presetList.styleSettings.preview.reset();
                    privatePreset = this.presetList.getByName('private');
                }
                this.presetList.styleSettings.preview.removeStyles(this.name);

                var name = this.getName();
                this.element.remove();
                can.trigger(this.presetList.element, 'presetRemove', {
                    'name': name
                });
                if (privatePreset) privatePreset.select();
            }
        },
        /* endMpslReplace */

        select: function(hard) {
            hard = typeof hard === 'undefined' ? false : hard;

            if (hard || !this.isSelected()) {
                var siblings = this.element.siblings();
                siblings.removeClass('layer-preset-control-selected');
                this.element.addClass('layer-preset-control-selected');

                this.render();

                if (this.getType() !== MPSL.LayerPreset.DEFAULT) {
                    this.dispatch('select', [this]);
                } else {
                    this.dispatch('unselect', [this]);
                }
            }
        },

        unselect: function() {
            if (this.isSelected()) {
                this.element.removeClass('layer-preset-control-selected');
                this.dispatch('unselect', [this]);
            }
        },

        isSelected: function() {
            return this.element.hasClass('layer-preset-control-selected');
        },

        getData: function(prepare) {
            prepare = typeof prepare === 'undefined' ? true : prepare;
            var options = $.extend(true, {}, this.options);
            if (prepare) {
                options.settings.hover = options.hover.allow_style;
                //delete options.style.allow_style;
                //delete options.hover.allow_style;
            }

            // Delete empty options
            /*$.each(['style', 'hover'], function(modeKey, mode) {
                $.each(options[mode], function(name, val) {
                    if (val === "" || val === null || typeof val === 'undefined') {
                        delete options[mode][name];
                    }
                });
            });*/

            return options;
        },

        getName: function() {
            return this.name;
        },

        getType: function() {
            return this.type;
        },

        /**
        * @param options - merge this.options with options
        * @param all - get All or by current style mode.
        * */
        getOptions: function(options, all) {
            options = typeof options === 'object' ? options : {};
            all = typeof all === 'undefined' ? false : all;
            if (all) {
                //return $.extend(true, {}, this.options, options);
                options = $.extend(true, {}, this.options, options);
                $.each(['style', 'hover'], function(modeKey, modeVal) {
                    $.each(options[modeVal], function(key) {
                        if (!MPSL.Vars.preset.options.hasOwnProperty(key)) {
                            delete options[modeVal][key];
                        }
                    });
                });
                return options;

            } else {
                var styleMode = this.presetList.getStyleMode();
                //return $.extend(true, {}, this.options[styleMode], options);
                options = $.extend(true, {}, this.options[styleMode], options);
                $.each(options, function(key) {
                    if (!MPSL.Vars.preset.options.hasOwnProperty(key)) {
                        delete options[key];
                    }
                });
                return options;
            }
        },

        setOptions: function(options, all, silent) {
            all = typeof all === 'undefined' ? false : all;
            silent = typeof silent === 'undefined' ? false : silent;

            //TODO: prepare options ?

            if (all) {
                this.options = options;
            } else {
                var styleMode = this.presetList.getStyleMode();
                this.options[styleMode] = options;
            }

            this.options.settings.hover = this.options.hover.allow_style;

            if (!silent) this.render();
        },

        render: function() {
            var layer = MPSL.Scope.workground.getCurrentLayer();
            if (layer) {
                var isPrivate = this.getType() === MPSL.LayerPreset.PRIVATE;

                this.presetList.styleSettings.preview.update(
                    //this.getOptions(),
                    this.getOptions({}, true),
                    isPrivate ? layer.options.private_preset_class : this.getName(),
                    //this.presetList.getStyleMode() === 'style',
                    true,
                    isPrivate
                );
            }
        },

        getLabel: function() {
            var label = this.options.settings.label;
            return label ? label : MPSL.Vars.lang['layer_preset_private_name'];
        },

        updateLabel: function(label) {
            //this.presetList.updatePresetLabel(label);

            this.options.settings.label = label;
            this.lableEl.text(label);

             can.trigger(this.presetList.element, 'presetUpdate', {
                'name': this.getName(),
                'label': label,
                'preset': this
            });
        },

        linkPresetList: function(layersControls) {
            this.presetList = layersControls;
        },

        scrollToElement: function() {
            this.presetList.element.animate({
                'scrollTop': this.presetList.element.scrollTop() + this.element.position().top - this.presetList.element.position().top - this.presetList.element.height() / 2 + this.element.height() / 2
            }, 100);
        }

    });
    can.extend(MPSL.LayerPreset.prototype, can.event);

    MPSL.StyleEditorModeSwitcher = can.Control.extend({
        mode: 'style'
    }, {
        buttons: {},

        init: function(el) {
            MPSL.Scope.style_model_switcher = this;

            el.buttonset();
            this.buttons = this.element.children('input')
        },

        '> input change': function(el) {
            var mode = el.data('mode');
            MPSL.StyleEditorModeSwitcher.mode = mode;
            can.trigger(this, 'switch', mode);
        },

        getMode: function() {
            return MPSL.StyleEditorModeSwitcher.mode;
        },

        setMode: function(mode) {
            this.buttons.removeAttr('checked');
            this.element.children('[data-mode="' + mode + '"]').click();
        }
    });

    MPSL.LayerStylePreview = can.Control.extend({}, {
        preview: {},
        bgToggle: {},
        bgToggleState: false,
        stylesWrapper: {},

        init: function(el, args) {
            MPSL.Scope.layer_style_preview = this;

            this.preview = el.children('.mpsl-style-editor-preview');
            this.bgToggle = el.children('.mpsl-style-editor-bg-toggle');
            this.stylesWrapper = $('#mpsl-preset-styles-wrapper');

            MPSL.Functions.disableSelection(this.bgToggle);
            can.trigger(this.bgToggle, 'click', this.bgToggleState);
        },

        '> .mpsl-style-editor-bg-toggle click': function(el, event, state) {
            this.bgToggleState = typeof state === 'undefined' ? !this.bgToggleState : state;
            if (this.bgToggleState) {
                this.element.css('background-color', 'black');
                el.css('background-color', 'white');
            } else {
                this.element.css('background-color', 'white');
                el.css('background-color', 'black');
            }
        },

        update: function(styles, className, updateStyleBlock, isPrivate) {
            styles = typeof styles === 'undefined' ? [] : styles;
            updateStyleBlock = typeof updateStyleBlock === 'undefined' ? false : updateStyleBlock;

            // Prepare styles
            var stylesStr, unit, styleTypes = ['style', 'hover'],
                hoverEnabled = styles['hover'].allow_style,
                normalCustomStyles = styles['style'].custom_styles,
                hoverCustomStyles = styles['hover'].custom_styles,
                regularCss = {}, regularCssStr,
                hoverCss = {}, hoverCssStr,
                allCss = {};

            delete styles['style'].allow_style;
            delete styles['style'].custom_styles;
            delete styles['hover'].allow_style;
            delete styles['hover'].custom_styles;

            $.each(styleTypes, function(typeKey, type) {
                $.each(styles[type], function(name, val) {
                    if (MPSL.Vars.preset.options[name].isChild) {
                        delete styles[type][name];
                        return;
                    }

                    //switch (name) {
                        //case 'font-style':
                        //    styles[type][name] = val = val ? 'italic' : '';
                        //    break;
                        //case 'line-height':
                        //    styles[type][name] = val = val === '' ? 'normal' : val;
                        //    break;
                    //}

                    // skip empty
                    if (typeof val !== 'string' || val === '') {
                        delete styles[type][name];
                        return;
                    }

                    // add unit
                    unit = MPSL.Vars.preset.options[name].unit;
                    if ($.isNumeric(val) && unit) {
                        styles[type][name] = $.trim(val) + unit;
                    } else {
                        styles[type][name] = $.trim(val);
                    }
                });
            });
            // End Prepare styles

            // Regular
            $.extend(regularCss, styles['style']);
            $.extend(regularCss, this.parseCSS(normalCustomStyles));
            $.extend(allCss, regularCss);
            regularCssStr = this.stringifyCSS(regularCss);

            // Hover
            $.extend(hoverCss, styles['hover']);
            $.extend(hoverCss, this.parseCSS(hoverCustomStyles));
            if (MPSL.StyleEditorModeSwitcher.mode === 'hover') $.extend(allCss, hoverCss);
            hoverCssStr = this.stringifyCSS(hoverCss);

            // Update preview
            this.preview.attr('style', '');
            this.preview.css(allCss);
            stylesStr = this.preview.attr('style');
            this.preview.attr('style', stylesStr);

            /* mpslReplace */
            // Update presets styles
            if (updateStyleBlock) {
                stylesStr = '';
                stylesStr += '.' + MPSL.Vars.preset.layer_class + '.' + className + '{' + regularCssStr + '}';
                if (hoverEnabled) {
                    stylesStr += '.' + MPSL.Vars.preset.layer_class + '.' + className + '.' + MPSL.Vars.preset.layer_hover_class + '{' + hoverCssStr + '}';
                }
                var presetStyleEl = $('#' + className);
                if (presetStyleEl.length) {
                    presetStyleEl.text(stylesStr);
                } else {
                    presetStyleEl = $('<style>', {
                        'type': 'text/css',
                        'class': 'mpsl-preset-style',
                        'id': className,
                        'text': stylesStr
                    });
                    this.stylesWrapper.append(presetStyleEl);
                }
            }
            /* endMpslReplace */
        },

        parseCSS: function(css) {
            css = typeof css === 'string' ? css : '';
            var cssObj = {};
            css = css.replace(/\/\*.+?\*\//g, '');
            css = $.trim(css);
            if (css) {
                css = css.split(';');
                $.each(css, function(key, val) {
                    if (val) {
                        css[key] = val.split(':');
                        if (css[key].length === 2) {
                            var optName = $.trim(css[key][0]), optVal = $.trim(css[key][1]);
                            if (optName && optVal) {
                                cssObj[optName] = optVal;
                            }
                        }
                    }
                });
            }
            return cssObj;
        },

        stringifyCSS: function(css) {
            css = typeof css === 'object' ? css : {};
            var str = '';
            $.each(css, function(key, val) {
                str += key + ':' + val + ';';
            });
            return str;
        },

        reset: function() {
            this.preview.attr('style', '');
        },

        removeStyles: function(className) {
            var presetStyleEl = $('#' + className);
            if (presetStyleEl.length) presetStyleEl.remove();
        }

    });

})(jQuery, MPSL);

jQuery(function ($) {
    // Init slide page controller
    window.slidePageController = new MPSL.SlidePageController($('#slide-wrapper'));
});
