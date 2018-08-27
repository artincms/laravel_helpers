/**
 * Created by AdelRaheli on 4/28/2018.
 */

(function ($) {
    'use strict';
    $.fn.idle = function (options) {
        var defaults = {
                idle: 60000, //idle time in ms
                events: 'mousemove keydown mousedown touchstart', //events that will trigger the idle resetter
                onIdle: function () {}, //callback function to be executed after idle time
                onActive: function () {}, //callback function to be executed after back from idleness
                onHide: function () {}, //callback function to be executed when window is hidden
                onShow: function () {}, //callback function to be executed when window is visible
                keepTracking: true, //set it to false if you want to track only the first time
                startAtIdle: false,
                recurIdleCall: false
            },
            idle = options.startAtIdle || false,
            visible = !options.startAtIdle || true,
            settings = $.extend({}, defaults, options),
            lastId = null,
            resetTimeout,
            timeout;

        //event to clear all idle events
        $(this).on( "idle:stop", {}, function( event) {
            $(this).off(settings.events);
            settings.keepTracking = false;
            resetTimeout(lastId, settings);
        });

        resetTimeout = function (id, settings) {
            if (idle) {
                idle = false;
                settings.onActive.call();
            }
            clearTimeout(id);
            if(settings.keepTracking) {
                return timeout(settings);
            }
        };

        timeout = function (settings) {
            var timer = (settings.recurIdleCall ? setInterval : setTimeout), id;
            id = timer(function () {
                idle = true;
                settings.onIdle.call();
            }, settings.idle);
            return id;
        };

        return this.each(function () {
            lastId = timeout(settings);
            $(this).on(settings.events, function (e) {
                lastId = resetTimeout(lastId, settings);
            });
            if (settings.onShow || settings.onHide) {
                $(document).on("visibilitychange webkitvisibilitychange mozvisibilitychange msvisibilitychange", function () {
                    if (document.hidden || document.webkitHidden || document.mozHidden || document.msHidden) {
                        if (visible) {
                            visible = false;
                            settings.onHide.call();
                        }
                    } else {
                        if (!visible) {
                            visible = true;
                            settings.onShow.call();
                        }
                    }
                });
            }
        });

    };
}(jQuery));

function init_seo_in_idle(idle_time,seo_job_time, scroll) {
    var idle_time = idle_time || 10000;
    var seo_job_time = seo_job_time || 5000;
    var scroll = scroll || false;
    $(document).idle({
        onIdle: function(){
            if (scroll)
            {
                setTimeout(function(){
                    window.scrollTo(0,document.body.scrollHeight/8);
                }, seo_job_time/8);

                setTimeout(function(){
                    window.scrollTo(0,document.body.scrollHeight/7);
                }, seo_job_time/7);

                setTimeout(function(){
                    window.scrollTo(0,document.body.scrollHeight/5);
                }, seo_job_time/5);

                setTimeout(function(){
                    window.scrollTo(0,document.body.scrollHeight/2);
                }, seo_job_time/2);

                setTimeout(function(){
                    window.scrollTo(0,document.body.scrollHeight);
                }, seo_job_time/1.5);
            }
            setTimeout(function(){
                window.location.reload(1);
            }, seo_job_time);
        },
        idle: idle_time
    });
}