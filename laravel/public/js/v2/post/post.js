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

	// ========================== LOAD COMMENTS ===========================

	var post_id = $('.post-action-bar').data('post-id');
	var Comments = new CommentPagination( post_id );
	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
			Comments.getNextPage( function ( data ) {
				renderComments( data );
			});
		}
	});


	// ========================== COMMENT REPLY ===========================
	$('.comments-listing').on('click', 'a.reply', function() {
		if ( $(this).hasClass('auth') ) {
			$('#guestSignup').modal('show');
		} else {
			post = $(this).data('postid');
			reply = $(this).data('replyid');
			comment_container = $(this).siblings('.reply-box');
			$.ajax({
				url: window.site_url + 'profile/commentform/' + post + '/' + reply,
				success: function(data) {
					comment_container.append(data);//load in the form with CSRF protection!
				}
			});
		}
	});

	/**
	 *	Algo for rendering comments
	 *	data:
	 *		comments
	 *		is_mod
	 *		active_user_id
	 */
	function renderComments ( data ) {
		console.log('render comments');
		var source   = $("#comment-template").html();
		var comment_template = Handlebars.compile(source);
		var comments = data.comments;
		
		comments.forEach( function ( comment ) {
			comment.margin = comment.depth * 10 + '%';
			var rendered_comment = comment_template( { comment: comment, is_mod: data.is_mod, active_user_id: data.active_user_id } );
			$('.comments-listing').append( rendered_comment );
			console.log('rendered comment ' + comment._id);
		});
	}

});