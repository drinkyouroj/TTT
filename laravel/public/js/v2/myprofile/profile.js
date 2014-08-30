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
			profile.filter = true;
			profile.viewRender();
		});


//View renders for settings/follow
	$('.header-right a').click(function(event) {
		//event.preventDefault();
		$('.section-selectors a').removeAttr('class');//gets rid of the class.
		profile.view = $(this).prop('id');

		window.location.hash = profile.view;

		if($(this).hasClass('followers') || $(this).hasClass('following')) {
			profile.type = window.user_id;
		}

		profile.viewInit($(this).prop('id'));//new view has to be rendered out of this scenario
	});


//Pagination detection.
	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
			profile.paginate();
		}
	});

//Only init the system on "window load" so we can catch the right hash.
	
		profile.target = $('#profile-content');
		/*
		// Somehow this is really messing with the loading.
		if(typeof window.location.hash  == 'undefined') {
			window.location.hash = 'collection';
			profile.view = 'collection';
		} else {
			view = window.location.hash;
			profile.view = view.substring(1);
		}
		*/
		
		profile.viewInit('collection');//Render initial view.
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

			this.url = base_url + this.type + '/' + this.page;
			
			if(this.view == 'collection') {
				//special case in which the collection requires a comment url.
				this.comment_url = window.site_url + 'rest/profile/comments/' + this.comment_page;
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
		this.urlConstructor();
		this.getData(this.url, function(data) {
			$.each(data.collection, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					post: val.post
				};
				$('#collection-content',target).append(post_item_template(view_data));
			});
		});
		
	};

	this.renderComments = function() {
		//scope issues
		var comment_item_template = this.comment_item_template;
		var target = this.target;
		this.urlConstructor();
		this.getData(this.comment_url, function(data) {
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
		this.getData(this.url,function(data) {
			$.each(data.drafts, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					draft: val
				};
				$('#default-content',target).append(drafts_item_template(view_data));
			});
		});

	};

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
		$('#default-content', this.target).append(this.settings_template());
	}


//AJAX data getter
	this.getData = function(get_url, callback) {
		$.ajax({
			url: get_url,
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