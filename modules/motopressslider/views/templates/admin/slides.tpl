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
 * Caller: /controllers/admin/MPSLSlidesController.php
 *
 * Uses:
 *     int $slider_id
 *     array $page_heading_data (For ui-panel-heading.tpl)
 *     array $slides_data (For ui-table.tpl)
 *     string $admin_templates Path to templates dir:
 *                             ".../motopressslider/views/templates/admin" (no
 *                             slash in the end).
 *}

<div class="row">
    <div class="col-lg-12">
        <div class="panel slides-panel">
            {include file="$admin_templates/ui-panel-heading.tpl" heading=$page_heading_data}

            {include file="$admin_templates/ui-table.tpl" table=$slides_data}

            <div class="panel-footer">
                <a href="{mpsl_link view='sliders'}" class="btn btn-default"><i class="process-icon-close"></i> {l s='Close' mod='motopressslider'}</a>
                <a href="{mpsl_link view='slider' id=$slider_id}" class="btn btn-default pull-right"><i class="process-icon-edit"></i> {l s='Slider Settings' mod='motopressslider'}</a>
            </div>
        </div>
    </div>

    <div id="mpsl-info-box"></div>
</div>
