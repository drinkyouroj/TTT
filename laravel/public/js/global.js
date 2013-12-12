window.site_url = '/tt/';//has trailing slash
window.error = 'You done messed up A-A-Ron....'; //default error for the JS side of the app.

$(function() {
	$('.system-share a').on('click', function(event){
		event.preventDefault;
	});
	
	$('.follow').on('click', function() {
		follow(event, $(this).data('user'));
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

	//Search function with a dropdownbox too.
	$('.header-wrapper .search input').on('keyup', function() {
		result_box = $(this).siblings('.result-box');
		
		//make sure we have more than 3 characters
		if( $(this).val().length >= 3 ) {
			keyword = $(this).val();
			$.ajax({
				url: window.site_url+'search/'+keyword,
				success: function(data) {
					box = '<ul>';
					$.each(data, function() {
						box = box +
						'<li><a href="'+window.site_url+'posts/'+this.alias+'">'+this.title+'</a></li>';
					});
					box = box + '</ul>';
					result_box.html('');
					result_box.append(box);
				}
			});
		}
	});

});

//Equal Heights
$(window).on('load',function() {
	var blocks = $('.equal-height');
	var maxHeight = 0;
	blocks.each(function(){
		maxHeight = Math.max(maxHeight, parseInt($(this).css('height')));
	});
	blocks.css('height', maxHeight);
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
function fav(e,id) {
	e.preventDefault();
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


function repost(e,id) {
	e.preventDefault();
	
	$.ajax({
		url: window.site_url+'rest/reposts/'+id,
		//type:"POST",
		success: function(data) {
			console.log(data.result);
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

function like(e,id) {
	e.preventDefault();
	console.log(id);
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