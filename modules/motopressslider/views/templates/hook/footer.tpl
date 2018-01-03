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

<!-- MOTOSLIDER FOOTER -->
{if !empty($g_fonts_url)}
    <link rel="stylesheet" type="text/css" class="mpsl-admin-fonts-link" href="{$g_fonts_url|escape:'htmlall':'UTF-8'}" />
{/if}

<div id="mpsl-preset-styles-wrapper">
    {if is_array($default_presets)}
        {foreach from=$default_presets key=class item=preset}
            <style type="text/css" class="mpsl-preset-style" id="{$class|escape:'htmlall':'UTF-8'}">{$preset|escape:'htmlall':'UTF-8'}</style>
        {/foreach}
    {/if}
    {if is_array($custom_presets)}
        {foreach from=$custom_presets key=class item=preset}
            <style type="text/css" class="mpsl-preset-style" id="{$class|escape:'htmlall':'UTF-8'}">{$preset|escape:'htmlall':'UTF-8'}</style>
        {/foreach}
    {/if}
    {if is_array($private_presets)}
        {foreach from=$private_presets key=class item=preset}
            <style type="text/css" class="mpsl-preset-style" id="{$class|escape:'htmlall':'UTF-8'}">{$preset|escape:'htmlall':'UTF-8'}</style>
        {/foreach}
    {/if}
</div>

{* Add footer scripts, like: *}
{* <script type="text/javascript" src="/modules/motopressslider/..."></script> *}
{foreach from=$footer_scripts item=script}
    <script type="text/javascript" src="{$script|escape:'htmlall':'UTF-8'}"></script>
{/foreach}
<!-- END OF MOTOSLIDER FOOTER -->
