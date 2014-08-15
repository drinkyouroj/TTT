$(function() {
	$('.btn.delete-account').on('click', function() {
		id = $(this).data('user');
		if(id) {
			$.ajax({
				url: window.site_url+'rest/userdelete/'+id,
				type:"GET",//previously delete
				success: function(data) {
					window.location.href = window.site_url+'user/logout';
				}
			});
		}
	});
});
