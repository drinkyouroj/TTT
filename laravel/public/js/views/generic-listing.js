$(function() {
	$('select.sort_by_filter').change(function(){
        window.location.href = $(this).find("option:selected").prop('value');
    });
});