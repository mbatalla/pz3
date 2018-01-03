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
 * Caller: /controllers/admin/MPSLSlidersController.php
 *
 * Uses:
 *     array $page_heading_data
 *     array $sliders_data
 *     array slider_presets
 *     string $admin_templates Path to templates dir:
 *                             ".../motopressslider/views/templates/admin" (no
 *                             slash in the end).
 *     boolean $magic_quotes_state Whether magic quotes is enabled or not.
 *}

<div class="row">
    <div class="col-lg-12">
        {if $magic_quotes_state}
            <div class="alert alert-warning">{l s='Warning: MotoPress Slider doesn\'t work with PHP extension Magic Quotes. This feature has been deprecated as of PHP 5.3.0 and removed as of PHP 5.4.0.' mod='motopressslider'} <a href="http://php.net/manual/en/security.magicquotes.disabling.php" target="_blank">{l s='Disable Magic Quotes' mod='motopressslider'}</a> {l s='extension to continue.' mod='motopressslider'}</div>
        {/if}

        <div class="panel sliders-panel">
            {* Panel header *}
            {include file="$admin_templates/ui-panel-heading.tpl" heading=$page_heading_data}
            {* Sliders table *}
            {include file="$admin_templates/ui-table.tpl" table=$sliders_data}
            <div class="alert alert-info">{l s='* From the page editor insert the shortcode from the sliders table. From the html use:' mod='motopressslider'} <strong>&lt;?php motoSlider( "alias" ); ?&gt;</strong></div>
        </div>
    </div>

    <div id="mpsl-info-box"></div>

    {include file="$admin_templates/preview-dialog.tpl"}

    <div id="mpsl-slider-preset-wrapper">
        <div class="bootstrap">
            <table class="widefat mpsl-templates-table mpsl-templates-group-main">
                <thead>
                    <tr>
                        <th colspan="2">{l s='Choose Slider Type:' mod='motopressslider'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$slider_presets item=preset}
                        <tr>
                            <th>
                                <input type="radio" name="slider_type" value="{$preset['id']|escape:'htmlall':'UTF-8'}" id="mpsl-preset-{$preset['id']|escape:'htmlall':'UTF-8'}" data-id="{$preset['id']|escape:'htmlall':'UTF-8'}" data-type="{$preset['slider_type']|escape:'htmlall':'UTF-8'}" {if $preset['selected']}checked="checked"{/if} />
                            </th>
                            <th>
                                {strip}
                                    <label for="mpsl-preset-{$preset['id']|escape:'htmlall':'UTF-8'}">
                                        <b>{$preset['label']|escape:'htmlall':'UTF-8'}</b>
                                        {if isset($preset['description']) && $preset['description']}
                                            <p class="description">{$preset['description']|escape:'htmlall':'UTF-8'}</p>
                                        {/if}
                                    </label>
                                {/strip}
                            </th>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            <button id="cancel-creation" class="btn btn-default">{l s='Cancel' mod='motopressslider'}</button>
            <button id="create-slider" class="btn btn-primary pull-right">{l s='Create Slider' mod='motopressslider'}</button>
        </div>{* .bootstrap *}
    </div>{* #mpsl-slider-preset-wrapper *}

</div>{* .row *}
