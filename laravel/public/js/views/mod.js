//This function is for the mods
$(function() {
	//Ban the user
	$('.mod-ban').on('click', function() {
		mod_ban($(this).data('id'));
	});
	
	//Delete the content
	$('.mod-delete').on('click', function() {
		mod_delete($(this).data('id'));
	});
	
	//Delete the content
	$('.mod-del-comment').on('click', function() {
		mod_del_comment($(this).data('delid'));
	});
	
});

function mod_ban(id) {
	$.ajax({
		url: window.site_url+'mod/ban/'+id,
		type:'GET',
		success: function(data) {
			console.log(data.result);
			//Below is a pretty rudimentary setup for now, but it definitely works well.
			if(data.result == 'banned') {
				alert('This user has been banned');
			} else {
				alert('This user has been unbanned');
			}
		}
	});
}

function mod_delete(id) {
	$.ajax({
		url: window.site_url+'mod/delpost/'+id,
		type:'GET',
		success: function(data) {
			//Below is a pretty rudimentary setup for now, but it definitely works well.
			if(data.result == 'deleted') {
				alert('This article has been deleted');
			} else {
				alert('This article has been undeleted');
			}
		}
	});
}

function mod_del_comment(id) {
	$.ajax({
		url: window.site_url+'mod/delcomment/'+id,
		type:'GET',
		success: function(data) {
			if(data.result == 'deleted') {
				$('#comment-'+id+'>.comment-body').html('<span class="deleted">Comment was deleted</span>');
			}
		}
	});
}