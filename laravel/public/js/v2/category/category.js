$(function() {
	// Initialize some vars...
	var current_category = $('.filters').data('current-category');
	var current_sortby = $('.filters').data('current-filter');
	var current_page = 1;  // Default page

	var no_additional_content = false;
	var is_fetching_next_page = false;


	// Slide toggle for filter dropdowns
	$('.category-filter-title').click(function(event) {
		event.preventDefault();
		$(this).next().toggle();
	});

	$(document).mouseup(function (e) {
		var container = $('.cat-container');
		var category = $('.cat-container .category-filter-title');		
		if(	!category.is(e.target) && container.has(e.target).length === 0) {
			category.next().hide();
		}
	})

	$(document).mouseup(function (e) {
		var container = $('.sort-container');
		var filter = $('.sort-container .category-filter-title');
		if(	!filter.is(e.target) && container.has(e.target).length === 0 ) {
			filter.next().hide();
		}
	})


	// =========================== HANDLBARS TEMPLATE ===========================
	// Add some logic to handlebars template
	Handlebars.registerHelper('isRepost', function ( item, options ) {
	  	if ( item == 'repost' )
	  		return options.fn(this);
	  	else
	  		return options.inverse(this);
	});
	var source   = $("#post-item-template").html();
	var feed_item_template = Handlebars.compile(source);


	// =============================== FEED PAGINATION =============================
	/**
	 *	Scroll listener for feeding the feed when user reaches bottom of feed.
	 */
	$(window).scroll(function() {
		// Ensure that we have additional content to fetch and that we are not
		// currently fetching content
		if ( !no_additional_content && !is_fetching_next_page ) {
			//If 100px near the bottom load more stuff.
			if($(window).scrollTop() + $(window).height() > $(document).height() - 150) {
				feedTheFeedOnScroll();
			}
		}
	});
	// Just incase the window height is not scrollable
	if ( $(window).height() >= $(document).height() ) {
		feedTheFeedOnScroll();
	}

	/**
	 *	This function fetches more content for the feed, used when the user reaches the bottom
	 *	of the feed.
	 */
	function feedTheFeedOnScroll ( ) {
		// fetch more content
		fetchContentForFeed( function ( posts ) {
			// add new feed items to DOM
			posts.forEach( function ( post, index ) {
				// Render the html
				view_data = { 
					post: post, 
					site_url: window.site_url,
					image_url: window.image_url,
					users: undefined 
				};
				var post = feed_item_template( view_data );
				// Add it to DOM
				$('.feed-container').append( post );
				// Fade in
				if ( index === posts.length - 1 ) {
					$('.feed-container .post-container').fadeIn();		
				}
			});
		}, true );
	}


	// ============================== GENERAL FUNCTIONS ===============================
	/**
	 *	The actual ajax call to rest interface.
	 *		callback: function to be called only after succesfull fetch of feed content
	 */
	function fetchContentForFeed ( callback, advance_page ) {
		is_fetching_next_page = true;
		// Advance the page, remember to decrement if the call failed.
		if ( advance_page )
			current_page++;

		$.ajax({
			url: window.site_url + 'rest/categories/' + current_category + '/' + current_sortby + '/' + current_page,
			success: function ( data ) {
				if ( data.error ) {
					if ( advance_page )
						current_page--;
					no_additional_content = true;
				} else if ( data.posts ) {
					if ( data.posts.length == 0 )
						no_additional_content = true;
					callback( data.posts );
				}

				if ( no_additional_content ) {
					$('.loading-container img').fadeOut();
				} else if ( $(window).height() >= $(document).height() ) {
					feedTheFeedOnScroll();
				}
			},
			complete: function () {
				is_fetching_next_page = false;
			},
			error: function () {
				if ( advance_page )
					current_page--;
			}
		});	
	}

	// ========================== Fixed signup Bar ===========================
	middle = $('.posts-container').offset();
	window.show_join = middle.top;
	window.reached = false;

	$(window).scroll(function(event) {
		current = $(window).scrollTop();
		if(current > window.show_join) {
			$('.join-banner').show().fadeIn();
			$('.content-wrapper').css('margin-bottom',$('.join-banner').height());
		}
	});

	if ( !window.logged_in ) {
		$( window ).resize(function() {
	  		$('.content-wrapper').css('margin-bottom',$('.join-banner').height());
		});
	}

});