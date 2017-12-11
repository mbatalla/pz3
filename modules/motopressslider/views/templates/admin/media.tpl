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
 * Caller: /controllers/admin/MPSLMediaController.php
 *
 * Uses:
 *     string $admin_templates Path to templates dir:
 *                             ".../motopressslider/views/templates/admin" (no
 *                             slash in the end).
 *}

<div class="row">
    <div class="col-lg-12">
        {include file="$admin_templates/media-library.tpl" sid='mpsl-media-library' is_frame=false}
    </div>
    <div id="mpsl-info-box"></div>
</div>
