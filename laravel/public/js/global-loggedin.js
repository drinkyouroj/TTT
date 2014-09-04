$(function() {
	$('.system-share a').on('click', function(event){
		event.preventDefault();
	});
	
	//*Mark as Read  *********************************************
	$('.mark-read').on('click',function(event) {
		mark_read();//function defined below
	});
	
	//*Follow Actions*********************************************
	$('.profile-options .follow').on('click', function(event) {
		event.preventDefault();
		follow($(this).data('user'),'profile');
	});
	
	$('.follow-container .follow').on('click', function(event) {
		event.preventDefault();
		follow($(this).data('user'),'post');
		$(this).fadeOut();
	});
	
		//See the followers list
		$('.followers a').on('click', function(event){
			followers_box($(this).data('user'));
		});
		
		$('.following a').on('click', function(event){
			following_box($(this).data('user'));
		});

	//*Favorite***************************************************
	$('.system-share a.fav').on('click', function() {
		fav($(this).data('post'));
		
	});

		//unfavorite for 
		$('.generic-item a.favorite').on('click', function() {
			post_id = $(this).data('post');
			
			fav(post_id,true);//calling from a post.
			console.log(result);
			
		});

	//Repost******************************************************
	$('.system-share a.repost').on('click', function() {
		repost($(this).data('post'));
	});
	
	//Like********************************************************
	$('.system-share a.like').on('click', function() {
		like($(this).data('post'));
	});
	
	//Delete Comment**********************************************
	$('.comments-listing').on('click', 'a.delete', function(event) {
		event.preventDefault();
		comment_delete($(this).data('delid'));
	});
	// Like/Unlike Comment****************************************
	$('.comments-listing').on('click', '.like-comment', function() {
		var comment_id = $(this).closest('.comment').attr('id');
		comment_id = comment_id.split('-')[1];
		if ( $(this).hasClass('active') ) {
			comment_unlike( comment_id, $(this) );
		} else {
			comment_like( comment_id, $(this) );
		}
		$(this).toggleClass('active');
	});
	// Flag/Unflag Comment*****************************************
	$('.comments-listing').on('click', '.flag-comment', function() {
		var comment_id = $(this).closest('.comment').attr('id');
		comment_id = comment_id.split('-')[1];
		if ( $(this).hasClass('active') ) {
			comment_unflag( comment_id, $(this) );
		} else {
			comment_flag( comment_id, $(this) );
		}
	});
});

/**
 * Mark as Read function
 */

function mark_read() {
	$.ajax({
		url: window.site_url+'rest/notification/',
		type: "POST",
		data: {"notification_ids": window.cur_notifications},
		success: function(data) {
			console.log(data);
			$('.notifications-parent').removeClass('active-notifications');
			$('.notifications-parent ul.notifications').children().remove();
			$('.notifications-parent ul.notifications').append('<li class="no-notifications"><span>You have no notifications!</span></li>');
		}
	});
}

/**
 * Global functions for the 4 major ajax based actions
 * Follow/Unfollow
 * Favorite
 * Repost
 * Like
 */

/**
 * User based function 
 */
function follow(id,location) {
	$.ajax({
		url: window.site_url+'rest/follows/'+id,
		//type:"POST",
		success: function(data) {
			error_log(data.result, 'follow');
			if(location == 'profile') {
				if(data.result == 'success') {
					//follow
					$('.profile-options a.follow span.follow-action').html('UnFollow');
					$('.profile-options a.follow').addClass('unfollow');
				}else {
					//delete
					$('.profile-options a.follow span.follow-action').html('Follow');
					$('.profile-options a.follow').removeClass('unfollow');
				}
			}
		}
	});
}

function followers_box(id) {
	$.ajax({
		url: window.site_url+'rest/followers/'+id,
		type:"GET",
		success: function(data) {
			$('#followbox .modal-body').empty();
			var $follows;
			$.each(data.followers, function(index, value) {
				$('#followbox .modal-body').append('<a href="'+window.site_url+'profile/'+value.username+'"><div class="profile-container"><div class="profile-img-container" style="background-image: url('+window.site_url+'rest/profileimage/'+value.id+');"></div><h5>'+value.username+'</h5><div class="clearfix"></div></div></a>');
			});
			
			$('#followbox .modal-title').html(window.cur_user+"'s Followers");
			$('#followbox').modal('show');
		}
	});
}

function following_box(id) {
	$.ajax({
		url: window.site_url+'rest/following/'+id,
		type:"GET",
		success: function(data) {
			
			$('#followbox .modal-body').empty();
			$.each(data.following, function(index, value) {
				$('#followbox .modal-body').append('<a href="'+window.site_url+'profile/'+value.username+'"><div class="profile-container"><div class="profile-img-container" style="background-image: url('+window.site_url+'rest/profileimage/'+value.id+');"></div><h5>'+value.username+'</h5><div class="clearfix"></div></div></a>');
			});
			
			$('#followbox .modal-title').html('People '+window.cur_user+' Follows');
			$('#followbox').modal('show');
		}
	});
}

/**
 * Post (as in the articles) based function 
 */
function fav(id,post) {
	$.ajax({
		url: window.site_url+'rest/favorites/'+id,
		//type:"POST",
		success: function(data) {
			console.log(data);
			
			//If this is called from the system share links
			if(!post) {
				error_log(data.result,'fav');
			} else {
				//sucks to have to do it this way.
				if(data.result == 'deleted') {
					$('.post-id-'+post_id).fadeOut().remove();
				}
			}
		}
	});
	
	
}


function repost(id) {
	$.ajax({
		url: window.site_url+'rest/reposts/'+id,
		//type:"POST",
		success: function(data) {
			error_log(data.result,'repost');
		}
	});
}

function like(id) {
	console.log(id);
	$.ajax({
		url: window.site_url+'rest/likes/'+id,
		//type:"POST",
		success: function(data) {
			error_log(data.result,'like');
		}
	});
}


function comment_delete(id) {
	$.ajax({
		url: window.site_url+'rest/comments/'+id,
		type:"GET",//This used to be delete, but not anymore.
		success: function(data) {
			if(data.result == 'deleted') {
				$('#comment-'+id+'>.comment-body').html('<span class="deleted">(This comment has been deleted)</span>');
				$('#comment-'+id+' a.delete').fadeOut(function() {
					$(this).remove();
				});
			}
		}
	});
}

function comment_like(id, scope) {
	$.ajax({
		url: window.site_url + 'rest/comment/like/' + id,
		type: 'GET',
		success: function(data) {
			var $count_element = $(scope).closest('.comment').find('.like-comment-count');
			var count = $count_element.html();
			count++;
			$count_element.html(count);
		}
	});
}
function comment_unlike(id, scope) {
	$.ajax({
		url: window.site_url + 'rest/comment/unlike/' + id,
		type: 'GET',
		success: function(data) {
			var $count_element = $(scope).closest('.comment').find('.like-comment-count');
			var count = $count_element.html();
			count--;
			$count_element.html(count);
		}
	});
}
function comment_flag(id, scope) {
	$.ajax({
		url: window.site_url + 'rest/comment/flag/' + id,
		type: 'GET',
		success: function(data) {
			$(scope).toggleClass('active');
		}
	});
}
function comment_unflag(id, scope) {
	$.ajax({
		url: window.site_url + 'rest/comment/unflag/' + id,
		type: 'GET',
		success: function(data) {
			$(scope).toggleClass('active');
		}
	});
}

function error_log(result, action) {
	switch(result) {
		case 'success':
			console.log('success');
			add_result(action);
		break;
		case 'mutual':
			console.log('mutual');
		break;
		case 'exists':
			console.log('exists');
		break;
		case 'deleted':
			del_result(action);
		break;
		default:
		case 'fail':
			console.log(window.error);
		break;
	}
}

//This function controls the live adding of numbers to the existing likes, etc
function add_result(action) {
	if(action != 'follow') {
		val = parseInt($('.system-share a.'+action+' span.numbers').html());
		
		//place done
		$('.'+action+'-container').addClass('done');
		
		//if the value isn't false
		if(val) {
			$('.system-share a.'+action+' span.numbers').html(val+1);
		} else {
			$('.system-share a.'+action).append('<span class="brackets">(<span class="numbers">1</span>)</span>');
		}
	} else if(action == 'follow') {
		//Gotta check to see if you're mutual now.
		
		
		//if mutual, put in the message user
		
		//put in the unfollow mark either way.
	
	}
}


//This function controls live deleting.
function del_result(action) {
	if(action != 'follow') {
		val = parseInt($('.system-share a.'+action+' span.numbers').html());
		
		$('.'+action+'-container').removeClass('done');
		
		if(val) {
			if(val-1 >= 1) {
				$('.system-share a.'+action+' span.numbers').html(val-1);
			} else {
				$('.system-share a.'+action+' span.brackets').detach();
				console.log('detach');
			}
		}
	} else if(action == 'follow') {
		
		//Gotta put in the "follow"
		
	}
}