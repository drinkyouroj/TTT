$(function() {
	window.logged_in = false;

	$('.system-share a, a.follow, .follow-container a').on('click', function(event){
		event.preventDefault();
		proceedToSignup();
	});

	$('.signup-form .email-please').on('click', function() {
		$(this).fadeOut('slow',function(){
			$('.signup-form .email-group').fadeIn('slow');
		});
	});
	
	$('.comments-listing').on('click', '.flag-comment, .like-comment, .reply', function() {
		proceedToSignup();
	});

	$('.comment-reply').submit(function(event) {
		event.preventDefault();
		proceedToSignup( true );
	});

	$('.post-action-bar a, .utility-container a').click(function(event) {
		event.preventDefault();
		proceedToSignup();
	});

	$('.sidebar-option.feed a, .sidebar-option.saves a').click(function(event) {
		event.preventDefault();
		proceedToSignup();
	});

	$(window).load(function(){
		if(window.redirect == false) {
		    if(!window.disable_signup) {
		        var signup = $.cookie("signup");
		        if(typeof signup == 'undefined') {
		            $.cookie("signup", 1, {expires: 4, path: '/'});
		            proceedToSignup();
		        }
		    }
		}
	});

	function proceedToSignup ( save_comment ) {
		// For now we just bring up the modal!
		// window.location.href = window.site_url + 'user/signup';
		$('#signupModal').modal('show');
        $('.modal-backdrop.in').addClass('half');
        var current_location = window.location.pathname;
        $('#signupModal form .redirect').remove();  // Just incase the input already exists
        $('#signupModal form').append('<input class="redirect" name="redirect" type="hidden" value="' + current_location + '">');
       	if ( save_comment ) {
       		var comment = $('.comment-form textarea').val();
       		$('#signupModal form .restore-comment').remove();  // Just incase the input already exists
        	$('#signupModal form').append('<input class="restore-comment" name="restore-comment" type="hidden" value="' + comment + '">');
       	}
	}

	// The dynamic input fields for signup
	$('#password').on('input', function() {
		$('#password_confirmation').slideDown();
	});
	$('.signup_form .inputs').on('input', function() {
		// If username, password, and password confirmation fields are all populated => display captcha
		var username = $('#username').val();
		var password = $('#password').val();
		var password_confirmation = $('#password_confirmation').val();

		if ( username.length && password.length && password_confirmation.length ) {
			$('#captcha, .captcha-equation').slideDown();
		}
	});
	// Random username generate (used in signup)
	$('.rando').click( function() {
		$.ajax({
			url: window.site_url + 'rest/random',
			success: function ( data ) {
				if ( data && data.username ) {
					$('#username').val( data.username );
				}
			}
		});
	});
});



