jQuery(document).ready(function($) {
    "use strict";

    if ($('body').hasClass('broxme_wp-header-transparent')) {
        var fixedPadding = $('.broxme_wp-header-wrapper').height();        
        $('#broxme_wp-titlebar').css('padding-top', fixedPadding + 55); 
    }

    //to top scroller
    $(window).scroll(function() {
        var scrollPos = 300;
        if ($(this).scrollTop() > scrollPos) {
            $(".broxme_wp-totop-scroller").addClass("totop-show")
        } else {
            $(".broxme_wp-totop-scroller").removeClass("totop-show")
        }
    });

    $("html").easeScroll({
      frameRate: 60,
      animationTime: 1000,
      stepSize: 120,
      pulseAlgorithm: 1,
      pulseScale: 8,
      pulseNormalize: 1,
      accelerationDelta: 20,
      accelerationMax: 1,
      keyboardSupport: true,
      arrowScroll: 50,
      touchpadSupport: true,
      fixedBackground: true
    });
});