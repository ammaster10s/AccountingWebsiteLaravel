(function($) {
    "use strict";
    $.fn.jnewparallax = function() {
        var element, parent, bgpos, sizemode, speed, dpos, elesize, scrollpos, ratio, bgtoppos, scrolldiff;
        element = this;
        parent = $(this).parent();
        bgpos = $(this).data('position');
        sizemode = $(this).data('sizemode');
        speed = $(this).data('speed');
        var reset_paralax = function() {
            setTimeout(function() {
                init_position();
                do_parallax();
            }, 500);
        };
        var init_position = function() {
            var calcpos = function() {
                var minpos = $(parent).position().top - $(window).height();
                var maxpos = $(parent).position().top + $(parent).outerHeight();
                return {
                    'min': minpos,
                    'max': maxpos,
                    'diff': (maxpos - minpos),
                    'wh': $(window).height()
                };
            };
            var calcresize = function() {
                $(element).attr('style', '');
                var eleresizer = null;
                var toppos = 0;
                var leftpos = 0;
                if (sizemode === 'nostretch') {
                    eleresizer = {
                        'elewidth': $(element).width(),
                        'eleheight': $(element).height(),
                        'containerwidth': $(parent).outerWidth(),
                        'containerheight': $(parent).outerHeight()
                    };
                } else {
                    var imagesize = $.new_get_image_container_size($(element), $(window), 'zoom');
                    $(element).css({
                        'height': imagesize[0],
                        'width': imagesize[1]
                    });
                    eleresizer = {
                        'eleheight': imagesize[0],
                        'elewidth': imagesize[1],
                        'containerwidth': $(parent).outerWidth(),
                        'containerheight': $(parent).outerHeight()
                    };
                }
                toppos = (eleresizer.containerheight - eleresizer.eleheight) / 2;
                if (bgpos === 'center') {
                    leftpos = (eleresizer.containerwidth - eleresizer.elewidth) / 2;
                } else if (bgpos === 'left') {
                    leftpos = 0;
                } else if (bgpos === 'right') {
                    leftpos = eleresizer.containerwidth - eleresizer.elewidth;
                }
                // jpobj.doTranslate(element, leftpos + "px", toppos + "px");
                eleresizer.leftpos = leftpos;
                return eleresizer;
            };
            dpos = calcpos();
            elesize = calcresize();
        };
        var fixedbackground = function(scrollpos, ratio, scrolldiff) {
            bgtoppos = -1 * ((elesize.eleheight - (elesize.eleheight - dpos.wh)) - (speed * (dpos.wh / dpos.diff)) - scrolldiff + (ratio * speed));
            // jpobj.doTranslate(element, elesize.leftpos + "px", Math.floor(bgtoppos) + "px");

            element.css({"marginTop": Math.floor(bgtoppos) + "px"});
        };
        var do_parallax = function() {
            if (dpos !== undefined) {
                ratio = null;
                scrollpos = $(window).scrollTop();
                if (scrollpos >= dpos.min && scrollpos <= dpos.max) {
                    scrolldiff = scrollpos - dpos.min;
                    ratio = scrolldiff / dpos.diff;
                    fixedbackground(scrollpos, ratio, scrolldiff);
                }
            }
        };
        // if (!joption.ismobile) {
        $(window).bind('load resize', reset_paralax);
        $(window).bind('scroll', do_parallax);
        // }
    };
})(jQuery);