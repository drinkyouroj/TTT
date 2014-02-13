//This function is for the admins to feature a post.
$(function() {
	$('.feature').on('click', function() {
		feature($(this).data('id'));
	});
	
	$('.admin-mod').on('click', function() {
		admin_mod( $(this).data('id'));
	});
	
});



function feature(id) {
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


function admin_mod(id) {
	$.ajax({
		url: window.site_url+'admin/modassign/'+id,
		type:'GET',
		success: function(data) {
			//Below is a pretty rudimentary setup for now, but it definitely works well.
			if(data.status == 'attached') {
				alert('User is now a mod');
			} else {
				alert('User is nolonger a mod');
			}
		}
	});
}
