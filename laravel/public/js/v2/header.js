$(function() {

	// Sidebar
	$('#offcanvas-placeholder').sidr({
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
    // $('#accordion').on('show.bs.collapse', function () {
    //     $('.sidebar-option .in')
    // });
});