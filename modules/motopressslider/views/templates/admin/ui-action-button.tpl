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
 * Caller: /views/templates/admin/ui-table.tpl
 *
 * Uses:
 *     array $action
 *     array $record Record fields values.
 *     bool $is_button Optional. Default: false.
 *}

{* Init optional variables *}
{if !isset($is_button)}
    {$is_button = false}
{/if}

{* Prepare classes *}
{$classes = ''}
{if $is_button}
    {$classes = 'btn btn-default'}
{/if}
{if isset($action['class'])}
    {$classes = $classes|cat:$action['class']}
{/if}

{* Get or generate the link *}
{$link = '#'}
{if isset($action['view'])}
    {if isset($action['id_name'])}
        {$id = $record[$action['id_name']]}
        {capture assign=link}{mpsl_link view=$action['view'] id=$id}{/capture}
    {else}
        {capture assign=link}{mpsl_link view=$action['view']}{/capture}
    {/if}
{else}
    {if isset($action['link'])}
        {$link = $action['link']}
    {/if}
{/if}

{* Build action button *}
<a class="{$classes|escape:'htmlall':'UTF-8'}" href="{$link|escape:'javascript':'UTF-8'}" title="{$action['title']|escape:'htmlall':'UTF-8'}" {if isset($action['atts'])}{foreach from=$action['atts'] key=attr_name item=id_name} {$attr_name|escape:'htmlall':'UTF-8'}="{$record[$id_name]|escape:'htmlall':'UTF-8'}"{/foreach}{/if}>
    {if isset($action['icon'])}<i class="{$action['icon']|escape:'htmlall':'UTF-8'}"></i> {/if}
    {$action['title']|escape:'htmlall':'UTF-8'}
</a>
