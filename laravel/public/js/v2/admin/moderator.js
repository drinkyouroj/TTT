$(function() {
	// Setup the sidebar
	$('.toggle-admin-sidebar').sidr({
    	name: 'offcanvas-admin-sidebar',
    	side: 'right',
    	speed: 300,
    	onOpen: function() {
    		$('#offcanvas-admin-placeholder').addClass('sidebar-open');
    	},
    	onClose: function() {
    		$('#offcanvas-admin-placeholder').removeClass('sidebar-open');
    	}
    });

    //================================= POST RELATED FUNCTIONS ==========================

    // Soft delete post
    $('#offcanvas-admin-sidebar .mod-post-delete').click( function() {
        var post_id = getPostId();
        $.ajax({
            url: window.site_url + 'mod/delete/post/' + post_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    // Deleted the post..
                    $('#offcanvas-admin-sidebar .mod-post-delete').addClass('hidden');
                    $('#offcanvas-admin-sidebar .mod-post-undelete').removeClass('hidden');
                    $('#offcanvas-admin-sidebar .post-featured-label').html('').addClass('hidden');
                }
            }
        });
    });

    // Undelete a post
    $('#offcanvas-admin-sidebar .mod-post-undelete').click( function() {
        var post_id = getPostId();
        $.ajax({
            url: window.site_url + 'mod/undelete/post/' + post_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    // Published the post..
                    $('#offcanvas-admin-sidebar .mod-post-delete').removeClass('hidden');
                    $('#offcanvas-admin-sidebar .mod-post-undelete').addClass('hidden');
                }
            }
        });
    });

    // Remove a category from a post
    $('#offcanvas-admin-sidebar .mod-post-category-delete').click( function() {
        var post_id = getPostId();
        var category_id = $(this).data('category-id');
        var $li = $(this).closest('li');
        $.ajax({
            url: window.site_url + 'mod/delete/post/' + post_id + '/category/' + category_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $li.remove();
                }
            }
        });
    }); 

    //================================= USER RELATED FUNCTIONS ==========================
    
    // Ban a user
    $('#offcanvas-admin-sidebar .mod-ban-user').click( function () {
        var target_user_id = window.user_id;
        $.ajax({
            url: window.site_url + 'mod/ban/user/' + target_user_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .mod-ban-user').addClass('hidden');
                    $('#offcanvas-admin-sidebar .mod-unban-user').removeClass('hidden');
                }
            }
        });
    });

    // Un-Ban a user
    $('#offcanvas-admin-sidebar .mod-unban-user').click( function () {
        var target_user_id = window.user_id;
        $.ajax({
            url: window.site_url + 'mod/unban/user/' + target_user_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .mod-ban-user').removeClass('hidden');
                    $('#offcanvas-admin-sidebar .mod-unban-user').addClass('hidden');
                }
            }
        });
    });

});

function getPostId () {
    return $('.post-action-bar').data('post-id');
}