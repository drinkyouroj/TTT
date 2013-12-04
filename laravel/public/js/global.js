window.site_url = '/tt/';//has trailing slash
window.error = 'You done messed up A-A-Ron....'; //default error for the JS side of the app.

$(function() {
	$('.follow').on('click', follow(e));
	$('.fav').on('click', fav(e));
	$('.repost').on('click', repost(e));
	$('.like').on('click', like(e));
	
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
function follow(e) {
	e.preventDefault();
	id = $(this).data('user');//let's just pull the id.
	$.ajax({
		url: window.site_url+'rest/follows/'+id,
		//type:"POST",
		success: function(data) {
			switch(data.result) {
				case 'success':
					console.log('success');
				break;
				case 'mutual':
					console.log('mutual');
				break;
				case 'exists':
					console.log('exists');
				break;
				default:
				case 'fail':
					console.log(window.error);
				break;
			}
		}
	});
}

/**
 * Post (as in the articles) based function 
 */
function fav(e) {
	e.preventDefault();
	id = $(this).data('post');//let's just pull the id.
	$.ajax({
		url: window.site_url+'rest/favorites/'+id,
		//type:"POST",
		success: function(data) {
			switch(data.result) {
				case 'success':
					console.log('success');
				break;
				case 'exists':
					console.log('exists');
				break;
				default:
				case 'fail':
					console.log(window.error);
				break;
			}
		}
	});
}


function repost(e) {
	e.preventDefault();
	id = $(this).data('post');//let's just pull the id.
	$.ajax({
		url: window.site_url+'rest/reposts/'+id,
		//type:"POST",
		success: function(data) {
			switch(data.result) {
				case 'success':
					console.log('success');
				break;
				case 'exists':
					console.log('exists');
				break;
				default:
				case 'fail':
					console.log(window.error);
				break;
			}
		}
	});
}

function like(e) {
	e.preventDefault();
	id = $(this).data('post');//let's just pull the id.
	$.ajax({
		url: window.site_url+'rest/likes/'+id,
		//type:"POST",
		success: function(data) {
			switch(data.result) {
				case 'success':
					console.log('success');
				break;
				case 'exists':
					console.log('exists');
				break;
				default:
				case 'fail':
					console.log(window.error);
				break;
			}
		}
	});
}