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
 *     - /views/templates/admin/sliders.tpl
 *     - /views/templates/admin/slides.tpl
 *
 * Uses:
 *     array $heading Panel heading data:
 *         array(
 *             'title' => ...,
 *             'count' => ...,
 *             'actions' => array(
 *                 %id% => array(
 *                     'title' => ...,
 *                     'link' => ...,
 *                     ['icon'] => ...
 *                 ),
 *                 ...
 *             )
 *         )
 *}

<div class="panel-heading">
    {$heading['title']|escape:'htmlall':'UTF-8'}
    <span class="badge">{$heading['count']|escape:'htmlall':'UTF-8'}</span>
    <span class="panel-heading-action">
        {foreach from=$heading['actions'] key=id item=action}
            <a id="{$id|escape:'htmlall':'UTF-8'}" class="list-toolbar-btn" href="{$action['link']|escape:'javascript':'UTF-8'}">
                <span data-toggle="tooltip" class="label-tooltip" data-original-title="{$action['title']|escape:'htmlall':'UTF-8'}" data-html="true" data-placement="top">
                    {if isset($action['icon'])}
                        <i class="{$action['icon']|escape:'htmlall':'UTF-8'}"></i>
                    {/if}
                </span>
            </a>
        {/foreach}
    </span>
</div>
