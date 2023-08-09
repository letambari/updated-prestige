$(document).ready(function(){

 //sticky-header
		$(window).on("scroll", function(e){
		if ($(window).scrollTop() >= 70) {
			$('.sticky').addClass('fixed-header');
			$('.sticky').addClass('visible-title');
		}
		else {
			$('.sticky').removeClass('fixed-header');
			$('.sticky').removeClass('visible-title');
		}
    });
	
	// ______________LOADER
   $("#global-loader").fadeOut("slow");

});