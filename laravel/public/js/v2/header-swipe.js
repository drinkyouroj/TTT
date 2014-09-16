$(function() {
	$(window).swipe({
		swipeLeft: function() {
		  	// Close
	  		$.sidr('close', 'offcanvas-sidebar');
		},
		swipeRight: function() {
		  	// Open
		  	$.sidr('open', 'offcanvas-sidebar');
		},
		preventDefaultEvents: false,
		threshold: 25 // min pixel swipe distance
	});
});