$(function() {
	$('.system-share a').on('click', function(event){
		event.preventDefault;
	});
	
	//Follow someone
	$('.follow').on('click', function() {
		follow(event, $(this).data('user'));
	});
		//See the followers list
		$('.followers').on('click', function(event){
			followers_box(event, $(this).data('user'));
		});
		
		$('.following').on('click', function(event){
			following_box(event, $(this).data('user'));
		});
	
	$('.fav').on('click', function() {
		fav(event, $(this).data('post'));
	});
	
	$('.repost').on('click', function() {
		repost(event, $(this).data('post'));
	});
	
	$('.like').on('click', function() {
		like(event, $(this).data('post'));
	});
	
	$('.navbar-right .notifications-parent')
		.mouseenter(function() {
			$('ul.notifications',this).slideDown();
		})
		.mouseleave(function() {
			$('ul.notifications',this).slideUp();
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
function follow(e,id) {
	e.preventDefault();
	
	$.ajax({
		url: window.site_url+'rest/follows/'+id,
		//type:"POST",
		success: function(data) {
			
		}
	});
}

function followers_box(e,id) {
	e.preventDefault();
	$.ajax({
		url: window.site_url+'rest/followers/'+id,
		//type:"POST",
		success: function(data) {
			
			$('#followbox .modal-body').empty();
			$.each(data.followers, function(index, value) {
				$('#followbox .modal-body').append('<a href="'+window.site_url+'profile/'+value.username+'">'+value.username+'</a>');
			});
			
			$('#followbox .modal-title').html('Your Followers');
			$('#followbox').modal('show');
		}
	});
}

function following_box(e,id) {
	e.preventDefault();
	$.ajax({
		url: window.site_url+'rest/following/'+id,
		//type:"POST",
		success: function(data) {
			
			$('#followbox .modal-body').empty();
			$.each(data.following, function(index, value) {
				$('#followbox .modal-body').append('<a href="'+window.site_url+'profile/'+value.username+'">'+value.username+'</a>');
			});
			
			$('#followbox .modal-title').html('People You Follow');
			$('#followbox').modal('show');
		}
	});
}


/**
 * Post (as in the articles) based function 
 */
function fav(e,id) {
	e.preventDefault();
	$.ajax({
		url: window.site_url+'rest/favorites/'+id,
		//type:"POST",
		success: function(data) {
			error_log(data.result,'fav');
		}
	});
}


function repost(e,id) {
	e.preventDefault();
	
	$.ajax({
		url: window.site_url+'rest/reposts/'+id,
		//type:"POST",
		success: function(data) {
			error_log(data.result,'repost');
		}
	});
}

function like(e,id) {
	e.preventDefault();
	console.log(id);
	$.ajax({
		url: window.site_url+'rest/likes/'+id,
		//type:"POST",
		success: function(data) {
			error_log(data.result,'like');
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
	val = parseInt($('.system-share a.'+action+' span.numbers').html());
	//if the value isn't false
	if(val) {
		$('.system-share a.'+action+' span.numbers').html(val+1);
	} else {
		$('.system-share a.'+action).append('<span class="brackets">(<span class="numbers">1</span>)</span>');
	}
}


//This function controls live deleting.
function del_result(action) {
	val = parseInt($('.system-share a.'+action+' span.numbers').html());
	if(val) {
		if(val-1 >= 1) {
			$('.system-share a.'+action+' span.numbers').html(val-1);
		} else {
			$('.system-share a.'+action+' span.brackets').detach();
			console.log('detach');
		}
	}
}