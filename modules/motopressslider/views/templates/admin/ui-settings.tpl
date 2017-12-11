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
 * Callers:
 *     /views/templates/admin/slider.tpl
 *     /views/templates/admin/slide.tpl
 *
 * Uses:
 *     string $name
 *     array $settings Grouped settings.
 *     bool $is_product Optional. Default: false.
 *     bool $inline_tabs Optional. Default: false.
 *     string $after The template to call after.
 *     array $exclude Optional. Exclude some options from settings form.
 *                    Default: an empty array.
 *     bool $hide_wrappers Optional. Default: false.
 *     string $type Optional. Output type. Variants: "default", "alternate".
 *                  Default: "default".
 *}

{if !isset($is_product)}
    {$is_product = false}
{/if}
{if !isset($inline_tabs)}
    {$inline_tabs = false}
{/if}
{if !isset($exclude)}
    {$exclude = array()}
{/if}
{if !isset($hide_wrappers)}
    {$hide_wrappers = false}
{/if}
{if !isset($type)}
    {$type = 'default'}
{/if}

{$prefix = "mpsl-$name-settings-"}

{strip}
    {* Settings wrapper start *}
    {if !$hide_wrappers}
        <div id="{$prefix|escape:'htmlall':'UTF-8'}tabs" class="{$prefix|escape:'htmlall':'UTF-8'}wrapper mpsl_options_wrapper clearfix wide {if $is_product}product-slider{/if}">
    {/if}

        {* Column for navigation and panels (for inline tabs only) *}
        <div class="{if $inline_tabs}form-horizontal col-lg-12{else}col-lg-2 col-md-3{/if}">

            {* Settings tabs *}
            <div class="list-group tabs-navigation {if $inline_tabs}tabs-list-group{/if}">
                {foreach from=$settings key=group_name item=group}
                    {if !$is_product || !$group['product_hidden']}
                        {$href = $prefix|cat:$group_name}
                        <a id="{$name|escape:'htmlall':'UTF-8'}_{$group_name|escape:'htmlall':'UTF-8'}" class="list-group-item" href="#{$href|escape:'htmlall':'UTF-8'}">{$group['title']|escape:'htmlall':'UTF-8'}</a>
                    {/if}
                {/foreach}
            </div>

        {* Another column for panels (for not inline tabs only) *}
        {if !$inline_tabs}
            </div>
            <div class="form-horizontal col-lg-10 col-md-9">
        {/if}

            {* Settings panels *}
            {foreach from=$settings key=group_name item=group}
                {$tab_id = $prefix|cat:$group_name}
                <div id="{$tab_id|escape:'htmlall':'UTF-8'}" class="mpsl-settings-tab-content" style="display: none;">
                    <div class="panel {if $inline_tabs}tabs-panel{/if}">

                        {if !empty($group['title'])}
                            <h3 class="tab">
                                {if array_key_exists('icon', $group) && !empty($group['icon'])}
                                    <i class="{$group['icon']|escape:'htmlall':'UTF-8'}"></i>&nbsp;
                                {/if}
                                {$group['title']|escape:'htmlall':'UTF-8'}
                            </h3>
                        {/if}

                        {foreach from=$group['options'] key=option_name item=option}
                            {if !in_array($option_name, $exclude)}
                                {$is_hidden = ($option['type'] === 'hidden' || (isset($option['hidden']) && $option['hidden']))}
                                {$class = 'mpsl-option-wrapper '}
                                {if $is_hidden}{$class = $class|cat:'mpsl-option-wrapper-hidden '}{/if}
                                {if isset($option['class'])}{$class = $class|cat:$option['class']}{/if}
                                <div class="{$class|escape:'htmlall':'UTF-8'}" data-group="{$group_name|escape:'htmlall':'UTF-8'}">
                                    {mpsl_input settings=$option type=$type}
                                </div>
                            {/if}
                        {/foreach}

                        {if isset($after)}
                            {$template_path = $admin_templates|cat:$after}
                            {include file=$template_path}
                        {/if}

                    </div>{* .panel *}
                </div>{* #%group ID% *}
            {/foreach}

        {* Close the last opened column *}
        </div>{* .form-horizontal *}

    {* Settings wrapper end *}
    {if !$hide_wrappers}
        </div><!-- End of settings tabs -->
    {/if}
{/strip}
