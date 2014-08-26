$(function() {

	// ====================== HANDLBARS TEMPLATE ========================
	// Add some logic to handlebars template
	Handlebars.registerHelper('isRepost', function ( item, options ) {
	  	if ( item == 'repost' )
	  		return options.fn(this);
	  	else
	  		return options.inverse(this);
	});
	var source   = $("#feed-item-template").html();
	var feed_item_template = Handlebars.compile(source);
	
	

	// ====================== FEED FILTERING ============================
	
	var current_feed_filter = 'all';
	var current_feed_page = 1;

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
		// Make sure user selected a different filter.
		if ( filter !== current_feed_filter ) {
			// Set current_feed_filter
			current_feed_filter = filter;
			// Reset the feed page to 1
			current_feed_page = 1;
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
			$.ajax({
				url: window.site_url + 'rest/feed/' + current_feed_filter + '/' + current_feed_page,
				success: function ( data ) {
					// Check that we have feed data
					if ( data.error ) {
						console.log( data.error );
					} else if ( data.feed ) {
						data.feed.forEach( function ( item, index ) {
							// Render the html
							item.site_url = window.site_url;  // send this to template for url's
							var post = feed_item_template( item );
							// Add it to DOM
							$('.feed-container').append( post );
							// Fade in
							if ( index === data.feed.length - 1 ) {
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
					}
				}
			});

		}
	}
});