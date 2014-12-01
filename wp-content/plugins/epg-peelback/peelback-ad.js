jQuery('document').ready( function($) {
    "use strict";

    $(function(){
        $('body').peelback({
            adImage     : '//epgmediallc.com/wp-content/uploads/2014/11/synchrony-1411.jpg',
            peelImage   : '//epgmedia.s3.amazonaws.com/web/EPG%20Media/peel-image.png',
            clickURL    : 'https://www.synchronybusiness.com/markets/outdoor-power-equipment-financing.html',
            smallSize   : 75,
            bigSize     : 500,
            autoAnimate : true,
            gaTrack     : false,
            debug       : false
        });
    });

});