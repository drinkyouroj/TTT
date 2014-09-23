
//Events in the Profile Page
$(function() {

//Non user specific code
	var user_action = new UserAction;
	$('div.follow-container a.follow').click(function(event) {
		event.preventDefault();
		user_action.user_id = window.user_id;
		user_action.action = 'follow';

		if( $(this).hasClass('follow-button') ) {
			current_state = false;//currently 
		} else {
			current_state = true;//currently following
		}
		that = $(this);
		user_action.send(function(data){
			if(data.result == 'deleted') {
				that.removeClass('following-button').addClass('follow-button');
				that.html('Follow');
			} else {
				that.removeClass('follow-button').addClass('following-button');
				that.html('Following');
			}
		});
	});

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

	var notification_item_template = $('#notifications-template').html();
	profile.notification_item_template = Handlebars.compile(notification_item_template);

	// No Content Template
	var no_content_template = $('#no-content-template').html();
	profile.no_content_template = Handlebars.compile(no_content_template);

//User Title renders Collection
	$('.header-left h2 a').click(function() {
		$('.section-selectors a#collection').click();
	});

	// User avatar brings up avatar selection modal
	$('.header-left h2 .avatar-image').click(function() {
		profile.renderAvatarModal();
	});

//View renders based on the id selectors.
	$('.section-selectors a').click(function(event) {
		event.preventDefault();
		// Update url accordingly
		var hash = $(this).attr('href');
		if(history.pushState) {
    		history.replaceState(null, null, hash);
		} else {
			location.hash = hash;
		}

		$('.section-selectors a').removeAttr('class');//gets rid of active state.
		$(this).prop('class','active');
		profile.view = $(this).prop('id');
		profile.type = 'all';//default

		//window.location.hash = profile.view;
		profile.viewInit($(this).prop('id'));//new view has to be rendered out of this scenario
	});

	//Just to catch the sidebar feed button
	$('.sidebar-option.feed').click(function(event) {
		event.preventDefault();
		$.sidr('close', 'offcanvas-sidebar');
		$('.section-selectors a').removeAttr('class');//gets rid of active state.
		$('.section-selectors a#feed').prop('class','active');
		profile.view = 'feed';
		profile.type = 'all';//default
		profile.page = 1;
		profile.viewInit(profile.view);
	});

	//Catching the Notification on the top dropdown
	$('.dropdown-wrapper .view-all a').click(function(event) {
		event.preventDefault();
		$('.header-container .col-right .navbar-dropdown-toggle').click();
		$('.section-selectors a').removeAttr('class');//gets rid of active state.
		$('.section-selectors a#notifications').prop('class','active');
		profile.view = 'notifications';
		profile.page = 1;
		profile.viewInit(profile.view);
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

		//Collection options-link
			$('body').on('click', '#collection-content .options-link',function(event) {
				event.preventDefault();
				$(this).siblings('.post-options').toggle();
			});
			// Close when click elsewhere
			$(document).mouseup(function (e) {
			    var container = $('.post-options');
			    var container_toggle = $('.options-link');
			    if (!container.is(e.target) // if the target of the click isn't the container...
			    	&& !container_toggle.is(e.target)
			        && container.has(e.target).length === 0) { // ... nor a descendant of the container
			        container.hide();
			    	// change back to delete if user clicked delete and not confirm
			    	$(container).find('.post-delete').show();
			    	$(container).find('.post-delete-confirm').hide();
			    }
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
	$('.fing-fer a, a#settings').click(function(event) {
		event.preventDefault();
		$('.section-selectors a').removeAttr('class');//gets rid of the class.
		profile.view = $(this).prop('id');

		if($(this).hasClass('followers') || $(this).hasClass('following')) {
			profile.type = window.user_id;
		}

		profile.viewInit($(this).prop('id'));//new view has to be rendered out of this scenario
	});

	//Catching when someone does the settings from the dropdown.
	$('.additional-user-actions a.profile-settings').click(function(event) {
		event.preventDefault();
		$('.section-selectors a').removeAttr('class');		
		$('.header-container .col-right .navbar-dropdown-toggle').click();		
		profile.view = 'settings';
		profile.page = 1;
		profile.viewInit(profile.view);
	});

	//image upload code.
	$('body').on('change', '#uploadAvatar input.image', function() {
		profile.avatarUpload();
	});

	//password change
	$('body').on('click', '#changePassword button',function() {
		profile.changePassword();
	});

	//update email
	$('body').on('submit', '#email-update-form', function(event) {
		event.preventDefault();
		profile.updateEmail( $(this) );
	});

	//We'll just leave this as is for now.
	$('#deleteModal').on('click','.btn.delete-account', function() {
		var password = $('.delete-account-password').val();
		if ( !password )
			return;
		var id = $(this).data('user');
		if(id) {
			$.ajax({
				url: window.site_url+'rest/userdelete',
				type:"POST",
				data: {
					id: id,
					password: password
				},
				success: function(data) {
					if ( data.error ) {
						$('.delete-account-error').html( data.error );
					} else if ( data.success ) {
						window.location.href = window.site_url+'user/logout';	
					}
				}
			});
		}
	});

//Collection Post Actions.
//We should probably make these into better action systems.
	//Set Featured events
	$('body').on('click', '.set-featured',function() {
		profile.setFeatured( $(this).data('id') );
	});

	//Delete Post
	$('body').on('click', '.post-delete', function() {
		$(this).fadeOut(function() {
			$(this).siblings('.post-delete-confirm').fadeIn().css('display', 'block');
		});
	});
	$('body').on('click', '.post-delete-confirm', function() {
		profile.setPostDelete( $(this).data('id') );
	});

	//Remove Repost.
	$('body').on('click', '.remove-repost',function() {
		profile.setRepostDelete( $(this).data('id') );
	});

	// Delete Draft
	$('body').on('click', '.delete-draft', function(event) {
		event.preventDefault();
		post_id = $(this).data('id');
		$('#removeDraftModal button.delete').data('post',post_id);
		$('#removeDraftModal').modal('show');
	});

	$('#removeDraftModal button.delete').click(function(event) {
		event.preventDefault();
		if(typeof $(this).data('post') != 'undefined') {
			post_id = $(this).data('post');
			profile.deleteDraft( post_id );
		}
		$('#removeDraftModal').modal('hide');
	});


//Saves Actions
	//Remove Saved
	$('body').on('click', 'a.remove-save',function() {
		profile.setSaveDelete( $(this).data('id') );		
	});


//System INIT.
	profile.target = $('#profile-content');
	
	// Figure out the hash so we can load the right content in.
	if ( typeof window.location.hash  == 'undefined' || !window.location.hash.length ) {
		window.location.hash = 'collection';
		profile.view = 'collection';
		profile.type = 'all';
		profile.page = 0;
		//profile.viewInit(profile.view);//Render initial view.	
	} else {
		view = window.location.hash;

		$('.section-selectors a').removeClass('active');
		$(view, '.section-selectors').prop('class', 'active');

		if ( view == '#followers' || view == '#following' ) {
			profile.type = window.user_id;
		}
		profile.page = 0;
		profile.view = view.substring(1);
		//profile.viewInit(profile.view);//Render initial view.
	}
	profile.viewInit(profile.view);//Render initial view.

	window.page_processing = false;
	window.comment_page_processing = false;
	window.finished_pagination = false;

	//figure out which modals to show.
	if(window.post) {
		if(window.post == 'draft') {
			$('#draftsModal').modal('show');
		} else if (window.post == 'published') {
			$('#publishModal').modal('show');
		}
	}

//Pagination detection.
	$(window).scroll(function() {
		if(profile.view != 'settings' && !window.page_processing) {
			if($(window).scrollTop() + $(window).height() > $(document).height() - 150) {
				profile.paginate();
			}
		}
	});

});

//The User Profile functions.
function ProfileActions() {

	this.view = 'collection';//set a default view
	this.type = 'all';
	this.filter = false; //this is if we're only changing the type and doing another pull
	this.photo_init = false;  // we have not initialized the photo selection modal at this point

	//View initialization for when you click on a new view.
	this.viewInit = function(view) {
		//Everytime a view is rendered the page count should be reset.
		this.page = 1;
		this.comment_page = 1;//this only pertains to the collection page
		this.view = view;
		window.page_processing = false;
		//fade in fade out scenario
		var that = this;//JS scope is fun... not.
		this.target.fadeOut(100);
		this.target.html('');
		this.viewRenderContainer();
		this.viewRender(true);
		this.target.fadeIn(100);
		this.finished_pagination = false;  // Used to stop pagination.
	};

	//Container init
	this.viewRenderContainer = function() {
		if(this.view == 'collection') {
			//collection has a different outer template
			this.target.html(this.collection_template({site_url: window.site_url}));
		} else {
			this.target.html(this.default_template({site_url: window.site_url, view: this.view}));
		}
	};

	//Actual Content Rendering routes
	this.viewRender = function(init) {
		if(this.filter) {
			this.viewClear();
		}

		base_url = window.site_url + 'rest/profile/' + this.view + '/';

		switch(this.view) {
			default:
			case 'collection':
				this.url = base_url + this.type + '/' + window.user_id + '/' + this.page;
				this.feature_url = window.site_url + 'rest/profile/featured/' + window.featured_id;
				this.comment_url = window.site_url + 'rest/profile/comments/' + window.user_id + '/' + this.comment_page;
				this.renderCollection();
				if(init) {
					this.renderComments();					
				}
				if((init || this.type == 'all' || this.type == 'post') && window.featured_id && this.page == 1 ) {
					this.renderFeatured();//only renders when the person has a featured article.
				}
				window.scrollTo(0,0);
				break;

			case 'feed':
				this.url = base_url + this.type + '/' + this.page;
				this.renderFeed();
				break;

			case 'saves':
				this.url = base_url + this.page;
				this.renderSaves();
				break;

			case 'drafts':
				this.url = base_url + this.page;
				this.renderDrafts();
				break;

			case 'settings':
				this.url = base_url + this.page;
				this.renderSettings();
				break;

			case 'notifications':
				this.url = base_url + this.page;
				this.renderNotifications();
				break;

			case 'followers':
				this.url = base_url + this.type + '/' + this.page;
				this.renderFollowers();
				break;

			case 'following':
				this.url = base_url + this.type + '/' + this.page;
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

	// Delete draft
	this.deleteDraft = function ( post_id ) {
		var url = window.site_url + 'rest/profile/post/' + post_id;
		this.getData( url, function ( data ) {
			if ( data.success ) {
				$('#draft-container-' + post_id).fadeOut(function() {
					$(this).remove();
				});
			}
		});
	};


//Specific Render methods

	this.renderNotifications = function () {
		var notification_item_template = this.notification_item_template;
		var no_content_template = this.no_content_template;
		var parent = this;

		this.getData(this.url,function(data) {
			if ( data.no_content ) {
				$('#default-content',parent.target).append( no_content_template( {section: 'notifications'} ) );
				$('.loading-container img').hide();
				parent.finished_pagination = true;
			} else {
				if ( data.notifications && data.notifications.length == 0 ) {
					parent.finished_pagination = true; // no more notifications
					$('.loading-container img').fadeOut();
				} else {
					$.each(data.notifications, function(idx, val) {
						view_data = {
							notification: val,
							site_url: window.site_url,
							image_url: window.image_url
						};
						$('#default-content',parent.target).append(notification_item_template(view_data));
					});
					parent.checkBodyHeight();
				}
			}
		});

	};

	this.renderCollection = function() {
		//below has to be done to pass through the scope of both getData and $.each
		var post_item_template = this.post_item_template;
		var no_content_template = this.no_content_template;
		var parent = this;
		var editCheck = this.editCheck;

		this.getData(this.url, function(data) {

			if ( data.no_content ) {
				$('#collection-content',parent.target).append( no_content_template( {section: 'collection'} ) );
				$('.loading-container img').hide();
				parent.finished_pagination = true;
			} else {
				if ( data.collection && data.collection.length ) {
					$.each(data.collection, function(idx, val) {
						if ( val.post && val.post.id != window.featured_id ) {
							var editable = val.post ? editCheck(val.post.published_at) : false;
							view_data = {
								site_url: window.site_url,
								post: val.post,
								user_id: window.user_id,
								editable: editable,
								featured_id: window.featured_id,
								post_type: val.post_type,
								myprofile: window.myprofile,
								image_url: window.image_url
							};
							$('#collection-content',parent.target).append(post_item_template(view_data));
						}
					});
					parent.checkBodyHeight();
				} else {
					parent.finished_pagination = true;
					$('.loading-container img').fadeOut();
				}
			}
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
		var editCheck = this.editCheck;

		this.getData(this.feature_url, function(data) {
			var editable = data.featured ? editCheck(data.featured.published_at) : false;
			view_data = {
				site_url: window.site_url,
				post: data.featured,
				user_id : window.user_id,
				editable: editable,
				myprofile: window.myprofile,
							image_url: window.image_url
			}
			$('#collection-content',target).prepend( feature_item_template(view_data) );

		});
	}


	//Collection Actions
		//Set a post as featured.
		this.setFeatured = function(id) {
			
			featured_url = window.site_url + 'rest/profile/featured/' + id;
			var parent = this;
			this.setData(featured_url, function(data) {
				if ( data.success ) {
					$('#collection-content .feature-item', parent.target).fadeOut(function() {
						$(this).remove();
					});
					$('#collection-content #post-' + id).fadeOut( function() {
						$(this).remove();
					});
					window.featured_id = id;//make the window remember the featured id.
					parent.feature_url = window.site_url + 'rest/profile/featured/' + window.featured_id;
					parent.renderFeatured();
				}
			});
			
		}

		this.setPostDelete = function(id) {
			$('#post-'+id).fadeOut().remove();
			removePost = window.site_url + 'rest/profile/post/' + id;
			this.getData(removePost, function(data) {

			});
		}

		//Remove a repost
		this.setRepostDelete = function(id) {
			$('#post-'+id).fadeOut().remove();
			removeRepost = window.site_url + 'rest/profile/repost/'+ id;
			this.getData(removeRepost, function(data) {
				
			});
		}

	this.renderComments = function() {
		//scope issues
		var comment_item_template = this.comment_item_template;
		var target = this.target;

		this.getData(this.comment_url, function(data) {
			if ( data.comments && data.comments.length ) {
				$.each(data.comments,function(idx, val) {
					view_data = {
						site_url: window.site_url,
						comment: val,
						image_url: window.image_url
					}
					$('#comment-content',target).append(comment_item_template(view_data));
				});
			}
		});		
	}

	this.renderFeed = function() {
		//scope issues
		var post_item_template = this.post_item_template;
		var no_content_template = this.no_content_template;
		var parent = this;

		this.getData(this.url,function(data) {
			if ( data.no_content ) {
				$('#default-content',parent.target).append( no_content_template( {section: 'feed'} ) );
				$('.loading-container img').hide();
				parent.finished_pagination = true;
			} else {

				if ( data.feed && data.feed.length ) {
					$.each(data.feed, function(idx, val) {
						if(val.post) {
							view_data = {
								site_url: window.site_url,
								post: val.post,
								feed_type: val.feed_type,
								users: val.users,
								image_url: window.image_url
							};
							$('#default-content',parent.target).append(post_item_template(view_data));
						}
					});
					parent.checkBodyHeight();
				} else {
					// No more content in feed
					parent.finished_pagination = true;
					$('.loading-container img').fadeOut();
				}

			}
		});
	};

	this.renderSaves = function() {
		//scope issues
		var saves_item_template = this.saves_item_template;
		var no_content_template = this.no_content_template;
		var parent = this;

		this.getData(this.url,function(data) {
			if ( data.no_content ) {
				$('#default-content',parent.target).append( no_content_template( {section: 'saves'} ) );
				$('.loading-container img').hide();
				parent.finished_pagination = true;
			} else {
				if ( data.saves && data.saves.length ) {
					$.each(data.saves, function(idx, val) {
						view_data = {
							site_url: window.site_url,
							save: val.post,
							date: val.created_at,
							image_url: window.image_url
						};
						$('#default-content',parent.target).append(saves_item_template(view_data));
					});
					parent.checkBodyHeight();
					// If we recieved fewer than expected, assume end of drafts and fade out loading gif
					if ( data.saves.length < 12 ) {
						$('.loading-container img').fadeOut();	
					}
				} else {
					// Finsished fetching all saves
					parent.finished_pagination = true;
					$('.loading-container img').fadeOut();
				}
			}
		});

	};

		this.setSaveDelete = function(post_id) {
			$('#save-'+post_id).fadeOut().remove();
			url = window.site_url + 'rest/profile/saves/delete/' + post_id;
			this.getData(url, function(data) {

			});
		};

	this.renderDrafts =  function() {
		//scope issues
		var drafts_item_template = this.drafts_item_template;
		var no_content_template = this.no_content_template;
		var parent = this;

		draftDate = this.draftDate;
		this.getData(this.url,function(data) {		
			if ( data.no_content ) {
				$('#default-content',parent.target).append( no_content_template( {section: 'drafts'} ) );
				$('.loading-container img').hide();
				parent.finished_pagination = true;
			} else {
				if ( data.drafts && data.drafts.length ) {
					$.each(data.drafts, function(idx, val) {
						view_data = {
							site_url: window.site_url,
							draft: val,
							date: draftDate(val.updated_at),
							image_url: window.image_url
						};
						$('#default-content',parent.target).append(drafts_item_template(view_data));
					});
					parent.checkBodyHeight();
					// If we recieved fewer than expected, assume end of drafts and fade out loading gif
					if ( data.drafts.length < 12 ) {
						$('.loading-container img').fadeOut();	
					}
				} else {
					// No more drafts to load
					parent.finished_pagination = true;
					$('.loading-container img').fadeOut();
				}
			}
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
		var parent = this;

		this.getData(this.url, function(data) {
			if ( data.follow && data.follow.length ) {
				$.each(data.follow, function(idx, val) {
					view_data = {
						site_url: window.site_url,
						username: val.followers.username,
						user_id:  val.follower_id,
						image_url: window.image_url
					};
					$('#default-content', parent.target).append(follow_template(view_data));
				});
				parent.checkBodyHeight();
				// Quick fix, if we loaded fewer than 20 users assume that we reached end of pagination visually
				// by fading out loading gif (although we will still end up making another request on scroll).
				if ( data.follow.length < 20 ) {
					$('.loading-container img').fadeOut();
				}
			} else {
				// No more followers to load
				parent.finished_pagination = true;
				$('.loading-container img').fadeOut();
			}
		});
	}

	this.renderFollowing = function() {
		var follow_template = this.follow_template;
		var parent = this;

		this.getData(this.url, function(data) {
			if ( data.follow && data.follow.length ) {
				$.each(data.follow, function(idx, val) {
					view_data = {
						site_url: window.site_url,
						username: val.following ? val.following.username : 'nobody',
						user_id:  val.user_id,
						image_url: window.image_url
					};
					$('#default-content', parent.target).append(follow_template(view_data));
				});
				parent.checkBodyHeight();
				// Quick fix, if we loaded fewer than 20 users assume that we reached end of pagination visually
				// by fading out loading gif (although we will still end up making another request on scroll).
				if ( data.follow.length < 20 ) {
					$('.loading-container img').fadeOut();
				}
			} else {
				parent.finished_pagination = true;
				$('.loading-container img').fadeOut();
			}
		});
	}

	this.renderAvatarModal = function() {
		// Make sure we dont render it more than once
		if(this.photo_init == false) {
			photo_input = new PhotoInput();
			this.photo_init = true;

			photo_input.target = $('#photoModal .modal-body');
			photo_input.input = $('#uploadAvatar input.image');
			photo_input.image_dom = '#uploadAvatar .thumb-container, .avatar-image, .header-container .avatar';
			photoInit(photo_input);  // Found in photo.js
			
			photo_input.viewInit();
		}
	}

	this.renderSettings = function() {
		
		if( window.user_image.length > 1 ) {
			user_image = window.image_url + '/' + window.user_image;
		} else {
			user_image = '/images/profile/avatar-default.png';
		}
		
		view_data = {
			site_url: this.site_url,
			user_image: user_image,
			email: window.email,
			image_url: window.image_url
		};
		$('#default-content', this.target).append(this.settings_template(view_data));
		$('.loading-container img').fadeOut();
		this.renderAvatarModal();

		$('body').on('click', '.avatar-modal', function(event) {
			event.preventDefault();			
			$('#photoModal').modal('show');
		})
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
		    }
		}

	this.updateEmail = function( form ) {
		// Fetch fields
		var data = {
			password: $(form).find('input.current-password').val(),
			new_email: $(form).find('input.new-email').val()
		};
		// Send Request
		$.ajax({
			url: window.site_url + 'rest/profile/email/update',
			type: 'post',
			data: data,
			success: function ( data ) {
				if ( data.success ) {
					$(form).fadeOut( function() {
						$(this).siblings('.email-update-success').removeClass('hidden');
						$(this).siblings('.email-update-error').remove();
						$(this).remove();
					});
				} else if ( data.error ) {
					$(form).siblings('.email-update-error').html( data.error ).removeClass('hidden');
					$(form).find('input.current-password').val('');
				}
			}
		});
		// Handle Response
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
				$('form#changePassword .message-box').html('<p class="error">'+response.message+'Please try again</p>');
			} else {
				$('form#changePassword .message-box').html('<p class="success">Success! Your password has been changed.</p>');
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
		if(!window.page_processing && !this.finished_pagination) {
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
			this.renderComments();
		}
	};

	// This continues pagination if the body is un-scrollable!
	this.checkBodyHeight = function() {
		if ( $(window).height() >= $(document).height() ) {
			this.paginate();
		}
	}
}