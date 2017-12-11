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
 *     views/templates/admin/media.tpl
 *     views/templates/admin/slide.tpl
 *
 * Uses:
 *     string $sid ID attribute of the media library.
 *     bool $is_frame Optional. Default: true.
 *     bool $is_product Optional. Default: false.
 *     array $products Optional. Defautl: [].
 *     string module_name "MotoPress Slider". (From MotoPressSlider::prepareHeaderVars())
 *     string ajax_url (From MotoPressSlider::prepareHeaderVars())
 *}

{if !isset($is_frame)}
    {$is_frame = true}
{/if}
{if !isset($is_product)}
    {$is_product = false}
{/if}
{if !isset($products)}
    {$products = array()}
{/if}

{$panels = array()}
{if $is_product}
    {capture assign=products_title}{l s='Product Image' mod='motopressslider'}{/capture}
    {capture assign=images_title}{l s='From Library' mod='motopressslider'}{/capture}
    {$panels['products'] = $products_title}
    {$panels['images'] = $images_title}
{else}
    {capture assign=images_title}{l s='Library' mod='motopressslider'}{/capture}
    {$panels['images'] = $images_title}
{/if}
{capture assign=uploader_title}{l s='Upload New' mod='motopressslider'}{/capture}
{$panels['uploader'] = $uploader_title}

{strip}
    <div id="{$sid|escape:'htmlall':'UTF-8'}" class="mpsl-media-library {if $is_product}product-library{/if} clearfix wide {if $is_frame}in-frame{/if}">
        <div class="mpsl-media-tabs">

            {* Tabs navigation *}
            <div class="list-group tabs-list-group mpsl-tabs-nav">
                {foreach from=$panels key=name item=title}
                    <a class="list-group-item" href="#{$sid|escape:'htmlall':'UTF-8'}-{$name|escape:'htmlall':'UTF-8'}">{$title|escape:'htmlall':'UTF-8'}</a>
                {/foreach}
            </div>

            {* Tabs panels *}
            {foreach from=array_keys($panels) item=name}
                <div id="{$sid|escape:'htmlall':'UTF-8'}-{$name|escape:'htmlall':'UTF-8'}" class="panel tabs-panel mpsl-media-{$name|escape:'htmlall':'UTF-8'}">
                    {if $name != 'uploader'}
                        {* Product notice *}
                        {if $name == 'products'}
                            <div class="alert alert-warning">
                                <strong>{l s='Use any of this image only as a resizable template! On the frontend it would be replaced by real product image.' mod='motopressslider'}</strong>
                            </div>
                        {/if}
                        {* Media items *}
                        <p class="mpsl-no-records list-empty">
                            <span class="list-empty-msg">
                                <i class="icon-warning-sign list-empty-icon"></i> {l s='No images found' mod='motopressslider'}
                            </span>
                        </p>
                        <div class="panel-footer mpsl-media-controls">
                            <button class="btn btn-default button-close"><i class="process-icon-close"></i> {l s='Close' mod='motopressslider'}</button>
                            <button class="btn btn-primary pull-right button-select" disabled="disabled"><i class="process-icon-ok"></i> {l s='Select Image' mod='motopressslider'}</button>
                        </div>
                    {else}
                        {* Uploading form *}
                        <button class="btn btn-primary button-back">{l s='Back To Library' mod='motopressslider'}</button>
                        <form action="{$ajax_url|escape:'javascript':'UTF-8'}&action=mpslUploadMedia" id="{$sid|escape:'htmlall':'UTF-8'}-form" class="dropzone mpsl-media-form"></form>
                    {/if}
                </div>
            {/foreach}

        </div>
        {if $is_frame}
            <div class="mpsl-media-back"></div>
        {/if}
    </div>
{/strip}
