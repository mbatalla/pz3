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
 * Caller: /controllers/admin/MPSLImpexController.php
 *
 * Uses:
 *     array $sliders Sliders minimum data: array('id', 'name', 'alias').
 *}

<div class="row">
    <div class="col-lg-12">

        <div class="panel sliders-panel">
            <div class="panel-heading">
                {l s='Import Sliders' mod='motopressslider'}
            </div>
            <div class="alert alert-info">
                {l s='To import sliders select MotoPress Slider Export file that you downloaded before then click import button.' mod='motopressslider'}
            </div>
            <div class="table-responsive-row wide clearfix">
                <form id="mpsl_import_form" class="form-horizontal" action="{mpsl_link view="importer"}" method="POST" enctype="multipart/form-data">
                    {* Form field *}
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-1"></div>
                            <label class="control-label col-lg-2">{l s='Import file' mod='motopressslider'}</label>
                            <div class="col-lg-9">
                                <input type="file" id="import_file" class="btn btn-default" name="import_file" required="required" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-1"></div>
                            <label class="control-label col-lg-2">{l s='Enable HTTP Auth' mod='motopressslider'}</label>
                            <div class="col-lg-9">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input type="radio" id="http_auth_on" name="http_auth" value="on" />
                                    <label for="http_auth_on">{l s='On' mod='motopressslider'}</label>
                                    <input type="radio" id="http_auth_off" name="http_auth" value="off" checked="checked" />
                                    <label for="http_auth_off">{l s='Off' mod='motopressslider'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                        <div class="form-group need-http_auth" style="display: none;">
                            <div class="col-lg-1"></div>
                            <label class="control-label col-lg-2" for="http_auth_login">{l s='Login' mod='motopressslider'}</label>
                            <div class="col-lg-5">
                                <input type="text" id="http_auth_login" name="http_auth_login" disabled="disabled" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group need-http_auth" style="display: none;">
                            <div class="col-lg-1"></div>
                            <label class="control-label col-lg-2" for="http_auth_password">{l s='Password' mod='motopressslider'}</label>
                            <div class="col-lg-5">
                                <input type="text" id="http_auth_password" name="http_auth_password" disabled="disabled" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    {* Control buttons *}
                    <div class="panel-footer">
                        <a href="{mpsl_link view='sliders'}" class="btn btn-default"><i class="process-icon-back"></i> {l s='Back' mod='motopressslider'}</a>
                        <button type="submit" class="btn btn-default pull-right"><i class="process-icon-import"></i> {l s='Import Slider' mod='motopressslider'}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel sliders-panel">
            <div class="panel-heading">
                {l s='Export Sliders' mod='motopressslider'}
            </div>
            <div class="alert alert-info">
                {l s='Downloads an export file that contains your selected sliders to import on your new site.' mod='motopressslider'}
            </div>
            <div class="table-responsive-row wide clearfix">
                <form id="mpsl_export_form" class="form-horizontal" action="{mpsl_link view="exporter"}" method="POST" enctype="multipart/form-data">
                    {* Form field *}
                    <div class="table-responsive-row wide clearfix">
                        <table class="table mpsl-export-table">
                            <thead>
                                <tr class="nodrag nodrop">
                                    <th class="fixed-width-sm center"><input type="checkbox" id="export-check-all" name="export-check-all" /></th>
                                    <th class="fixed-width-sm center"><span class="title_box">{l s='ID' mod='motopressslider'}</span></th>
                                    <th><span class="title_box">{l s='Name' mod='motopressslider'}</span></th>
                                    <th><span class="title_box">{l s='Alias' mod='motopressslider'}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                {if !empty($sliders)}
                                    {$is_odd = true}
                                    {foreach from=$sliders item=slider}
                                        <tr class="table-row {if $is_odd}odd{/if}">
                                            <td class="fixed-width-sm center"><input type="checkbox" name="ids[]" class="export-check" value="{$slider['id']|escape:'htmlall':'UTF-8'}" /></td>
                                            <td class="fixed-width-sm center">{$slider['id']|escape:'htmlall':'UTF-8'}</td>
                                            <td>{$slider['name']|escape:'htmlall':'UTF-8'}</td>
                                            <td>{$slider['alias']|escape:'htmlall':'UTF-8'}</td>
                                        </tr>
                                        {$is_odd = !$is_odd}
                                    {/foreach}
                                {else}
                                    <tr class="no-records-row">
                                        <td class="list-empty" colspan="4">
                                            <div class="list-empty-msg">
                                                <i class="icon-warning-sign list-empty-icon"></i>
                                                {l s='No records found' mod='motopressslider'}
                                            </div>
                                        </td>
                                    </tr>
                                {/if}
                            </tbody>
                        </table>
                    </div>
                    {* Control buttons *}
                    <div class="panel-footer">
                        <a href="{mpsl_link view='sliders'}" class="btn btn-default"><i class="process-icon-back"></i> {l s='Back' mod='motopressslider'}</a>
                        <button type="submit" class="btn btn-default pull-right"><i class="process-icon-export"></i> {l s='Export Sliders' mod='motopressslider'}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
