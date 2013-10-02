window.App = Ember.Application.createWithMixins(Bootstrap.Register, {
	LOG_TRANSITIONS: true,
    LOG_BINDINGS: true,
    LOG_VIEW_LOOKUPS: true,
    LOG_STACKTRACE_ON_DEPRECATION: true,
    LOG_VERSION: true,
    debugMode: true
});

App.Router.reopen({
  rootURL: '/tt/'//This is for the dev.
});

//Application Data
App.ApplicationAdapter = DS.RESTAdapter.extend({
	namespace:'tt/rest'
});

App.LoadingRoute = Ember.Route.extend({});

App.Router.map(function() {
  //Index Doesn't really have to be defined.
  
  
  //Profile Router
	this.resource("profiles", function() {
		this.route("single",  {path: '/single/:profile_id'})
		this.route("edit", {path: '/edit/:profile_id'});
		this.route("follow", {path: '/follow/:profile_id'});
		this.route("unfollow", {path: '/unfollow/:profile_id'});
	});

  //Messages Router
    this.resource("messages", function(){
    	this.route("single", {path:"/single/:message_id"});
    	this.route("write", {path:"/write"});
    	this.route("report", {path:"/report/:message_id"});
    });
    
  //Posts Router
	this.resource("posts", function() {
		//Normal Controls
		this.route("single",{path:"/single/:post_id"});
		this.route("write",{path:"/write"});
		
		//Social Controls
		this.route("favotite",{path:"/favorite/:post_id"});
		this.route("up",{path:"/up/:post_id"});
		this.route("down",{path:"/down/:post_id"});
		this.route("repost",{path:"/repost/:post_id"});
	});
  
  //Comments Router
	this.resource("comments", function() {
		this.route("single",{path:"/single/:comment_id"});
		this.route("write",{path:"/write"});
		
		this.route("up",{path:"/up/:comment_id"});
		this.route("down",{path:"/down/:comment_id"});
	});
  
  
});

