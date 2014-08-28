$(function() {
	
	// ======================== ACTION BAR ACTIONS ========================
	// Scroll to comment form
	$(".action-comment").click(function() {
	    $('html, body').animate({
	        scrollTop: $(".comment-form").offset().top
	    }, 750);
	    $('.comment-form textarea').focus();
	});
	// Tooltips
	$('.post-action-bar a').tooltip();
	$('.post-action-bar a').click(function() {
		var $action = $(this);
		var Action = new UserAction();
		Action.action = $action.data('action');
		Action.user_id = $('.post-action-bar').data('user-id');
		Action.post_id = $('.post-action-bar').data('post-id');
		Action.send( function ( data ) {
			if ( data && data.result == 'fail') {
				// TODO: notify of failure?
			} else {
				$action.find('> span').toggleClass('hidden');
			}
		});
	});

	// ========================== COMMENT REPLY ===========================
	$('.the-comment a.reply').on('click', function(e) {
		post = $(this).data('postid');
		reply = $(this).data('replyid');
		comment_container = $(this).siblings('.reply-box');
		$.ajax({
			url: window.site_url+'profile/commentform/'+post+'/'+reply,
			success: function(data) {
				comment_container.append(data);//load in the form with CSRF protection!
			}
		});
	});
});