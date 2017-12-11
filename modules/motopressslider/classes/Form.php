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

class MPSLForm
{
    private static $instance = null;

    private $module = null;

    public function __construct($module = null)
    {
        if (!is_null($module) && is_a($module, 'MotoPressSlider')) {
            $this->module = $module;
        } else {
            $this->module = mpsl_get_module();
        }
    }

    public function createControl($settings, $prepend = '', $parent = '')
    {
        $this->checkSettings($settings);
        $control_atts = $this->createControlAtts($settings, $parent);
        $control = '';
        $field = '';

        // Create input field
        $field .= $this->createField($settings);
        if (!in_array($settings['type'], array('hidden', 'text_shadow'))) {
            if (in_array($settings['type'], array('checkbox', 'multicheckbox', 'switcher', 'radio_group',
                'button_group', 'action_group', 'codemirror'))
            ) {
                $field = '<div class="col-lg-9">' . $field . '</div>';
            } else {
                $field = '<div class="col-lg-5">' . $field . '</div>';
            }
        }

        // Build control
        $control .= '<div ' . $control_atts . '>';
        if ($settings['type'] !== 'text_shadow') {
            $control .= '<div class="col-lg-1"></div>';
            $control .= $this->createLabel($settings, $prepend);
        }
        $control .= $field;
        $control .= '</div>';

        return $control;
    }

    /**
     * Output all element in a single line and column, no "col-??-?" classes.
     */
    public function createAlternateControl($settings, $prepend = '', $parent = '')
    {
        $this->checkSettings($settings);
        $control_atts = $this->createControlAtts($settings, $parent);
        $control = '';
        $field = '';

        // Create input field
        $field .= $this->createField($settings);

        // Build control
        $control .= $this->createAlternateLabel($settings, $prepend);
        $control .= '<div ' . $control_atts . '>';
        $control .= $field;
        $control .= '</div>';

        return $control;
    }

    /**
     * Create multiple options in a single line.
     */
    public function createControlGroup($multiple_settings)
    {
        $control = '';

        // Add labels
        foreach ($multiple_settings as $no => $settings) {
            $this->checkSettings($settings);
            $multiple_settings[$no] = $settings;
            $control .= $this->createAlternateLabel($settings);
        }

        // Add options
        $col_size = 12/count($multiple_settings);
        $col_class = 'col-md-' . $col_size;
        foreach ($multiple_settings as $settings) {
            $control_atts = $this->createControlAtts($settings);
            $control .= '<div class="' . $col_class . '">';
            $control .= '<div ' . $control_atts . '>';
            $control .= $this->createField($settings);
            $control .= '</div>';
            $control .= '</div>';
        }

        return $control;
    }

    private function checkSettings(&$settings)
    {
        $settings = array_merge(array(
            // 'name' => ...,
            // 'id' => "mpsl_" . %name%,
            'type' => 'text',
            'label' => '',
            'default' => '',
            'value' => null,
            'list' => array(),
            'unit' => '',
            'hidden' => false,
            'required' => false,
            'multiple' => false,
            'disabled' => false,
            'readonly' => false
        ), $settings);

        $settings['id'] = MotoPressSlider::PREFIX . $settings['name'];

        if (is_null($settings['value'])) {
            $settings['value'] = $settings['default'];
        }

        $settings['real_type'] = $settings['type'];
        if ($settings['real_type'] == 'checkbox' && !empty($settings['list'])) {
            $settings['real_type'] = 'multicheckbox';
        }
        if ($settings['real_type'] == 'select' && $settings['multiple']) {
            $settings['real_type'] = 'multiselect';
        }
    }

    private function createControlAtts($settings, $parent = '')
    {
        $control_atts = array(
            'class' => 'form-group mpsl-option mpsl-option-' . $settings['type'],
            'data-mpsl-option-type' => $settings['type'],
            'data-name' => $settings['name'],
            'data-parent-name' => (empty($parent) ? '' : $parent)
        );
        if ($settings['type'] == 'hidden' || $settings['type'] == 'multiple' || $settings['hidden']) {
            $control_atts['class'] .= ' hidden';
        }
        if (!empty($parent)) {
            $control_atts['class'] .= ' mpsl-nested-option';
        }
        // Convert array into HTML attributes
        $control_atts = mpsl_implode_assoc('" ', $control_atts, '="');
        return $control_atts;
    }

    private function createField($settings)
    {
        $type = $settings['real_type'];

        switch ($type) {
            case 'text':
                return $this->createText($settings);
            case 'number':
                return $this->createNumber($settings);
            case 'textarea':
                return $this->createTextarea($settings);
            case 'select':
                return $this->createSelect($settings);
            case 'multiselect':
                return $this->createMultiselect($settings);
            case 'select_list':
                return $this->createSelectList($settings);
            case 'size_select':
                return $this->createSizeSelect($settings);
            case 'checkbox':
                return $this->createCheckbox($settings);
            case 'multicheckbox':
                return $this->createMulticheckbox($settings);
            case 'switcher':
                return $this->createSwitcher($settings);
            case 'radio_group':
                return $this->createRadioGroup($settings);
            case 'button_group':
                return $this->createButtonGroup($settings);
            case 'action_group':
                return $this->createActionGroup($settings);
            case 'library_image':
                return $this->createLibraryImage($settings);
            case 'image_url':
                return $this->createImageUrl($settings);
            case 'alias':
                return $this->createAlias($settings);
            case 'shortcode':
                return $this->createShortcode($settings);
            case 'align_table':
                return $this->createAlignTable($settings);
            case 'datepicker':
                return $this->createDatepicker($settings);
            case 'codemirror':
                return $this->createCodemirror($settings);
            case 'style_editor':
                return $this->createStyleEditor($settings);
            case 'multiple':
                return $this->createMultiple($settings);
            case 'font_picker':
                return $this->createFontPicker($settings);
            case 'color_picker':
                return $this->createColorPicker($settings);
            case 'text_shadow':
                return $this->createTextShadow($settings);
            case 'hidden':
                return $this->createHidden($settings);
            case 'animation_control':
                return $this->createAnimationControl($settings);
        }

        return '';
    }

    private function createLabel($settings, $append = '')
    {
        $id = $settings['id'];
        $label = '';

        if ($settings['required']) {
            $label .= '<label class="control-label col-lg-2 required" for="' . $id . '">';
        } else {
            $label .= '<label class="control-label col-lg-2" for="' . $id . '">';
        }

        if (!empty($settings['label']) && $settings['real_type'] != 'checkbox') {
            $label_body = '';
            if (isset($settings['description']) && !empty($settings['description'])) {
                $label_body .= '<span class="label-tooltip" data-toggle="tooltip" title=""';
                $label_body .= ' data-original-title="' . $settings['description'] . '">';
                $label_body .= $settings['label'];
                $label_body .= '</span>';
            } else {
                $label_body .= $settings['label'];
            }
            if ($settings['real_type'] == 'multicheckbox') {
                $label .= '<div class="checkbox">' . $label_body . '</div>';
            } elseif ($settings['real_type'] == 'radio_group') {
                $label .= '<div class="radio">' . $label_body . '</div>';
            } else {
                $label .= $label_body;
            }
        } else {
            $label .= '&nbsp;';
        }

        $label .= $append;

        $label .= '</label>';

        return $label;
    }

    private function createAlternateLabel($settings, $append = '')
    {
        $id = $settings['id'];
        $label = '';

        if (!empty($settings['label']) && $settings['real_type'] != 'checkbox') {
            if ($settings['required']) {
                $label .= '<label class="control-label required" for="' . $id . '">';
            } else {
                $label .= '<label class="control-label" for="' . $id . '">';
            }

            // Create label body
            $label_body = '';
            if (isset($settings['description']) && !empty($settings['description'])) {
                $label_body .= '<span class="label-tooltip" data-toggle="tooltip" title=""';
                $label_body .= ' data-original-title="' . $settings['description'] . '">';
                $label_body .= $settings['label'];
                $label_body .= '</span>';
            } else {
                $label_body .= $settings['label'];
            }
            // Add label body to label
            if ($settings['real_type'] == 'multicheckbox') {
                $label .= '<div class="checkbox">' . $label_body . '</div>';
            } elseif ($settings['real_type'] == 'radio_group') {
                $label .= '<div class="radio">' . $label_body . '</div>';
            } else {
                $label .= $label_body;
            }

            $label .= $append;

            $label .= '</label>';
        }

        return $label;
    }

    private function checkValue(&$value, $settings)
    {
        if (is_array($value)) {
            $value = array_intersect_key($value, $settings['list']);
            if (empty($value) && !empty($settings['list'])) {
                $value[] = reset($settings['list']);
            }
        } else {
            if (!empty($settings['list']) && !array_key_exists($value, $settings['list'])) {
                $value = reset($settings['list']);
            }
        }
        return $value;
    }

    private function createText($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        if (!isset($settings['label2']) || empty($settings['label2'])) {
            $control .= '<input type="text" id="' . $id . '" name="' . $id . '"';
            $control .= '  value="' . $value . '" ' . $atts . ' />';
        } else {
            $control .= '<div class="input-group wide">';
            $control .= '<span class="input-group-addon fixed-width-sm">';
            $control .= $settings['label2'];
            $control .= '</span>';
            $control .= '<input type="text" id="' . $id . '" name="' . $id . '" value="' . $value . '" />';
            $control .= '</div>';
        }

        return $control;
    }

    private function createNumber($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        $parts = array();
        if (isset($settings['label2']) && !empty($settings['label2'])) {
            $parts[] = '<span class="input-group-addon fixed-width-sm">' . $settings['label2'] . '</span>';
        }
        $parts[] = '<input type="text" id="' . $id . '" name="' . $id . '" value="' . $value . '" ' . $atts . ' />';
        if (!empty($settings['unit'])) {
            $parts[] = '<span class="input-group-addon fixed-width-sm">' . $settings['unit'] . '</span>';
        }

        if (count($parts) == 1) {
            $control .= $parts[0];
        } else {
            $control .= '<div class="input-group wide">';
            $control .= implode('', $parts);
            $control .= '</div>';
        }

        return $control;
    }

    private function createTextarea($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<textarea id="' . $id . '" name="' . $id . '" ' . $atts . '>' . $value . '</textarea>';

        return $control;
    }

    private function createSelect($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $this->checkValue($value, $settings);
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<select id="' . $id . '" name="' . $id . '" ' . $atts . '>';
        foreach ($settings['list'] as $variant => $label) {
            $selected = ($variant == $value ? ' selected="selected"' : '');
            $control .= '<option value="' . $variant . '" ' . $selected . '>' . $label . '</option>';
        }
        $control .= '</select>';

        return $control;
    }

    private function createMultiselect($settings)
    {
        $id = $settings['id'];
        $values = (array)$settings['value'];
        $this->checkValue($values, $settings);
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<select id="' . $id . '" name="' . $id . '" multiple="multiple" ' . $atts . '>';
        foreach ($settings['list'] as $variant => $label) {
            $selected = (in_array($variant, $values) ? ' selected="selected"' : '');
            $control .= '<option value="' . $variant . '" ' . $selected . '>' . $label . '</option>';
        }
        $control .= '</select>';

        return $control;
    }

    private function createSelectList($settings)
    {
        $id = $settings['id'];
        $control = '';

        $control .= '<div class="mpsl-select-list-box">';
        $control .= '<div class="mpsl-select-list-scroller">';
        $control .= '<ul id="' . $id . '" class="mpsl-select-list">';

        // Add list items
        foreach ($settings['list'] as $key => $value) {
            $control .= '<li data-value="' . $key . '">' . $value . '</li>';
        }

        $control .= '</ul>';
        $control .= '</div>';
        $control .= '</div>';

        return $control;
    }

    private function createSizeSelect($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $this->checkValue($value, $settings);
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<select id="' . $id . '" name="' . $id . '" ' . $atts . '>';
        foreach ($settings['list'] as $variant => $label) {
            $selected = ($variant == $value ? ' selected="selected"' : '');
            $control .= '<option value="' . $variant . '" ' . $selected . '>' . $label . '</option>';
        }
        $control .= '</select>';

        return $control;
    }

    private function createCheckbox($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        if ($value === true) {
            $atts = ' checked="checked"' . $atts;
        }

        $control .= '<div class="checkbox">';
        $control .= '<label class="control-label">';
        $control .= '<input type="checkbox" id="' . $id . '" name="' . $id . '" ' . $atts . ' />&nbsp;';
        $control .= $settings['label'];
        $control .= '</label>';
        $control .= '</div>';

        return $control;
    }

    private function createMulticheckbox($settings)
    {
        $id = $settings['id'];
        $values = (array)$settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        foreach ($settings['list'] as $name => $label) {
            $control .= '<p class="checkbox">';
            $control .= '<label class="control-label">';
            $checked = (in_array($name, $values) ? ' checked="checked"' : '');

            $control .= '<input type="checkbox" id="' . $id . '_' . $name . '" name="' . $id . '[]"';
            $control .= ' value="' . $name . '" ' . $checked . $atts . ' />&nbsp;';

            $control .= $label;

            $control .= '</label>';
            $control .= '</p>';
        }

        return $control;
    }

    private function createSwitcher($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $value = ($value !== 'off' && $value !== false ? 'on' : 'off');
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<span class="switch prestashop-switch fixed-width-lg">';
        foreach (array('on', 'off') as $state) {
            $label = Tools::ucfirst($state);
            $checked = ($state == $value ? ' checked="checked"' : '');

            $control .= '<input type="radio" id="' . $id . '_' . $state . '" name="' . $id . '"';
            $control .= ' value="' . $state . '" ' . $checked . $atts . ' />';

            $control .= '<label for="' . $id . '_' . $state . '">' . $label . '</label>';
        }
        $control .= '<a class="slide-button btn"></a>';
        $control .= '</span>';

        return $control;
    }

    private function createRadioGroup($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        foreach ($settings['list'] as $name => $label) {
            $control .= '<p class="radio">';
            $control .= '<label class="control-label">';
            $checked = ($name == $value ? ' checked="checked"' : '');

            $control .= '<input type="radio" id="' . $id . '_' . $name . '" name="' . $id . '"';
            $control .= ' value="' . $name . '" ' . $checked . $atts . ' />';

            $control .= $label;

            $control .= '</label>';
            $control .= '</p>';
        }

        return $control;
    }

    private function createButtonGroup($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $this->checkValue($value, $settings);
        $button_size = 'large';
        $atts = $this->getAtts($settings);
        $control = '';

        if (isset($settings['button_size']) && $settings['button_size']) {
            $button_size = $settings['button_size'];
        }

        $control .= '<div class="button-group button-' . $button_size . '">';
        foreach ($settings['list'] as $name => $label) {
            $active = ($name == $value ? 'active' : '');
            $control .= '<button id="' . $id . '_' . $name . '" name="' . $id . '"';
            $control .= ' class="button ' . $active . '" value="' . $name . '" ' . $atts . '>';
            $control .= $label;
            $control .= '</button>';
        }
        $control .= '</div>';

        return $control;
    }

    private function createActionGroup($settings)
    {
        $actions = array();
        foreach ($settings['list'] as $name => $label) {
            $actions[] = '<a href="javascript:void(0)" value="' . $name . '">' . $label . '</a>';
        }

        $control = '<div class="actions">';
        // Add secondary label
        if (isset($settings['label2']) && !empty($settings['label2'])) {
            $control .= $settings['label2'] . '&nbsp;';
        }
        $control .= implode(' | ', $actions);
        $control .= '</div>';

        return $control;
    }

    private function createLibraryImage($settings)
    {
        $id = $settings['id'];
        $atts = $this->getAtts($settings);
        $button_label = '';
        $can_remove = (isset($settings['can_remove']) && $settings['can_remove'] ? true : false);
        $control = '';

        if (isset($settings['button_label'])) {
            $button_label = $settings['button_label'];
        } else {
            $button_label = $this->module->l('Select Image');
        }

        $control .= '<input type="hidden" id="' . $id . '" name="' . $id . '"';
        $control .= ' value="' . $settings['value'] . '" ' . $atts . ' />';

        $control .= '<button class="mpsl-option-library-image-btn btn btn-default">' . $button_label . '</button>';

        if ($can_remove) {
            $control .= '&nbsp;<a href="#" class="mpsl-option-library-image-remove">';
            $control .= $this->module->l('remove');
            $control .= '</a>';
        }

        return $control;
    }

    private function createImageUrl($settings)
    {
        $id = $settings['id'];
        $atts = $this->getAtts($settings);
        $button_label = '';
        $control = '';

        if (isset($settings['button_label'])) {
            $button_label = $settings['button_label'];
        } else {
            $button_label = $this->module->l('Load');
        }

        $control .= '<input type="text" id="' . $id . '" name="' . $id . '"';
        $control .= ' value="' . $settings['value'] . '" ' . $atts . ' />';

        $control .= '<button class="mpsl-option-image-load-btn btn btn-default">' . $button_label . '</button>';

        return $control;
    }

    private function createAlias($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<input type="text" id="' . $id . '" name="' . $id . '" value="' . $value . '" ' . $atts . ' />';

        return $control;
    }

    private function createShortcode($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<input type="text" id="' . $id . '" name="' . $id . '" value="' . $value . '" ' . $atts . ' />';

        return $control;
    }

    private function createAlignTable($settings)
    {
        $control = '';

        $control .= '<table class="mpsl-align-table form-group">';
        $control .= '<tbody>';
        $control .= '<tr>';
        $control .= '<td><a href="javascript:void(0)" data-hor="left" data-vert="top"></a></td>';
        $control .= '<td><a href="javascript:void(0)" data-hor="center" data-vert="top"></a></td>';
        $control .= '<td><a href="javascript:void(0)" data-hor="right" data-vert="top"></a></td>';
        $control .= '</tr>';
        $control .= '<tr>';
        $control .= '<td><a href="javascript:void(0)" data-hor="left" data-vert="middle"></a></td>';
        $control .= '<td><a href="javascript:void(0)" data-hor="center" data-vert="middle"></a></td>';
        $control .= '<td><a href="javascript:void(0)" data-hor="right" data-vert="middle"></a></td>';
        $control .= '</tr>';
        $control .= '<tr>';
        $control .= '<td><a href="javascript:void(0)" data-hor="left" data-vert="bottom"></a></td>';
        $control .= '<td><a href="javascript:void(0)" data-hor="center" data-vert="bottom"></a></td>';
        $control .= '<td><a href="javascript:void(0)" data-hor="right" data-vert="bottom"></a></td>';
        $control .= '</tr>';
        $control .= '</tbody>';
        $control .= '</table>';

        if (isset($settings['options'])) {
            $parent = $settings['name'];
            foreach ($settings['options'] as $settings2) {
                $control .= $this->createAlternateControl($settings2, '', $parent);
            }
        }

        return $control;
    }

    private function createDatepicker($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<input type="text" id="' . $id . '" name="' . $id . '" value="' . $value . '" ' . $atts . ' />';

        return $control;
    }

    private function createCodemirror($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<textarea id="' . $id . '" name="' . $id . '" ' . $atts . '>' . $value . '</textarea>';

        return $control;
    }

    private function createStyleEditor($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);
        $control = '';

        if (!isset($settings['edit_label'])) {
            $settings['edit_label'] = $this->module->l('Edit');
        }
        if (!isset($settings['remove_label'])) {
            $settings['remove_label'] = $this->module->l('Remove');
        }

        $control .= '<select class="mpsl-layer-style-list col-md-6 col-lg-8">';
        // Add "Custom" group
        $control .= '<optgroup label="' . $this->module->l('Custom Presets') . '"';
        $control .= ' class="mpsl-layer-style-list-group-custom"></optgroup>';
        // Add "Default" group
        $control .= '<optgroup label="' . $this->module->l('Default') . '"';
        $control .= ' class="mpsl-layer-style-list-group-default"></optgroup>';
        $control .= '</select>';

        $control .= '<button class="btn btn-primary mpsl-edit-layer-style col-md-3 col-lg-2" ' . $atts . '>';
        $control .= $settings['edit_label'];
        $control .= '</button>';

        $control .= '<button class="btn btn-default mpsl-remove-layer-style col-md-3 col-lg-2" ' . $atts . '>';
        $control .= $settings['remove_label'];
        $control .= '</button>';

        $control .= '<input type="hidden" id="' . $id . '" name="' . $id . '" class="mpsl-layer-style-value"';
        $control .= ' value="' . $value . '" disabled="disabled" />';

        return $control;
    }

    private function createMultiple($settings)
    {
        $id = $settings['id'];
        $control = '';
        $value = $settings['value'];
        $atts = $this->getAtts($settings);

        if (isset($settings['hidden']) && ($settings['hidden'] || $settings['hidden'] == 'true')) {
            return $control;
        }

        $control .= '<textarea id="' . $id . '" name="' . $id . '" ' . $atts . '>';
        $control .= (is_string($value) ? $value : Tools::jsonEncode($value));
        $control .= '</textarea>';

        return $control;
    }

    private function createFontPicker($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $this->checkValue($value, $settings);
        $atts = $this->getAtts($settings);
        $control = '';

        $control .= '<select id="' . $id . '" name="' . $id . '" ' . $atts . '>';
        // Add fonts
        foreach ($settings['list'] as $variant => $data) {
            $selected = ($variant == $value ? ' selected="selected"' : '');
            if (is_array($data)) {
                $data_atts = ' ';
                if (isset($data['atts']) && is_array($data['atts'])) {
                    foreach ($data['atts'] as $attr_name => $attr_value) {
                        $attr_str = implode(',', $attr_value);
                        $data_atts .= " {$attr_name}='{$attr_str}'";
                    }
                }

                $control .= '<option value="' . $variant . '"' . $data_atts . $selected . '>';
                $control .= $data['label'];
                $control .= '</option>';
            } else {
                $control .= '<option value="' . $variant . '" ' . $selected . '>' . $data . '</option>';
            }
        } // foreach
        $control .= '</select>';

        return $control;
    }

    private function createColorPicker($settings)
    {
        $id = $settings['id'];
        $value = $settings['value'];
        $atts = $this->getAtts($settings);

        $control = '<input type="text" id="' . $id . '" name="' . $id . '" class="mpsl-color-picker"';
        $control .= ' value="' . $value . '" ' . $atts . ' />';

        return $control;
    }

    private function createTextShadow($settings)
    {
        $control = '';

        foreach ($settings['options'] as $option_settings) {
            $control .= $this->createControl($option_settings, '', $settings['name']);
        }

        return $control;
    }

    private function createHidden($settings)
    {
        $id = $settings['id'];
        $atts = $this->getAtts($settings);

        $control = '<input type="hidden" id="' . $id . '" name="' . $id . '"';
        $control .= ' value="' . $settings['value'] . '" ' . $atts . ' />';

        return $control;
    }

    private function createAnimationControl($settings)
    {
        $id = $settings['id'];
        $type = $settings['animation_type']; // "start"/"end"
        $cloned_options = $settings['options'];
        $control = '';

        // Edit animation button
        $control .= '<button class="btn btn-default mpsl-edit-animation-btn wide">';
        $control .= $settings['button_label'];
        $control .= '</button>';

        // Start of the editor
        $control .= '<div id="' . $id . '" class="mpsl-animation-editor">';

        // --> Start of left wrapper
        $control .= '<div class="mpsl-animation-left-wrapper">';
        $control .= '<div class="mpsl-animation-scene-wrapper">';

        // --> --> Add main scene
        $control .= '<div class="mpsl-animation-scene ms_current_slide">';
        $control .= '<div class="mpsl-animation-preview-wrapper">';
        $control .= '<h1 class="mpsl-animation-preview">' . $this->module->l('Lorem ipsum') . '</h1>';
        $control .= '</div>';
        $control .= '<button class="btn btn-default mpsl-play-animation">';
        $control .= '<i class="icon-play"></i>' . $this->module->l('Play') . '</button>';
        $control .= '</div>';

        // --> --> Add "duration" and "timing_function" options to the bottom of the scene wrapper
        $control .= '<div class="mpsl-timing-and-duration">';
        $control .= '<div class="mpsl-controls">';
        $control .=
            $this->createAlternateControl($cloned_options[$type . '_duration_clone'], '', $settings['name']);
        $control .=
            $this->createAlternateControl($cloned_options[$type . '_timing_function_clone'], '', $settings['name']);
        $control .= '</div>';
        $control .= '</div>';

        // --> End of left wrapper
        $control .= '</div>'; // Scene wrapper
        $control .= '</div>'; // Left wrapper

        // --> Right wrapper
        $control .= '<div class="mpsl-animation-right-wrapper">';
        $control .= $this->createAlternateControl($cloned_options[$type . '_animation_clone'], '', $settings['name']);
        $control .= '</div>';

        // --> Footer wrapper
        $control .= '<div class="mpsl-animation-bottom-wrapper clearfix">';
        $control .= '<button class="btn btn-default mpsl-animation-close">' . $this->module->l('Close') . '</button>';
        $control .= '<button class="btn btn-primary mpsl-animation-apply pull-right">';
        $control .= $this->module->l('Apply');
        $control .= '</button>';
        $control .= '</div>';

        // End of editor
        $control .= '</div>';

        return $control;
    }

    private function getAtts($settings)
    {
        $atts = '';
        if ($settings['required']) {
            $atts = ' required="required"';
        }
        if ($settings['disabled']) {
            $atts = ' disabled="disabled"';
        }
        if ($settings['readonly']) {
            $atts = ' readonly="readonly"';
        }
        return $atts;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
