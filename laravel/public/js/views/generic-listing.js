window.category_page = 1;

$(function() {
	/*
	$('select.sort_by_filter').change(function(){
        window.location.href = $(this).find("option:selected").prop('value');
    });
    */
	
	
});

window.busy = false;

$(window).scroll(function() {
	//If 100px near the bottom load more stuff.
	if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		if(!window.busy) {
			window.category_page++;
			load_more();
		}	
	}
});

function load_more() {
	window.busy = true;
	$.ajax({
		url: window.site_url+'rest/categories/'+window.category+'/'+window.filter,
		data: {"page": window.category_page},
		type:"GET",
		success:function(data) {
			if(data.error) {
				
			} else {
				$.each(data,function(index, item) {
					item['url'] = window.image_url;
					var source = $('#featured-template').html();
					var template = Handlebars.compile(source);
					var html = template(item);
					
					$('.generic-listing .row').append(
						'<div class="animated fadeIn col-md-4 post-id-'+item.id+'">' +
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
