$(function() {
	$('.system-share a, a.follow').on('click', function(event){
		event.preventDefault();
		
		$('#guestSignup').modal('toggle');
	});
});