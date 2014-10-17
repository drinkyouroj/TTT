$(function() {
	var user_action = new UserAction;
	$('.follow-button').click(function(event) {
		event.preventDefault();
		user_action.action = 'follow';
		user_action.user_id = $(this).data('userid');
		that = $(this);
		user_action.send(function(data) {
			if(data.result == 'success') {
				that.addClass('following');

			} else {
				that.removeClass('following');
				
			}
		});
	});
});