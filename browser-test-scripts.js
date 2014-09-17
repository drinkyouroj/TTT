for ( var i = 0; i < 10; i++ ) {
 	setTimeout(function () {
        $.ajax({ url: 'http://staging.2000tim.es/rest/likes/95', type: 'GET', success: function(data){ console.log(data.result)} }) 
    }, 20);
}