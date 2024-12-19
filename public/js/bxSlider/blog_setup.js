$(document).ready(function () {
    $(function () {
        $('.bxslider').bxSlider({mode: 'fade', slideWidth: 345});
    });
    $(function () {
        $('.bxslider-related').bxSlider({
            minSlides: 1,
            maxSlides: 2,
            slideWidth: 345,
            infiniteLoop: true,
            slideMargin: 5,
            pager: false
        });
    });
});
$(function () {
    $('.bxslider-img').bxSlider({mode: 'fade', slideWidth: 345, pager: true, controls: true});
});