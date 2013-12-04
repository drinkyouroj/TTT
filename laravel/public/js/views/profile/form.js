$(function(){
	$('.form-group a').tooltip({
		placement: 'top'
	});
	
	$('.post-form form').bind("keyup keypress", function(e) {
	  var code = e.keyCode || e.which; 
	  if (code  == 13) {               
	    e.preventDefault();
	    return false;
	  }
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
	
	$('.photo-results').on('click','img',function() {
		img = $(this).data('image');//HTML5 rocks!
		$('.photo-chosen').html('');
		
		$newImage = $('<img src="'+img+'"> <input type="hidden" name="image" value="'+img+'" >');
		$('.photo-chosen').append($newImage);
	});
	
});


function image_pull() {
	if($('.photos input.search-query').val().length >= 3) {
		
		//Let's do the load.gif
		$('.photos .photo-results').html('<img src="'+window.site_url+'">');
		
		keyword = $('.photos input.search-query').val();
		$.ajax({
			url: window.site_url+'rest/flickr/?text='+ keyword,
			success: function(data) {
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