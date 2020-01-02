$(document).ready(function () {
    var $scrollToTopButton = $('.scroll-to-top--button');
    var $window = $(window);

    //scroll event
    $window.scroll(function () {
        if ($window.scrollTop() > 100) {
            $scrollToTopButton.addClass('scroll-to-top--show')
        } else {
            $scrollToTopButton.removeClass('scroll-to-top--show')
        }
    });

    //click event
    $scrollToTopButton.on('click', function (event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, '300');
    });
});
