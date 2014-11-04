jQuery('document').ready( function($) {
    "use strict";

    $(function(){
        $('body').peelback({
            adImage     : 'http://local.wordpress.dev/wp-content/uploads/2014/11/0210619_PS19017_S14-OPE_magazine_500x500_v1mm.jpg',
            peelImage   : 'http://epgmedia.s3.amazonaws.com/web/EPG%20Media/peel-image.png',
            clickURL    : '#',
            smallSize   : 75,
            bigSize     : 500,
            autoAnimate : true,
            gaTrack     : false,
            debug       : false
        });
    });

});