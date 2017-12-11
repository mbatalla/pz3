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
 * Caller: /views/templates/admin/slide.tpl
 *
 * Uses:
 *     string $name
 *     array $settings Grouped settings.
 *     array $layout
 *     bool $is_product Optional. Default: false.
 *}

{if !isset($is_product)}
    {$is_product = false}
{/if}

{$prefix = "mpsl-$name-settings"}
<div id="{$prefix|escape:'htmlall':'UTF-8'}" class="{$prefix|escape:'htmlall':'UTF-8'}-wrapper mpsl_options_wrapper clearfix wide {if $is_product}product-slider{/if}">
    {* Settings panels *}
    {strip}
        <div class="form-horizontal row">
            {foreach from=$layout key=group_name item=data}
                {$group_id = "`$prefix`-`$group_name`"}
                <div id="{$group_id|escape:'htmlall':'UTF-8'}" class="{$data['class']|escape:'htmlall':'UTF-8'}">

                    {foreach from=$data['options'] item=set}
                        {if $set['type'] == 'text'}
                            <div class="mpsl-option-wrapper">
                                <div class="mpsl-info">{$set['text']|escape:'javascript':'UTF-8'}</div>
                            </div>
                        {elseif $set['type'] != 'grouped'}
                            {if !isset($set['prepend'])}{$set['prepend'] = array()}{/if}
                            {foreach from=$set['list'] item=option_name}
                                {$option = $settings[$group_name]['options'][$option_name]}
                                {$is_hidden = ($option['type'] === 'hidden' || (isset($option['hidden']) && $option['hidden']))}
                                <div class="mpsl-option-wrapper {if $is_hidden}mpsl-option-wrapper-hidden{/if}" data-group="{$group_name|escape:'htmlall':'UTF-8'}">
                                    {if array_key_exists($option_name, $set['prepend'])}
                                        {mpsl_input settings=$option type=$set['type'] prepend=$set['prepend'][$option_name]}
                                    {else}
                                        {mpsl_input settings=$option type=$set['type']}
                                    {/if}
                                </div>
                            {/foreach}
                        {else}{* if $set['type'] == 'grouped' *}
                            {$options = array()}
                            {foreach from=$set['list'] item=option_name}
                                {$options[] = $settings[$group_name]['options'][$option_name]}
                            {/foreach}
                            <div class="mpsl-option-wrapper" data-group="{$group_name|escape:'htmlall':'UTF-8'}">
                                {mpsl_input settings=$options type='grouped'}
                            </div>
                        {/if}
                    {/foreach}

                </div>{* #%group ID% *}
            {/foreach}
        </div>{* .form-horizontal.row *}
    {/strip}
</div><!-- End of settings tabs -->
