$(function() {

	//Content Editable vs Textarea based input.
	var iOSver = iOSversion();//iOS 4 and below do not support contentEditable.
	var contentEditableSupport = "contentEditable" in document.documentElement;

	if(contentEditableSupport || iOSver[0] >= 5 ) {
		//Advanced Editor for when we have content editable.
		var $editor = new MediumEditor($('.story .text-input'), {
			buttons: ['bold', 'italic'],
			delay: 80,
			cleanPastedHTML: true,
			placeholder: 'Write Your Story Here.'
		});
	} else {
		//Gotta hide the contenteditable div and show the $.
		$('.story .text-input').fade();
		$('.story textarea.normal-input').show();
		$editor = $('.story textarea.normal-input');//designate the $editor as the text area.
	}

	//Controls.
	$('.controls-wrapper .save-draft').click(function() {

	});

	$('.controls-wrapper .save-draft').click(function() {

	});

});

function iOSversion() {
  if (/iP(hone|od|ad)/.test(navigator.platform)) {
    // supports iOS 2.0 and later: <http://bit.ly/TJjs1V>
    var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
    return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
  }
}