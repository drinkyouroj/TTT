

//Events
$(function() {
	var profile = new ProfileActions;//initialize class

	//Let's Compile the handlebars source.
	var collection_source = $('#collection-template').html();
	profile.collection_template = Handlebars.compile(collection_source);
	
	//container for default.
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
	
	profile.target = $('#profile-content');
	profile.viewRender();//Render initial view.


	//View renders based on the selectors.
	$('.section-selectors a').click(function(event) {
		event.preventDefault();
		$('.section-selectors a').removeAttr('id');//gets rid of active state.
		$(this).prop('id','active');
		profile.view = $(this).prop('class');
		profile.viewRender();
	});


});

function ProfileActions() {

	this.view = 'collection';//set a default view

	this.type = 'all';

	this.viewRender = function() {
		var that = this;//JS scope is fun... not.

		//Everytime a view is rendered the page count should be reset.
		this.page = 1;
		this.comment_page = 1;//this only pertains to the collection page

		that.target.fadeOut(200,function() {
			that.target.html('');
			switch(that.view) {
				default:
				case 'collection':
					that.renderCollection();
				break;
				case 'feed':
					that.renderFeed();
				break;
				case 'saves':
					that.renderSaves();
				break;
				case 'drafts':
					that.renderDrafts();
				break;
			}
			that.target.fadeIn();
		});
	};

	this.urlConstructor = function() {
		
		base_url = window.site_url + 'rest/profile/' + this.view + '/';

		if(this.view == 'collection' || this.view == 'feed' ) {
			this.url = base_url + this.type + '/' + this.page;

			if(this.view == 'collection') {
				//special case in which the collection requires a comment url.
				this.comment_url = window.site_url + 'rest/profile/comments/' + this.comment_page;
			}

		} else {
			this.url = base_url + this.page;
		}
	};

	this.renderCollection = function() {
		//collection has a different outer template
		this.target.html(this.collection_template());
		
		//below has to be done to pass through the scope of both getData and $.each
		var post_item_template = this.post_item_template;
		var target = this.target;

		this.urlConstructor();//construct the URL.

		get_collection = this.url;
		this.getData(this.url, function(data) {
			$.each(data.collection, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					post: val.post
				};
				$('#collection-content',target).append(post_item_template(view_data));
			});
		});

		var comment_item_template = this.comment_item_template;
		this.getData(this.comment_url, function(data) {
			$.each(data.comments,function(idx, val) {
				view_data = {
					site_url: window.site_url,
					comment: val
				}
				$('#comment-content',target).append(comment_item_template(view_data));
			});
		});
	};

	this.renderFeed = function() {
		this.target.html(this.default_template({view: this.view}));
		
		//scope issues
		var post_item_template = this.post_item_template;
		var target = this.target;


		this.urlConstructor();
		
		this.getData(this.url,function(data) {
			$.each(data.feed, function(idx, val) {
				console.log(val);
				view_data = {
					site_url: window.site_url,
					post: val.post
				};
				$('#default-content',target).append(post_item_template(view_data));
			});
		});
	};

	this.renderSaves = function() {
		this.target.html(this.default_template({view: this.view}));
		
		//scope issues
		var saves_item_template = this.saves_item_template;
		var target = this.target;


		this.urlConstructor();
		
		this.getData(this.url,function(data) {
			$.each(data.saves, function(idx, val) {
				console.log(val);
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
		this.target.html(this.default_template({view: this.view}));
		
		//scope issues
		var drafts_item_template = this.drafts_item_template;
		var target = this.target;


		this.urlConstructor();
		
		this.getData(this.url,function(data) {
			$.each(data.drafts, function(idx, val) {
				view_data = {
					site_url: window.site_url,
					drafts: val.drafts
				};
				$('#default-content',target).append(drafts_item_template(view_data));
			});
		});

	};



	//Paginate

	this.page_processing = false;

	this.paginate = function() {
		if(this.page) {

		} else {
			//
		}
	};


	this.getData = function(get_url, callback) {
		$.ajax({
			url: get_url,
			success: function(data) {
				console.log(data);
				callback(data);
			},
			complete: function(xhr, status) {

			},
			error: function(xhr, status) {
				
			}
		})
	};


}


