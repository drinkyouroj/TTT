$(function() {
	// Default variables
	var current_feed_filter = 'all';
	var current_feed_page = 1;
	// This var is used to prevent multiple requests being sent while getting additional
	// content for the feed.
	var is_fetching_next_page = false;
	// Once the feed has retrieved all results, prevent additional calls (reset when filter changes)
	var no_additional_content = false;

	// ========================= HANDLBARS TEMPLATE ===========================
	// Add some logic to handlebars template
	Handlebars.registerHelper('isRepost', function ( item, options ) {
	  	if ( item == 'repost' )
	  		return options.fn(this);
	  	else
	  		return options.inverse(this);
	});
	var source   = $("#feed-item-template").html();
	var feed_item_template = Handlebars.compile(source);
	
	

	// ========================= FEED FILTERING ===============================
	// Click handling for sorting your feed
	$('.feed-filter .filter').click(function() {
		var filter = $(this).data('feed-filter');
		filterFeed( filter, $(this) );
	});

	/**
	 *	Filter the feed based on given filter.
	 *		filter: filter term - all, post, or repost
 	 *		scope: the filter button/element
	 */
	function filterFeed ( filter, scope ) {
		// Make sure user selected a different filter, and we are not currently fetching the feed.
		if ( filter !== current_feed_filter && !is_fetching_next_page ) {
			// Set current_feed_filter
			current_feed_filter = filter;
			// Reset the feed page to 1
			current_feed_page = 1;
			// Reset the no_additional_content
			no_additional_content = false;
			// Remove active class from previous filter
			$('.feed-filter .filter').removeClass('active');
			// Add active class to scope
			$(scope).addClass('active');
			// Fade out and remove the old feed items from the dom
			var $feedItems = $('.feed-container .post-container');
			var oldFeedHasFaded = $feedItems.length ? false : true;  // If no items in feed, the fadeOut function is not called, so just set this value to true. 
			$feedItems.fadeOut( 'slow', function() {
				$(this).remove();
				oldFeedHasFaded = true;
			});
			// Fetch feed posts
			fetchContentForFeed( function ( feed_items ) {
				feed_items.forEach( function ( item, index ) {
					// Render the html
					item.site_url = window.site_url;  // send this to template for url's
					var post = feed_item_template( { post: item.post, feed_type: item.feed_type, users: item.users } );
					// Add it to DOM
					$('.feed-container').append( post );
					// Fade in
					if ( index === feed_items.length - 1 ) {
						// This is a little tricky, but basically we dont want to fade in the
						// new feed items until the old ones have faded out completely.
						var checkFeedFadeOut = setInterval( function() {
							if (oldFeedHasFaded) {
								window.clearInterval(checkFeedFadeOut);
								$('.feed-container .post-container').fadeIn();		
							}
						}, 250);
						
					}
				});
			});
		}
	}

	// =============================== FEED PAGINATION =============================
	
	/**
	 *	Scroll listener for feeding the feed when user reaches bottom of feed.
	 */
	$(window).scroll(function() {
		// Ensure that we have additional content to fetch and that we are not
		// currently fetching content
		if ( !no_additional_content && !is_fetching_next_page ) {
			//If 100px near the bottom load more stuff.
			if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
				feedTheFeedOnScroll();
			}
		}
	});

	/**
	 *	This function fetches more content for the feed, used when the user reaches the bottom
	 *	of the feed.
	 */
	function feedTheFeedOnScroll ( ) {
		console.log('Feed the Feed!');
		// fetch more content
		fetchContentForFeed( function ( feed_items ) {
			// add new feed items to DOM
			feed_items.forEach( function ( item, index ) {
				// Render the html
				item.site_url = window.site_url;  // send this to template for url's
				var post = feed_item_template( item );
				// Add it to DOM
				$('.feed-container').append( post );
				// Fade in
				if ( index === feed_items.length - 1 ) {
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
			current_feed_page++;

		$.ajax({
			url: window.site_url + 'rest/feed/' + current_feed_filter + '/' + current_feed_page,
			success: function ( data ) {
				if ( data.error ) {
					console.log( data.error );
					if ( advance_page )
						current_feed_page--;
				} else if ( data.feed ) {
					if ( data.feed.length == 0 )
						no_additional_content = true;
					callback( data.feed );
				}
			},
			complete: function () {
				is_fetching_next_page = false;
			},
			error: function () {
				if ( advance_page )
					current_feed_page--;
			}
		});	
	}
});