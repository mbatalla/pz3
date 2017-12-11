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

<div class="mpsl-button-dropdown">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{$title|escape:'htmlall':'UTF-8'} <span class="caret"></span></button>
    <ul class="dropdown-menu">
        {foreach from=$variants item=v}
            {strip}
                <li class="mpsl-do-macros-thing"
                    {if isset($v['macros'])}data-macros="%{$v['macros']|escape:'htmlall':'UTF-8'}%"{/if}
                    {if isset($v['preset'])}data-preset="{$v['preset']|escape:'htmlall':'UTF-8'}"{/if}
                >
                    {$v['label']|escape:'javascript':'UTF-8'}
                </li>
        {/foreach}
    </ul>
</div>
