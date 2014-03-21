$(function() {
	$('.btn.delete-account').on('click', function() {
		id = $(this).data('user');
		if(id) {
			$.ajax({
				url: window.site_url+'rest/user/'+id,
				type:"DELETE",
				success: function(data) {
					window.location.href = window.site_url+'user/logout';
				}
			});
		}
	});
});
