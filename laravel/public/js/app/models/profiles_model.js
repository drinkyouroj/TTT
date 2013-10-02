

App.Profile = DS.Model.extend({
	username: DS.attr(),
	email: DS.attr(),
	bio: DS.attr(),
	created_at: DS.attr(),
	updated_at: DS.attr(),
	posts: DS.hasMany('post'),//Only loads latest few 
	comments: DS.hasMany('comment'),//Only loads the latest few comments
	messages: DS.hasMany('message'),
	follows: DS.hasMany('follow')
});

App.Follow = DS.Model.extend({
	user_id: DS.attr(),
	follow_id: DS.attr(),
	created_at: DS.attr(),
	updated_at: DS.attr()
});
