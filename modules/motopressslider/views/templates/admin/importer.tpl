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
 * Caller: /controllers/admin/MPSLImporterController.php
 *
 * Uses:
 *     bool $is_imported
 *     array $log
 *     bool $is_debug
 *}

<div class="row">
    <div class="col-lg-12">
        <div class="panel sliders-panel">

            {if $is_imported}
                <div class="panel-heading">
                    {l s='Import Success' mod='motopressslider'}
                </div>
                <div class="alert alert-success">
                    {l s='Sliders data successfully imported.' mod='motopressslider'}
                </div>
                {if $is_debug}
                    <p>{l s='Import log:' mod='motopressslider'}</p>
                    <ul class="error-log">
                        {foreach from=$log item=log_entry}
                            <li><code>{$log_entry|escape:'htmlall':'UTF-8'}</code></li>
                        {/foreach}
                    </ul>
                {/if}
            {else}
                <div class="panel-heading">
                    {l s='Import Failure' mod='motopressslider'}
                </div>
                <div class="alert alert-danger">
                    {l s='Sliders data not imported.' mod='motopressslider'}
                </div>
                <p>{l s='Something went wrong while import sliders. Here is the import log:' mod='motopressslider'}</p>
                <ul class="error-log">
                    {foreach from=$log item=log_entry}
                        <li><code>{$log_entry|escape:'htmlall':'UTF-8'}</code></li>
                    {/foreach}
                </ul>
            {/if}

            <div class="panel-footer">
                {if $is_imported}
                    <a href="{mpsl_link view='sliders'}" class="btn btn-default"><i class="process-icon-close"></i> {l s='Close' mod='motopressslider'}</a>
                {else}
                    <a href="{mpsl_link view='impex'}" class="btn btn-default"><i class="process-icon-back"></i> {l s='Back' mod='motopressslider'}</a>
                {/if}
            </div>

        </div>
    </div>
</div>
