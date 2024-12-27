$(document).ready(function () {
    $(function () {
        $('.bxslider').bxSlider({mode: 'fade', slideWidth: 400});
    });
    $(function () {
        $('.bxslider-related').bxSlider({
            minSlides: 1,
            maxSlides: 3,
            slideWidth: 360,
            slideMargin: 5,
            pager: false
        });
    });
});
$(function () {
    $('.bxslider-img').bxSlider({mode: 'fade', slideWidth: 1110, pager: false, controls: true});
});

document.getElementById('scrollButton').addEventListener('click', function () {
    const targetElement = document.getElementById('internal-pg-nav1');
    targetElement.scrollIntoView({behavior: 'smooth'});
});