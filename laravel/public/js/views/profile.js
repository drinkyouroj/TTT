$(function() {
	
	$('.options .delete').on('click', 'a', function() {
		id = $(this).data('id');
		$.ajax({
			url: window.site_url+'rest/posts/'+id,
			type:"DELETE",//
			success: function(data) {
				console.log(data.result);
				if(data.result == 'unpublished') {
					$('.activity-container .post-id-'+id).fadeOut().remove();
				} else {
					console.log(data);
				}
			}
		});
	});
	
	$('.options .feature').on('click', 'a', function() {
		id = $(this).data('id');
		$that = $(this);
		$.ajax({
			url: window.site_url+'rest/feature/'+id,
			type:"GET",
			success: function(data) {
				console.log(data.result);
				if(data.result == 'success') {
					$('.activity-container>div').removeClass('user-featured');
					$('.activity-container .post-id-'+id).addClass('user-featured');
					$('.options .feature').removeClass('hidden');
					$that.parent().addClass('hidden');
				} else {
					console.log(data);
				}
			}
		});
	});
});