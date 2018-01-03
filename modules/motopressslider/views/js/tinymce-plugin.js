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

// Aslo see file /js/admin/tinymce.inc.js
if( tinyMCE && MPSLShortcodes ) {

    function mpsl_tinymce3_plugin() {
        // See also "theme_advanced_buttons2"
        tinyMCE.create('tinymce.plugins.motopressslider', {
            createControl: function (name, manager) {
                if( MPSLShortcodes.length > 0 ) {
                    switch( name ) {
                        case 'insert_mpsl':
                            var list_box = manager.createListBox('insert_mpsl', {
                                title: 'Insert MotoPress Slider',
                                onselect: function (v) {
                                    tinyMCE.execCommand('mceInsertContent', false, v);
                                }
                            });
                            MPSLShortcodes.forEach(function (item) {
                                list_box.add(item.name, item.shortcode);
                            });
                            return list_box;
                            break;
                    }
                }
                return null;
            }
        });
    }

    function mpsl_tinymce4_plugin() {
        tinyMCE.PluginManager.add('motopressslider', function (editor) {
            var shortcodes = [],
                active_editor = tinyMCE.activeEditor;

            if( MPSLShortcodes.length > 0 ) {
                MPSLShortcodes.forEach(function (item) {
                    shortcodes.push({
                        text: item.name,
                        onclick: function () {
                            active_editor.insertContent(item.shortcode);
                        }
                    });
                });

                editor.addMenuItem('insert_mpsl', {
                    text: 'MotoPress Slider',
                    context: 'insert',
                    icon: false,
                    menu: shortcodes
                });
            }
        });
    }

    if( tinyMCE.majorVersion === '4' ) {
        mpsl_tinymce4_plugin();
    } else {
        mpsl_tinymce3_plugin();
    }
} // if( tinyMCE && MPSLShortcodes )
