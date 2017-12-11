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
 * Caller: /includes/ajax-functions.php/mpsl_products_preview()
 *
 * Uses:
 *     array $products
 *}

{strip}
    {if count($products)}
        {$is_odd = true}
        {foreach from=$products key=id item=product}
            {$name = $product['name']}
            {$url = $product['product_url']}
            {$img_url = $product['cover_preview']}
            {$description = $product['short_description']}
            <tr {if $is_odd}class="odd"{/if}>
                <td class="fixed-width-sm center">
                    {$id|escape:'htmlall':'UTF-8'}
                </td>
                <td class="fixed-width-md center">
                    <a href="{$url|escape:'javascript':'UTF-8'}" target="_blank" title="{$name|escape:'htmlall':'UTF-8'}">
                        <img class="img img-thumbnail" src="{$img_url|escape:'javascript':'UTF-8'}" alt="{$name|escape:'htmlall':'UTF-8'}" />
                    </a>
                </td>
                <td>
                    <a href="{$url|escape:'javascript':'UTF-8'}" target="_blank" title="{$name|escape:'htmlall':'UTF-8'}">{$name|escape:'htmlall':'UTF-8'}</a>
                </td>
                <td>
                    <p>{$description|escape:'htmlall':'UTF-8'}</p>
                </td>
            </tr>
            {$is_odd = !$is_odd}
        {/foreach}
    {else}
        <tr class="odd">
            <td class="list-empty" colspan="4">
                <div class="list-empty-msg">
                    <i class="icon-warning-sign list-empty-icon"></i>
                    {l s='No records found' mod='motopressslider'}
                </div>
            </td>
        </tr>
    {/if}
{/strip}
