
/**
 *	Class used for loading comments. Keeps an internal state
 * 	of where we are at in the pagination process so that the
 *	we just have to call getNextPage method.
 */

function CommentPagination ( post_id ) {
	this.post_id = post_id;
	var pagination = 10;
	var page = 1;
	var is_fetching_comments = false;
	var no_more_comments = false;


	this.getNextPage = function ( callback ) {

		if ( is_fetching_comments || no_more_comments || !this.post_id )
			return undefined;
		console.log('paginate: ' + pagination + ' page: ' + page);

		is_fetching_comments = true;
		$.ajax({
			url: window.site_url + 'rest/post/' + post_id + '/comments/' + pagination + '/' + page,
			success: function ( data ) {
				// Proceed to populate the comments
				if ( data && data.comments && data.comments.length ) {
					page++;
					callback( data );
				} else {
					no_more_comments = true;
					callback( undefined );
				}
			},
			complete: function () {
				is_fetching_comments = false;
			}
		});
	}

	this.commentDeepLink = function ( comment_id, callback ) {

		if ( is_fetching_comments || no_more_comments || !this.post_id )
			return undefined;

		console.log('paginate: ' + pagination + ' page: ' + page);
		is_fetching_comments = true;
		$.ajax({
			url: window.site_url + 'rest/post/' + post_id + '/comments/deeplink/' + comment_id,
			type: 'GET',
			success: function ( data ) {
				if ( data && data.comments ) {
					page = data.page;
					pagination = data.paginate;
					callback( data );
				}
			},
			complete: function () {
				is_fetching_comments = false;
			}
		});
	}
}