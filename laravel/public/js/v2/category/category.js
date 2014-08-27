$(function() {
	// Initialize some vars...
	var current_category = $('.filter-category.active').data('category-filter');
	var current_sortby = $('.filter-sortby.active').data('sortby-filter');;
	var current_page = 1;  // Default page

	// Slide toggle for filter dropdowns
	$('.category-filter-title').click(function(event) {
		event.preventDefault();
		$(this).next().slideToggle();
	});

});