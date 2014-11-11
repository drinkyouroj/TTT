$(function() {

	var socket = io();

	var Message = Backbone.Model.extend({
		defaults: function(){
			return {
				to: 'username',
				to_id: 1,
				msg: 'test'
			}
		}
	});

	var MessageCollection = Backbone.Collection.extend({
		model: Message
		//localStorage: new Backbone.localStorage('messages-sondry')
	});

	var Messages = new MessageCollection;

	//Individual Message line view.
	var MessageView = Backbone.View.extend({
		template: Handlebars.compile( $('#message-listing-view').html() ),

		render: function() {
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		}

	});

	//Represents a view per person
	var PersonView = Backbone.View.extend({
		el: $('#message-person'),
		template: Handlebars.compile( $('#message-person-view').html() ),
		events: {
			"click .send" : "sendMessage"
		},

		initialize: function() {
			//this.input = this.
		},

		sendMessage: function() {
			text = this.$el.find('.message-input').html();
		}		
	});


	var AppView = Backbone.View.extend({
		
		el: $("#message-app"),

		template: Handlebars.compile( $('#message-app-view').html() ),

	});

	var App = new AppView;

});