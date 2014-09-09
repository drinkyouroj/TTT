$(function() {

	//target is the modal body div.
	photo_input.target = $('#modalBody');//make sure to have an id set on it.
	photo_input.input = $('input.processed-image');//define which input the system is going to give the image to.

	//inits the photo system.
	photo_input.viewInit();
});

//portable photo_input system.
var photo_input = function() {

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
	this.query_input = $('.photos input.search-query',this.target);
	this.photo_results = $('.photo-results',this.target);
	this.photo_chosen = $('.photo_chosen',this.target);
	this.photo_processor = $('.photo-processor',this.target);
	this.image_select = $('.top-submit-container .image-select');
	this.image_edit = $('.top-submit-container .image-edit');

	//Buttons
	this.reset = $('.photo-system .reset-search',this.target);

	//Labels
	this.chosen_label = $('.chosen-label', this.target);
	this.processed_label = $('.processed-label', this.target);

	//Templates
	this.system_template = Handlebars.compile( $('#photo-input-template').html() );
	this.photo_item_template = Handlebars.compile( $('#photo-item-template').html() );
	this.photo_pager_template = Handlebars.compile( $('#photo-pager-template').html() );

	//View initialization
	this.viewInit = function() {
		//hide stuff.
		this.photo_chosen.hide();
		this.chosen_label.hide();
		this.processed_label.hide();

		this.target.append(this.system_template());
		//get the inputs bound.
		this.inputBindings();

	}

	//Events and key bindings are stored in here.
	this.inputBindings = function() {
		//Search catch on enter keydown (prevents the form from being submitted)
		this.query_input.bind('keyup keypress', function(e) {
			var code = e.keyCode || e.which; 
			if (code  == 13 && $(this).val().length >=3 ) {
				e.preventDefault();

				this.photo_search_page = 1;//reset the page.
				this.keyword = $(this).val();

				this.searchImages();//Pulls in images from Flickr

				this.photo_results.fadeIn();//Fade the results.
				return false;
			}
		});

		//Pager for the photo result sets
		this.photo_results.on('click', 'a.pager', function() {
			this.photo_search_page = $(this).data('page');
			this.searchImages();
		});

			//Click on the photo results to select the image.
		this.photo_results.on('click','img',function() {
			this.reset.removeClass('hidden');//Show the reset button

			thi.selected_image = $(this).data('image');//HTML5 rocks!

			this.photo_chosen.html('');//empty the image from the chosen pile.
			this.photo_results.fadeOut();//Hide the photo selection options
			this.photo_processor.fadeIn();//fade in the photo process options
			this.chosen_label.fadeIn();
			
			//Lets grab a no filter version.
			this.process = 'nofilter';
			this.applyProcess()
			
		});//Loads in the chosen photos

	}

	this.searchImages = function() {
		//first get the loading going
		url = window.site_url+'rest/flickr/?text='+ this.keyword + '&page=' + this.page,//Gotta add pagination code.

		//scope issues.
		thumbUrl = this.thumbUrl;
		imageUrl = this.imageUrl;
		photo_template = this.photo_item_template;
		pager_template = this.photo_pager_template;

		this.getData(url, {}, function(data) {
			$('.photos .photo-results',target).html('');//clear it out.
			
			photos = data.photos.photo;//actual photo array
			$.each(photos,function(index, value) {
				view_data = {
					id: value.id,
					image_url: thumbUrl(value),
					image_url_orig: imageUrl(value)
				}
				newImage = photo_template(view_data);				
				$('.photos .photo-results',target).append(newImage);
				image_counter = index;
			});

			next_page = this.photo_search_page + 1;
				
			if(next_page > 2) {
				prev_page = this.photo_search_page -1;
				view_data = {
					class: 'previous',
					html: '&#60 Prev',
					page: prev_page
				}

				previous = pager_template(view_data);
				$('.photos .photo-results',target).append(previous);
			}
			
			if(image_counter >= 29) {
				view_data = {
					class: 'more',
					html: 'More &#62',
					page: next_page
				}
				more = pager_template(view_data);
				$('.photos .photo-results',target).append(more);
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
			that.input.val('');//reset the input
			that.chosen_label.fadeOut();
			that.processed_label.fadeIn();
			that.photo_chosen.fadeIn();
			that.photo_chosen.css('background-image', '');
			that.photo_chosen.css('background-image','url('+window.site_url+'uploads/final_images/'+data+')' );
			//Checks to see if this is the intial phase of image selection.
			if(that.input.val().length == 0) {
				that.image_select.fadeOut();
				that.image_edit.fadeIn();
			}
			that.input.val(data);//actually place in the data.
		});
	}

	this.getData = function(url, data, callback) {
		$.ajax({
			type:"GET",
			url: url,
			data: data,
			sucess:function(data) {
				callback(data);
			},
			complete: function(xhr, status) {

			},
			error: function(xhr, status) {

			}
		});
	}

}