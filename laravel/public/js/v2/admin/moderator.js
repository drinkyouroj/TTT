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
});