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

(function ($, MPSL) {
    MPSL.Tabs = can.Control.extend(
        {
            defaults: {
                active: 0, // Active tab number, starting from 0
                on_change: null
            }
        },
        {
            element: null,
            tabs: null,
            panels: null,
            all_tabs: null,
            all_panels: null,
            count: 0,
            active: 0,
            on_change: null,

            init: function (element, settings) {
                var self = this;
                this.element = element;

                // Get tabs and panels
                this.tabs = {};
                this.panels = {}

                this.all_tabs = element;
                var all_hrefs = '';

                this.element.each(function (index) {
                    // Add tab
                    var tab = $(this);
                    self.tabs[index] = tab;
                    tab.removeClass('active');
                    tab.data('no', index);
                    // Add panel
                    var href = tab.attr('href');
                    all_hrefs += (index > 0 ? ', ' + href : href);
                    var panel = $(href);
                    self.panels[index] = panel;
                    panel.hide();
                })

                this.all_panels = $(all_hrefs);

                // Update settings
                settings = $.extend({}, MPSL.Tabs.defaults, settings);
                this.count = this.element.length;
                this.active = Math.min(this.count, Math.max(settings.active, 0));
                this.on_change = settings.on_change;

                // Show active tab
                if (this.count > 0) {
                    this.tabs[this.active].addClass('active');
                    this.panels[this.active].show();
                    this.onChange(this.active);
                }

                // Add "click" handler
                this.element.click(function (event) {
                    event.preventDefault();

                    var no = $(this).data('no');
                    self.selectTab(no);
                });
            },

            selectTab: function (no) {
                var old_no = this.active;
                if (no == old_no) {
                    return;
                }

                this.tabs[old_no].removeClass('active');
                this.panels[old_no].hide();
                this.panels[no].show();
                this.tabs[no].addClass('active');

                this.active = no;

                this.onChange(no);
            },

            getActivePanel: function () {
                if (this.count > 0) {
                    return this.panels[this.active];
                } else {
                    return null;
                }
            },

            getAllPanels: function () {
                return this.all_panels;
            },

            getAllTabs: function () {
                return this.all_tabs;
            },

            onChange: function (no) {
                if (this.on_change) {
                    this.on_change(this.tabs[no], this.panels[no]);
                }
            },

            getTabs: function() {
                return 
            }
        }
    ); // End of MPSL.Tabs
})(jQuery, MPSL);
