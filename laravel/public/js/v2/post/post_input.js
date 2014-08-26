/**
This file handles the save/drafts system along with the content editable vs not system selection.
**/
$(function() {

	//gotta call the validation system here.
	save_post.form = $('form.post_input');
	save_post.form.validate(save_post.validate_options);
	
	//Below binds the modal close event incase there's an error
	$('#imageModal').on('hidden.bs.modal', function () {
		$('.modal-header span.image-error',this).remove();
	});

	//Content Editable vs Textarea based input.
	var iOSver = iOSversion();//iOS 4 and below do not support contentEditable.
	var contentEditableSupport = "contentEditable" in document.documentElement;

	//set the boxes to the variables.
	$contentEditable = $('.story .text-input');
	$textAreaInput = $('.story textarea.normal-input');

	if(contentEditableSupport || iOSver[0] >= 5 ) {
		//Advanced Editor for when we have content editable.
		window.editor = new MediumEditor($contentEditable, {
			buttons: ['bold', 'italic'],
			delay: 80,
			cleanPastedHTML: true,
			disableDoubleReturn: true,
			placeholder: 'Write Your Story Here.'
		});
		//remove the validation rules.
		$textAreaInput.rules("remove");
		$textAreaInput.remove();

		//$contentEditable.rules("add", save_post.body_rule);
	} else {
		//Gotta hide the contenteditable div and show the $.
		$contentEditable.fade();
		$contentEditable.remove();
		$contentEditable.rules("remove");

		//Show the TextArea
		$textAreaInput.show();
		window.editor = $textAreaInput;//designate the $editor as the text area.
	}
	save_post.editor = window.editor;


	//Controls.
	$('.controls-wrapper .categorization').click(function(event) {
		event.preventDefault;
		$('.category-wrapper').slideToggle('slow',function() {
			//Gotta figure out if we want to have a white bg.
			//$('body').append('<div class="modal-backdrop fade in"></div>');
		});
	});


	$('.controls-wrapper .save-draft').click(function() {
		save_post.sendDraft(window.editor);
	});

	$('.controls-wrapper .submit-post').click(function() {
		save_post.sendPublish(window.editor);
	});
});

function iOSversion() {
  if (/iP(hone|od|ad)/.test(navigator.platform)) {
    // supports iOS 2.0 and later: <http://bit.ly/TJjs1V>
    var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
    return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
  }
}

//Big bad save post class
var save_post = new function() {
	
	this.body_rule = {
		required: true,
		minlength: 400,
		maxlength: 14000,//This is generalized.
	};

	this.validate_options = {
		ignore: [],//":hidden:not(.editable)",
		rules: {
			title: {
				required: true,
				minlength: 5
			},
			image: {
				required: true,
				minlength: 1
			},
			'category[]': {
				required: true,
				minlength: 1,
				maxlength: 2
			},
			body: this.body_rule
		},
		messages: {
			image: {
				required: 'You need to select an image.'
			}
		},
		invalidHandler: function(form, validator) {
			//console.log(form);
			$.each(validator.errorList, function(idx,value) {

				//Category or Story Type issues.
				if(	$(value.element).hasClass('category') ) {
					if($('.category-wrapper').css('display') == 'none') {
						$('a.categorization').click();
					}
				}
				//image issue.
				if( $(value.element).hasClass('processed-image') ) {
					$('#imageModal .modal-header').append('<span class="error image-error">Be sure to pick an image!</span>');
					$('a.image-select-modal').click();//just pull up the modal.
				}
			});
		}
		
	};

	//States
	this.draft = 0;
	this.published = 0;

	//Information
	this.id = 0;

	this.title = '';

	this.tagline_1 = '';
	this.tagline_2 = '';
	this.tagline_3 = '';

	this.category = new Array();
	this.story_type = '';


	this.body = '';
	this.image = '';

	//Below compiles the data into an object we can send.
	this.dataCompile = function() {
		return {
			draft: this.draft,
			published: this.published,

			id: this.id,

			title: this.title,
			tagline_1: this.tagline_1,
			tagline_2: this.tagline_2,
			tagline_3: this.tagline_3,
			category: this.category,
			story_type: this.story_type,
			body: this.body,
			image: this.image
		}
	};

	this.validate = function() {
		console.log(this.form.valid(this.validate_options));
	};
	//this function grabs existing data and runs validation
	this.grabData = function() {

		this.id = $('input.id',this.form).val();
		this.title = $('input.title',this.form).val();;

		this.tagline_1 = $('input.tagline_1',this.form).val();
		this.tagline_2 = $('input.tagline_2',this.form).val();
		this.tagline_3 = $('input.tagline_3',this.form).val();
		//gotta work on the one below.
		this.category = new Array();
		this.story_type = $('input.tagline_1',this.form).val();

		//if the editor is a normal input, check it.
		if( $(this.editor).hasClass('normal-input')) {
			this.body = $('textarea.normal-input',this.form).val();
		} else {
			this.body = $('div.text-input',this.form).html();
		}
		
		this.image = $('input.processed-image',this.form).val();
		if(this.validate()) {
			this.send();
		}
	}

	//Functions.
	this.send = function() {
		$.ajax({
			type: "POST",
			url: window.site_url+'rest/savepost',
			data: this.dataCompile,//uses the data function to get 
			success: function(data) {
				console.log(data);
				switch(data.result) {
					default:
					case 'create':
						this.createAfter();
						break;
					case 'update':
						this.updateAfter();
						break;

				}
			}
		});
	};


	this.sendDraft = function() {
		//gotta set the states
		this.draft = 1;
		this.published = 0;
		this.grabData();
	};

	this.sendPublish = function() {
		//gotta set the states
		this.draft = 0;
		this.published = 1;
		this.grabData();
	};

	this.createAfter = function() {
		console.log('create');
	};

	this.updateAfter = function() {
		console.log('update');
	};
	
}