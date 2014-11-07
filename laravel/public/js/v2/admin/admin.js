// Note: function getPostId() is located in moderator.js (loaded before admin.js)
$(function() {

    // ========================== ADMIN WEEKLY DIGEST ===================================
    $('#offcanvas-admin-sidebar form#weeklyDigest').submit( function(event) {
        event.preventDefault();
        $.ajax({
            url: window.site_url + 'admin/digest/submit',
            type: 'POST',
            success: function ( data ) {
                if ( data.success ) {
                    $('form#weeklyDigest').slideUp();
                    $('.digest-title').html('Weekly Digest Sent');
                } else {
                    $('form#weeklyDigest').find('.error').html( data.error );
                }
            }
        });
    });

    // ========================== ADMIN RANDOM VIEWS TO ALL POSTS =======================
    $('#offcanvas-admin-sidebar .admin-add-random-view-counts').click( function() {
        var $self = $(this);
        $.ajax({
            url: window.site_url + 'admin/add-random-view-counts',
            type: 'POST',
            success: function ( data ) {
                if ( data.success ) {
                    $self.addClass('disabled');
                    $self.addClass('btn-default');
                    $self.removeClass('btn-primary');
                } else {

                }
            }
        })
    });

});