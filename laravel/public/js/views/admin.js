//This function is for the admins to feature a post.
$(function() {
	$('.feature').on('click', function() {
		
		height = $('.feature-options #height option:selected').val();
		width = $('.feature-options #width option:selected').val();
		order = $('.feature-options #order option:selected').val();
		
		feature($(this).data('id'), height, width, order);
	});
	
	$('.admin-mod').on('click', function() {
		admin_mod( $(this).data('id'));
	});
	
	$('.hard-del').on('click', function() {
		hard_delete_post( $(this).data('id'));
	});
	
});

function feature(id,height, width, order) {
		
	$.ajax({
		url: window.site_url+'admin/feature/'+id,
		type:'GET',
		data: {
			height: height,
			width: width,
			order: order
		},
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

function hard_delete_post(id) {
	$.ajax({
		url: window.site_url+'admin/harddeletepost/'+id,
		type:'GET',
		success: function(data) {
			//Below is a pretty rudimentary setup for now, but it definitely works well.
			if(data.result == 'deleted') {
				alert('Post is hard deleted');
			} else {
				alert('Post is restored');
			}
		}
	});
}
