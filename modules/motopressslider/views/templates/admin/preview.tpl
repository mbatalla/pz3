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
 * Caller: /controllers/admin/MPSLPreviewController.php
 *
 * Uses:
 *     MPSLSlider $slider Slider object.
 *     bool $is_slide_preview
 *}

<div class="mpsl-wrapper wrap">
    <div class="mpsl-preview-wrapper">
        {motoslider slider=$slider slide_preview=$is_slide_preview}

        {* Hide admin area *}
        {literal}
            <style type="text/css">
                body {
                    background-color: #eeeeee;
                }

                #header,
                #footer,
                #main > *,
                #content > * {
                    display: none !important;
                }

                #main > #content,
                #content > .mpsl-wrapper,
                #content > .motoslider_wrapper {
                    display: block !important;
                }

                body {
                    display: table;
                    width: 100%;
                }

                #main,
                .mpslpreview #main {
                    margin: 0;
                    padding: 0;
                    display: table-cell;
                    min-height: 0;
                    vertical-align: middle;
                    float: none;
                }

                #content,
                .page-sidebar #content {
                    margin: 0;
                    padding: 0 10px;
                    background: inherit;
                    min-width: 0;
                }
            </style>
            <script type="text/javascript">
                window.mpslResizePreview = function() {
                    jQuery(window).trigger('resize');
                };
            </script>
        {/literal}
    </div>
</div><!-- End of .mpsl-wrapper.wrap -->
