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
    /*
     * Objects:
     *     MPSL.FormController
     *     MPSL.OptionControl
     *         MPSL.TextControl
     *         MPSL.NumberControl
     *         MPSL.TextareaControl
     *             MPSL.CodeMirrorControl
     *         MPSL.TinyMCEControl
     *         MPSL.CheckboxControl
     *             MPSL.SwitcherControl
     *         MPSL.SelectControl
     *             MPSL.FontPickerControl
     *             MPSL.SizeSelectControl
     *         MPSL.SelectListControl
     *         MPSL.RadioGroupControl
     *             MPSL.RadioButtonsControl
     *         MPSL.ButtonGroupControl
     *         MPSL.ActionGroupControl
     *         MPSL.AliasControl
     *         MPSL.ImageLibraryControl
     *         MPSL.VideoLibraryControl
     *         MPSL.ImageUrlControl
     *         MPSL.ShortcodeControl
     *         MPSL.HiddenControl
     *         MPSL.AlignTableControl
     *         MPSL.DatePicker
     *         MPSL.StyleEditorControl
     *         MPSL.ColorPickerControl
     *         MPSL.MultipleControl
     *         MPSL.TextShadowControl
     *         MPSL.AnimationControl
     */

    MPSL.FormController = can.Control.extend(
        {},
        {
            id: null,
            element: null,

            option_controllers: {},
            settings: {},
            vars_group: "page", // Group name in MPSL.Vars: MPSL.Vars["page"] etc.

            dependency_types: {
                'dependency': 'visibility', // Hide by "visibility" CSS rule when disabled
                'disabled_dependency': 'disabled' // Hide by "disabled" HTML attr when disabled
            },
            dependency: {},
            disabled_dependency: {},

            init: function (element, settings) {
                this.id = MPSL.Vars.page.id; // ID is always in "page" group, no need to use this.vars_group
                this.element = element;
                this.settings = settings;

                this.dependency = {};
                this.disabled_dependency = {};

                this.createOptionControllers();
                this.updateActionsAndHelpers();
                this.showAllDependencies();

                can.trigger(this, 'initFinish');
            },

            createOptionControllers: function () {
                var self = this;
                var option_elements = self.element.find('.mpsl-option');
                var settings = {
                    'show_error_type': this.settings.show_error_type
                };
                $.each(option_elements, function (key, option) {
                    var option_controller = self.createOptionController($(option), settings);
                    var option_name = option_controller.getName();
                    self.option_controllers[option_name] = option_controller;
                });
            },

            createOptionController: function (option, settings) {
                var type = option.attr('data-mpsl-option-type');

                // Extend settings with some additional parameters
                var is_nested = option.hasClass('mpsl-nested-option');
                var parent_option = '';
                if (is_nested) {
                    var parent_option_name = option.attr('data-parent-name');
                    parent_option = this.option_controllers[parent_option_name];
                }
                $.extend(settings, {
                    'is_nested': is_nested,
                    'parent_option': (is_nested) ? parent_option : {},
                    'form': this
                });

                // Create control
                var control;
                switch(type) {
                    case 'text': control = new MPSL.TextControl($(option), settings); break;
                    case 'number': control = new MPSL.NumberControl($(option), settings); break;
                    case 'textarea': control = new MPSL.TextareaControl($(option), settings); break;
                    case 'tiny_mce': control = new MPSL.TinyMCEControl($(option), settings); break;
                    case 'checkbox': control = new MPSL.CheckboxControl($(option),settings); break;
                    case 'switcher': control = new MPSL.SwitcherControl($(option),settings); break;
                    case 'select': control = new MPSL.SelectControl($(option), settings); break;
                    case 'select_list': control = new MPSL.SelectListControl($(option), settings); break;
                    case 'radio_group': control = new MPSL.RadioGroupControl($(option), settings); break;
                    case 'radio_buttons': control = new MPSL.RadioButtonsControl($(option), settings); break;
                    case 'button_group': control = new MPSL.ButtonGroupControl($(option), settings); break;
                    case 'action_group': control = new MPSL.ActionGroupControl($(option), settings); break;
                    // Custom controls
                    case 'library_image': control = new MPSL.ImageLibraryControl($(option), settings);  break;
                    case 'library_video': control = new MPSL.VideoLibraryControl($(option), settings); break;
                    case 'image_url': control = new MPSL.ImageUrlControl($(option), settings); break;
                    case 'hidden': control = new MPSL.HiddenControl($(option), settings); break;
                    case 'alias': control = new MPSL.AliasControl($(option),settings); break;
                    case 'shortcode': control = new MPSL.ShortcodeControl($(option),settings); break;
                    case 'align_table': control = new MPSL.AlignTableControl($(option),settings); break;
                    case 'datepicker': control = new MPSL.DatePicker($(option), settings); break;
                    case 'codemirror': control = new MPSL.CodeMirrorControl($(option), settings); break;
                    case 'style_editor': control = new MPSL.StyleEditorControl($(option), settings); break;
                    case 'multiple': control = new MPSL.MultipleControl($(option), settings); break;
                    case 'font_picker': control = new MPSL.FontPickerControl($(option), settings); break;
                    case 'size_select': control = new MPSL.SizeSelectControl($(option), settings); break;
                    case 'color_picker': control = new MPSL.ColorPickerControl($(option), settings); break;
                    case 'text_shadow': control = new MPSL.TextShadowControl($(option), settings); break;
                    case 'animation_control': control = new MPSL.AnimationControl($(option), settings); break;
                    default: control = new MPSL.OptionControl($(option), settings); break;
                }

                this.addDependency(control);

                return control;
            },

            addDependency: function (control, dependency_type) {
                if (typeof dependency_type === 'undefined') {
                    // Add both dependencies
                    var self = this;
                    $.each(this.dependency_types, function (dependency_name) {
                        self.addDependency(control, dependency_name);
                    });
                    return false;
                }

                var dependency = control.getDependency(dependency_type);
                if (typeof dependency !== 'undefined') {
                    if (MPSL.Functions.typeIn(dependency.value, ['array', 'object'])) {
                        // Convert
                        //     'dependency' => array(
                        //         'parameter' => ...,
                        //         'value' => array(%value 1%, %value 2%), // Or single value
                        //         'operator' => %operator% // Optional, "IN" by default
                        //     )
                        // into
                        //     "%operator%;%value 1%,%value 2%"
                        // For example:
                        //     dependency.value = "IN;custom,product";
                        dependency.value = dependency.operator + ';' + dependency.value.join(',');
                    }

                    // Add dependency
                    if (!this[dependency_type][dependency.parameter]) {
                        // Add new dependency to this.dependency or to
                        // this.disabled_dependency
                        this[dependency_type][dependency.parameter] = {};
                    }
                    if (!this[dependency_type][dependency.parameter][dependency.value]) {
                        // Set value of dependency in this.dependency or
                        // in this.disabled_dependency
                        this[dependency_type][dependency.parameter][dependency.value] = [];
                    }
                    // Add new controller to dependency value in this.dependency
                    // or this.disabled_dependency
                    this[dependency_type][dependency.parameter][dependency.value].push(control.getName());
                }
            },

            // Previous function name - "updateOptionControllers"
            updateActionsAndHelpers: function () {
                var self = this;
                $.each(MPSL.Vars[this.vars_group].options, function (option_name, option) {
                    if (self.option_controllers.hasOwnProperty(option_name)) {
                        if (option.hasOwnProperty('actions')) {
                            $.each(option.actions, function (action_name, action) {
                                self.option_controllers[option_name].addAction(action_name, action);
                            });
                        }
                        if (option.hasOwnProperty('helpers')) {
                            $.each(option.helpers, function (key, helper_name) {
                                self.option_controllers[option_name].addHelper(helper_name, self.option_controllers[helper_name]);
                            });
                        }
                        if (option.hasOwnProperty('options')) {
                            $.each(option.options, function (clone_option_name, clone_option) {
                                if (self.option_controllers.hasOwnProperty(clone_option_name) && clone_option.hasOwnProperty('helpers')) {
                                    $.each(clone_option.helpers, function (key, helper_name) {
                                        self.option_controllers[clone_option_name].addHelper(helper_name, self.option_controllers[clone_option_name]);
                                    });
                                }
                            });
                        }
                    }
                });
            },

            showAllDependencies: function () {
                var self = this;
                $.each(this.dependency_types, function(dependency_name, dependency_method) {
                    /** @var dependence_option Depends from param. */
                    /** @var dependence_values Depends from values. */
                    $.each(self[dependency_name], function(dependence_option, dependence_values) {
                        self.showDependencedOptions(dependence_option, dependency_name);
                    });
                });
            },

            showDependencedOptions: function (option_name, dependency_type) {
                if (typeof dependency_type === 'undefined') {
                    // Show both dependencies
                    var self = this;
                    $.each(self.dependency_types, function (dependency_name) {
                        self.showDependencedOptions(option_name, dependency_name);
                    });
                    return false;
                }

                var enable = false;

                // Need to check the option if call showDependencedOptions() not
                // from showAllDependencies() function
                if (this[dependency_type].hasOwnProperty(option_name)) {
                    var self = this;
                    var option_controller = this.option_controllers[option_name];
                    var option_value = option_controller.getValue();
                    var dependence_option_name = option_name;
                    var dependence_values = this[dependency_type][option_name];
                    var dependency_method = this.dependency_types[dependency_type];

                    if (!$.isArray(option_value)) {
                        option_value = [option_value];
                    }

                    /** @var options Depending on the "dependence_values" parameters. */
                    $.each(dependence_values, function (dependence_value, options) {
                        // Convert dependence value back to operator and values
                        // from "%operator%;%value 1%,%value 2%"
                        dependence_value = dependence_value.split(';');
                        var operator = dependence_value[0];
                        dependence_value = dependence_value[1].split(',');
                        $.each(dependence_value, function (key, value) {
                            dependence_value[key] = option_controller.fixValueType(value);
                        });

                        $.each(options, function (key, option) {
                            enable = false;
                            if (self.option_controllers[dependence_option_name].isEnabledBy(dependency_method)) {
                                var enables = [];
                                $.each(option_value, function (opt_key, opt_val) {
                                    enables[opt_key] = $.grep(dependence_value, function (dval) {
                                        return (dval == opt_val);
                                    });
                                    enables[opt_key] = !!(operator === 'NOT IN' ? !enables[opt_key].length : enables[opt_key].length);
                                });

                                if (operator === 'IN') {
                                    enable = MPSL.Functions.inArray(true, enables);
                                } else if (operator === 'NOT IN') {
                                    enable = !MPSL.Functions.inArray(false, enables);
                                } else { // if (operator === 'AND')
                                    enable = $.grep(enables, function (dval) {
                                        return dval === true;
                                    });
                                    enable = (enable.length === dependence_value.length && !MPSL.Functions.inArray(false, enables));
                                }
                            }
                            if (enable) {
                                self.option_controllers[option]['enableBy'](dependency_method);
                            } else {
                                self.option_controllers[option]['disableBy'](dependency_method);
                            }
                            self.showDependencedOptions(self.option_controllers[option].getName(), dependency_type);
                        });
                    });
                }
            },

            verifyOptions: function () {
                var self = this;
                $.each(self.option_controllers, function (index, option_controller) {
                    if (option_controller.isEnabled() && !option_controller.verifyOption()) {
                        return false;
                    }
                });
                return true;
            },

            setOption: function (option_name, new_value) {
                var option = this.getOptionController(option_name);
                if (option !== false) {
                    option.setValue(new_value);
                    return true;
                } else {
                    return false;
                }
            },

            updateOption: function (option_name, new_value) {
                return this.setOption(option_name, new_value);
            },

            getOption: function (option_name, default_value) {
                var option = this.getOptionController(option_name);
                if (option !== false) {
                    return option.getValue();
                } else {
                    if (typeof default_value !== 'undefined') {
                        return default_value;
                    } else {
                        return '';
                    }
                }
            },

            getOptions: function () {
                var options = {};
                $.each(this.option_controllers, function (option_name, option_controller) {
                    if (option_controller.isVisible()) {
                        options[option_name] = option_controller.getValue();
                    }
                });
                return options;
            },

            getGroupedOptions: function () {
                var options = {};
                $.each(this.option_controllers, function (option_name, option_controller) {
                    if (option_controller.isVisible()) {
                        var group_name = option_controller.getGroup();
                        if (typeof options[group_name] === 'undefined') {
                            options[group_name] = {};
                        }
                        options[group_name][option_name] = option_controller.getValue();
                    }
                });
                return options;
            },

            getOptionController: function (name) {
                if (typeof this.option_controllers[name] === 'undefined') {
                    return false;
                } else {
                    return this.option_controllers[name];
                }
            },

            render: function () {}
        }
    ); // End of MPSL.FormController

    MPSL.OptionControl = can.Control.extend(
        {
            defaults: {}
        },
        {
            name: '',
            group: '',

            element: null,
            wrapper: null,
            form: null,
            macros_buttons: null,

            controls: [],
            helpers: {},
            actions: {},

            is_nested: false,
            parent_option: {},
            child_options: {},

            vars_group: 'page',
            show_error_type: 'popup',

            is_multivalue: false,

            is_valid: false,
            is_visible: true, // Previously was "enabled"
            has_disabled_attr: true, // Previously was "disalbedEnabled"

            setup: function (element, options) {
                this._super(element, options);
                this.setElement(element);
            },

            init: function (element, settings) {
                // "Outer" option wrapper; contains labels, .mpsl-option etc.
                this.wrapper = element.closest('.mpsl-option-wrapper');
                this.form = settings.form;
                this.macros_buttons = element.parent('.mpsl-option-wrapper').find('.mpsl-do-macros-thing');

                this.macros_buttons.on('click', this.proxy('doMacrosThing'));

                // TODO: Rewrite "vars_group" recognition.
                if (element.closest('.mpsl_layers_wrapper').length) {
                    this.vars_group = 'layer';
                } else if (element.closest('.mpsl-style-editor-wrapper').length) {
                    this.vars_group = 'preset';
                } else {
                    this.vars_group = 'page';
                }

                this.name = element.attr('data-name');
                this.group = element.closest('[data-group]').attr('data-group');

                this.helpers = {};
                this.actions = {};

                this.is_nested = settings.is_nested;
                this.parent_option = settings.parent_option;
                this.child_options = {};
                if (this.is_nested) {
                    this.parent_option.addChild(this);
                }

                if (settings.hasOwnProperty('show_error_type')) {
                    this.show_error_type = settings.show_error_type;
                }

                if (!this.is_nested) {
                    this.settings = MPSL.Vars[this.vars_group].grouped_options[this.group]['options'][this.name];
                    this.is_multivalue = MPSL.Vars[this.vars_group].grouped_options[this.group]['options'][this.name].hasOwnProperty('list');
                } else {
                    this.settings = MPSL.Vars[this.vars_group].grouped_options[this.group]['options'][this.parent_option.getName()]['options'][this.name];
                }

                this.is_valid = true;
                this.is_visible = true;
                this.has_disabled_attr = true;
            },

            setElement: function (element) {
                this.element = element.find('input');
            },

            addChild: function (option) {
                this.child_options[option.getName()] = option;
            },

            getName: function () {
                return this.name;
            },

            getLabel: function () {
                if (this.settings.hasOwnProperty('label') && this.settings.label) {
                    return this.settings.label;
                } else if (this.settings.hasOwnProperty('label2') && this.settings.label2){
                    return this.settings.label2;
                } else {
                    return this.getName();
                }
            },

            getDependency: function (type) {
                return this.settings[type];
            },

            getGroup: function () {
                return this.group;
            },

            getValue: function () {
                return this.element.val();
            },

            setValue: function (value, silent) {
                this.element.val(value);

                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                } else {
                    can.trigger(this.element, 'silentChange');
                }
            },
            showError: function (error) {
                MPSL.Functions.showMessage(error, MPSL.ERROR);
            },

            hideError: function () {},

            verifyOption : function () {
                return true;
            },

            isValid: function () {
                return this.is_valid;
            },

            isVisible: function () {
                return this.is_visible;
            },

            hasDisabledAttr: function () {
                return this.has_disabled_attr;
            },

            isEnabled: function () {
                return (this.is_visible && this.has_disabled_attr);
            },

            isEnabledBy: function (type) {
                if (typeof type === 'undefined') {
                    type = 'visibility';
                }
                switch (type) {
                    case 'visibility': return this.is_visible; break;
                    case 'disabled': return this.has_disabled_attr; break;
                }
                return false;
            },

            enable: function () {
                this.enableBy('visibility');
            },

            enableBy: function (type) {
                if (typeof type === 'undefined') {
                    type = 'visibility';
                }

                switch (type) {
                    case 'visibility':
                        this.is_visible = true;
                        this.wrapper.removeClass('mpsl-dependency-hide');
                        break;
                    case 'disabled':
                        this.has_disabled_attr = true;
                        this.element.removeAttr('disabled');
                        break;
                }
            },

            disable: function () {
                this.disableBy('visibility');
            },

            disableBy: function (type) {
                if (typeof type === 'undefined') {
                    type = 'visibility';
                }

                switch (type) {
                    case 'visibility':
                        this.is_visible = false;
                        this.wrapper.addClass('mpsl-dependency-hide');
                        break;
                    case 'disabled':
                        this.has_disabled_attr = false;
                        this.element.attr('disabled', 'disabled');
                        break;
                }
            },

            setForm: function (form) {
                this.form = form;
            },

            addHelper: function (name, ctrl) {
                this.helpers[name] = ctrl;
            },

            addAction: function (name, action) {
                this.actions[name] = {};
                var self = this;
                $.each(action, function (option_name, option_value) {
                    self.actions[name][option_name] = {};
                    self.actions[name][option_name].ctrl = self.form.option_controllers[option_name];
                    self.actions[name][option_name].value = option_value;
                });
            },

            fixValueType: function (value) {
                return value;
            },

            doMacrosThing: function (event) {
                var element = $(event.target);
                var macros = element.attr('data-macros');
                this.setValue(macros);
            },

            'change': function () {
                this.form.showDependencedOptions(this.getName());
                if (this.verifyOption()) {
                    this.form.render();
                }
            },

            'silentChange': function() {}
        }
    ); // End of MPSL.OptionControl

    MPSL.TextControl = MPSL.OptionControl.extend(
        {},
        {
            verifyOption: function () {
                if (this.settings.hasOwnProperty('required') && this.settings.required == true) {
                    // Required text input should not be empty
                    if (this.getValue() === '') {
                        this.showError(MPSL.Vars.lang['empty_input_error'].replace('%s', this.getLabel()));
                        return false;
                    }
                } else {
                    this.hideError();
                }
                return true;
            }
        }
    ); // End of MPSL.TextControl

    MPSL.NumberControl = MPSL.OptionControl.extend(
        {},
        {
            verifyOption: function () {
                var value = this.getValue();

                // If the value is empty but not required then all OK; otherwise
                // the option is not proper
                if (!value) {
                    if (!this.settings.hasOwnProperty('required') || !this.settings.required) {
                        return true;
                    } else {
                        this.showError(MPSL.Vars.lang['empty_input_error'].replace('%s', this.getLabel()));
                        return false;
                    }
                }

                var pattern_float = new RegExp(/^[+-]?(\d+[.])?\d+$/);
                if (!pattern_float.test(value)) {
                    this.showError(MPSL.Vars.lang['validate_digitals_only'].replace('%s', this.getLabel()));
                    return false;
                }

                if (this.settings.hasOwnProperty('min')) {
                    if (value < this.settings.min) {
                        this.showError(MPSL.Vars.lang['validate_less_min'].replace('%s', this.getLabel()).replace('%d', this.settings.min));
                        return false;
                    }
                }

                if (this.settings.hasOwnProperty('max')) {
                    if (value > this.settings.max) {
                        this.showError(MPSL.Vars.lang['validate_greater_max'].replace('%s', this.getLabel()).replace('%d', this.settings.max));
                        return false;
                    }
                }

                // Otherwise
                this.hideError();
                return true;
            },

            'input keydown': function (element, event) {
                var old_value = this.getValue();
                old_value = (!isNaN(parseFloat(old_value)) && isFinite(old_value) ? parseFloat(old_value) : 0);

                if (event.keyCode == '38') { // Up button
                    this.setValue(old_value + 1);
                } else if (event.keyCode == '40') { // Down button
                    this.setValue(old_value - 1);
                }
            }
        }
    ); // End of MPSL.NumberControl

    MPSL.TextareaControl = MPSL.OptionControl.extend(
        {},
        {
            setElement: function (element) {
                this.element = element.find('textarea');
            },

            doMacrosThing: function (event) {
                var element = $(event.target);
                var macros = element.attr('data-macros');
                var new_text = this.getValue() + macros;
                this.setValue(new_text);
            }
        }
    ); // End of MPSL.TextareaControl

    MPSL.CodeMirrorControl = MPSL.TextareaControl.extend(
        {
            refreshEditors: function (container) {
                if (typeof container === 'undefined') {
                    container = $('body');
                }

                if (container.length) {
                    if (container.hasClass('mpsl-option-codemirror')) {
                        container.find('.CodeMirror').get(0).CodeMirror.refresh();
                    } else {
                        container.find('.mpsl-option-codemirror .CodeMirror').each(function () {
                            this.CodeMirror.refresh();
                        });
                    }
                }
            }
        },
        {
            codemirror_element: null,
            codemirror_object: null,

            init: function (element, options) {
                this._super(element, options);

                this.codemirror_object = CodeMirror.fromTextArea(this.element.get(0), {
                    lineNumbers: true,
                    mode: (typeof this.settings.mode !== 'undefined' ? this.settings.mode : null)
                });
                this.codemirror_element = this.element.next();
                this.codemirror_object.on('change', this.proxy('onCodemirrorChange'));
            },

            // TODO: Check it and maybe fix it.
            // Update on every change
            onCodemirrorChange: function () {
                if (this.codemirror_element.is(":visible")) {
                    var self = this;
                    var timeout = setTimeout(function () {
                        self.element.change();
                        clearTimeout(timeout);
                    }, 1);
                }
            },

            getValue: function () {
                return this.codemirror_object.getValue();
            },

            setValue: function (value, silent) {
                this.codemirror_object.setValue(value);
                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            },

            enableBy: function (type) {
                if (type === 'disabled') {
                    this.codemirror_object.setOption('readOnly', false);
                } else {
                    this._super(type);
                }
            },

            disableBy: function (type) {
                if (type === 'disabled') {
                    this.codemirror_object.setOption('readOnly', 'nocursor');
                } else {
                    this._super(type);
                }
            }
        }
    ); // End of MPSL.CodeMirrorControl

    MPSL.TinyMCEControl = MPSL.OptionControl.extend(
        {},
        {
            ready: null,
            editor_wrapper: null,
            editor_area: null,

            init: function (element, options) {
                this._super(element, options);

                this.editor_wrapper = element.find('.wp-editor-wrap');
                this.editor_area = element.find('#mpsl-text');

                this.initTinyMCE();
            },

            initTinyMCE: function () {
                var id = MPSL.Vars.settings.alt_prefix + this.name;
                var ready_defer = $.Deferred();
                this.ready = ready_defer.promise();

                if (tinyMCE.majorVersion === '4') {
                    tinyMCE.on('AddEditor', function (args) {
                        if (args.editor.id === id) {
                            args.editor.on('init', function (editor) {
                                ready_defer.resolve(args.editor);
                            });
                        }
                    });
                } else {
                    tinyMCE.onAddEditor.add(function (mce, editor) {
                        if (editor.editorId === id) {
                            editor.onInit.add(function (ed) {
                                ready_defer.resolve(ed);
                            });
                        }
                    });
                }

                var self = this;
                $.when(self.ready).then(function (editor) {
                    self.editor = editor;
                    self.element.find('.wp-switch-editor.switch-tmce').on('click', self.proxy('change'));
                    self.editor_area.on('change keyup paste blur', self.proxy('change'));

                    if (tinyMCE.majorVersion === '4') {
                        editor.on('keyup change', self.proxy('change'));
                        self.editor.addCommand('mpsl_post_macros', self.proxy('change'));
                    } else {
                        self.editor.onKeyUp.add(self.proxy('change'));
                        self.editor.onChange.add(self.proxy('change'));
//                        self.editor.onUndo.add(self.proxy('change'));
//                        self.editor.onRedo.add(self.proxy('change'));
//                        self.editor.onPaste.add(self.proxy('change'));
                        self.editor.addCommand('mpsl_post_macros', self.proxy('change'));
                    }
                });
            },

//            'change': function (element) {
//                // Skip control wrapper (textarea change)
//                if (element && this.element[0] !== element[0]) {
//                    this._super();
//                }
//            },

            getValue: function () {
                if (this.editor) {
                    if (this.editor_wrapper.hasClass('tmce-active')) {
                        return this.editor.getContent();
                    } else {
                        return this.editor_area.val();
                    }
                } else {
                    return '';
                }
            },

            setValue: function (content, silent) {
                if (this.editor) {
                    this.editor.undoManager.clear();

                    if (this.editor_wrapper.hasClass('tmce-active')) {
                        this.editor.setContent(content);
                    } else {
                        this.editor_area.val(content);
                    }

                    if (MPSL.Functions.isNot(silent)) {
                        this.element.change();
                    }
                }
            },

            setElement: function (element) {
                this.element = element;
            }
        }
    ); // End of MPSL.TinyMCEControl

    MPSL.CheckboxControl = MPSL.OptionControl.extend(
        {},
        {
            getValue: function () {
                if (this.element.length > 1) {
                    return this.element.filter(':checked').map(function (_, element) {
                        return $(element).val();
                    }).get();
                } else {
                    return this.element.is(':checked');
                }
            },

            setValue: function (value, silent) {
                this.element.attr('checked', false);

                if ($.isArray(value)) {
                    var self = this;
                    $.each(value, function (key, value) {
                        self.element.filter('[value="' + value + '"]').attr('checked', true);
                    });
                } else {
                    this.element.attr('checked', value);
                }

                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            },

            fixValueType: function (value) {
                if (this.is_multivalue) {
                    return value;
                } else {
                    // TODO: Test this.
                    switch (typeof(value)) {
                        case 'string': return (value == true); break;
                        case 'boolean': return value; break;
                        default: return Boolean(value); break;
                    }
                }
            }
        }
    ); // End of MPSL.CheckboxControl

    MPSL.SwitcherControl = MPSL.CheckboxControl.extend(
        {},
        {
            getValue: function () {
                var value = this.element.filter(':checked').val(); // "on"/"off"
                return (value === 'on' ? true : false);
            },

            setValue: function (value, silent) {
                var state = (value ? 'on' : 'off');
                this.element.attr('checked', false);
                this.element.filter('[value="' + state + '"]').attr('checked', true);

                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            }
        }
    ); // End of MPSL.SwitcherControl

    MPSL.SelectControl = MPSL.OptionControl.extend(
        {},
        {
            dynamic_list: {},
            related_ctrl: {},
            related_element: {},
            list_attr_settings: {},
//            related_change_binded: false,

            init: function (element, options) {
                this._super(element, options);

                this.dynamic_list = {};
                this.related_ctrl = {};
                this.related_element = {};
//                this.related_change_binded = false;

                var options = MPSL.Vars[this.vars_group].options[this.getName()];
                this.list_attr_settings = (typeof options === 'undefined' ? null : {'data-variants': {'delimiter': ',', 'type': 'split'}});

                can.bind.call(this.form, 'initFinish', this.proxy('initFinish'));
            },

            initFinish: function () {
                if (this.settings.hasOwnProperty('dynamicList')) {
                    this.dynamic_list = this.settings.dynamicList;
                    this.related_ctrl = this.helpers[this.dynamic_list.parameter];
                    this.related_element = this.related_ctrl.element;

                    this.related_element.on('change silentChange', this.proxy('onRelatedChange'));
                    /** @todo: Fix this */
                    /** @todo: Call only once, after all controls inited */
                    can.trigger(this.related_element, 'change silentChange');
//                    this.related_ctrl.bindRelatedChange(this);
                }
            },

//            bindRelatedChange: function(dependsControl) {
////                if (!this.related_change_binded) {
//                    this.element.on('change silentChange', dependsControl.proxy('onRelatedChange'));
//                    can.trigger(this.element, 'silentChange');
////                    this.related_change_binded = true;
////                }
//            },

            onRelatedChange: function (event) {
                var element = $(event.target),
                    selected = element.find('option:selected'),
                    option_fields = [];

                if (selected.length) {
                    var values = selected.attr(this.dynamic_list.attr),
                        attr_settings = this.related_ctrl.list_attr_settings[this.dynamic_list.attr];

                    values = this.parseAttr(values, attr_settings);

                    $.each(values, function(index, value) {
                        option_fields.push($('<option></option>', {
                            'value': value.key,
                            'text': value.value
                        }));
                    });
                }

                this.element.html(option_fields);
            },

            parseAttr: function (values, attr_settings) {
                if (typeof values === 'undefined' || !values) {
                    return [];
                }

                switch (attr_settings['type']) {
                    case 'split':
                        values = values.split(attr_settings['delimiter']);
                        $.each(values, function(key, value) {
                            if (value) {
                                values[key] = {
                                    'key': value,
                                    'value': (typeof value === 'string' ? (value[0].toUpperCase() + value.substr(1)) : value)
                                };
                            }
                        });
                        break;
                    case 'json':
                        values = JSON.parse(values);
                        break;
                }

                return values;
            },

            setElement: function (element) {
                this.element = element.find('select');
            }
        }
    ); // End of MPSL.SelectControl

    MPSL.FontPickerControl = MPSL.SelectControl.extend(
        {},
        {
            laded_class: 'mpsl-font-loaded',

            init: function (element, options) {
                this._super(element, options);
                this.element.on('silentChange', this.proxy('onSilentChange'));
            },

            'change': function () {
                this._super();
                this.loadFont(this.element.find('option:selected'));
            },

            'onSilentChange': function () {
                this.loadFont(this.element.find('option:selected'));
            },

            loadFont: function (option_element) {
                if (option_element.length && !option_element.hasClass(this.laded_class)) {
                    var font_name = option_element.val(),
                        variants = option_element.attr('data-variants');

                    if (typeof variants !== 'undefined') {
                        var _variants = [];
                        variants = this.parseAttr(variants, this.list_attr_settings['data-variants']);

                        $.each(variants, function (index, variant) {
                            // Normal
                            variant.key = (variant.key === 'normal' ? 'regular' : variant.key);
                            _variants.push(variant.key);

                            // Italic
                            variant.key = (variant.key === 'regular' ? 'italic' : variant.key + 'italic');
                            _variants.push(variant.key);
                        });

                        _variants = _variants.join(',');

                        var link = $('<link />', {
                            'rel': 'stylesheet',
                            'type': 'text/css',
                            'class': 'mpsl-admin-font-link',
                            'href': '//fonts.googleapis.com/css?family=' + font_name + (_variants ? ':' + _variants : '')
                        });
                        $('head').append(link);

                        option_element.addClass(this.laded_class);
                    }
                }
            }
        }
    ); // End of MPSL.FontPickerControl

    MPSL.SizeSelectControl = MPSL.SelectControl.extend(
        {},
        {
            'change': function () {
                this._super();

                var id = this.form.getOption('image_id', false);
                if (id !== false) {
                    var image_size = this.getValue();
                    var new_img_url = MPSL.Functions.productImgUrl(id, image_size);
                    this.form.setOption('image_url', new_img_url);
                }
            }
        }
    ); // End of MPSL.SizeSelectControl

    MPSL.SelectListControl = MPSL.OptionControl.extend(
        {},
        {
            setElement: function (element) {
                this.element = element.find('ul');
            },

            setValue: function (value) {
                this.setActive(this.element.find('li[data-value="' + value +'"]'));
            },

            getValue: function () {
                return this.element.find('li.active').attr('data-value');
            },

            setActive: function (element) {
                element.addClass('active').siblings().removeClass('active');
            },

            'li click' : function (element) {
                this.setActive(element);
                this.element.change();
            }
        }
    ); // End of MPSL.SelectListControl

    MPSL.RadioGroupControl = MPSL.OptionControl.extend(
        {},
        {
            getValue: function () {
                return this.element.filter(':checked').val();
            },

            setValue: function (value, silent) {
                this.element.attr('checked', false);
                this.element.filter('[value="' + value + '"]').attr('checked', true);
                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            }
        }
    ); // End of MPSL.RadioGroupControl

    MPSL.RadioButtonsControl = MPSL.RadioGroupControl.extend(
        {},
        {
            init: function (element, options) {
                this._super(element, options);
                this.element.parent().buttonset();
            },

            setValue: function (value, silent) {
                this.element.attr('checked', false).next('label').removeClass('button-primary');
                this.element.filter('[value="' + value + '"]').attr('checked', true).next('label').addClass('button-primary');
                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            },

            'change' : function (event, element) {
                MPSL.OptionControl.prototype.change.apply(this, arguments);
                this.element.next('label').removeClass('button-primary');
                this.element.filter(':checked').next('label').addClass('button-primary');
            }
        }
    ); // End of MPSL.RadioButtonsControl

    MPSL.ButtonGroupControl = MPSL.OptionControl.extend(
        {},
        {
            setElement: function (element) {
                this.element = element.find('button');
            },

            getValue: function () {
                return this.element.filter('.active').val();
            },

            setValue: function (value, silent) {
                this.element.removeClass('active')
                            .filter('[value="' + value + '"]')
                            .addClass('active');

                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            },

            '.button click': function (element) {
                this.element.removeClass('active');
                element.addClass('active');
                this.change();
            }
        }
    ); // End of MPSL.ButtonGroupControl

    MPSL.ActionGroupControl = MPSL.OptionControl.extend(
        {},
        {
            getValue: function () {
                return '';
            },

            setValue: function(value, silent) {
                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            },

            '.actions > a click': function (element) {
                $.each(this.actions[element.attr('value')], function (key, option) {
                    option.ctrl.setValue(option.value);
                });
                this.change();
            }
        }
    ); // End of MPSL.ActionGroupControl

    MPSL.AliasControl = MPSL.OptionControl.extend(
        {},
        {
            verifyOption : function () {
                var result = false;
                var value = this.getValue();

                var pattern = new RegExp(/^[-_a-zA-Z0-9]{1,250}$/);
                if (!pattern.test(value)) {
                    this.showError(MPSL.Vars.lang['alias_not_valid']);
                    result = false;
                } else {
                    result = true;
//                    result = MPSL.Functions.checkAliasExists(value);
                }

                return result;
            }
        }
    ); // End of MPSL.AliasControl

    MPSL.ImageLibraryControl = MPSL.OptionControl.extend(
        {},
        {
            frame: null,

            init: function (element, args) {
                this._super(element, args);

                var options = {
                    type: 'image',
                    id: 'mpsl-add-image-' + this.name
                };

                this.frame = MPSL.MediaLibrary.create(options);
                this.frame.on('open', this.proxy('onFrameOpen'));
                this.frame.on('select', this.proxy('onFrameSelect'));
                this.frame.on('close', this.proxy('onFrameClose'));
            },

            onFrameOpen: function () {
                var id = this.getValue();
                if (id !== '') {
                    var type = this.form.getOption('image_type', 'library');
                    this.frame.selectItem(id, type);
                } else {
                    this.frame.deselectItem();
                }
                this.frame.openAppropriateTab();
            },

            onFrameSelect: function () {
                var selection = this.frame.getSelection();
                // Set "image_id"
                this.setValue(selection.atts.id, true);
                // Set "image_url"
                var image_url = selection.atts.url;
                if (selection.is_product) {
                    var image_type = this.form.getOption('image_size', MPSL.FULLSIZE);
                    image_url = MPSL.Functions.productImgUrl(selection.atts.id, image_type);
                }
                $.each(this.helpers, function () {
                    this.setValue(image_url);
                });
                // Set "image_type"
                this.form.setOption('image_type', selection.type);
            },

            onFrameClose: function () {},

            '.mpsl-option-library-image-btn click': function () {
                this.frame.openLibrary();
            },

            '.mpsl-option-library-image-remove click': function () {
                this.setValue('', true);
                $.each(this.helpers, function () {
                    this.setValue('');
                });
                this.form.setOption('image_type', 'library');
            }
        }
    ); // End of MPSL.ImageLibraryControl

    MPSL.VideoLibraryControl = MPSL.OptionControl.extend(
        {},
        {
            frame: null,

            init: function (element, args) {
                this._super(element, args);

                var options = {
                    type: 'video',
                    id: 'mpsl-add-video-' + this.name,
                };

                this.frame = MPSL.MediaLibrary.create(options);
                this.frame.on('open', this.proxy('onFrameOpen'));
                this.frame.on('select', this.proxy('onFrameSelect'));
                this.frame.on('close', this.proxy('onFrameClose'));
            },

            onFrameOpen: function () {
                var id = this.getValue();
                if (id !== '') {
                    this.frame.selectItem(id);
                } else {
                    this.frame.deselectItem();
                }
            },

            onFrameSelect: function () {
                var selection = this.frame.getSelection();
                this.setValue(selection.atts.id);
            },

            onFrameClose: function () {},

            '.mpsl-option-library-video-btn click': function () {
                this.frame.openLibrary();
            }
        }
    ); // End of MPSL.VideoLibraryControl

    MPSL.ImageUrlControl = MPSL.OptionControl.extend(
        {},
        {
            '.mpsl-option-image-load-btn click': function () {
                this.change();
            }
        }
    ); // End of MPSL.ImageUrlControl

    MPSL.ShortcodeControl = MPSL.OptionControl.extend({}, {}); // End of MPSL.ShortcodeControl

    MPSL.HiddenControl = MPSL.OptionControl.extend({}, {}); // End of MPSL.HiddenControl

    MPSL.AlignTableControl = MPSL.OptionControl.extend(
        {},
        {
            table: null,

            init: function (element, options) {
                this._super(element, options);
                this.table = element.find('.mpsl-align-table');
            },

            setElement: function (element) {
                this.element = element;
            },

            '.mpsl-align-table a click': function (element) {
                var hor = element.attr('data-hor'),
                    vert = element.attr('data-vert'),
                    x = this.child_options['offset_x'].settings.default,
                    y = this.child_options['offset_y'].settings.default;

                this.child_options['hor_align'].setValue(hor, true);
                this.child_options['vert_align'].setValue(vert, true);
                this.child_options['offset_x'].setValue(x, true);
                this.child_options['offset_y'].setValue(y, true);

                this.selectBullet(hor, vert);

                this.change();
            },

            getValue: function() {
                return {
                    'hor': this.child_options['hor_align'].getValue(),
                    'vert': this.child_options['vert_align'].getValue()
                };
            },

            setValue: function (value, silent) {
                this.selectBullet(value.hor, value.vert);
                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            },

            selectBullet: function (hor, vert) {
                this.table
                    .find('a.selected')
                        .removeClass('selected')
                        .end()
                    .find('a[data-hor="' + hor + '"][data-vert="' + vert + '"]')
                        .addClass('selected');
            }
        }
    ); // End of MPSL.AlignTableControl

    MPSL.DatePicker = MPSL.OptionControl.extend(
        {},
        {
            init: function (element, options) {
                this._super(element, options);
                this.element.datepicker({
                    dateFormat : 'dd-mm-yy 00:00'
                });
            },

            verifyOption : function () {
                var value = this.getValue();

                if (value === '') {
                    return true;
                }

                var min_day = 1,
                    max_day = 31,
                    min_month = 1,
                    max_month = 12,
                    min_year = 1970, // http://php.net/manual/en/function.strtotime.php#refsect1-function.strtotime-notes
                    max_year = 2037, // approximately 19 Jan 2038 03:14:07 UTC
                    min_hour = 0,
                    max_hour = 23,
                    min_minute = 0,
                    max_minute = 59;

                var date_time_pattern = new RegExp(/^(\d{2})-(\d{2})-(\d{4})\s(\d{2}):(\d{2})$/), // 1 - day, 2 - month, 3 - year, 4 - hour, 5 - minute
                    date_pieces = value.match(date_time_pattern);

                // Check format
                if (date_pieces === null) {
                    this.showError(MPSL.Vars.lang['validate_date_format'].replace('%s', this.getLabel()));
                    return false;
                }

                // Check day approximately  range
                if (date_pieces[1] < min_day || date_pieces[1] > max_day) {
                    this.showError(MPSL.Vars.lang['validate_day'].replace('%s', this.getLabel()).replace('%day', date_pieces[1]));
                    return false;
                }

                if (date_pieces[2] < min_month || date_pieces[2] > max_month) {
                    this.showError(MPSL.Vars.lang['validate_month'].replace('%s', this.getLabel()).replace('%month', date_pieces[2]));
                    return false;
                }

                if (date_pieces[3] < min_year || date_pieces[3] > max_year) {
                    this.showError(MPSL.Vars.lang['validate_year'].replace('%s', this.getLabel()).replace('%year', date_pieces[3]).replace('%min', min_year).replace('%max', max_year));
                    return false;
                }

                if (date_pieces[4] < min_hour || date_pieces[4] > max_hour) {
                    this.showError(MPSL.Vars.lang['validate_hour'].replace('%s', this.getLabel()).replace('%hour', date_pieces[4]));
                    return false;
                }

                if (date_pieces[5] < min_minute || date_pieces[5] > max_minute) {
                    this.showError(MPSL.Vars.lang['validate_minute'].replace('%s', this.getLabel()).replace('%minute', date_pieces[5]));
                    return false;
                }

                return true;
            }
        }
    ); // End of MPSL.DatePicker

    MPSL.StyleEditorControl = MPSL.OptionControl.extend(
        {},
        {
            editor_element: null,
            global_list_element: null,

            init: function (element, options) {
                this._super(element, options);

                this.editor_element = $('#mpsl-style-editor-modal');
                this.input = element.children('.mpsl-layer-style-value');
                this.list = element.children('.mpsl-layer-style-list');
                this.global_list_element = $('#mpsl-layer-preset-list-child-wrapper');

                this.addPreset('', MPSL.Vars.lang['none'], 'head');

                // NOTE: Don't change the order of init widgets (options, presetList, ...)!
                can.bind.call(this.editor_element, 'create', this.proxy('onEditorCreate'));
                can.bind.call(this.global_list_element, 'presetAdd', this.proxy('onPresetAdd'));
                can.bind.call(this.global_list_element, 'presetRemove', this.proxy('onPresetRemove'));
                can.bind.call(this.global_list_element, 'presetUpdate', this.proxy('onPresetUpdate'));
            },

            onEditorCreate: function (event, editor) {
                editor.linkControl(this);
            },

            addPreset: function (name, label, group) {
                if (!group) {
                    return;
                }

                var option = $('<option></option>', {
                    'value': name,
                    'text': label
                });

                if (group === 'head') {
                    this.list.find('optgroup:first').before(option);
                } else {
                    this.list.find('.mpsl-layer-style-list-group-' + group).append(option);
                }
            },

            onPresetAdd: function (event, data) {
                var group = null;
                switch (data.preset.getType()) {
                    case MPSL.LayerPreset.PRIVATE: group = 'head'; break;
                    case MPSL.LayerPreset.CUSTOM: group = 'custom'; break;
                    case MPSL.LayerPreset.DEFAULT: group = 'default'; break;
                }

                if (!group) {
                    return;
                }

                this.addPreset(
                    data.name,
                    (data.isPrivate ? MPSL.Vars.lang['layer_preset_private_name'] : data.label),
                    group
                );
            },

            onPresetRemove: function (event, data) {
                this.list.find('[value="' + data.name + '"]').remove();
            },

            onPresetUpdate: function (event, data) {
                this.list.find('[value="' + data.name + '"]').text(data.label);
            },

            selectPreset: function (name) {
                this.list.find('[value="' + name + '"]').attr('selected', 'selected');
            },

            '> .mpsl-edit-layer-style click': function () {
                this.editor_element.dialog('open');
            },

            /* mpslReplace */
            '> .mpsl-remove-layer-style click': function () {
                this.setValue('');
            },

            '> .mpsl-layer-style-list change': function () {
                var name = this.list.find('option:selected').val();
                this.setValue(name);
                can.trigger(this.global_list_element, 'presetSelect', [name]);
            },
            /* endMpslReplace */

            getValue: function () {
                return this.input.val();
            },

            setValue: function (value, silent) {
                this.input.val(value);
                this.selectPreset(value);

                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            }
        }
    ); // End of MPSL.StyleEditorControl

    MPSL.ColorPickerControl = MPSL.OptionControl.extend(
        {},
        {
            color_picker_element: null,

            init: function (element, options) {
                this._super(element, options);

                var append_to = $();
                if (this.vars_group === 'preset') {
                    append_to = this.element.closest('.mpsl-style-editor-content');
                } else {
                    append_to = this.element.closest('.mpsl-option-wrapper')
                }

                this.color_picker_element = this.element.spectrum({
                    appendTo: append_to,
                    cancelText: MPSL.Vars.lang['cancel'],
                    chooseText:  MPSL.Vars.lang['choose'],
                    color: '',
                    allowEmpty: true,
                    showInput: true,
                    showAlpha: true,
                    preferredFormat: 'hex3'
                });
            },

            getValue: function () {
                var spectrum = this.color_picker_element.spectrum('get');
                return (spectrum ? spectrum.toRgbString() : '');
            },

            setValue: function (value, silent) {
                this.color_picker_element.spectrum('set', value);
                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                }
            },

            enableBy: function (type) {
                if (type === 'disabled') {
                    this.color_picker_element.spectrum('enable');
                } else {
                    this._super(type);
                }
            },

            disableBy: function (type) {
                if (type === 'disabled') {
                    this.color_picker_element.spectrum('disable');
                } else {
                    this._super(type);
                }
            }
        }
    ); // End of MPSL.ColorPickerControl

    MPSL.MultipleControl = MPSL.OptionControl.extend(
        {},
        {
            setElement: function (element) {
                this.element = element.find('textarea');
            },

            getValue: function () {
                return JSON.parse(this._super());
            },

            setValue: function (value, silent) {
                this._super(JSON.stringify(value), silent);
            }
        }
    ); // End of MPSL.MultipleControl

    MPSL.TextShadowControl = MPSL.OptionControl.extend(
        {},
        {
            init: function (element, options) {
                this._super(element, options);
            },

            setElement: function (element) {
                this.element = element;
            },

            getValue: function () {
                var hor_len = this.child_options['text_shadow_hor_len'].getValue(),
                    vert_len = this.child_options['text_shadow_vert_len'].getValue(),
                    radius = this.child_options['text_shadow_radius'].getValue(),
                    color = this.child_options['text_shadow_color'].getValue();

                if (hor_len === '' && vert_len === '' && radius === '' && color === '') {
                    return '';
                }

                hor_len = (hor_len ? hor_len : 0) + this.child_options['text_shadow_hor_len'].settings.unit;
                vert_len = (vert_len ? vert_len : 0) + this.child_options['text_shadow_vert_len'].settings.unit;
                radius = (radius ? radius : 0) + this.child_options['text_shadow_radius'].settings.unit;

                return [hor_len, vert_len, radius, color].join(' ');
            },

            setValue: function (value, silent) {
                if (MPSL.Functions.isNot(silent)) {
                    this.element.change();
                } else {
                    can.trigger(this.element, 'silentChange');
                }
            }
        }
    ); // End of MPSL.TextShadowControl

    MPSL.AnimationControl = MPSL.OptionControl.extend(
        {},
        {
            type: 'start', // "start"/"end"

            editor: null,
            scene: null,
            preview_wrapper: null,
            preview: null,

            animation: null,
            duration: null,
            timing_function: null,

            init: function (element, options) {
                this._super(element, options);

                this.type = this.settings.animation_type;

                this.editor = element.find('.mpsl-animation-editor');
                this.scene = this.editor.find('.mpsl-animation-scene');
                this.preview_wrapper = this.scene.find('.mpsl-animation-preview-wrapper');
                this.preview = this.preview_wrapper.find('.mpsl-animation-preview');

                can.bind.call(this.preview, 'animationend', this.proxy('resetPreviewClasses'));
                can.bind.call(this.form, 'initFinish', this.proxy('initFinish'));
            },

            initFinish: function () {
                // Get cloned options
                this.animation = this.getChildOption('animation'); // "%type%_animation_clone"
                this.duration = this.getChildOption('duration'); // "%type%_duration_clone"
                this.timing_function = this.getChildOption('timing_function'); // "%type%_timing_function_clone"

                var self = this;

                this.editor.dialog({
                    resizable: false,
                    draggable: false,
                    autoOpen: false,
                    modal: true,
                    width: 1000,
                    height: $(window).height()/1.7,
                    title: MPSL.Vars.lang['animation_modal'],
                    closeText: '',
                    dialogClass: 'mpsl-animation-editor-dialog',
                    appendTo: '#content', // "#content.bootstrap", we need ".bootstrap" to get PS Bootstrap styles
                    position: {'my': 'center', 'at': 'center', 'of': window},
                    open: function (e) {
                        $('body').css('overflow', 'hidden'); // Hide main scrollbar
                        self.resizeDialog();
                        self.playAnimation();
                    },
                    beforeClose: function () {
                        $('body').css('overflow', 'inherit'); // Show main scrollbar
                    }
                });

                // "Close"/"Apply" buttons click
                this.editor.find('.mpsl-animation-apply').on('click', this.proxy('applyValues'));
                this.editor.find('.mpsl-animation-close').on('click', function () {
                    self.editor.dialog('close');
                });

                // "Play" button click
                this.editor.find('.mpsl-play-animation').on('click', this.proxy('playAnimation'));

                // On [cloned] options change
                this.animation.on('change', this.proxy('playAnimation'));
                this.duration.on('change', this.proxy('playAnimation'));
                this.timing_function.on('change', this.proxy('playAnimation'));

                $(window).on('resize', this.proxy('onWindowResize'));
            },

            getChildOption: function (name) {
                var full_name = this.type + '_' + name + '_clone'; // "start_duration_clone", "end_animation_clone" etc.
                return this.child_options[full_name];
            },

            playAnimation: function () {
                var duration = this.duration.getValue();
                var timing_function = this.timing_function.getValue();
                var animation_class = this.animation.getValue();

                if (animation_class == 'auto') {
                    // Automatically generate end animation class using start animation class
                    animation_class = this.form.getOption('start_animation', 'fadeIn');
                    animation_class = animation_class.replace('In', 'Out');
                }

                this.preview.css({
                    '-webkit-animation-duration': duration + 'ms',
                    'animation-duration': duration + 'ms'
                });
                this.resetPreviewClasses();
                this.preview.addClass('mpsl-' + animation_class + ' mpsl-' + timing_function);
            },

            applyValues: function () {
                if (!this.verifyCloneValues()) {
                    return false;
                }

                $.each(this.child_options, function (_, option) {
                    $.each(option.helpers, function (_, helper_option) {
                        helper_option.setValue(option.getValue());
                    });
                });

                this.editor.dialog('close');
            },

            setElement: function (element) {
                this.element = element;
            },

            getValue: function () {
                return false;
            },

            setValue: function() {},

            change: function() {},

            verifyCloneValues: function () {
                var ok = true;
                $.each(this.child_options, function (_, option) {
                    if (!option.verifyOption()) {
                        ok = false;
                        return false;
                    }
                });
                return ok;
            },

            verifyOriginalValues: function () {
                var ok = true;
                $.each(this.child_options, function (_, option) {
                    $.each(option.helpers, function (_, helper_option) {
                        if (!helper_option.verifyOption()) {
                            ok = false;
                            return false;
                        }
                    });
                    if (!ok) {
                        return false;
                    }
                });
                return ok;
            },

            onWindowResize: function () {
                if (this.editor.dialog('isOpen')) {
                    this.resizeDialog();
                }
            },

            resizeDialog: function () {
                var dialog_height = $(window).height()/1.7;
                if (dialog_height > 200) { // Minimum height - 200px
                    this.editor.dialog('option', 'height', dialog_height);
                    this.editor.dialog('option', 'position', {'my': 'center', 'at': 'center', 'of': window});
                    // Update line height of the preview item (<h1>), it must be
                    // half of the preview wrapper
                    var line_height = this.scene.height()/2;
                    this.preview.css('line-height', line_height + 'px');
                    // Update [negative] margin of the preview wrapper, must be
                    // half of it's size
                    var margin_top = this.preview_wrapper.height()/2;
                    var margin_left = this.preview_wrapper.width()/2;
                    this.preview_wrapper.css({
                        'margin-top': '-' + margin_top + 'px',
                        'margin-left': '-' + margin_left + 'px'
                    });
                }
            },

            '.mpsl-edit-animation-btn click': function () {
                if (this.verifyOriginalValues()) {
                    // Load values
                    $.each(this.child_options, function (_, option) {
                        $.each(option.helpers, function(_, helper_option) {
                            option.setValue(helper_option.getValue());
                        });
                    });
                    // Show dialog box
                    this.editor.dialog('open');
                }
            },

            resetPreviewClasses: function () {
                this.preview.attr('class', 'mpsl-animation-preview mpsl-animated');
            }
        }
    ); // End of MPSL.AnimationControl
})(jQuery, MPSL);
