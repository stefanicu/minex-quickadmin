// bxSlider
$(document).ready(function(){
	$('.bxslider').bxSlider;
	$('.bxsliderSediu').bxSlider({
		mode: 'fade',
		autoStart: true,
		auto: true,
		controls: false
	});
	$('.bxsliderProd').bxSlider({
		captions: true,
		pager: false
	});
	$('.bx-simlar').bxSlider({
		minSlides: 3,
		maxSlides: 6,
		slideWidth: 200,
		slideMargin: 50,
		pager: false
	});
});