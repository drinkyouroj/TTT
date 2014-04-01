window.error = 'You done messed up A-A-Ron....'; //default error for the JS side of the app.
window.result_box = false;

$(function() {

	//Search function with a dropdownbox too.
	$('.header-wrapper .search input').on('keyup', function() {
		result_box = $(this).siblings('.result-box');
		
		//make sure we have more than 3 characters
		if( $(this).val().length >= 3 ) {
			keyword = $(this).val();
			$.ajax({
				url: window.site_url+'search/'+keyword,
				success: function(data) {
					
					if(data.posts.length) {
						post_box = '<ul class="post_search"><li class="title">Posts</li>';
						$.each(data.posts, function() {
							post_box = post_box +
							'<li><a href="'+window.site_url+'posts/'+this.alias+'">'+this.title+'</a></li>';
						});
						post_box = post_box + '</ul>';
					} else {
						post_box = '';
					}
					
					if(data.users.length) {
						user_box = '<ul class="user_search"><li class="title">Users</li>';
						$.each(data.users, function() {
							user_box = user_box +
							'<li><a href="'+window.site_url+'profile/'+this.username+'">'+this.username+'</a></li>';
						});
						user_box = user_box + '</ul>';
					} else {
						user_box = '';
					}
					
					box = post_box + user_box;
					
					result_box.html('');
					result_box.append(box);
					
					if(data.posts.length || data.users.length) {
						window.result_box = true;
					}
				}
			});
		}
	});
	
});

$(window).on('scroll', function() {
	if($(window).scrollTop() >= 230) {
		$('.desktop-filters-container').css({position: 'fixed', marginTop: 30});
	} else {
		$('.desktop-filters-container').css({position: 'static', marginTop: 0});
	}
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

//Detects and resets the search result box when you click outside of it.
$(window).on('click', function() {
	if(window.result_box) {
		window.result_box = false;//first reset as false.
		$('.header-wrapper .result-box').html('');
	}
});