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
	    if(!window.disable_signup) {
	        var signup = $.cookie("signup");
	        console.log(signup);
	        if(typeof signup == 'undefined') {
	            $.cookie("signup", 1, {expires: 4, path: '/'});
	            proceedToSignup();
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

});



