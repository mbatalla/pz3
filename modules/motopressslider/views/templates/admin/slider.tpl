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
 * Caller: /controllers/admin/MPSLSliderController.php
 *
 * Uses:
 *     array $settings Slider settings (grouped).
 *     int $slider_id (For slider-footer.tpl)
 *     bool $is_product (For slider-footer.tpl)
 *     bool $is_new_slider (For slider-footer.tpl)
 *     int $template_id (For slider-footer.tpl)
 *     string $admin_templates Path to templates dir:
 *                             ".../motopressslider/views/templates/admin" (no
 *                             slash in the end).
 *}

<div class="row">
    <div class="col-lg-12">
        {* Slider settings *}
        {include file="$admin_templates/ui-settings.tpl" name='slider' settings=$settings is_product=$is_product after='/slider-footer.tpl'}

        {* Preview panel for product content query *}
        {if $is_product}
            <div id="mpsl-product-preview" style="display: none;">
                <div class="col-lg-2 col-md-3"></div>
                <div class="col-lg-10 col-md-9">
                    <div class="panel">

                        <div class="panel-heading">
                            {l s='Search Results' mod='motopressslider'}
                            <span class="badge">0</span>
                        </div>

                        <button type="button" id="get_products" class="btn btn-primary">{l s='Preview Products' mod='motopressslider'}</button>

                        <div class="table-responsive-row wide clearfix">
                            <table id="mpsl-product-preview-table" class="table">
                                <thead>
                                    <tr class="nodrag nodrop">
                                        <th class="fixed-width-sm center"><span class="title_box">{l s='ID' mod='motopressslider'}</span></th>
                                        <th class="fixed-width-md center"><span class="title_box">{l s='Product Image' mod='motopressslider'}</span></th>
                                        <th><span class="title_box">{l s='Product Name' mod='motopressslider'}</span></th>
                                        <th><span class="title_box">{l s='Short Description' mod='motopressslider'}</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="odd">
                                        <td class="list-empty" colspan="4">
                                            <div class="list-empty-msg">
                                                <i class="icon-warning-sign list-empty-icon"></i>
                                                {l s='No records found' mod='motopressslider'}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> {* Table wrapper*}

                    </div>{* .panel *}
                </div>{* Right column *}
            </div>{* Products preview wrapper *}
        {/if}
    </div>
    <div id="mpsl-info-box"></div>
</div>
