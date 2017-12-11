/*
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
 */

jQuery(function ($) {
    // Show/hide "Auth" inputs by clicking on "Auth" checkbox
    var auth_switcher = $('#http_auth_on, #http_auth_off');
    var auth_on = $('#http_auth_on');
    var depend_wrappers = $('.need-http_auth');
    var depend_inputs = depend_wrappers.find('input');
    auth_switcher.on('change', function (event) {
        if( auth_on.is(':checked') ) {
            depend_inputs.removeAttr('disabled').attr('required', 'required');
            depend_wrappers.show();
        } else {
            depend_inputs.removeAttr('required').attr('disabled', 'disabled');
            depend_wrappers.hide();
        }
    });

    // Check/uncheck all lines in "Export" table by clicking on the checkbox in
    // the <thead>
    $("#export-check-all").change(function (event) {
        var export_checks = $('.export-check');
        if( $(this).attr("checked") ) {
            export_checks.attr("checked", "checked");
        } else {
            export_checks.removeAttr("checked");
        }
    });

    // Check/uncheck line in "Export" table by clicking on the <tr>
    $(".mpsl-export-table .table-row").on("click", function (e) {
        var checkbox = $(this).find(".export-check");
        if( checkbox.length > 0 ) {
            checkbox = checkbox.first();
            if( !checkbox.attr("checked") ) {
                checkbox.attr("checked", "checked");
            } else {
                checkbox.removeAttr("checked");
            }
        }
    });

    // Stop event propagation for checkbox clicks; otherwise <tr> click handler
    // will restore the old value
    $(".export-check").on("click", function (event) {
        event.stopPropagation();
    });
});
