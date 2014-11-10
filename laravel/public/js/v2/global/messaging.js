$(function() {
	console.log('test');
	console.log("Ok, Autobahn loaded", autobahn.version);
	url = 'ws://192.168.9.149:11111';
	var connection = new autobahn.Connection({
		url: url
	});
	connection.onopen = function (session, details) {
		console.log('connection successful');
		console.log(session + details);
	}
	connection.onclose = function(session, details) {
		console.log(details);
		console.log(session);
		console.log('failed connection or connection closed');
	
	}	
	connection.open();
	
});