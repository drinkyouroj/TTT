//Route extentions
App.IndexRoute = Ember.Route.extend({
});

//Posts
App.PostsRoute = Ember.Route.extend({
	model: function(params) {
		return this.store.findAll('post');
	},
	renderTemplate: function() {
	    this.render({ outlet: 'main' });
	}
});

	App.PostsSingleRoute = Ember.Route.extend({
		model: function(params) {
			return this.store.find('post', params.post_id);
		},
		setupController: function(controller, model) {
			controller.set('content',model);
		},
		renderTemplate: function() {
		    this.render({ outlet: 'post' });
		}
	});

//Profiles
App.ProfilesRoute = Ember.Route.extend({
	model: function(params) {
		console.log(params);
		return this.store.find('profile', params.profile_id);
	},
	renderTemplate: function() {
	    this.render({ outlet: 'main' });
	}
});


//Messages
App.MessagesRoute = Ember.Route.extend({
	model: function(params) {
		return this.store.find('message', params.message_id);
	},
	renderTemplate: function() {
		this.render({outlet:'main'});
	}
});

App.CommentsRoute = Ember.Route.extend({
	
});

