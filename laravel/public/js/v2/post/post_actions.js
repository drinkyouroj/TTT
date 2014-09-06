
/**
 *	A list of user actions related to posts.
 *	
 *	actions: (set by this.action)
 *		save: save/unsave a post
 *			@required this.post_id
 *		repost: repost/un-repost a post
 *			@required this.post_id
 *		like: like/un-like a given post
 *			@required this.post_id
 *		follow: follow a user
 *			@required this.user_id (the user to follow)
 */
function UserAction () {
	this.action = undefined;
	this.post_id = undefined;
	this.user_id = undefined;

	this.send = function ( callback ) {
		switch(this.action) {
			case 'save':
				this.ajaxSave( callback );
				break;
			case 'repost':
				this.ajaxRepost( callback );
				break;
			case 'like':
				this.ajaxLike( callback );
				break;
			case 'follow':
				this.ajaxFollow( callback );
				break;
			case 'read':
				this.ajaxRead( callback );
				break;
			case 'flag':
				this.ajaxFlag( callback );
				break;
		}
	};

	// Save/Un-save the post
	this.ajaxSave = function ( callback ) {
		if ( this.action == 'save' && this.post_id ) {
			$.ajax({
				url: window.site_url + 'rest/favorites/' + this.post_id,
				success: function ( data ) {
					console.log(data);
					callback( data );
				}
			});
		} else {
			console.log('missing parameters!');
		}
	};

	this.ajaxRepost = function ( callback ) {
		if ( this.action == 'repost' && this.post_id ) {
			$.ajax({
				url: window.site_url + 'rest/reposts/' + this.post_id,
				success: function ( data ) {
					callback( data );
				}
			});
		} else {
			console.log('missing parameters!');
		}
	}

	this.ajaxLike = function ( callback ) {
		if ( this.action == 'like' && this.post_id ) {
			$.ajax({
				url: window.site_url + 'rest/likes/' + this.post_id,
				success: function ( data ) {
					callback( data );
				}
			});
		} else {
			console.log('missing parameters!');
		}
	}

	this.ajaxFollow = function ( callback ) {
		if ( this.action == 'follow' && this.user_id ) {
			$.ajax({
				url: window.site_url + 'rest/follows/' + this.user_id,
				success: function ( data ) {
					callback( data );
				}
			});
		} else {
			console.log('missing parameters!');
		}
	}

	this.ajaxRead = function ( callback ) {
		if ( this.action == 'read' && this.post_id ) {
			$.ajax({
				url: window.site_url + 'rest/read/' + this.user_id,
				success: function ( data ) {
					callback( data );
				}
			});
		} else {
			console.log('missing parameters!');
		}
	}

	this.ajaxFlag = function ( callback ) {
		if ( this.action == 'flag' && this.post_id ) {
			$.ajax({
				url: window.site_url + 'rest/flag/' + this.user_id,
				success: function ( data ) {
					callback( data );
				}
			});
		} else {
			console.log('missing parameters!');
		}
	}
}