// Note: function getPostId() is located in moderator.js (loaded before admin.js)
$(function() {


    // ========================== ADMIN POST FUNCTIONALITIES ============================
	// Set post as featured
    $('#offcanvas-admin-sidebar .admin-set-featured').click( function() {
        var post_id = getPostId();
        var position = $('select[name="admin-featured-position"]').val();

        $.ajax({
            url: window.site_url + 'admin/featured/post/' + post_id + '/position/' + position,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .post-featured-label').removeClass('hidden').html('Featured ' + position);
                    $('#offcanvas-admin-sidebar .admin-unset-featured').removeClass('hidden');
                }
            }
        });
    });

    // Remove post from featured
    $('#offcanvas-admin-sidebar .admin-unset-featured').click( function() {
    	var post_id = getPostId();
    	$.ajax({
            url: window.site_url + 'admin/featured/post/' + post_id + '/remove',
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .post-featured-label').addClass('hidden').html('');
                    $('#offcanvas-admin-sidebar .admin-unset-featured').addClass('hidden');
                    $('#offcanvas-admin-sidebar .admin-set-featured').removeClass('hidden');
                }
            }
        });
    });

    // ========================== ADMIN USER FUNCTIONALITIES ============================
    // User delete
    $('#offcanvas-admin-sidebar .admin-soft-delete').click( function() {
        var target_user_id = window.user_id;
        $.ajax({
            url: window.site_url + 'admin/delete/user/' + target_user_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .admin-soft-delete').addClass('hidden');
                    $('#offcanvas-admin-sidebar .admin-restore-soft-delete').removeClass('hidden');
                }
            }
        });
    });
    // User Restore
    $('#offcanvas-admin-sidebar .admin-restore-soft-delete').click( function() {
        var target_user_id = window.user_id;
        $.ajax({
            url: window.site_url + 'admin/restore/user/' + target_user_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .admin-soft-delete').removeClass('hidden');
                    $('#offcanvas-admin-sidebar .admin-restore-soft-delete').addClass('hidden');
                }
            }
        });
    });
    // Assign User as moderator
    $('#offcanvas-admin-sidebar .admin-assign-moderator').click( function() {
        var target_user_id = window.user_id;
        $.ajax({
            url: window.site_url + 'admin/assign/moderator/user/' + target_user_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .admin-assign-moderator').addClass('hidden');
                    $('#offcanvas-admin-sidebar .admin-unassign-moderator').removeClass('hidden');
                }
            }
        });
    });
    // Revoke moderator privalages for given user
    $('#offcanvas-admin-sidebar .admin-unassign-moderator').click( function() {
        var target_user_id = window.user_id;
        $.ajax({
            url: window.site_url + 'admin/unassign/moderator/user/' + target_user_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .admin-assign-moderator').removeClass('hidden');
                    $('#offcanvas-admin-sidebar .admin-unassign-moderator').addClass('hidden');
                }
            }
        });
    });
    // User account reset
    $('#offcanvas-admin-sidebar .admin-user-reset').click( function() {
        var target_user_id = window.user_id;
        $.ajax({
            url: window.site_url + 'admin/reset/user/' + target_user_id,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    $('#offcanvas-admin-sidebar .admin-user-reset').addClass('disabled');
                }
            }
        });
    });
});