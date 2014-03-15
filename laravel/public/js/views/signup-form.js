$(function() {
	/* This is included in the global-no-login now.
	$('.signup-form .email-please').on('click', function() {
		$(this).fadeOut('slow',function(){
			$('.signup-form .email-group').fadeIn('slow');
		});
	});*/
	
	$('.signup-form form').validate({
		ignore: [],
		rules: {
			password: {
				required: true,
				minlength: 6
			},
			password_confirmation: {
				required: true,
				minlength: 6
			}
		}
	});
	
});