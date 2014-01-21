//This function is for the admins to feature a post.
$(function() {
	$('.feature').on('click', function() {
		feature(event, $(this).data('id'));
	});
});





function feature(e, id) {
	e.preventDefault();
	$.ajax({
		url: window.site_url+'admin/feature/'+id,
		type:'GET',
		success: function(data) {
			//Below is a pretty rudimentary setup for now, but it definitely works well.
			if(data.status) {
				alert('This Article has been set as Featured');
			} else {
				alert('This Article has been Un Featured');
			}
		}
	});
}
