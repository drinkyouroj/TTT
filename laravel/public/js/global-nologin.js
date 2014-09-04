$(function() {
	$('.system-share a, a.follow, .follow-container a').on('click', function(event){
		event.preventDefault();
		
		$('#guestSignup').modal('toggle');
	});
	
	// This makes sure that the sidebar is closed in the event that the signup modal comes up
	$('#guestSignup').on('show.bs.modal', function (e) {
		$.sidr('close', 'offcanvas-sidebar', function() {
        });
	});


	$('.signup-form .email-please').on('click', function() {
		$(this).fadeOut('slow',function(){
			$('.signup-form .email-group').fadeIn('slow');
		});
	});
	
	$('.comments-listing').on('click', '.flag-comment', function() {
		$('#guestSignup').modal('toggle');
	});
	$('.comments-listing').on('click', '.like-comment', function() {
		$('#guestSignup').modal('toggle');
	});

	$('.comment-reply').submit(function(event) {
		event.preventDefault();
		$('#guestSignup').modal('toggle');
	});

});