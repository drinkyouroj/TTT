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

    $('#offcanvas-admin-sidebar .admin-nsfw').click( function() {
        var post_id = getPostId();
        var that = this;
        $.ajax({
            url: window.site_url + 'admin/nsfw/post/' + post_id,
            type: 'GET',
            success: function(data) {
                if(data.nsfw) {
                    $(that).html('Unset NSFW');
                } else if(!data.nsfw) {
                    $(that).html('Set NSFW');
                } else {
                    console.log(data);
                }
            }
        });
    });
    // Update view count for a given post
    $('#offcanvas-admin-sidebar .admin-update-view-count').click( function() {
        var input = $(this).closest('.input-group').find('input');
        var button = $(this);
        button.addClass('disabled');
        var new_count = input.val();
        $.ajax({
            url: window.site_url + 'admin/post/update-view-count',
            type: 'POST',
            data: {
                new_count: new_count,
                post_id: getPostId()
            },
            success: function ( data ) {
                if ( data.success ) {
                    $('.view-count').html(new_count);
                    button.removeClass('disabled');
                } else {
                    $('.view-count').html('ERROR').css('color', 'red');
                }
            }
        });
    });
    // Post edit
    $('#offcanvas-admin-sidebar .admin-edit-post').click( function() {
        // Toggle all the editable fields
        $('.post-heading h2').toggleClass('hidden');
        $('ul.post-taglines li').toggleClass('hidden');
        $('.post-content-container').toggleClass('hidden');
        $(this).addClass('hidden');
        $(this).siblings('.admin-edit-post-submit').removeClass('hidden');
    });
    // Post edit submit
    $('#offcanvas-admin-sidebar .admin-edit-post-submit').click( function() {
        // Gather data
        var post_id = $('.post-action-bar').data('post-id');
        var body = $('.admin-post-body').val();
        var title = $('.admin-post-title').val();
        var tagline_1 = $('.admin-post-tagline-1').val();
        var tagline_2 = $('.admin-post-tagline-2').val();
        var tagline_3 = $('.admin-post-tagline-3').val();

        $.ajax({
            url: window.site_url + 'admin/post/edit',
            type: 'POST',
            data: {
                post_id: post_id,
                body: body,
                title: title,
                tagline_1: tagline_1,
                tagline_2: tagline_2,
                tagline_3: tagline_3
            },
            success: function() {
                // Check response and reload page accordingly
                location.reload();
            }
        })
    });
    
    // ========================== ADMIN WEEKLY DIGEST ===================================
    $('#offcanvas-admin-sidebar form#weeklyDigest .set-digest').click( function() {
        // Get the current post alias, and target position
        var alias = $('form#weeklyDigest').data('post-alias');
        var $inputField = $(this).closest('.input-group').find('input');
        var self = this;
        var position = $inputField.attr('name').split('digest_post_')[1];

        $.ajax({
            url: window.site_url + 'admin/digest/add/post',
            type: 'POST',
            data: {
                position: position,
                post_alias: alias
            },
            success: function ( data ) {
                if ( data.success ) {
                    $inputField.val(alias);
                    $(self).addClass('disabled');
                } else {
                    // assume error
                    $('form#weeklyDigest').find('.error').html('Error!');
                }
            }
        });
    });
});