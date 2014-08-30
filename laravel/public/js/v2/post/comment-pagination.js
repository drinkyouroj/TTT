
/**
 *	Class used for loading comments. Keeps an internal state
 * 	of where we are at in the pagination process so that the
 *	we just have to call getNextPage method.
 */

function CommentPagination ( post_id ) {
	this.pagination = 10;
	this.page = 1;
	this.post_id = post_id;
	this.is_fetching_comments = false;
	this.no_more_comments = false;


	this.getNextPage = function ( callback ) {
		if ( this.is_fetching_comments || this.no_more_comments || !this.post_id )
			return undefined;
		
		this.is_fetching_comments = true;
		$.ajax({
			url: window.site_url + 'rest/post/' + post_id + '/comments/' + this.pagination + '/' + this.page,
			success: function ( data ) {
				// Proceed to populate the comments
				if ( data && data.comments && data.comments.length ) {
					this.is_fetching_comments = false;
					this.page++;
					callback( data );
				} else {
					this.no_more_comments = true;
					callback( undefined );
				}
			} 
		});
	}
}