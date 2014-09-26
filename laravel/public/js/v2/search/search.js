$(function() {
	$('.users-tab, .posts-tab').click(function() {
		// Update the form accordingly
		var type = $(this).hasClass('posts-tab') ? 'posts' : 'users';
		$('.search-filter').val(type);
	});
});