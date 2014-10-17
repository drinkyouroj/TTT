$(function() {
	var user_action = new UserAction;
	$('.follow-button').click(function(event) {
		event.preventDefault();
		user_action.action = 'follow';
		user_action.user_id = $(this).data('userid');
		user_action.send();
	});
});