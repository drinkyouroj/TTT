$(window).load(function() {
	$('#top-featured').packery({
	  itemSelector: '.featured-item',
	  "columnWidth": ".gutter-sizer",
	});
});


$(function() {
	
});



window.featured_page = 1;

$(window).scroll(function() {
	//If 100px near the bottom load more stuff.
	if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		window.featured_page++;
		load_more();
	}
});

function load_more() {
	$.ajax({
		url: window.site_url+'rest/featured/',
		data: {"page": window.featured_page},
		type:"GET",
		success:function(data) {
			if(data.error) {
				console.log(data);
			} else {
				
				$data = $(data);
				
				$('#top-featured')
					.append($data)
					.packery('appended',$data)
					;
			}
		}
	});
}


