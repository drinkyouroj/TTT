$(function() {

	var current_feed_filter = 'all';

	// Click handling for sorting your feed
	$('.feed-filter .filter').click(function() {
		var filter = $(this).data('feed-filter');
		filterFeed( filter, $(this) );
	});

	/**
	 *	Filter the feed based on given filter.
	 *		filter: filter term - all, posts, or reposts
 	 *		scope: the filter button/element
	 */
	function filterFeed ( filter, scope ) {
		// Make sure user selected a different filter.
		if ( filter !== current_feed_filter ) {
			// Set current_feed_filter
			current_feed_filter = filter;
			// Remove active class from previous filter
			$('.feed-filter .filter').removeClass('active');
			// Add active class to scope
			$(scope).addClass('active');
			// TODO: fetch feed posts

				// TODO: fade in the new feed posts


			// TODO: fade out the previous feed

		}
	}
});