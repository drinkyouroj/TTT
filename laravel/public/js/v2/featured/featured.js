$(function() {	
	//Makes the top banner have a background and shadow when scrolling down on the featured page.
	$(window).scroll(function() {
		// Pagination for content
		var scrollTop = $(window).scrollTop(); 
		var windowHeight = $(window).height();
		var documentHeight = $(document).height();
		if ( scrollTop > 50 ) {
			$('.header-inner-wrapper').addClass('active');
		} else {
			$('.header-inner-wrapper').removeClass('active');
		}
	});

// ========================== Fixed signup Bar ===========================
	middle = $('.middle-wrapper').offset();
	window.show_join = middle.top - 200;
	window.reached = false;

	$(window).scroll(function(event) {
		current = $(window).scrollTop();
		if(current > window.show_join) {
			$('.join-banner').show().fadeIn();
			$('.content-wrapper').css('margin-bottom',$('.join-banner').height());
		}
	});

	if ( !window.logged_in ) {
		$( window ).resize(function() {
	  		$('.content-wrapper').css('margin-bottom',$('.join-banner').height());
		});
	}
});