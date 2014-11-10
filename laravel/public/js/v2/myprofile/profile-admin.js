$(function() {
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
                    $('#offcanvas-admin-sidebar .admin-user-reset').addClass('disabled').html('Password Reset!');
                }
            }
        });
    });
});