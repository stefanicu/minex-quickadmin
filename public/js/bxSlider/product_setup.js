$(document).ready(function () {
    $(function () {
        $('.bxslider').bxSlider({mode: 'fade', slideWidth: 400});
    });
    $(function () {
        $('.bxslider-related').bxSlider({minSlides: 3, maxSlides: 6, slideWidth: 250, slideMargin: 50, pager: false});
    });
});
$(function () {
    $('.bxslider-img').bxSlider({mode: 'fade', slideWidth: 800, pager: false, controls: true});
});