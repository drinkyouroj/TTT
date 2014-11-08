$(function() {
	console.log('test');
	console.log("Ok, Autobahn loaded", autobahn.version);



	var connection = new autobahn.Connection({
		url: 'ws://'+window.site_url+'/socket'
	});
	connection.onopen = function (session, details) {
		console.log('connection successful');
		console.log(session+ details);
	}
	connection.onclose = function(session, details) {
		console.log('failed connection or connection closed');
	}
	connection.open();
});

//just a test
function start_conn() {
	/*
	
	conn.onopen = function (session) {
		console.log('testing messaging');
	}
	conn.open();
	*/
}
