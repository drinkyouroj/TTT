window.site_url = '/tt/';//has trailing slash
window.error = 'You done messed up A-A-Ron....'; //default error for the JS side of the app.

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