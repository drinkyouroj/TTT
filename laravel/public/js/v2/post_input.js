$(function() {
	var current = 1;
	var total_panes =1;
	var char_limit = 1500;

	$('.text-inputs').keydown(function(){
		if($('#body-'+current ,this).html().length > 1500 ) {
			prev = current;
			current = current+1;			
			$('.text-inputs').append('<div class="row-divider"><span><span class="of">'+prev+'</span>/<span class="total">'+current+'</span></span></div>');
			$('.text-inputs').append('<div class="row-content" id="body-'+current+'">');
			$('#body-'+current).attr('contenteditable','true').focus(); 

			//If we go above 1, we need to update the numbers.
			if(current >= 2) {
				$('.row-divider').each(function(key, value) {
					//$('.of',value).
				});
			}
		}
		console.log(current);
	});

});