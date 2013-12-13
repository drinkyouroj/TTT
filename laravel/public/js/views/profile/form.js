jQuery.validator.addMethod("maxthree", function(value, element) {
  return $(element).find(":selected").length <= 3;
},"Max Three Categories.");

$(function(){
	
	$('.post-form form').validate({
		rules: {
			title: {
				required: true,
				minlength: 5,
				remote: {
					url: window.site_url+'rest/posttitle',
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
	
	$('.form-group a').tooltip({
		placement: 'top'
	});
	
	
	$('.photos input.search-query').bind('keyup keypress', function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {               
			e.preventDefault();
			image_pull();
			return false;
		}
	});
	
	$('.activate-search').on('click', image_pull());
	
	window.selected_image = 0;
	
	$('.photo-results').on('click','img',function() {
		img = $(this).data('image');//HTML5 rocks!
		$('.photo-chosen').html('');
		$newImage = $('<img src="'+img+'"> <input type="hidden" name="image" value="'+img+'" >');
		$('.photo-chosen').append($newImage);
		window.selected_image = img;//attach the source to a global variable
		$('.photo-results').fadeOut();//Hide the photo options
		$('.photo-processor').fadeIn();//fade in the photo process options
	});
	
	$('.photo-processor').on('click', 'img', function() {
		url = window.selected_image;
		console.log(url);
		title = $('form input.title').val();
		console.log(title);
		process = $(this).data('process');//pick up the process type
		console.log(process);
		
		//make sure we have all 3 values.
		if(url.length && title.length && process.length) {
			$.ajax({
				type: "GET",
				url: window.site_url+'rest/photo/',
				data: {
					url: encodeURIComponent(url),
					title: title,
					process: process
				},
				success: function(data) {
					console.log(data);
					$('.photo-processed').html('');
					$('.photo-processed').append('<img src="'+window.site_url+'uploads/final_images/'+data+'">');
				}
			});
		}
		
	});
	
	
});


function image_pull() {
	if($('.photos input.search-query').val().length >= 3) {
		
		//Let's do the load.gif
		$('.photos .photo-results').html('<img width="200" src="'+window.site_url+'img/profile/loading.gif">');
		
		keyword = $('.photos input.search-query').val();
		$.ajax({
			url: window.site_url+'rest/flickr/?text='+ keyword,
			success: function(data) {
				$('.photos .photo-results').html('');
				photos = data.photos.photo;
				$.each(photos,function(index, value) {
					
					image_url = 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'_s.jpg';
					image_url_orig = 'http://farm'+value.farm+'.static.flickr.com/'+value.server+'/'+value.id+'_'+value.secret+'.jpg';
					
					var $newAppend = $('<img class="result-image '+value.id+'" src="'+image_url+'" data-image="'+image_url_orig+'">');
					$('.photos .photo-results').append($newAppend);
					if(index> 30) {//This just limits the query so that it only gets 30 right now.  We'll get pagination figured out soon.
						return false;
					}
				});
			}
		});
	}
}