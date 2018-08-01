jQuery(document).ready(function($) {

    //Home header
    $(window).on('scroll',function(e){
        var top = $(this).scrollTop();
        if (top>205)
            $('#main-nav').addClass('fixed-pos');
        else
            $('#main-nav').removeClass('fixed-pos');
    });

});