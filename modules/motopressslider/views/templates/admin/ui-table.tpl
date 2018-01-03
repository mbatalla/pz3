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
 *     - /views/templates/admin/sliders.tpl
 *     - /views/templates/admin/slides.tpl
 *
 * Uses:
 *     array $table Table data:
 *         array(
 *             'name' => ...,
 *             'columns' => array(
 *                 %name% => array(
 *                     'title' => ...,
 *                     ['class'] => ...,
 *                     ['type'] => 'actions', 'sort'
 *                 ),
 *                 ...
 *             ),
 *             ['actions'] => array(
 *                 array(
 *                     'title' => ...,
 *                     ['link'] => ...,
 *                     ['view'] => ...,
 *                     ['id_name'] => ...,
 *                     ['icon'] => ...,
 *                     ['type'] => 'divider',
 *                     ['atts'] => array(
 *                         %attribute name% => %column name%,
 *                         ...
 *                     )
 *                 ),
 *                 ...
 *             ),
 *             'rows' => array(
 *                 array(
 *                     %name 1% => %value 1%,
 *                     %name 2% => %value 2%,
 *                     ...
 *                 ),
 *                 ...
 *             ),
 *             ['push'] => %column name% // Push data- attribute to <tr> with the value of %column name%
 *         )
 *}

{$cols_count = count($table['columns'])}
{$rows_count = count($table['rows'])}

{strip}
    <div class="table-responsive-row wide clearfix">
        <table id="{$table['name']|escape:'htmlall':'UTF-8'}-table" class="table {$table['name']|escape:'htmlall':'UTF-8'}">
            <thead>
                <tr class="nodrag nodrop">
                    {foreach from=$table['columns'] item=col}
                        <th {if isset($col['class'])}class="{$col['class']|escape:'htmlall':'UTF-8'}"{/if}>
                            <span class="title_box">{$col['title']|escape:'htmlall':'UTF-8'}</span>
                        </th>
                    {/foreach}
                </tr>
            </thead>
            <tbody>

                {$is_odd = true}
                {foreach from=$table['rows'] item=row}
                    <tr {if $is_odd}class="odd" {/if}
                        {if isset($table['push'])}
                            {* data-slides-id="1" *}
                            {$value = $row[$table['push']]}
                            data-{$table['name']|escape:'htmlall':'UTF-8'}-{$table['push']|escape:'htmlall':'UTF-8'}="{$value|escape:'htmlall':'UTF-8'}"
                        {/if}
                    >
                        {* Row columns *}
                        {foreach from=$table['columns'] key=col_name item=col}
                            <td {if isset($col['class'])}class="{$col['class']|escape:'htmlall':'UTF-8'}"{/if}>
                                {* Column content - general content, sort icon or action buttons *}
                                {if !isset($col['type'])}
                                    {$col['type'] = 'general'}
                                {/if}
                                {if $col['type'] == 'sort'}
                                    {* Sort icon *}
                                    <div class="mpsl-sort-icon"></div>
                                {elseif $col['type'] == 'actions'}
                                    {* Action buttons *}
                                    <div class="btn-group-action">
                                        <div class="btn-group pull-right">
                                            {$main_action = reset($table['actions'])}
                                            {$skip_action = key($table['actions'])}
                                            {include file="$admin_templates/ui-action-button.tpl" action=$main_action record=$row is_button=true}
                                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-caret-down"></i>&nbsp;
                                            </button>
                                            <ul class="dropdown-menu">
                                                {foreach from=$table['actions'] key=key item=action}
                                                    {if $key != $skip_action}
                                                        {if !isset($action['type']) || $action['type'] != 'divider'}
                                                            <li>
                                                                {include file="$admin_templates/ui-action-button.tpl" action=$action record=$row}
                                                            </li>
                                                        {else}
                                                            <li class="divider"></li>
                                                        {/if}
                                                    {/if}
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                {else}
                                    {* General content *}
                                    {htmlspecialchars_decode($row[$col_name]|escape:'htmlall':'UTF-8')}{* HTML, cannot escape, but also can't skip "escape" filter *}
                                {/if}
                            </td>
                        {/foreach}
                    </tr>
                    {$is_odd = !$is_odd}
                {/foreach}

                <tr class="no-records-row" {if $rows_count > 0}style="display: none"{/if}>
                    <td class="list-empty" colspan="{$cols_count|escape:'htmlall':'UTF-8'}">
                        <div class="list-empty-msg">
                            <i class="icon-warning-sign list-empty-icon"></i>
                            {l s='No records found' mod='motopressslider'}
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
{/strip}
