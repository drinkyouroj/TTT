$(function() {
	$('.system-share a, a.follow, .follow-container a').on('click', function(event){
		event.preventDefault();
		
		$('#guestSignup').modal('toggle');
	});
	
	
	$('.signup-form .email-please').on('click', function() {
		$(this).fadeOut('slow',function(){
			$('.signup-form .email-group').fadeIn('slow');
		});
	});
	
});