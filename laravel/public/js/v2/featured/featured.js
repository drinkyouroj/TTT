$(function() {
	// Slide toggle for filter dropdowns
	$('.category-filter-title').click(function(event) {
		event.preventDefault();
		$(this).next().toggle();
	});

	$(window).scroll(function() {
		// Pagination for content
		var scrollTop = $(window).scrollTop(); 
		var windowHeight = $(window).height();
		var documentHeight = $(document).height();
		if ( scrollTop > 50 ) {
			$('.header-inner-wrapper').addClass('active');
		} else {
			$('.header-inner-wrapper').removeClass('active');
		}
	});



// ========================== Fixed signup Bar ===========================
	middle = $('.middle-wrapper').offset();
	window.show_join = middle.top - 200;
	window.reached = false;

	$(window).scroll(function(event) {
		current = $(window).scrollTop();
		if(current > window.show_join) {
			$('.join-banner').show().fadeIn();
			$('.content-wrapper').css('margin-bottom',$('.join-banner').height());
		}
	});

	if ( !window.logged_in ) {
		$( window ).resize(function() {
	  		$('.content-wrapper').css('margin-bottom',$('.join-banner').height());
		});
	}
});

/*
$(function() {
	paginate = new Paginate;
	paginate.page = 0;
	window.page_processing = false;
	paginate.url = window.site_url + 'rest/featured/';
	paginate.response_name = 'featured';
	paginate.array_name = 'post';
	paginate.target = '.trending-content';
	paginate.loading_gif_target = '.loading-container img';

	template_src = $('#post-item-template').html();
	paginate.template = Handlebars.compile(template_src);

	$(window).scroll(function() {
		// Pagination for content
		var scrollTop = $(window).scrollTop(); 
		var windowHeight = $(window).height();
		var documentHeight = $(document).height();
		if(scrollTop + windowHeight > documentHeight - 100) {
			paginate.paginate();
		}
		if ( scrollTop > 50 ) {
			$('.header-inner-wrapper').addClass('active');
		} else {
			$('.header-inner-wrapper').removeClass('active');
		}
	});

	// Toggle the header title depending on position of scroll

});


function Paginate() {
	this.page = undefined;
	this.url = undefined;
	this.template = undefined;
	this.array_name = undefined;
	this.target = undefined;
	this.loading_gif_target = undefined;
	this.no_more_results = false;

	this.view_data = {
		site_url: window.site_url,
		image_url: window.image_url,
		user_id: window.user_id
	}

	//renders views and stuff.
	this.viewRender = function() {
		var url = this.url + this.page;
		var view_data = this.view_data;
		var array_name = this.array_name;
		var response_name = this.response_name;
		var template = this.template;
		var target = this.target;

		var parent = this;

		this.getData(url, function(data) {
			if ( data[response_name] && data[response_name].length ) {
				$.each(data[response_name], function(idx, val) {
					view_data[array_name] = val[array_name];
					console.log(view_data);
					$(target).append(template(view_data));
				});
			} else {
				parent.no_more_results = true;
				$(parent.loading_gif_target).fadeOut();
			}
		});
	}

	this.getData = function(get_url, callback) {
		$.ajax({
			url: get_url,
			success: function(data) {
				callback(data);
			},
			complete: function(xhr, status) {
				window.page_processing = false;
			},
			error: function(xhr, status) {

			}
		});
	}

	this.paginate = function() {
		if(!window.page_processing && !this.no_more_results) {
			//If we did start processing.
			window.page_processing = true;
			this.page = this.page + 1;
			this.viewRender();
		}
	}
}
*/