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
				maxlength: 2
			},
			image: {
				required: true
			}
		},
		messages: {
			title: {
				//remote: 'The title is already in use'
			},
			image: {
				required: 'You need to select an image.'
			}
		}
	});

});