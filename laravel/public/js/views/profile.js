$(function() {
	
	$('.options .delete').on('click', 'a', function() {
		id = $(this).data('id');
		deleted = false;
		$that = $(this);
		$.ajax({
			url: window.site_url+'rest/postdelete/'+id,
			type:"GET",//
			success: function(data) {
				console.log(data.result);
				if(data.result == 'unpublished') {
					$('.activity-container .post-id-'+id).addClass('deleted');
					$that.html('undelete');
				} else {
					$('.activity-container .post-id-'+id).removeClass('deleted');
					$that.html('delete');
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
							new_featured = data[0];
							console.log(data);
							new_featured['url'] = window.image_url;
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

window.profile_page = 1;
window.busy = false;

$(window).scroll(function() {
	//If 100px near the bottom load more stuff.
	if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		window.profile_page++;
		load_more();
	}
});

function load_more() {
	window.busy = true;
	$.ajax({
		url: window.site_url+'rest/profile/'+window.cur_user,
		data: {"page": window.profile_page},
		type:"GET",
		success:function(data) {
			if(data.error) {
				console.log(data);
			} else {
				$.each(data,function(index, item) {
					item['url'] = window.image_url;
					var source = $('#activity-template').html();
					var template = Handlebars.compile(source);
					var html = template(item);
					
					$('.generic-listing').append(
						'<div class="animated fadeIn col-md-4 post-id-'+item.id+' '+ item.post_type +'">' +
						html +
						'</div>'
					);
				});
			}
		},
		complete:function() {
			window.busy = false;
		}
	});
}


