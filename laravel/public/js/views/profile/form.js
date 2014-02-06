jQuery.validator.addMethod("maxthree", function(value, element) {
  return $(element).find(":selected").length <= 3;
},"Max Three Categories.");

$(function(){
	
	//Form Validation***********************************************************
	$('.post-form form').validate({
		rules: {
			title: {
				required: true,
				minlength: 5,
				remote: {
					url: window.site_url+'rest/posttitle',//Gotta make sure the title doesn't exist already...
					type: 'GET',
					contentType: "application/json"
				}
			},
			category: {
				maxthree: true
			}
		},
		messages: {
			title: {
				remote: 'The title is already in use'
			}
		}
		
	});
	
	//Tool Tips*****************************************************************
	$('.form-group a').tooltip({
		placement: 'top'
	});
	
	//Photo Processing Systems**************************************************
	
	//Search catch on enter keydown (prevents the form from being submitted)
	$('.photos input.search-query').bind('keyup keypress', function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {               
			e.preventDefault();
			image_pull();//Pulls in images from Flickr
			return false;
		}
	});
	
	//The button for searching
	$('.activate-search').on('click', function() {
		image_pull();//Pulls in images from Flickr
	});//The Search button alias of the above.
	
	window.selected_image = 0;
	
	//Click on the photo results to select the image.
	$('.photo-results').on('click','img',function() {
		img = $(this).data('image');//HTML5 rocks!
		$('.photo-chosen').html('');//empty the image from the chosen pile.
		$newImage = $('<img src="'+img+'">');
		$('.photo-chosen').append($newImage);
		window.selected_image = img;//attach the source to a global variable
		$('.photo-results').fadeOut();//Hide the photo options
		$('.photo-processor').fadeIn();//fade in the photo process options
	});//Loads in the chosen photos
	
	//Let's reset the photo input system.
	$('.photo-system .reset-search').on('click', function() {
		$('.photo-chosen').html('');
		$('.photo-processed').html('');
		$('.photo-results').fadeIn();//Hide the photo options
		$('.photo-processor').fadeOut();//fade in the photo process options
		$('.post-form form input.processed-image').remove();//Get rid of the processed image.
	});
	
	//Effects Processor: Instahamming it.
	$('.photo-processor').on('click', 'img', function() {
		url = window.selected_image;
		title = $('form input.title').val();
		process = $(this).data('process');//pick up the process type
		
		//make sure we have all 3 values.
		if(url.length && title.length && process.length) {
			$.ajax({
				type: "GET",
				url: window.site_url+'rest/photo/',
				data: {
					url: encodeURIComponent(url),//Gotta encode that url
					title: title,
					process: process
				},
				success: function(data) {
					$('.photo-chosen').fadeOut;
					$('.photo-processor').fadeOut;
					$('.post-form form input.processed-image').remove();//Let's remove this just incase
					$('.photo-processed').html('');
					$('.photo-processed').append('<img src="'+window.site_url+'uploads/final_images/'+data+'">');
					$('.post-form form').append($('<input class="processed-image" type="hidden" name="image" value="'+data+'" >'));
				}
			});
		} else {
			console.log('error with image processor: missing var');
		}
	});//End of Photo Processor
	
	
	//The Carousel Like effect on the Form********************************************************
	
	/**
	 * NOTE: I use $.localScroll instead of $('#navigation').localScroll() so I
	 * also affect the >> and << links. I want every link in the page to scroll.
	 */
	$('.form-nav').localScroll({
		target: '.form-container', // could be a selector or a jQuery object too.
		queue:true,
		duration:1000,
		hash:true,
		onBefore:function( e, anchor, $target ){
			// The 'this' is the settings object, can be modified
		},
		onAfter:function( anchor, settings ){
			// The 'this' contains the scrolled element (#content)
		}
	});
	
	
});


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
					
					image_url = 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'_s.jpg';
					image_url_orig = 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'.jpg';
					
					var $newAppend = $('<img class="result-image '+value.id+'" src="'+image_url+'" data-image="'+image_url_orig+'">');
					$('.photos .photo-results').append($newAppend);
				});
				
			}
		});
	}
}