/*
$(function() {

	//target is the modal body div.
	photo_input.target = $('#modalBody');//make sure to have an id set on it.
	photo_input.input = $('input.processed-image');//define which input the system is going to give the image to.

	//inits the photo system.
	photo_input.viewInit();
});
*/
//Below starts the events. Had to separate out the scopes for the events entirely.
function photoInit(photoInput) {
	
	$('body').bind('keyup', photoInput.query_input, function(e) {
		
		var code = e.keyCode || e.which; 
		if (code  == 13  ) { //$(this).val().length >=3
			e.preventDefault();
			photoInput.photo_search_page = 1;//reset the page.
			photoInput.keyword = $(photoInput.query_input).val();
			photoInput.searchImages();//Pulls in images from Flickr
			return false;
		}
	});

	$('body').on('click', photoInput.search_button, function() {
		photoInput.photo_search_page = 1;
		photoInput.keyword = $(photoInput.query_input).val();
		photoInput.searchImages();
		// Append loading gif
		$(photoInput.photo_results).append('<div class="loading-container"><img src="' + window.site_url + 'images/posts/comment-loading.gif"></div>');
	});

	//Pager for the photo result sets
	$('body').on('click', photoInput.photo_results+' a.pager', function() {
		photoInput.photo_search_page = $(this).data('page');
		photoInput.searchImages();
	});

	//Click on the photo results to select the image.
	$('body').on('click',photoInput.photo_results+' img',function() {
		$('.loading-container').fadeIn(); // show loading gif. sometime it takes awhile.

		photoInput.selected_image = $(this).data('image');//HTML5 rocks!

		// $(photoInput.photo_chosen).html('');//empty the image from the chosen pile.
		$(photoInput.photo_results).fadeOut();//Hide the photo selection options
		$(photoInput.photo_processor).fadeIn();//fade in the photo process options
		$(photoInput.chosen_label).fadeIn();
		
		//Lets grab a no filter version.
		photoInput.process = 'nofilter';
		photoInput.applyProcess();

		// Clear any previous filters
		$(photoInput.photo_processor+' img').removeClass('active').first().addClass('active');
		
		// Show the 'OK' button
		$('.modal-footer.hidden').removeClass('hidden');

	});//Loads in the chosen photos	

	$('body').on('click', photoInput.photo_processor+' img',function() {		
		photoInput.process = $(this).data('process');
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		$('.loading-container').css('z-index', 1); // Display loading
		//make sure we have all values.
		if(photoInput.selected_image.length && photoInput.process.length) {
			photoInput.applyProcess();
		} else {
			console.log('error with image processor: missing var');
		}
	});
}



//portable photo_input system.
function PhotoInput() {

	this.target = undefined;
	this.input = undefined;

	//Defaults.
	this.photo_id = undefined;
	this.license = undefined;
	this.keyword = undefined;
	this.process = 'nofilter';
	this.photo_search_page = 1;
	this.selected_image = 0;

	//Major DOM elements
	//below are naked for "live" reasons.
	this.query_input = '.photos input.search-query';
	this.photo_results = '.photo-results';

	this.photo_chosen = '.photo-chosen';
	this.photo_processor = '.photo-processor';
	this.image_select = '.top-submit-container .image-select';
	this.image_edit = '.top-submit-container .image-edit';

	//Buttons
	this.search_button = '.activate-search';

	//Labels
	this.chosen_label = '.chosen-label';
	this.processed_label = '.processed-label';

	//Templates
	this.system_template = Handlebars.compile( $('#photo-input-template').html() );
	this.photo_item_template = Handlebars.compile( $('#photo-item-template').html() );
	this.photo_pager_template = Handlebars.compile( $('#photo-pager-template').html() );

	//View initialization
	this.viewInit = function() {
		//hide stuff.
		$(this.photo_chosen).hide();
		$(this.chosen_label).hide();
		$(this.processed_label).hide();

		//render the thing.
		this.target.append( $(this.system_template({site_url:window.site_url})) );
	}

	this.searchImages = function() {
		$(this.photo_results).fadeIn();//Fade the results.
		//first get the loading going
		url = window.site_url+'rest/flickr/?text='+ this.keyword + '&page=' + this.photo_search_page,//Gotta add pagination code.

		//scope issues.
		thumbUrl = this.thumbUrl;
		imageUrl = this.imageUrl;
		photo_template = this.photo_item_template;
		pager_template = this.photo_pager_template;
		photo_results = this.photo_results;
		photo_search_page = this.photo_search_page;

		this.getData(url, {}, function(data) {			
			$(photo_results).html('');//clear it out.
			photos = data.photos.photo;//actual photo array
			image_counter = 0;
			$.each(photos,function(index, value) {
				view_data = {
					id: value.id,
					image_url: thumbUrl(value),
					image_url_orig: imageUrl(value)
				}
				newImage = photo_template(view_data);				
				$(photo_results).append(newImage);
				image_counter = index;
			});

			if(image_counter == 0) {
				$(photo_results).append('No images were found with the keyword');
			}

			next_page = photo_search_page + 1;
			console.log('next_page: ' + next_page);
			if(next_page > 2 || image_counter >= 29) {
				$(photo_results).append('<br>');
			}

			if(next_page > 2) {
				prev_page = photo_search_page -1;
				view_data = {
					class: 'previous',
					html: '< Prev',
					page: prev_page
				}
				console.log('prev');
				previous = pager_template(view_data);
				$(photo_results).append(previous);
			}
			
			if(image_counter >= 29) {
				view_data = {
					class: 'more',
					html: 'More >',
					page: next_page
				}
				more = pager_template(view_data);
				$(photo_results).append(more);
			}

		});

	}
		this.thumbUrl = function(value) {
			return 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'_s.jpg';
		}

		this.imageUrl = function(value) {
			return 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'.jpg';
		}

	//grab a single image with the applied process
	this.applyProcess = function() {
		//set the variables.
		url = window.site_url + 'rest/photo/';
		data = {
			url: encodeURIComponent(this.selected_image),
			process: this.process
		}

		that = this;

		this.getData(url, data, function(data) {
			$('.loading-container').css('z-index', -1);  // hide loading
			that.input.val('');//reset the input
			$(that.chosen_label).fadeOut();
			$(that.processed_label).fadeIn();
			$(that.photo_chosen).fadeIn();
			$(that.photo_chosen).css('background-image', '');
			image_url = window.site_url+'uploads/final_images/'+data;
			$(that.photo_chosen).css('background-image','url('+image_url+')' );

			//for other applications than the post input page.
			if(that.image_dom.length) {
				$(that.image_dom).css('background-image','url('+image_url+')' );
			}

			//Checks to see if this is the intial phase of image selection.
			if(that.input.val().length == 0) {
				$(that.image_select).fadeOut();
				$(that.image_edit).fadeIn();
			}
			that.input.val(data).trigger('change');//actually place in the data.
		});
	}

	this.getData = function(url, data, callback) {			
		$.ajax({
			type:"GET",
			url: url,
			data: data,
			success: function(data) {
				callback(data);
			},
			complete: function(xhr, status) {
				//console.log(xhr);
			},
			error: function(xhr, status) {
				//console.log(xhr+status);
			}
		});
	}

}