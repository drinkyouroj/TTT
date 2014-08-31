$(function() {
	// Compile the templates
	var source   = $("#comment-template").html();
	var comment_template = Handlebars.compile(source);
	var reply_form_source = $('#comment-reply-template').html();
	var reply_form_template = Handlebars.compile( reply_form_source );

	// ======================== ACTION BAR ACTIONS ========================
	// Scroll to comment form
	$(".action-comment").click(function() {
	    $('html, body').animate({
	        scrollTop: $(".comments > form.comment-reply").offset().top - 150
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
			// Render a form accordingly
			var post_id = $(this).data('postid');
			var reply_id = $(this).data('replyid');
			var $comment_container = $(this).siblings('.reply-box');
			var new_form = reply_form_template( { post_id: post_id, reply_id: reply_id } );
			// hide/toggle other reply form and reply buttons
			$('.comments-listing form.comment-reply').remove();
			$('.comments-listing a.reply').show();
			// hide/toggle this form and button
			$(this).hide();
			$comment_container.append( new_form );
		}
	});

	// Comment Reply
	$(document).on('submit', 'form.comment-reply', function(event) {
		event.preventDefault();
		submit_comment( $(this) );
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
		var comments = data.comments;
		
		comments.forEach( function ( comment ) {
			comment.margin = comment.depth * 5 + '%';
			var rendered_comment = comment_template( { comment: comment, is_mod: data.is_mod, active_user_id: data.active_user_id } );
			$('.comments-listing').append( rendered_comment );
			console.log('rendered comment ' + comment._id);
		});
	}

	/**
	 *	Submit a comment
	 */
	function submit_comment ( form ) {
		$.ajax({
			url: window.site_url + 'rest/comment',
			type: 'POST',
			data: {
				post_id: $(form).find('input[name="post_id"]').val(),
				reply_id: $(form).find('input[name="reply_id"]').val(),
				body: $(form).find('textarea').val()
			},
			success: function ( data ) {
				if ( data.error ) {
					$(form).find('.error').html( data.error );
				} else if ( data.comment ) {
					// render the template
					data.comment.margin = data.comment.depth * 5 + '%';
					var new_comment = comment_template( data );
					if ( data.comment.parent_comment ) {
						// This comment was a reply to another comment, insert it accordingly
						$('.comments-listing #comment-' + data.comment.parent_comment).after( new_comment );
						$(form).remove();
					} else {
						// Started a new comment thread, just append to end of comments
						$('.comments-listing').prepend( new_comment );
						// Clear the form
						$(form).find('textarea').val( '' );
					}
					
				}
			}
		});
	}

});