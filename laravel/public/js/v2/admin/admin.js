// Note: function getPostId() is located in moderator.js (loaded before admin.js)
$(function() {

    // ========================= ADMIN CATEGORY FUNCTIONS ===============================
    $('#offcanvas-admin-sidebar .admin-edit-category-description').click(function() {
        // Replace the description with input field
        var description = $('.category-description').html().trim();
        var name = $('.category-title').html().trim();
        $('.category-description').html('<input type="text" value="' + description + '">');

        // Replace the title with input field
        var title = $('h1.category-title').html().trim();
        $('h1.category-title').html('<input type="text" value="' + title + '" style="text-align:center">');
        $('.category-header .category-title').html('<input type="text" style="text-align: center" value="' + name + '">');
    
        // Toggle the button
        $('#offcanvas-admin-sidebar .admin-edit-category-description-submit').removeClass('hidden');
        $('#offcanvas-admin-sidebar .admin-edit-category-description').addClass('hidden');
    });
    $('#offcanvas-admin-sidebar .admin-edit-category-description-submit').click(function() {
        // Replace the description with input field
        var new_description = $('.category-description > input').val().trim();
        var new_category_name = $('.category-header .category-title > input').val().trim();
        var category_alias = $('.category-description').data('category-alias');

        $.ajax({
            url: window.site_url + 'admin/category/description',
            type: 'POST',
            data: {
                category_alias: category_alias,
                new_title: new_category_name,
                new_description: new_description
            },
            success: function ( data ) {
                if ( data.success ) {
                    console.log('success');
                    // Remove the input fields and replace with new text
                    $('.category-description').html( new_description );
                    $('h1.category-title').html( new_category_name );

                    // Toggle the button
                    $('#offcanvas-admin-sidebar .admin-edit-category-description-submit').addClass('hidden');
                    $('#offcanvas-admin-sidebar .admin-edit-category-description').removeClass('hidden');
                }
            }
        })
        
    });

    // ========================== ADMIN WEEKLY DIGEST ===================================
    $('#offcanvas-admin-sidebar form#weeklyDigest .set-digest').click( function() {
        // Get the current post alias, and target position
        var alias = $('form#weeklyDigest').data('post-alias');
        var $inputField = $(this).closest('.input-group').find('input');
        var self = this;
        var position = $inputField.attr('name');
        
        $.ajax({
            url: window.site_url + 'admin/digest/setpost',
            type: 'POST',
            data: {
                position: position,
                alias: alias
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
    $('#offcanvas-admin-sidebar form#weeklyDigest').submit( function(event) {
        event.preventDefault();

        $.ajax({
            url: window.site_url + 'admin/digest/submit',
            type: 'POST',
            data: {
                digest_featured_post: $('input[name="digest_featured_post"]').val(),
                digest_post_2: $('input[name="digest_post_2"]').val(),
                digest_post_3: $('input[name="digest_post_3"]').val(),
                digest_post_4: $('input[name="digest_post_4"]').val(),
                digest_post_5: $('input[name="digest_post_5"]').val()
            },
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
        var new_count = input.val();
        // TODO
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
                    $('#offcanvas-admin-sidebar .admin-user-reset').addClass('disabled').html('Password Reset!');
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

    //Edit the featured user.
    if(typeof(callback) == 'ajaxForm') {
        $('#offcanvas-admin-sidebar .mod-user-controls ').ajaxForm({
            dataType: 'json'
        }).submit();
    }

});