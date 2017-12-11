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
 * Caller: /includes/ajax-functions/mpsl_fetch_media()
 *
 * Uses:
 *     array $data ["id", "file", "name", "preview_url", "width", "height"].
 *     bool can_delete Optional. Default: true.
 *     bool is_product Optional. Default: false.
 *}

{if !isset($can_delete)}
    {$can_delete = true}
{/if}
{if !isset($is_product)}
    {$is_product = false}
{/if}

{* All product media items must have negative data-id attribute *}
<div
    class="mpsl-media-item mpsl-media-item-{$data['id']|escape:'htmlall':'UTF-8'}"
    data-id="{$data['id']|escape:'htmlall':'UTF-8'}"
    data-file="{$data['file']|escape:'htmlall':'UTF-8'}"
    data-width="{$data['width']|escape:'htmlall':'UTF-8'}"
    data-height="{$data['height']|escape:'htmlall':'UTF-8'}"
    {if !$is_product}
        data-type="library"
    {else}
        data-type="product"
    {/if}
>
    {strip}
        <div class="mpsl-media-image">
            <img src="{$data['preview_url']|escape:'javascript':'UTF-8'}" alt="{$data['name']|escape:'htmlall':'UTF-8'}" />
        </div>
        <div class="mpsl-media-name">
            <span>{$data['name']|escape:'htmlall':'UTF-8'}</span>
        </div>
        {if $can_delete}
            <span class="mpsl-media-delete">&#10060;</span>
        {/if}
    {/strip}
</div>
