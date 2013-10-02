/**
 * This is the Posts Models including the comment model
 */

App.Post = DS.Model.extend({
	user_id: DS.attr(),
	title: DS.attr(),
	alias: DS.attr(),
	tagline: DS.attr(),
	category: DS.attr(),
	image: DS.attr(),
	body: DS.attr(),
	created_at: DS.attr(),
	updated_at: DS.attr(),
	comments: DS.hasMany('comment',{async:true}),
	profiles: DS.belongsTo('profile')
});

App.Comment = DS.Model.extend({
	user_id: DS.attr(),
	title: DS.attr(),
	post_id: DS.attr(),
	body: DS.attr(),
	up: DS.attr(),
	down: DS.attr(),
	published: DS.attr(),
	created_at: DS.attr(),
	updated_at: DS.attr(),
	profiles: DS.belongsTo('profile')
})
