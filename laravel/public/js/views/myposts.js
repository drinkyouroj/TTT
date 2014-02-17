$(function() {
	$('.activity-nav a.all').on('click', function() {
		$('.activity-container>div').fadeIn();
	});
	
	$('.activity-nav a.myposts').on('click', function() {
		$('.activity-container>div.favorite').fadeOut();
		$('.activity-container>div.post').fadeIn();
	});
	
	$('.activity-nav a.myfavorites').on('click', function() {
		$('.activity-container>div.favorite').fadeIn();
		$('.activity-container>div.post').fadeOut();
	});
	
});
