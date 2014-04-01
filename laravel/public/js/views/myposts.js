$(function() {
	$('.activity-nav a.all').on('click', function() {
		$('.activity-container>div').fadeIn();
		unactive($(this).parent());
	});
	
	$('.activity-nav a.myposts').on('click', function() {
		$('.activity-container>div.favorite').fadeOut();
		$('.activity-container>div.post').fadeIn();
		unactive($(this).parent());
	});
	
	$('.activity-nav a.myfavorites').on('click', function() {
		$('.activity-container>div.favorite').fadeIn();
		$('.activity-container>div.post').fadeOut();
		unactive($(this).parent());
	});
	
});


function unactive(that) {
	$('.activity-nav li').removeClass('active');
	that.addClass('active');
}
