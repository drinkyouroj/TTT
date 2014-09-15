$(function() {
	paginate = new Paginate;
	paginate.page = 0;
	window.page_processing = false;
	paginate.url = window.site_url + 'rest/featured/';
	paginate.response_name = 'featured';
	paginate.array_name = 'post';
	paginate.target = '.trending-content';

	template_src = $('#post-item-template').html();
	paginate.template = Handlebars.compile(template_src);

	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
			paginate.paginate();
		}
	});

});

$(function() {
	// Slide toggle for filter dropdowns
	$('.category-filter-title').click(function(event) {
		event.preventDefault();
		$(this).next().toggle();
	});
});



function Paginate() {
	this.page = undefined;
	this.url = undefined;
	this.template = undefined;
	this.array_name = undefined;
	this.target = undefined;

	this.view_data = {
		site_url: window.site_url,
		image_url: window.image_url,
		user_id: window.user_id
	}

	//renders views and stuff.
	this.viewRender = function() {
		var url = this.url + this.page;
		console.log(url);
		var view_data = this.view_data;
		var array_name = this.array_name;
		var response_name = this.response_name;
		var template = this.template;
		var target = this.target;

		this.getData(url, function(data) {
			$.each(data[response_name], function(idx, val) {
				view_data[array_name] = val[array_name];
				console.log(view_data);
				$(target).append(template(view_data));
			})
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
		if(!window.page_processing) {
			//If we did start processing.
			window.page_processing = true;
			this.page = this.page + 1;
			this.viewRender();
		}
	}
}