$(function() {
	$('body').on('click','.comment .mod-hide-comment', function(event) {
		event.preventDefault();
		id = $(this).data('hideid');
		scope = $(this);
		$.ajax({
			url: window.site_url+'mod/hide/comment/'+id,
			success: function(data) {
				console.log(data);
				if(data.success) {
					$('#comment-'+id).hide();
				} else {
					console.log('oops...');
				}
			}
		});
	});
});