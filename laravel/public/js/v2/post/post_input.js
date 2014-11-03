/**
This file handles the save/drafts system along with the content editable vs not system selection.
**/
$(function() {

	//gotta call the validation system here.
	save_post.form = $('form.post_input');
	// save_post.form.validate(save_post.validate_options);
	
	save_post.form.on('submit',function(event) {
		event.preventDefault;
	});

	//photo system initialize
	photo_input = new PhotoInput;

	photo_input.target = $('#imageModal .modal-body');
	photo_input.input = $('input.processed-image');
	photo_input.image_dom = 'form .image-system';
	photo_input.application = 'post-input';

	photoInit(photo_input);	
	photo_input.viewInit();

	//Below binds the modal close event incase there's an error
	$('#imageModal').on('hidden.bs.modal', function () {
		$('.modal-header span.image-error',this).remove();
	});

	// ======= POST PREVIEW =======
	var source   = $("#post-preview-template").html();
	var post_preview_template = Handlebars.compile(source);
	$('.preview-button').click( function() {
		// Gather the post data, compute the post array
		save_post.grabData();
		$.ajax({
			type: 'POST',
			url: window.site_url+'rest/parse-post-body',
			data: {
				body: save_post.body
			},
			success: function( data ) {
				// Compile handlebars script and populate the modal
				console.log(save_post.story_type);
				// Have to fetch categories manually
				var categories = [];
				$('.category:checked').each(function() {
					var title = $(this).data('title'); 
					categories.push(title);
				});
				var preview = post_preview_template({
					title: save_post.title,
					tagline_1: save_post.tagline_1,
					tagline_2: save_post.tagline_2,
					tagline_3: save_post.tagline_3,
					author: data.author,
					story_type: save_post.story_type,
					categories: categories,
					image_url: window.image_url + '/' + save_post.image,
					body_array_count: data.body_array.length + '',
					body_array: data.body_array
				});
				$('#previewModal .modal-body').html(preview);
			}
		});
	});

	//
	$('body').on('dragstart dragover dragenter dragleave drop', function (event) {
	    event.preventDefault();
	    return false;
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
			forcePlainText: true,
			disableDoubleReturn: true,
			placeholder: 'Write Your Post Here.'
		});
		save_post.editable=true;
		//hide the textarea
		$textAreaInput.css('display','none');
		save_post.textarea = $textAreaInput;
		save_post.editable = $contentEditable;
	} else {
		//Gotta hide the contenteditable div and show the $.
		$contentEditable.fade();
		$contentEditable.remove();
		$contentEditable.rules("remove");

		//Show the TextArea
		$textAreaInput.show();
		window.editor = $textAreaInput;//designate the $editor as the text area.
		save_post.editable=false;
	}
	save_post.editor = window.editor;

	//Controls.
	$('.categorization').click(function(event) {
		event.preventDefault();
		$('.category-wrapper').slideToggle('fast',function() {
			//Gotta figure out if we want to have a white bg.
			//$('body').append('<div class="modal-backdrop fade in"></div>');
		});
	});

	$('.close-category>a').click(function(event) {
		event.preventDefault();
		$('.category-wrapper').slideToggle('fast');
	});

	$('.save-draft').click(function() {
		save_post.autosave = false;
		save_post.sendDraft(window.editor);
	});

	$('.submit-post').click(function() {
		save_post.autosave = false;
		save_post.sendPublish(window.editor);
	});


	//prevent text input from being 
	$('textarea.title').bind('keypress', function(e) {
	  if ((e.keyCode || e.which) == 13) {
	    e.preventDefault();
	    return false;
	  }
	});

	// Simple frontend stop to selecting more than 2 categories
	$('input.category').on('change', function(evt) {
	   if($('input.category:checked').length > 2) {
	       this.checked = false;
	       $('.category-box .warning').removeClass('hidden');
	   }
	});

	// Only start autosave on drafted posts
	if ( !save_post.published ) {
		// If the post hase been previously drafted, start autosaving right away
		if ( save_post.draft && save_post.post_id != 0 ) {
			start_save();
		// Otherwise, dont start autosaving until a title has been entered.
		} else {
			$('textarea.title').on('change', function(event) {
				// console.log($(this).val().length);
				if($(this).val().length >= 5) {
					start_save();
				} else if($(this).val().length < 5) {
					stop_save();
				}
			});
			// Listen for change on contenteditable
			document.getElementById("post-content-editable").addEventListener("input", function() {
			    if(this.innerHTML.length >= 100) {
					start_save();
				} else if($(this).val().length < 100) {
					stop_save();
				}
			}, false);
		}
	}

	function start_save() {
		if(save_post.autosave_started == false) {
			// console.log('auto save started!');
			save_post.autosave = true;
			save_post.sendAutoSaveDraft();//send draft the moment this system starts.
			save_post.autoSaveInterval = setInterval(function() {
				save_post.sendAutoSaveDraft();
				$('.draft-publish .save-draft').text('Saving...');
				setTimeout(function() {
					$('.draft-publish .save-draft').text('Draft');
				}, 2000);
				
			}, 20000);//saves every 10 seconds.
			save_post.autosave_started = true;
		}
	}

	function stop_save() {
		// console.log('auto save cleared!');
		clearInterval(save_post.autoSaveInterval);
	}

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
	
	this.validate_options = {
		debug: true,
		ignore: [],
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
			body: {
				required: true,
				minlength: 800,
				maxlength: 14000,
			},
			story_type: {
				required: true
			}
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
				if(	$(value.element).hasClass('category') || $(value.element).hasClass('story-type') ) {
					if($('.category-wrapper').css('display') == 'none') {
						$('.category-wrapper').slideDown('fast');
					}
				}
				//image issue.
				if( $(value.element).hasClass('processed-image') ) {
					$('#imageModal .modal-header').append('<span class="error image-error">Be sure to pick an image!</span>');
					$('a.image-select-modal').click();//just pull up the modal.
				}
			});
		},
		submitHandler: function(form) {
			return false;
		}
	};
	this.validate_options_draft = {
		debug: true,
		rules: {
			title: {
				required: true,
				minlength: 5
			}
		}
	};

	this.autosave_started = false;

	//States
	this.draft = 0;
	this.published = 0;

	//Information
	this.post_id = 0;

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

			id: this.post_id,

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

	//content validation.
	this.validate = function() {
		//check to see if we're using contentEditable
		if(this.editable) {
			//yes
			data = this.editable.html();
			this.textarea.html( data );//load in the data into the textarea.
		}
		
		if (this.draft) {
			this.form.validate(this.validate_options_draft);
			return this.form.valid();
		} else {
			this.form.validate(this.validate_options);
			return this.form.valid();
		}
	};

	//this function grabs existing data and runs validation
	this.grabData = function() {

		//this.post_id = $('input.id',this.form).val();
		this.title = $('textarea.title',this.form).val();;

		this.tagline_1 = $('input[name="tagline_1"]',this.form).val();
		this.tagline_2 = $('input[name="tagline_2"]',this.form).val();
		this.tagline_3 = $('input[name="tagline_3"]',this.form).val();
		//gotta work on the one below.
		this.category = new Array();

		$('.category-box ul input:checked').each(function() {			
			save_post.category.push($(this).val());
		});

		this.story_type = $('select#story_type',this.form).val();

		//if the editor is a normal input, check it.
		if( $(this.editor).hasClass('normal-input')) {
			this.body = $('textarea.normal-input',this.form).val();
		} else {
			this.body = $('div.text-input',this.form).html();
		}
		
		this.image = $('input.processed-image',this.form).val();

		//validate the data and if its working, we can just send it out.
	}

	//Functions.
	this.send = function() {
		// console.log(this.dataCompile());
		save_post = this;
		$.ajax({
			type: "POST",
			url: window.site_url+'rest/savepost',
			data: {
					draft: this.draft,
					published: this.published,

					id: this.post_id,

					title: this.title,
					tagline_1: this.tagline_1,
					tagline_2: this.tagline_2,
					tagline_3: this.tagline_3,
					category: this.category,
					story_type: this.story_type,
					body: this.body,
					image: this.image,
					autosave: this.autosave
				},//uses the data function to get 
			success: function(data) {
				// console.log(data);

				switch(data.result) {
					default:
					case 'create':
						//create scenario
						save_post.createAfter(data);//Singleton self referring!
						break;
					case 'update':
						//update scenario
						save_post.updateAfter(data);
						break;
				}
			},
			complete: function(xhr, status) {
				// console.log(xhr.status);
			},
			error: function(xhr, status) {
				switch(xhr.responseJSON.error) {
					default:
					case '72':
						//72 hours limit has passed for editting.
						console.log('you can not edit published posts after 72 hours');
						break;
					case '10':
						//YOu've posted in the last 10 minuts.
						console.log('you have posted in the last 10 minutes');
						break;
					case '405':
						//Validation is really bad (as in they bypassed our client side validation scheme)
						//Redirect this person to terms.
						console.log('you have done some bad things (failed validation)');
						break;
				}
			}
		});
	};

	this.sendAutoSaveDraft = function () {
		this.grabData();
		this.autosave = true;
		// skip frontend validation for autosave...
		this.send();
	}

	this.sendDraft = function() {
		//gotta set the states
		this.draft = 1;
		this.published = 0;
		this.grabData();//grab data automatically sends out the data via "send function"
		this.autosave = false;
		if(this.validate()) {
			this.send();
		}
	};

	this.sendPublish = function() {
		//gotta set the states
		this.draft = 0;
		this.published = 1;
		this.grabData();
		this.autosave = false;
		if(this.validate()) {
			this.send();
		}
	};

	this.createAfter = function(data) {
		//first, make sure that update gets called from this point on.
		this.post_id = data.id;
		this.alias = data.alias;

		//call in the shared after send function.
		this.sharedAfter();

		//show the preview button
		$('.preview-button').fadeIn();

		if(this.published) {
			a_link.fadeIn();
		}
		//Make sure to change the "Submit to Update"
		//$('.submit-post').html('Publish');

	};

	this.updateAfter = function(data) {
		this.alias = data.alias;
		this.sharedAfter();
	};

	//This one updates and sets the 
	this.sharedAfter = function(data) {

		article_link = window.site_url+'posts/'+this.alias;

		if(this.published) {
			a_link = $('.article-link');
			a_link.prop('href',article_link);
		}
		if(this.autosave == false) {
			if(this.draft ) {
				window.location.href = window.site_url+'myprofile#drafts';
			} else {
				window.location.href = window.site_url+'myprofile#collection';
			}
		}
	}

}