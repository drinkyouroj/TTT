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
    // Add new category
    $('#offcanvas-admin-sidebar .admin-add-category').click( function() {
        $button = $(this);
        $button.hide();
        $button.next().removeClass('hidden');
    });
    $('#offcanvas-admin-sidebar .admin-add-category-submit').click( function() {
        var new_category_name = $('.admin-new-category-input').val();
        var new_category_description = $('.admin-new-category-description').val();
        $.ajax({
            url: window.site_url + 'admin/category/create',
            type: 'POST',
            data: {
                new_category_name: new_category_name,
                new_category_description: new_category_description
            },
            success: function ( data ) {
                if ( data.success ) {
                    $('.admin-new-category-input').val(''); // Clear input
                    $('.admin-new-category-description').val('');
                    $('.admin-new-category-input').parent().addClass('hidden'); // Hide the input field
                    $('.admin-add-category').show(); // Show the add cat button again
                } else {
                    $('.admin-add-category-error').removeClass('hidden');
                }
            }
        });
    });
});