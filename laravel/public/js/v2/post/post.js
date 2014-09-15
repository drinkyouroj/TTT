$(function() {
	// Compile the templates
	var source   = $("#comment-template").html();
	var comment_template = Handlebars.compile(source);
	var reply_form_source = $('#comment-reply-template').html();
	var reply_form_template = Handlebars.compile( reply_form_source );
	var edit_comment_form_source = $('#comment-edit-template').html();
	var edit_comment_form_template = Handlebars.compile( edit_comment_form_source );

	// ======================== ACTION BAR ACTIONS ========================
	
	// Tooltips
	$('.post-action-bar a').tooltip();
	// Actions
	if ( window.logged_in ) {
		// Scroll to comment form
		$(".action-comment").click(function() {
		    $('html, body').animate({
		        scrollTop: $(".comments > form.comment-reply").offset().top - 110
		    }, 750);
		    $('.comment-form textarea').focus();
		});
		// Actions (like, flag, etc...)
		$('.post-action-bar a').click(function(event) {
			event.preventDefault();
			var $action = $(this);
			var Action = new UserAction();
			Action.action = $action.data('action');
			if ( Action.action == 'follow' && $action.hasClass('active') ) {
				return;	// Hotfix: disabled unfollow from the post page
			}
			Action.user_id = $('.post-action-bar').data('user-id');
			Action.post_id = $('.post-action-bar').data('post-id');
			Action.send( function ( data ) {
				if ( data && data.result == 'fail') {
					// TODO: notify of failure?
				} else {
					$action.find('> span').toggleClass('hidden');
					$action.toggleClass('active');
				}
			});
		});
	}
	// ==================== Show Flagged and Mark as Read ==================
	utilities = $('.post-comment-wrapper').position();
	window.show_utilities = utilities.top -150;
	
	$(window).scroll(function(event) {
		current = $(window).scrollTop();
		if(current > window.show_utilities) {
			$('.utility-container').removeClass('hidden').fadeIn();
		}
	});

	// ========================== LOAD COMMENTS ===========================

	var post_id = $('.post-action-bar').data('post-id');
	var Comments = new CommentPagination( post_id );

	// Check if there is a comment hash in the url (ie: deep link to a comment)
	var hash = window.location.hash;
	if ( hash ) {
		var comment_id = hash.split('-')[1];
		window.target_comment = comment_id;
		Comments.commentDeepLink( comment_id, function ( data ) {
			renderComments( data );
		});
	}

	// Check if the initial height of the page is less than window (ie: there is no scroll, so the on scroll will never be called)
	$(document).ready(function() {
		
		if ( $("body").height() <= $(window).height() ) {
	    	Comments.getNextPage( function ( data ) {
	    		renderComments( data );
	    	});  
	    }
	});

	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
			Comments.getNextPage( function ( data ) {
				renderComments( data );
			});
		}
	});


	// ========================== COMMENT REPLY ===========================
	$('.comments-listing').on('click', '.comment.published a.reply', function() {
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
			$(this).siblings('a').hide();
			$comment_container.append( new_form );
		}
	});

	// Comment Reply
	$(document).on('submit', 'form.comment-reply', function(event) {
		event.preventDefault();
		submit_comment( $(this) );
	});

	// ========================= COMMENT EDITS =============================
	$('.comments-listing').on('click', '.comment.published a.edit', function(event) {
		event.preventDefault();
		var comment_id = $(this).data('editid');
		var comment_body = $(this).closest('.comment').find('.comment-body').html().trim();
		var $comment_edit_container = $(this).siblings('.reply-box');
		var edit_form = edit_comment_form_template( { comment_id: comment_id, comment_body: comment_body } );
		// Hide the action buttons
		$(this).hide();
		$(this).siblings('a').hide();
		$comment_edit_container.append( edit_form );
	});
	// Comment edit submit
	$(document).on('submit', 'form.comment-edit', function(event) {
		event.preventDefault();
		submit_comment_edit( $(this) );
	});
	// Comment edit cancel
	$('.comments-listing').on('click', '.comment-edit-cancel', function(event) {
		event.preventDefault();
		// Bring back the action buttons
		$(this).closest('.reply-links').find('a').show();
		// Remove the edit form
		$(this).closest('form').remove();
	});

	/**
	 *	Algo for rendering comments
	 *	data:
	 *		comments
	 *		is_mod
	 *		active_user_id
	 */
	function renderComments ( data ) {
		if ( data && data.comments ) {
			var comments = data.comments;
			comments.forEach( function ( comment ) {
				comment.margin = comment.depth * 5 + '%';
				var is_target = comment._id == window.target_comment;
				var rendered_comment = comment_template( 
						{ comment: comment, 
						   is_mod: data.is_mod, 
				   active_user_id: data.active_user_id,
				   site_url: window.site_url,
				   target_comment: is_target} );

				$('.comments-listing').append( rendered_comment );

				// Do we need to animate to the linked comment??
				if ( is_target ) {
					// Why yes we do.
					$('html, body').animate({
				        scrollTop: $(".comments .target-comment").offset().top - $(window).height() / 2
				    }, 750, function() {
				    	$('.comments .target-comment').removeClass('target-comment');
				    });
				}

			});
		}
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

	/**
	 *	Submit edit comment
	 */
	function submit_comment_edit ( form ) {
		// TODO: some frontend validation!
		$.ajax({
			url: window.site_url + 'rest/comment/edit',
			type: 'POST',
			data: {
				comment_id: $(form).find('input[name="comment_id"]').val(),
				body: $(form).find('textarea').val()
			},
			success: function ( data ) {
				if ( data.error ) {
					$(form).find('.error').html( data.error );
				} else {
					// TODO: update dom with edited comment
					$(form).closest('.comment').find('.comment-body').html( data.comment.body );
					$(form).closest('.reply-links').find('a').show();
					$(form).remove();
				}
			}
		})
	}

});