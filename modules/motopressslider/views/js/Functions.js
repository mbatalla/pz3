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

(function($, MPSL) {
    MPSL.TIMEOUT = 10000;

    MPSL.SUCCESS = 'success';
    MPSL.INFO    = 'info';
    MPSL.WARNING = 'warning';
    MPSL.ERROR   = 'error';

    MPSL.FULLSIZE = 'mpsl_fullsize'; // See also MotoPressSlider::FULLSIZE in motopressslider.php

    MPSL.Preloader = can.Construct(
        {
            class_name: '.mpsl-preloader',
            preloader_element: $('<div class="mpsl-preloader">'),
            global_preloader_element: $('.mpsl-global-preloader'),
            disabled: false,

            start: function (element) {
                if (this.disabled) {
                    return false;
                }

                if (typeof element === 'undefined') {
                    this.global_preloader_element.show();
                } else if (element.length) {
                    element.append(this.preloader_element.clone());
                }
            },

            stop: function (element) {
                if (typeof element === 'undefined') {
                    this.global_preloader_element.hide();
                } else if (element.length) {
                    element.children(this.class_name).remove();
                }
            },

            disable: function () {
                this.disabled = true;
            },

            enable: function () {
                this.disabled = false;
            }
        },
        {}
    ); // End of MPSL.Preloader

    MPSL.Functions = can.Construct.extend(
        {
            inArray: function (value, array) {
                return ($.inArray(value, array) > -1);
            },

            typeIn: function (value, array) {
                return ($.inArray(typeof value, array) > -1);
            },

            isNot: function (value) {
                return (typeof value === 'undefined' || !value);
            },

            getYoutubeId: function (url) {
                var regular_expr = /^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)|([A-za-z0-9_-]{11})/;
                var matches = url.match(regular_expr);
                if (matches != null && 1 in matches) {
                    return matches[1];
                } else {
                    return '';
                }
            },

            getVimeoId: function (url) {
                var regular_expr = /^(?:(?:https?:\/\/|)(?:www\.|player\.|)vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:\/|\?|\#\w*|))|(\d+)$/;
                var matches = url.match(regular_expr);
                if (matches != null && 1 in matches) {
                    return matches[1];
                } else {
                    return '';
                }
            },

            addParamsToUrl: function (url, params) {
                $.each(params, function (key, value) {
                   url = MPSL.Functions.addParamToUrl(url, key, value);
                });
                return url;
            },

            addParamToUrl: function (url, key, value) {
                var query = url.indexOf('?');
                var anchor = url.indexOf('#');

                // If have "?" without args then remove "?" from URL
                if (query == url.length - 1) {
                    url = url.substring(0, query);
                    query = -1; // "?" not found
                }

                // Create new URL
                var new_url = '';

                // Get URL without anchor
                if (anchor >= 0) {
                    // Copy till anchor
                    new_url = new_url + url.substring(0, anchor);
                } else {
                    // No anchor; copy full URL
                    new_url = new_url + url;
                }

                // Add query vars
                if (query >= 0) {
                    // Query already exists; just add new args
                    new_url = new_url + '&' + key + '=' + value;
                } else {
                    // Query not exists yet; add "?" and new args
                    new_url = new_url + '?' + key + '=' + value;
                }

                // Add anchor
                if (anchor >= 0) {
                    new_url = new_url + url.substring(anchor);
                }

                return new_url;
            },

            removeParamsFromUrl: function (url, params) {
                for (var key in params) {
                    url = MPSL.Functions.removeParamFromUrl(url, params[key]);
                }
                return url;
            },

            removeParamFromUrl: function (url, param) {
                var expr = new RegExp(param + '\\=([a-z0-9]+)', 'i');
                var match = url.match(expr);
                if (match) {
                    var url_part = match[0];
                    if (url.search('&' + url_part) >= 0) {
                        // Remove URL part ("...&key=value..."), but leave other
                        // args
                        url = url.replace('&' + url_part, '');
                    } else if (url.search('\\?' + url_part + '&') >= 0) {
                        // Remove URL part, but leave the "?" with other query
                        // args
                        url = url.replace('?' + url_part + '&', '?');
                    } else if (url.search('\\?' + url_part) >= 0) {
                        // No more args except URL part; remove query arg along
                        // with "?" char
                        url = url.replace('?' + url_part, '');
                    }
                }
                return url;
            },

            showMessage: function (message, type) {
                if (typeof type === 'undefined') {
                    type = MPSL.INFO;
                }

                var message_html = $(
                    '<div />',
                    {
                        'class': 'mpsl-message mpsl-message-' + type,
                        'html': message
                    }
                );

                var icon_class = '';
                if (type === MPSL.ERROR) {
                    icon_class = 'mpsl-icon-error';
                } else {
                    icon_class = 'mpsl-icon-info';
                }
                var icon = $('<i />', {'class': icon_class});

                message_html.prepend(icon);

                // Add message for MPSL.TIMEOUT miliseconds
                $('#mpsl-info-box').append(message_html);
                var timeout = setTimeout(function () {
                    message_html.remove();
                    clearTimeout(timeout);
                }, MPSL.TIMEOUT);

                return true;
            },

            getScrollbarWidth: function () {
                var scroll_width = window.browserScrollbarWidth;
                if (typeof scroll_width === 'undefined') {
                    // Create custom block with scrollbar and get it`s width
                    var div = $('<div style="width: 50px; height: 50px; position: absolute; left: -100px; top: -100px; overflow: auto;"><div style="width: 1px; height: 100px;"></div></div>');
                    $('body').append(div);
                    scroll_width = div[0].offsetWidth - div[0].clientWidth;
                    div.remove();
                }
                return scroll_width;
            },

            uniqid: function (prefix, more_entropy) {
                // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
                // +    revised by: Kankrelune (http://www.webfaktory.info/)
                // %        note 1: Uses an internal counter (in php_js global) to avoid collision
                // *     example 1: uniqid();
                // *     returns 1: 'a30285b160c14'
                // *     example 2: uniqid('foo');
                // *     returns 2: 'fooa30285b1cd361'
                // *     example 3: uniqid('bar', true);
                // *     returns 3: 'bara20285b23dfd1.31879087'
                if (typeof prefix === 'undefined') {
                    prefix = '';
                }

                var ret_id;
                var formatSeed = function (seed, req_width) {
                    seed = parseInt(seed, 10).toString(16); // To hex string
                    if (req_width < seed.length) { // So long we split
                        return seed.slice(seed.length - req_width);
                    }
                    if (req_width > seed.length) { // So short we pad
                        return Array(1 + (req_width - seed.length)).join('0') + seed;
                    }
                    return seed;
                };

                // Begin redundant
                if (!this.php_js) {
                    this.php_js = {};
                }
                // End redundant
                if (!this.php_js.uniqid_seed) { // Init seed with big random int
                    this.php_js.uniqid_seed = Math.floor(Math.random()*0x75bcd15);
                }
                this.php_js.uniqid_seed++;

                ret_id = prefix; // Start with prefix, add current milliseconds hex string
                ret_id += formatSeed(parseInt(new Date().getTime()/1000, 10), 8);
                ret_id += formatSeed(this.php_js.uniqid_seed, 5); // Add seed hex string
                if (more_entropy) {
                    // For more entropy we add a float lower to 10
                    ret_id += (Math.random()*10).toFixed(8).toString();
                }

                return ret_id;
            },

            ucfirst: function (str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            },

            disableSelection: function (element) {
                element.attr('unselectable', 'on')
                       .css({
                           '-moz-user-select': '-moz-none',
                           '-moz-user-select': 'none',
                           '-o-user-select': 'none',
                           '-khtml-user-select': 'none', // You could also put this in a class
                           '-webkit-user-select': 'none', // And add the CSS class here instead
                           '-ms-user-select': 'none',
                           'user-select': 'none'
                       })
                       .bind('selectstart', function () {
                           return false;
                       });
            },

            checkAliasExists: function (alias) {
                if (typeof MPSL.Vars.settings.aliases !== 'undefined') {
                    return (alias in MPSL.Vars.settings.aliases);
                } else {
                    var action = 'check_alias_exists';
                    var response = MPSL.Functions.najax(action, {'alias': alias});

                    if (response && response.result) {
                        // Also save aliases to Vars.settings
                        MPSL.Vars.settings.aliases = response.aliases;
                        return response.is_exist;
                    } else {
                        return true; // Do not reject that we don't know
                    }
                }
            },

            // Not asynchronous AJAX
            najax: function (action, data) {
                if (typeof data === 'undefined') {
                    data = {};
                }

                data['action'] = 'mpsl_' + action;
                data['nonce'] = MPSL.Vars.nonces[action];

                var result = false;

                $.ajax({
                    'type': 'GET',
                    'url': MPSL.Vars.ajax_url,
                    'data': data,
                    'async': false,
                    'dataType': 'JSON',
                    'success': function (response) {
                        if (response.hasOwnProperty('result')) {
                            result = response;
                        }
                    }
                });

                return result;
            },

            ajax: function (action, data, callback) {
                if (typeof data === 'undefined') {
                    data = {};
                }

                data['action'] = 'mpsl_' + action;
                data['nonce'] = MPSL.Vars.nonces[action];

                $.ajax({
                    'type': 'POST',
                    'url': MPSL.Vars.ajax_url,
                    'data': data,
                    'dataType': 'JSON',

                    'success': function (response) {
                        if (response.result) {
                            MPSL.Functions.showMessage(response.message, MPSL.SUCCESS);
                            // Run success callback
                            if (typeof callback !== 'undefined') {
                                callback(response);
                            }
                        } else {
                            MPSL.Functions.showMessage(response.message, MPSL.ERROR);
                        }
                    },

                    'error': function (jqXHR) {
                        var error = $.parseJSON(jqXHR.responseText);
                        if (error.is_debug) {
                            MPSL.Functions.showMessage(error.message, MPSL.ERROR);
                        }
                        console.error(error);
                    }
                });
            },

            imgUrl: function (file) {
                // url = ".../img/motopressslider/"
                var url = MPSL.Vars.img_url;
                // url = ".../img/motopressslider/No_Lorem_Ipsum.jpg"
                url += file;
                return url;
            },

            productImgUrl: function (cover_id, image_type) {
                var id_str = cover_id.toString();
                var format = 'jpg';
                if (cover_id in MPSL.Vars.cover_formats) {
                    format = MPSL.Vars.cover_formats[cover_id];
                }
                // url = ".../img/p/"
                var url = MPSL.Vars.product_img_url;
                // url = ".../img/p/1/0/"
                var folders = id_str.split('');
                url += folders.join('/') + '/';
                // url = ".../img/p/1/0/10"
                url += id_str;
                // url = ".../img/p/1/0/10-medium_default"
                if (typeof image_type !== 'undefined' && image_type != MPSL.FULLSIZE) {
                    url += '-' + image_type;
                }
                // url = ".../img/p/1/0/10-medium_default.jpg"
                url += '.' + format;
                return url;
            }
        },
        {}
    ); // End of MPSL.Functions
})(jQuery, MPSL);

jQuery(function ($) {
    // Remove message box on click
    $('#mpsl-info-box').on('click', '.mpsl-message', function (element) {
        $(this).remove();
    });
});
