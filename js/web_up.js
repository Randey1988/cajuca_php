$('.scrollup').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 600);
    return false;
});

$(window).scroll(function(){
    if( $(this).scrollTop() > 100 ){
        $('.scrollup').fadeIn();
    } else {
        $('.scrollup').fadeOut();
    }
});