window.category_page = 1;

$(function() {
	/*
	$('select.sort_by_filter').change(function(){
        window.location.href = $(this).find("option:selected").prop('value');
    });
    */
	
	
});

$(window).scroll(function() {
	//If 100px near the bottom load more stuff.
	if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		window.category_page++;
		load_more();
	}
});

function load_more() {
	$.ajax({
		url: window.site_url+'rest/categories/'+window.category+'/'+window.filter,
		data: {"page": window.category_page},
		type:"GET",
		success:function(data) {
			if(data.error) {
				console.log(data);
			} else {
				$.each(data,function(index, item) {
					item['url'] = window.site_url;
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
		}
	});
}
