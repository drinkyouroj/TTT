$(function() {
	/* This is included in the global-no-login now.
	$('.signup-form .email-please').on('click', function() {
		$(this).fadeOut('slow',function(){
			$('.signup-form .email-group').fadeIn('slow');
		});
	});*/
	
	$('form.signup_form').validate({
		ignore: [],
		rules: {
			username: {
				required: true,
				minlength: 3,
				maxlength: 15,
				remote: {
					url: window.site_url+'user/check',
					type: "GET",
					data: {
						username: function() {
							return $( "#username" ).val();
						}
					}
				}
			},
			password: {
				required: true,
				minlength: 6
			},
			password_confirmation: {
				required: true,
				minlength: 6
			}
		},
		messages: {
			username: {
				remote: "This username is already taken"
			}
		}
	});
	
});