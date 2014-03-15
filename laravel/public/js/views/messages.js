$(function() {
	$('.show-condensed').on('click', function() {
		$(this).fadeOut('slow', function() {
			$('.condensed-section').slideDown();
		});
	});
});
