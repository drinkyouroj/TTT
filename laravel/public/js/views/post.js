$(function() {
	$('.the-comment a.reply').on('click', function(e) {
		post = $(this).data('postid');
		reply = $(this).data('replyid');
		comment_container = $(this).siblings('.reply-box');
		$.ajax({
			url: window.site_url+'profile/commentform/'+post+'/'+reply,
			success: function(data) {
				comment_container.append(data);//load in the form with CSRF protection!
			}
		});
	});
});