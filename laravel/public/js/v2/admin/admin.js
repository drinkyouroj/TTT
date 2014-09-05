// Note: function getPostId() is located in moderator.js (loaded before admin.js)
$(function() {

	// Set post as featured
    $('#offcanvas-admin-sidebar .admin-set-featured').click( function() {
        var post_id = getPostId();
        var position = $('select[name="admin-featured-position"]').val();

        $.ajax({
            url: window.site_url + 'admin/featured/post/' + post_id + '/position/' + position,
            type: 'GET',
            success: function ( data ) {
                if ( data.success ) {
                    // TODO: update view
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
                    // TODO: update view
                    $('#offcanvas-admin-sidebar .post-featured-label').addClass('hidden').html('');
                    $('#offcanvas-admin-sidebar .admin-unset-featured').addClass('hidden');
                    $('#offcanvas-admin-sidebar .admin-set-featured').removeClass('hidden');
                }
            }
        });
    });
});