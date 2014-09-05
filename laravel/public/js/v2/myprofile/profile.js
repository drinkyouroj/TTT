//Events in the Profile Page
$(function() {
//Initialize Profile class
	var profile = new ProfileActions;

//Let's Compile the handlebars source.
	//Collection container
	var collection_source = $('#collection-template').html();
	profile.collection_template = Handlebars.compile(collection_source);
	
	//Container for default.
	var default_source = $('#default-profile-template').html();
	profile.default_template = Handlebars.compile(default_source);

	//Post Item compile (used for both collection and feed)
	var post_item_source = $('#post-item-template').html();
	profile.post_item_template = Handlebars.compile(post_item_source);

	var feature_item_source = $('#feature-item-template').html();
	profile.feature_item_template = Handlebars.compile(feature_item_source);

	//Comment Item compile
	var comment_item_source = $('#comment-template').html();
	profile.comment_item_template = Handlebars.compile(comment_item_source);

	//Saves Item
	var saves_item_source = $('#saves-template').html();
	profile.saves_item_template = Handlebars.compile(saves_item_source);	

	//Drafts Item
	var drafts_item_source = $('#drafts-template').html();
	profile.drafts_item_template = Handlebars.compile(drafts_item_source);

	//Settings template
	var settings_source = $('#settings-template').html();
	profile.settings_template = Handlebars.compile(settings_source);

	//Follow template
	var follow_template = $('#follow-template').html();
	profile.follow_template = Handlebars.compile(follow_template);
	

//View renders based on the id selectors.
	$('.section-selectors a').click(function(event) {
		//event.preventDefault();
		$('.section-selectors a').removeAttr('class');//gets rid of active state.
		$(this).prop('class','active');
		profile.view = $(this).prop('id');
		profile.type = 'all';//default

		//window.location.hash = profile.view;
		profile.viewInit($(this).prop('id'));//new view has to be rendered out of this scenario
	});

	//Collection Renders
		$('body').on('click','.collection-controls a', function(event) {
			event.preventDefault();

			$('.collection-controls a').removeAttr('class');
			$(this).prop('class','active');
			profile.type = $(this).data('type');
			profile.page = 1;
			profile.filter = true;
			profile.viewRender();
		});

	//Feed Filter Renders
		$('body').on('click', '.feed-controls a', function(event) {
			event.preventDefault();
			$('.feed-controls a').removeAttr('class');
			$(this).prop('class','active');
			profile.type = $(this).data('type');
			profile.page = 1;
			profile.filter = true;
			profile.viewRender();
		});


//View renders for settings/follow
	$('.header-right a').click(function(event) {
		//event.preventDefault();
		$('.section-selectors a').removeAttr('class');//gets rid of the class.
		profile.view = $(this).prop('id');

		if($(this).hasClass('followers') || $(this).hasClass('following')) {
			profile.type = window.user_id;
		}

		profile.viewInit($(this).prop('id'));//new view has to be rendered out of this scenario
	});

	//image upload code.
	$('body').on('change', '#uploadAvatar input#image', function() {
		profile.avatarUpload();
	});

	//password change
	$('body').on('click', '#changePassword button',function() {
		profile.changePassword();
	});

	$('#deleteModal').on('click','.btn.delete-account', function() {
		id = $(this).data('user');
		if(id) {
			$.ajax({
				url: window.site_url+'rest/userdelete/'+id,
				type:"GET",//previously delete
				success: function(data) {
					window.location.href = window.site_url+'user/logout';
				}
			});
		}
	});

//Set Featured events
	$('body').on('click', '.set-featured',function() {
		profile.setFeatured($(this).data('id'));
	})

//Pagination detection.
	$(window).scroll(function() {
		if(profile.view != 'settings') {
			if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
				profile.paginate();
			}
		}
	});


//System INIT.
	profile.target = $('#profile-content');
	
	// Figure out the hash so we can load the right content in.
	if(typeof window.location.hash  == 'undefined' || !window.location.hash.length) {
		window.location.hash = 'collection';
		profile.view = 'collection';
		profile.type = 'all';
		profile.viewInit('collection');//Render initial view.
	} else {
		view = window.location.hash;

		$('.section-selectors a').removeClass('active');
		$(view, '.section-selectors').prop('class', 'active');

		if(view == '#followers' || view == '#following') {
			profile.type = window.user_id;
		}

		profile.view = view.substring(1);
		profile.viewInit(profile.view);//Render initial view.
	}

	
	window.page_processing = false;
	window.comment_page_processing = false;
	

});

//The User Profile functions.
function ProfileActions() {

	this.view = 'collection';//set a default view
	this.type = 'all';
	this.filter = false; //this is if we're only changing the type and doing another pull

	//View initialization for when you click on a new view.
	this.viewInit = function(view) {
		//Everytime a view is rendered the page count should be reset.
		this.page = 1;
		this.comment_page = 1;//this only pertains to the collection page
		this.view = view
		//fade in fade out scenario
		var that = this;//JS scope is fun... not.
		this.target.fadeOut(100,function() {
			that.target.html('');
			that.viewRenderContainer();
			that.viewRender(true);
			that.target.fadeIn(100);

		});
	};

	//Container init
	this.viewRenderContainer = function() {
		if(this.view == 'collection') {
			//collection has a different outer template
			this.target.html(this.collection_template());
		} else {
			this.target.html(this.default_template({view: this.view}));
		}
	};

	//Actual Content Rendering routes
	this.viewRender = function(init) {		
		if(this.filter) {
			this.viewClear();
		}
		switch(this.view) {
			default:
			case 'collection':
				this.renderCollection();
				if(init) {					
					this.renderComments();					
				}
				if((init || this.type == 'all') && window.featured_id && this.page == 1 ) {
					this.renderFeatured();//only renders when the person has a featured article.
				}
				break;

			case 'feed':
				this.renderFeed();
				break;

			case 'saves':
				this.renderSaves();
				break;

			case 'drafts':
				this.renderDrafts();
				break;

			case 'settings':
				this.renderSettings();
				break;

			case 'followers':
				this.renderFollowers();
				break;

			case 'following':
				this.renderFollowing();
				break;
		}
	};

	//Clears the content before filters
	this.viewClear = function() {
		if(this.view == 'collection') {
			clear = $('#collection-content',this.target);
		} else {
			clear = $('#default-content',this.target);
		}
		clear.html('');
		this.filter = false;
	}

	//URL constructor
	this.urlConstructor = function() {
		base_url = window.site_url + 'rest/profile/' + this.view + '/';

		var viewArray = ['collection', 'feed', 'following', 'followers'];
		
		if( viewArray.indexOf(this.view) != -1) {
			
			if(this.view == 'collection') {
				this.url = base_url + this.type + '/' + window.user_id + '/' + this.page;
				this.feature_url = window.site_url + 'rest/profile/featured/' + window.featured_id;
				this.comment_url = window.site_url + 'rest/profile/comments/' + window.user_id + '/' + this.comment_page;
			} else {
				this.url = base_url + this.type + '/' + this.page;
			}

		} else {
			this.url = base_url + this.page;
		}
	};


//Specific Render methods

	this.renderCollection = function() {
		//below has to be done to pass through the scope of both getData and $.each
		var post_item_template = this.post_item_template;
		var target = this.target;
		var editCheck = this.editCheck;
		this.urlConstructor();
		this.getData(this.url, function(data) {
			$.each(data.collection, function(idx, val) {
				var editable = editCheck(val.post.published_at);
				view_data = {
					site_url: window.site_url,
					post: val.post,
					user_id: window.user_id,
					editable: editable,
					featured_id: window.featured_id,
					post_type: val.post_type,
					myprofile: window.myprofile
				};
				$('#collection-content',target).append(post_item_template(view_data));
			});
		});
		
	};

		this.editCheck = function(published_at) {
			date = moment(published_at);
			threeDaysAgo  = moment().subtract('3','days');
			return date.tz('America/Los_Angeles') >= threeDaysAgo.tz('America/Los_Angeles');
		}

	this.renderFeatured = function() {
		var feature_item_template = this.feature_item_template;
		var target = this.target;
		this.urlConstructor();
		this.getData(this.feature_url,function(data) {
			view_data = {
				site_url: window.site_url,
				post: data.featured
			}
			$('#collection-content',target).prepend( feature_item_template(view_data) );

		});
	}

		this.setFeatured = function(id) {
			$('#collection-content .feature-item',target).fadeOut().remove();

			featured_url = window.site_url + 'rest/profile/featured/' + id;
			var target = this.target;
			this.setData(featured_url, function(data) {
				//console.log(data);
				
				
			});
			window.featured_id = id;//make the window remember the featured id.
			this.renderFeatured();
		}

	this.renderComments = function() {
		//scope issues
		var comment_item_template = this.comment_item_template;
		var target = this.target;
		this.urlConstructor();
		this.getData(this.comment_url, function(data) {
			console.log(data);
			$.each(data.comments,function(idx, val) {

				view_data = {
					site_url: window.site_url,
					comment: val
				}
				$('#comment-content',target).append(comment_item_template(view_data));
			});
		});		
	}

	this.renderFeed = function() {
		//scope issues
		var post_item_template = this.post_item_template;
		var target = this.target;
		this.urlConstructor();
		this.getData(this.url,function(data) {
			$.each(data.feed, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					post: val.post
				};
				$('#default-content',target).append(post_item_template(view_data));
			});
		});
	};

	this.renderSaves = function() {
		//scope issues
		var saves_item_template = this.saves_item_template;
		var target = this.target;
		this.urlConstructor();
		console.log(this.url);
		this.getData(this.url,function(data) {
			$.each(data.saves, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					save: val.post,
					date: val.created_at
				};
				$('#default-content',target).append(saves_item_template(view_data));
			});
		});

	};

	this.renderDrafts =  function() {
		//scope issues
		var drafts_item_template = this.drafts_item_template;
		var target = this.target;
		this.urlConstructor();
		draftDate = this.draftDate;
		this.getData(this.url,function(data) {			
			$.each(data.drafts, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					draft: val,
					date: draftDate(val.updated_at)
				};
				$('#default-content',target).append(drafts_item_template(view_data));
			});
		});

	};
	
		this.draftDate = function(updated_at) {
			updated = moment(updated_at);
			twoDaysAgo  = moment().subtract('2','days');
			if( updated.tz('America/Los_Angeles') >= twoDaysAgo.tz('America/Los_Angeles') ) {
				return updated.calendar();
			} else {
				return updated.format('MM DD YYYY');
			}
		}

	this.renderFollowers = function() {
		var follow_template = this.follow_template;
		var target = this.target;
		this.urlConstructor();
		this.getData(this.url, function(data) {
			$.each(data.follow, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					username: val.followers.username,
					user_id:  val.follower_id
				};
				$('#default-content', target).append(follow_template(view_data));
			});
		});

	}

	this.renderFollowing = function() {
		var follow_template = this.follow_template;
		var target = this.target;
		this.urlConstructor();
		this.getData(this.url, function(data) {
			$.each(data.follow, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					username: val.following.username,
					user_id:  val.user_id
				};
				$('#default-content', target).append(follow_template(view_data));
			});
		});
	}

	this.renderSettings = function() {
		//simple stuff.
		$('#default-content', this.target).append(this.settings_template({site_url: this.site_url}));
	}

		this.avatarUpload = function() {
			$('#uploadAvatar').ajaxForm({
				beforeSubmit: this.avatarRequest,
				success: this.avatarResponse,
				dataType: 'json'
			}).submit();
		}

		this.avatarRequest = function (formData, jqForm, options) {
			$("#avatarErrors").hide().empty();
		    $("#avatarOutput").css('display','none');
		    return true; 
		}

		this.avatarResponse = function (response, statusText, xhr, $form) {
			var $errors = $("#avatarErrors");
			var $output =  $("#avatarOutput");
			var site_url = this.site_url;
			if(response.success == false)
		    {
		        var arr = response.errors;
		        $.each(arr, function(index, value)
		        {
		            if (value.length != 0)
		            {
		               $errors.append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
		            }
		        });
		        $errors.show();
		    } else {
		    	$('.header-wrapper .avatar-image').animate({opacity: 0},'slow', function() {
		    		$(this).css(
		    			{'background-image': 'url("uploads/avatars/' +response.image+'")' }
		    		).animate({opacity: 1});
		    	});
		        $output.html('<img src="uploads/avatars/' +response.image+'" />');
		        $output.css('display','block');
		    }
		}

	this.changePassword = function() {
		$('form#changePassword').ajaxForm({
			beforeSubmit: this.passRequest,
			success: this.passResponse,
			dataType: 'json'
		}).submit();
	}

		this.passRequest = function(formData, jsForm, options) {
			$('form#changePassword .message-box').html('');
		}

		this.passResponse = function(response, statusText, xhr, $form) {
			$('form#changePassword input').val('');//reset all values
			if(response.success == false) {
				$('form#changePassword .message-box').html('<p>'+response.message+'Please try again</p>');
			} else {
				$('form#changePassword .message-box').html('<p>Smashing success! Your Password has been changed</p>');
			}
		}


//AJAX data getter
	this.getData = function(get_url, callback) {
		$.ajax({
			url: get_url,
			type: "GET",
			success: function(data) {
				callback(data);
			},
			complete: function(xhr, status) {
				window.page_processing = false;
			},
			error: function(xhr, status) {
				
			}
		})
	};

//AJAX data setter
	this.setData = function(set_url, callback) {
		$.ajax({
			url: set_url,
			type: "POST",
			success: function(data) {
				callback(data);
			},
			complete: function(xhr,status) {

			},
			error: function(xhr,status) {

			}
		})
	}

//Pagination Code
	this.paginate = function() {
		if(!window.page_processing) {
			//If we did start processing.
			window.page_processing = true;
			this.page = this.page + 1;
			this.viewRender(false);
		}
	};

	//page 
	this.commentPaginate = function() {
		if(!window.comment_page_processing) {
			window.comment_page_processing = true;
			this.comment_page = this.page + 1;
			this.urlConstructor();
			this.renderComments();
		}
	};
}