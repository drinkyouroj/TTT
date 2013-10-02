

App.Message = DS.Model.extend({
	from_uid: DS.attr(),
	to_uid: DS.attr(),
	reply_id: DS.attr(),
	body: DS.attr(),
	created_at: DS.attr(),
	updated_at: DS.attr(),
	profile: DS.belongsTo('user') 
})