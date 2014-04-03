//Truncate
String.prototype.trunc = String.prototype.trunc ||
      function(n){
          return this.length>n ? this.substr(0,n-1)+'&hellip;' : this;
      };

//Handlebar Helper.
Handlebars.registerHelper('ifCond', function(v1, v2, options) {
  if(v1 === v2) {
    return options.fn(this);
  }
  return options.inverse(this);
});

Handlebars.registerHelper('ifTopText', function(height, body, options) {
  if(height == 1) {
  	//if height is really 1
    return options.fn(this);//true
  } else if(height >= 2 && body.length < 700 ) {
  	//if this is 
  	return options.fn(this);//true
  }
  
  return options.inverse(this);//false
});

Handlebars.registerHelper('ifBottomText', function(height, body, options) {
	if(height >= 2) {
		return options.fn(this);//true
	/*
	  if(body.length < 500 ) {
	  	return options.inverse(this);//false
	  } else if(body.length >= 500) {
	  	return options.fn(this);//true
	  }
  */
	}
  return options.inverse(this);//false
});


Handlebars.registerHelper('realheight', function(width, height, body) {
	if(height >= 2 && body.length < 700 ) {
		return 1;
	} else {
		return height;
	}
});

Handlebars.registerHelper("limitbody", function(body, width) {
	switch(width) {
		default:
		case 0:
			return body.trunc(120);
		break;
		case 1:
			return body.trunc(510);
		break;
		case 2:
			return body.trunc(1150);
		break;
		case 3:
			return body.trunc(2200);
		break;
	}
});

Handlebars.registerHelper('url', function() {
  return window.site_url;
});


$(window).load(function() {
	$('#top-featured').packery({
	  itemSelector: '.featured-item',
	  "columnWidth": ".gutter-sizer",
	});
});


$(function() {
	
});

window.featured_page = 1;
window.busy = false;

$(window).scroll(function() {
	//If 100px near the bottom load more stuff.
	if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		if(!window.busy) {
			window.featured_page++;
			load_more();
		}
	}
});

function load_more() {
	window.busy = true;
	$.ajax({
		url: window.site_url+'rest/featured/',
		data: {"page": window.featured_page},
		type:"GET",
		success:function(data) {
			if(data.error) {
				console.log(data);
			} else {
				//Compile the Handlebars into something.
				var source = $('#home-featured-template').html();
				var template = Handlebars.compile(source);
				var html = template(data).trim();
				$view_data = $(html);
				$('#top-featured')
					.append($view_data)
					.packery('appended',$view_data);
				
			}
		},
		complete:function() {
			window.busy = false;
		}
	});
}


