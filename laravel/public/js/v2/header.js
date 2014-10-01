$(function() {

	// Sidebar
	$('.toggle-sidebar').sidr({
    	name: 'offcanvas-sidebar',
    	side: 'left',
    	speed: 300,
    	onOpen: function() {
    		$('#offcanvas-placeholder').addClass('sidebar-open');
    	},
    	onClose: function() {
    		$('#offcanvas-placeholder').removeClass('sidebar-open');
    	}
    });

    var notificationsMarkedAsRead = false;
    // Dropdown
    $('.navbar-dropdown-toggle').click(function() {
    	$('.notification-label').fadeOut();
    	$('.navbar-dropdown').toggle();
        if ( !notificationsMarkedAsRead ) {
            // Mark the notifications as read.
            $.ajax({
                url: window.site_url + 'rest/notification/',
                type: "POST",
                data: { "notification_ids": window.cur_notifications },
                success: function(data) {
                    if ( data.result == 'success' ) {
                        notificationsMarkedAsRead = true;
                    }
                }
            });
        }
    });

    // Bring up the signup for disabled actions
    $('#offcanvas-sidebar .sidebar-option.disabled > a').click(function(event) {
        event.preventDefault();
        $.sidr('close', 'offcanvas-sidebar', function() {
            $('#guestSignup').modal('show');
        });
    });

    $('.sidebar-option > a').click(function() {
        var $icon = $(this).find('span');
        if ( $icon.hasClass('glyphicon-plus') ) {
            $icon.removeClass('glyphicon-plus');
            $icon.addClass('glyphicon-minus');
        } else {
            $icon.addClass('glyphicon-plus');
            $icon.removeClass('glyphicon-minus');
        }
    });
    
    // Click outside of sidebar closes sidebar
    $(document).mouseup(function (e) {
        var container = $('#offcanvas-sidebar');
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) { // ... nor a descendant of the container
            // Close sidr
            $.sidr('close', 'offcanvas-sidebar');
        }
        var dropdown = $('.navbar-dropdown');
        var dropdown_toggle = $('.navbar-dropdown-toggle');
        if (!dropdown.is(e.target) && !dropdown_toggle.is(e.target) && dropdown.has(e.target).length === 0) {
            $(dropdown).hide();
        }
    });
});