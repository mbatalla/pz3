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
 * Caller: /controllers/admin/MPSLExporterController.php
 *
 * Uses:
 *     string $requested_ids
 *}

<div class="row">
    <div class="col-lg-12">
        <div class="panel sliders-panel">

            <div class="panel-heading">
                {l s='Export Error' mod='motopressslider'}
            </div>

            {if !empty($requested_ids)}
                <div class="alert alert-danger">
                    {l s='Something went wrong with export if you see this page. Requested slider IDs: %1$s.' sprintf=$requested_ids mod='motopressslider'}
                </div>
            {else}
                <div class="alert alert-warning">
                    {l s='You did not select sliders for export.' mod='motopressslider'}
                </div>
            {/if}

            <div class="panel-footer">
                <a href="{mpsl_link view='impex'}" class="btn btn-default"><i class="process-icon-back"></i> {l s='Back' mod='motopressslider'}</a>
            </div>

        </div>
    </div>
</div>
