$(function() {

	$('.menu-btn').on('click', function(e) {
		e.preventDefault();
		$(this).toggleClass('menu-btn_active');
		$('.header-catalog').toggleClass('header-catalog_active');
	});

	video.addEventListener('click',function(){
		video.play();
	},false);
		$(video).click();
	video.play();

});


$(document).ready(function(){
	$(".main-products-item-1").click(function () {
		if ($(".main-product-item-1").is(":hidden")) {
			$(".main-product-item-1").show("slow");
			$(".main-product-item-2").hide("slow");
			$(".main-product-item-3").hide("slow");
			$(".main-product-item-4").hide("slow");
		} else {
			$(".main-product-item-1").hide("slow");
		}
		return false;
	});
	$(".main-products-item-2").click(function () {
		if ($(".main-product-item-2").is(":hidden")) {
			$(".main-product-item-2").show("slow");
			$(".main-product-item-1").hide("slow");
			$(".main-product-item-3").hide("slow");
			$(".main-product-item-4").hide("slow");
		} else {
			$(".main-product-item-2").hide("slow");
		}
		return false;
	});
	$(".main-products-item-3").click(function () {
		if ($(".main-product-item-3").is(":hidden")) {
			$(".main-product-item-3").show("slow");
			$(".main-product-item-1").hide("slow");
			$(".main-product-item-2").hide("slow");
			$(".main-product-item-4").hide("slow");
		} else {
			$(".main-product-item-3").hide("slow");
		}
		return false;
	});
	$(".main-products-item-4").click(function () {
		if ($(".main-product-item-4").is(":hidden")) {
			$(".main-product-item-4").show("slow");
			$(".main-product-item-1").hide("slow");
			$(".main-product-item-2").hide("slow");
			$(".main-product-item-3").hide("slow");
		} else {
			$(".main-product-item-4").hide("slow");
		}
		return false;
	});
});