
$(function(){
	
	//Form Validation***********************************************************
	$('.post-form form').validate({
		ignore: [],
		rules: {
			title: {
				required: true,
				minlength: 5
			},
			'category[]': {
				required: true,
				minlength: 1,
				maxlength: 3
			},
			image: {
				required: true
			}
		},
		messages: {
			image: {
				required: 'You need to select an image.'
			}
		}
		
	});
	
});