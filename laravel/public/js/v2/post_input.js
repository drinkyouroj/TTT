$(function() {

	//Content Editable vs Textarea based input.
	var iOSver = iOSversion();//iOS 4 and below do not support contentEditable.
	var contentEditableSupport = "contentEditable" in document.documentElement;

	if(contentEditableSupport || iOSver[0] >= 5 ) {
		//Advanced Editor for when we have content editable.
		var $editor = new MediumEditor($('.story .text-input'), {
			buttons: ['bold', 'italic'],
			delay: 80,
			cleanPastedHTML: true,
			placeholder: 'Write Your Story Here.'
		});
	} else {
		//Gotta hide the contenteditable div and show the $.
		$('.story .text-input').fade();
		$('.story textarea.normal-input').show();
		$editor = $('.story textarea.normal-input');//designate the $editor as the text area.
	}

	//Controls.
	$('.controls-wrapper .save-draft').click(function() {

	});

	$('.controls-wrapper .save-draft').click(function() {

	});

	//photo system activate
	photoSelection();

});

function iOSversion() {
  if (/iP(hone|od|ad)/.test(navigator.platform)) {
    // supports iOS 2.0 and later: <http://bit.ly/TJjs1V>
    var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
    return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
  }
}


//Photo Processing Systems**************************************************
function photoSelection() {
	$('.photo-chosen').hide();
	
	//Search catch on enter keydown (prevents the form from being submitted)
	$('.photos input.search-query').bind('keyup keypress', function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {               
			e.preventDefault();
			window.photo_search_page = 1;
			image_pull();//Pulls in images from Flickr
			$('.photo-results').fadeIn();
			return false;
		}
	});
	
	//The button for searching
	$('.activate-search').on('click', function() {
		window.photo_search_page = 1;
		image_pull();//Pulls in images from Flickr
		$('.photo-results').fadeIn();
	});//The Search button alias of the above.
	
	//Paginated 
	window.photo_search_page = 1;
	
	$('.photo-results').on('click', 'a.pager', function() {
		window.photo_search_page = $(this).data('page');
		console.log(window.photo_search_page);
		image_pull();
	});
	
	window.selected_image = 0;
	$('.chosen-label, .processed-label').hide();
	
	//Click on the photo results to select the image.
	$('.photo-results').on('click','img',function() {
		$('.photo-system .reset-search').removeClass('hidden');//Show the reset button
		img = $(this).data('image');//HTML5 rocks!
		$('.photo-chosen').html('');//empty the image from the chosen pile.
		
		window.selected_image = img;//attach the source to a global variable
		$('.photo-results').fadeOut();//Hide the photo options
		$('.photo-processor').fadeIn();//fade in the photo process options
		$('.chosen-label').fadeIn();
		
		//Lets grab a no filter version.
		image_grab(img, 'nofilter');
		
	});//Loads in the chosen photos
	
	//Let's reset the photo input system.
	$('.photo-system .reset-search').on('click', function() {
		$('.photo-chosen').css('background-image','');
		$('.photo-chosen').fadeOut();
		$('.photo-processed').html('');
		$('.photo-results').fadeIn();//Hide the photo options
		$('.photo-processor').fadeOut();//fade in the photo process options
		$('.post-form form input.processed-image').val('');//Get rid of the processed image.
		$('.photo-system .reset-search').addClass('hidden');//Hide the reset button
	});
	
	//Effects Processor: Instahamming it.
	$('.photo-processor').on('click', 'img', function() {
		url = window.selected_image;
		process = $(this).data('process');
		
		//make sure we have all 3 values.
		if(url.length && process.length) {
			image_grab(url, process);
		} else {
			console.log('error with image processor: missing var');
		}
	});//End of Photo Processor
}

//Function is used to pull images via the server from flickr.  This is for the Image Listing.
function image_pull() {
	if($('.photos input.search-query').val().length >= 3) {
		
		//Let's do the load.gif
		$('.photos .photo-results').html('<img width="200" src="'+window.site_url+'img/profile/loading.gif">');
		page = window.photo_search_page;
		keyword = $('.photos input.search-query').val();
		$.ajax({
			url: window.site_url+'rest/flickr/?text='+ keyword + '&page=' + page,//Gotta add pagination code.
			success: function(data) {
				$('.photos .photo-results').html('');
				photos = data.photos.photo;
				
				$.each(photos,function(index, value) {
					console.log(value);
					image_url = 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'_s.jpg';
					image_url_orig = 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'.jpg';
					
					var $newAppend = $('<img class="result-image '+value.id+'" src="'+image_url+'" data-image="'+image_url_orig+'">');
					
					$('.photos .photo-results').append($newAppend);
					image_counter = index;
				});
				
				//number of images on this page.
				console.log(image_counter);
				
				next_page = window.photo_search_page + 1;
				
				if(next_page > 2) {
					prev_page = window.photo_search_page -1;
					$previous = $('<a class="pager previous" data-page="'+prev_page+'">&#60 Prev</a>');
					$('.photos .photo-results').append($previous);
				}
				
				if(image_counter >= 29) {
					$more = $('<a class="pager more" data-page="'+next_page+'">More &#62</a>');
					$('.photos .photo-results').append($more);
				}
				
			}
		});
	}
}

//This grabs an individual image
function image_grab(url, process) {
	$.ajax({
		type: "GET",
		url: window.site_url+'rest/photo/',
		data: {
			url: encodeURIComponent(url),//Gotta encode that url
			process: process
		},
		success: function(data) {
			$('input.processed-image').val('');//Let's remove this just incase
			$('.chosen-label').fadeOut();
			$('.processed-label').fadeIn();
			$('.photo-chosen').fadeIn();
			$('.photo-chosen').css('background-image','');
			$('.photo-chosen').css('background-image','url('+window.site_url+'uploads/final_images/'+data+')' );
			$('.top-submit-container').css('background-image','url('+window.site_url+'uploads/final_images/'+data+')' );
			$('input.processed-image').val(data);
		}
	});
}