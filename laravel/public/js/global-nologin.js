$(function() {
	$('.system-share a, a.follow').on('click', function(event){
		event.preventDefault();
		window.location.replace(window.site_url+'user/login');
	});
});