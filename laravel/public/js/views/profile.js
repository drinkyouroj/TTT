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
					
					//Get rid of that featured item at the top.
					$('.profile-featured .generic-item').remove();
					
					$.ajax({
						url:window.site_url+'rest/posts/'+id,
						type: "GET",
						success: function(data) {
							new_featured = data;
							var source = $('#featured-template').html();
							var template = Handlebars.compile(source);
							var html = template(new_featured);
							
							$('.profile-featured').append(html);
						}
					});
					
				} else {
					console.log(data);
				}
			}
		});
	});
});