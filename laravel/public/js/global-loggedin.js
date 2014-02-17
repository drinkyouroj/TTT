$(function() {
	$('.system-share a').on('click', function(event){
		event.preventDefault();
	});
	
	//*Follow Actions*********************************************
	$('.profile-options .follow').on('click', function(event) {
		event.preventDefault();
		follow($(this).data('user'));
	});
	
	$('.follow-container .follow').on('click', function(event) {
		event.preventDefault();
		follow($(this).data('user'));
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
	$('.comments-listing a.delete').on('click', function(event) {
		event.preventDefault();
		comment_delete($(this).data('delid'));
	});
	
});



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
function follow(id) {
	$.ajax({
		url: window.site_url+'rest/follows/'+id,
		//type:"POST",
		success: function(data) {
			error_log(data.result, 'follow');
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
			
			$('#followbox .modal-title').html('Your Followers');
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
			
			$('#followbox .modal-title').html('People You Follow');
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
			console.log('repost');
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
		type:"DELETE",//
		success: function(data) {
			if(data.result == 'deleted') {
				$('#comment-'+id+'>.comment-body').html('<span class="deleted">Comment was deleted</span>');
			}
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
	console.log(result);
}

//This function controls the live adding of numbers to the existing likes, etc
function add_result(action) {
	if(action != 'follow') {
		val = parseInt($('.system-share a.'+action+' span.numbers').html());
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