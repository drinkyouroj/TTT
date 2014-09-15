$(function() {
	$('.system-share a, a.follow, .follow-container a').on('click', function(event){
		event.preventDefault();
		window.location.href = window.site_url+'user/signup';
	});

	$('.signup-form .email-please').on('click', function() {
		$(this).fadeOut('slow',function(){
			$('.signup-form .email-group').fadeIn('slow');
		});
	});
	
	$('.comments-listing').on('click', '.flag-comment, .like-comment, .reply', function() {
		window.location.href = window.site_url+'user/signup';
	});

	$('.comment-reply').submit(function(event) {
		event.preventDefault();
		window.location.href = window.site_url+'user/signup';
	});


	$('.sidebar-option.feed a, .sidebar-option.save a').click(function(event) {
		event.preventDefault();
		window.location.href = window.site_url+'user/signup';
	});

});