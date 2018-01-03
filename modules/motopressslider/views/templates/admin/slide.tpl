{*
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
 *}

{*
 * Caller: /controllers/admin/MPSLSlideController.php
 *
 * Uses:
 *     MPSLSlider $slider Slider object.
 *     int $slider_id
 *     bool $is_product
 *     array $slide_settings Slide settings (grouped).
 *     array $layer_settings Layer settings (grouped).
 *     array $preset_settings Preset settings (grouped).
 *     int $prev_slide_id
 *     int $next_slide_id
 *     array $layer_variants (See /includes/defaults/layer-variants.php)
 *     array layer_layout Layout data for layer settings.
 *     string $admin_templates Path to templates dir:
 *                             ".../motopressslider/views/templates/admin" (no
 *                             slash in the end).
 *}

<div class="row">
    <div id="slide-wrapper" class="col-lg-12">
{* Preloader *}
        <div class="mpsl-global-preloader"></div>

        <h3 class="no-margin">{l s='Slide Settings' mod='motopressslider'}</h3>

{* Slide settings *}
        {include file="$admin_templates/ui-settings.tpl" name='slide' settings=$slide_settings is_product=$is_product inline_tabs=true exclude=array('fonts')}

{* Slider Workground *}
        <div id="mpsl-workground">
            <div class="mpsl-slider-wrapper">
                <div class="mpsl-slide-bg-wrapper"></div>
                <div class="mpsl-slide-border-wrapper">
                    <div class="mpsl-slide-border"></div>
                </div>
                {motoslider slider=$slider edit_mode=true}
            </div>

            <div class="row">
                <div class="col-md-9">
{* Add Text / Add Image / Add Button / Add Video *}
                    {strip}
                        <div class="mpsl-layer-control-panel">
                            {foreach from=$layer_variants key=layer_type item=data}
                                {strip}
                                    {if isset($data['separate'])}
                                        {$separate = $data['separate']}
                                    {else}
                                        {$separate = false}
                                    {/if}

                                    {* Show variants *}
                                    {if isset($data['variants']) && !empty($data['variants'])}
                                        <div class="mpsl-button-dropdown">
                                            <button type="button" class="btn btn-default mpsl-add-layer" data-type="{$layer_type|escape:'htmlall':'UTF-8'}" disabled="disabled">
                                                {$data['label']|escape:'javascript':'UTF-8'}
                                            </button>
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                {$groups_count = count($data['variants'])}
                                                {$current_group = 1}
                                                {foreach from=$data['variants'] item=group_variants}
                                                    {foreach from=$group_variants item=variant}
                                                        <li class="mpsl-add-layer"
                                                            data-type="{$layer_type|escape:'htmlall':'UTF-8'}"
                                                            {if isset($variant['macros'])} data-macros="{$variant['macros']|escape:'htmlall':'UTF-8'}"{/if}
                                                        >
                                                            {$variant['label']|escape:'javascript':'UTF-8'}
                                                        </li>
                                                    {/foreach}
                                                    {if $current_group < $groups_count && $separate}
                                                        <li role="separator" class="divider"></li>
                                                    {/if}
                                                    {$current_group = $current_group + 1}
                                                {/foreach}
                                            </ul>
                                        </div>
                                    {else}
                                        <button type="button" class="btn btn-default mpsl-add-layer" data-type="{$layer_type|escape:'htmlall':'UTF-8'}" disabled="disabled">{$data['label']|escape:'javascript':'UTF-8'}</button>
                                    {/if}
                                {/strip}
                            {/foreach}
                        </div>
                    {/strip}
                </div>

                <div class="col-md-3">
{* Layers controls *}
                    <div class="mpsl-layer-control-panel">
                        <button type="button" class="btn btn-default mpsl-delete-layer" disabled="disabled">{l s='Delete Layer' mod='motopressslider'}</button>
                        <button type="button" class="btn btn-default mpsl-delete-all-layers" disabled="disabled">{l s='Delete All Layers' mod='motopressslider'}</button>
                    </div>
                </div>
            </div>{* .row *}
        </div>{* #mpsl-workground *}

        <div class="row">
{* Layers settings *}
            <div class="col-md-9">
                <div class="panel">
                    <div class="panel-heading">{l s='Layer Settings' mod='motopressslider'}</div>
                    <div class="mpsl_layers_wrapper">
                        {include file="$admin_templates/ui-settings-layout.tpl" name='layer' settings=$layer_settings layout=$layer_layout is_product=$is_product}
                    </div>

                </div>{* .panel *}
            </div>{* .col-lg-9 *}

{* Layers list *}
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-heading">{l s='Layers Sorting (drag to set an order)' mod='motopressslider'}</div>
                    <div class="mpsl-layers-list-wrapper">
                        <div class="mpsl-layers-list-child-wrapper">
                            <table class="table mpsl-layers-table">
                                <thead class="mpsl-layers-table-head hidden">
                                    <tr>
                                        <th class="fixed-width-xs">&nbsp;</th>
                                        <th>{l s='Type' mod='motopressslider'}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>{* .mpsl-layers-list-wrapper *}
                </div>{* .panel *}
            </div>{* .col-lg-3 *}

{* Slide controls *}
            <div class="col-md-12">
                {* Close button *}
                {if !$is_product}
                    <a class="btn btn-default" href="{mpsl_link view="slides" id=$slider_id}">{l s='Close' mod='motopressslider'}</a>
                {else}
                    <a class="btn btn-default" href="{mpsl_link view="sliders"}">{l s='Close' mod='motopressslider'}</a>
                {/if}

                {* Settings button *}
                <a id="slider_settings" class="btn btn-default" href="{mpsl_link view="slider" id=$slider_id}">{l s='Slider Settings' mod='motopressslider'}</a>

                {* Previous Slide/Next Slide *}
                {if !$is_product}
                    <a class="btn btn-default" href="{mpsl_link view="slide" id=$prev_slide_id}"><i class="icon-arrow-left"></i> {l s='Previous Slide' mod='motopressslider'}</a>
                    <a class="btn btn-default" href="{mpsl_link view="slide" id=$next_slide_id}"><i class="icon-arrow-right"></i> {l s='Next Slide' mod='motopressslider'}</a>
                {/if}

                {* Preview Slide/Slider *}
                <button type="button" id="slider_preview" class="btn btn-default" data-mpsl-slider-id="{$slider_id|escape:'htmlall':'UTF-8'}">
                    {if !$is_product}
                        {l s='Preview Slide' mod='motopressslider'}
                    {else}
                        {l s='Preview Slider' mod='motopressslider'}
                    {/if}
                </button>

                {* Save button *}
                <button type="button" id="save_slide" class="btn btn-primary">
                    {if !$is_product}
                        {l s='Save Slide' mod='motopressslider'}
                    {else}
                        {l s='Save Template' mod='motopressslider'}
                    {/if}
                </button>
            </div>
        </div>{* .row *}

{* Preview dialog *}
        {include file="$admin_templates/preview-dialog.tpl"}

{* Preset settings *}
        <div id="mpsl-style-editor-modal">
            <div id="mpsl-style-editor-wrapper" class="panel mpsl-style-editor-wrapper">
                <div class="row mpsl-style-editor-content">
                    <div class="col-md-8">
                        <div id="mpsl-style-editor-preview-area">
                            <div class="mpsl-style-editor-preview">{l s='Sample Text' mod='motopressslider'}</div>
                            <div class="mpsl-style-editor-bg-toggle"></div>
                        </div>
                        <div id="mpsl-style-editor-settings-wrapper" class="mpsl-style-editor-settings-wrapper font-horizontal">
                            <div id="mpsl-style-mode-switcher">
                                <input type="radio" id="mpsl_style_mode_switcher_style" name="mpsl_style_mode_switcher" data-mode="style" />
                                <label for="mpsl_style_mode_switcher_style">{l s='Normal state' mod='motopressslider'}</label>
                                <input type="radio" id="mpsl_style_mode_switcher_hover" name="mpsl_style_mode_switcher" data-mode="hover" />
                                <label for="mpsl_style_mode_switcher_hover">{l s='Hover state' mod='motopressslider'}</label>
                            </div>
                            <div data-group="font-typography" class="mpsl-preset-allow-style-wrapper">
                                <div class="mpsl-option-wrapper">
                                    {mpsl_input settings=$preset_settings['font-typography']['options']['allow_style'] type='alternate'}
                                </div>
                            </div>
                            {* Note: no wrappers supported for the tabs;
                             * "font-typography" and other settings must be on
                             * the equal level *}
                            {include file="$admin_templates/ui-settings.tpl" name='preset' settings=$preset_settings is_product=$is_product inline_tabs=true exclude=array('allow_style') hide_wrappers=true}
                        </div>
                    </div>{* .col-md-8 *}
                    <div class="col-md-4">
                        <!--h3 class="no-margin">Presets</h3-->
                        <div id="mpsl-layer-preset-list-wrapper">
                            <div id="mpsl-layer-preset-list-child-wrapper">
                                <table class="mpsl-layer-presets-table">
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>{* .col-md-4 *}
                </div>{* .row *}
                <div class="panel-footer mpsl-style-editor-footer">
                    <fieldset>
                        <button id="mpsl-apply-layer-preset" class="btn btn-primary">{l s='Apply Style' mod='motopressslider'}</button>
                        <button id="mpsl-save-as-layer-preset" class="btn btn-default">{l s='Duplicate' mod='motopressslider'}</button>
                        <button id="mpsl-create-layer-preset" class="btn btn-default">{l s='Create New Preset' mod='motopressslider'}</button>
                    </fieldset>
                </div>
            </div>{* #mpsl-style-editor-wrapper *}
        </div>{* #mpsl-style-editor-modal *}

{* Media libraries *}
        {include file="$admin_templates/media-library.tpl" sid='mpsl-add-image-bg_image_id' is_product=false}{* For slide option "bg_image_id" *}
        {include file="$admin_templates/media-library.tpl" sid='mpsl-add-image-ml' is_product=$is_product}{* For "Add Image" button *}
        {include file="$admin_templates/media-library.tpl" sid='mpsl-add-image-image_id' is_product=$is_product}{* For layer option "image_id" *}
    </div>{* .col-lg-12 *}
    <div id="mpsl-media-library-wrapper"></div>
    <div id="mpsl-info-box"></div>
</div>
