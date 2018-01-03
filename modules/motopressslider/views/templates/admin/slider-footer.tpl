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
 * Caller: /views/templates/admin/slider.tpl => /views/templates/admin/ui-settings.tpl
 *
 * Uses:
 *     bool $is_new_slider (From slider.tpl)
 *     int $slider_id (From slider.tpl)
 *     bool $is_product (From slider.tpl)
 *     int $template_id (From slider.tpl)
 *}

<div class="panel-footer">
    {if $is_new_slider}
        <a href="{mpsl_link view='sliders'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='motopressslider'}</a>
        <button id="create_slider" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Create' mod='motopressslider'}</button>
    {else}
        <a href="{mpsl_link view='sliders'}" class="btn btn-default"><i class="process-icon-close"></i> {l s='Close' mod='motopressslider'}</a>
        <button id="update_slider" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='motopressslider'}</button>
        {if !$is_product}
            <a href="{mpsl_link view='slides' id=$slider_id}" class="btn btn-default"><i class="process-icon-edit"></i> {l s='Edit Slides' mod='motopressslider'}</a>
        {else}
            <a href="{mpsl_link view='slide' id=$template_id}" class="btn btn-default"><i class="process-icon-edit"></i> {l s='Edit Template' mod='motopressslider'}</a>
        {/if}
    {/if}
</div>
