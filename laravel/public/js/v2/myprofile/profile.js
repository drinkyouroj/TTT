

//Events
$(function() {
	var profile = new ProfileActions;

	//Let's Compile the handlebars source.
	var collection_source = $('#collection-template').html();
	profile.collection_template = Handlebars.compile(collection_source);
	
	//Post Item compile
	var post_item_source = $('#post-item-template').html();
	profile.post_item_template = Handlebars.compile(post_item_source);

	var comment_item_source = $('#comment-template').html();
	profile.comment_template = Handlebars.compile(comment_item_source);

	
	profile.target = $('#profile-content');
	profile.viewRender();//Render initial view.


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
		var that = this;
		that.target.fadeOut(200,function() {
			that.target.html('');
			that.page = 1;//Everytime a new view is rendered, the page should be reset to zero.

			switch(that.view) {
				default:
				case 'collection':
					that.comment_page = 1;
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
		//collection has an outer template
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

		var comment_template = this.comment_template;
		this.getData(this.comment_url, function(data) {
			$.each(data.comments,function(idx, val) {
				view_data = {
					site_url: window.site_url,
					comment: val
				}
				$('#comment-content',target).append(comment_template(view_data));
			});
		});
	};

	this.renderFeed = function() {
		

	};

	this.renderSaves = function() {

	};

	this.renderDrafts =  function() {

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


