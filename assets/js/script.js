/*!
 * VA Social Buzz.
 *
 * @package   VisuAlive.
 * @version   1.0.0
 * @author    KUCKLU.
 * @copyright Copyright (c) KUCKLU and VisuAlive.
 * @link      http://visualive.jp/
 * @license   GNU General Public License version 2.0 later.
 */
;
(function ($, window, document, undefined) {
    'use strict';

    var vaSocialBuzz = {
        /**
         * Define cache var.
         *
         * @since 1.0.0
         */
        cache: {},

        /**
         * Main Function.
         *
         * @since 1.0.0
         */
        init: function () {
            this.cacheElements();
            this.bindEvents();
        },

        /**
         * Cache Elements.
         *
         * @since 1.0.0
         */
        cacheElements: function () {
            this.cache = {
                $window  : $(window),
                window   : window,
                $document: $(document),
                document : document,
                wordpress: vaSocialBuzzSettings
            };
        },

        /**
         * Bind Events.
         *
         * @since 1.0.0
         */
        bindEvents: function () {
            // Store object in new var
            var self = this;

            // Ajax Cache
            $.ajaxSetup({
                cache: true,
                async: true
            });

            // Run on document ready
            self.cache.$document.on('ready', function () {
                self.getJavaScriptSDK();
                self.createElements();
                self.gaEventTracking()
            });
        },

        /**
         * Escape.
         *
         * @since 1.0.0
         * @param str
         * @returns {*}
         * @private
         */
        _escapeHTML: function (str) {
            return str.replace(/<("[^"]*"|'[^']*'|[^'">])*>/g, '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/\'/g, '&apos;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        },

        /**
         * Deletion other than alphanumeric & hyphen.
         *
         * @since 1.0.0
         * @param str
         * @returns {*}
         * @private
         */
        _deletionOtherAlphanumeric: function (str) {
            return str.replace(/[^\w\-]/g, '');
        },

        /**
         * Deletion other than alphanumeric & hyphen.
         *
         * @since 1.0.0
         * @param str
         * @returns {*}
         * @private
         */
        _deletionOtherNumeric: function (str) {
            return str.replace(/[^0-9]/g, '');
        },

        /**
         * Load js sdk.
         *
         * @since 1.0.0
         */
        getJavaScriptSDK: function () {
            var self = this;

            $.getScript('//connect.facebook.net/' + self._deletionOtherAlphanumeric(self.cache.wordpress.locale) + '/sdk.js', function () {
                if (typeof self.cache.wordpress.appid != 'undefined') {
                    FB.init({appId : self._deletionOtherNumeric(self.cache.wordpress.appid), version : 'v2.5', status : true, cookie : true, xfbml : true});
                } else {
                    FB.init({version: 'v2.5', status: true, cookie: true, xfbml: true});
                }
            });

            $.getScript('//platform.twitter.com/widgets.js', function () {
                twttr.widgets.load();
            });
        },

        /**
         * Create Fb elements.
         *
         * @since 1.0.0
         */
        createElements: function () {
            var self = this,
                fbRoot = self.cache.document.getElementById('fb-root'),
                body;

            if (null == fbRoot) {
                body = self.cache.document.body;
                fbRoot = self.cache.document.createElement('div');

                fbRoot.setAttribute('id', 'fb-root');
                body.insertBefore(fbRoot, body.firstChild);
            }
        },

        /**
         * Google Analytics Events Tracking.
         */
        gaEventTracking: function () {
            var self      = this,
                $vasb     = $('#va-social-buzz'),
                $facebook = $vasb.find('.vasb_share_button-fb').children('a'),
                $twitter  = $vasb.find('.vasb_share_button-tw').children('a');

            if (typeof self.cache.window.GoogleAnalyticsObject != 'undefined' && self.cache.window.GoogleAnalyticsObject == 'ga') {
                window.onload = function () {
                    if (typeof (FB) != 'undefined') {
                        FB.Event.subscribe('edge.create', function (url) {
                            ga('send', 'social', 'facebook', 'like', url);
                        });
                        FB.Event.subscribe('edge.remove', function (url) {
                            ga('send', 'social', 'facebook', 'unlike', url);
                        });
                    }

                    if (typeof (twttr) != 'undefined') {
                        twttr.ready(function (twttr) {
                            twttr.events.bind('follow', function (e) {
                                ga('send', 'social', 'twitter', 'follow', self.cache.window.location.href);
                            });
                            twttr.events.bind('tweet', function () {
                                ga('send', 'social', 'twitter', 'tweet', self.cache.window.location.href);
                            });
                        } );
                    }

                    $facebook.on('click touchstart', function ( e ) {
                        e.preventDefault();

                        if (typeof (FB) != 'undefined' && typeof self.cache.wordpress.appid != 'undefined') {
                            FB.ui({
                                method      : 'share',
                                href        :  self.cache.window.location.href,
                                redirect_uri:  self.cache.window.location.href
                            }, function (response) {
                                if ( response !== null && typeof response.post_id !== 'undefined' ) {
                                    ga('send', 'social', 'facebook', 'share', self.cache.window.location.href);
                                }
                            });
                        } else {
                            ga('send', 'social', 'facebook', 'shareClick', self.cache.window.location.href);
                            self._shareNewWindow( this );
                        }
                    });

                    $twitter.on('click touchstart', function ( e ) {
                        e.preventDefault();
                        ga('send', 'social', 'twitter', 'tweetClick', self.cache.window.location.href);
                        self._shareNewWindow( this );
                    });
                }
            }
        },

        /**
         * Open new window.
         *
         * @since 1.0.3
         *
         * @param t this
         * @private
         */
        _shareNewWindow: function ( t ) {
            var href = $( t ).attr('href');

            window.open(href, '', 'width=550,height=500');
        }
    };

    // Get things going
    vaSocialBuzz.init();
})(window.jQuery, window, document, undefined);
