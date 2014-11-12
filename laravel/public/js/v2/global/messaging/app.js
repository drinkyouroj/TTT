$(function() {

	var socket = io();

	//Individual Message line view.
	var MessageView = Backbone.View.extend({
		template: Handlebars.compile( $('#message-listing-view').html() ) ,

		render: function() {
			this.$el.append(this.template(this.data));
		}

	});

	//Represents a view per person
	var PersonView = Backbone.View.extend({
		template: Handlebars.compile( $('#message-person-view').html() ),
		events: {
			"click .send" : "sendMessage"
		},

		initialize: function() {
			
		},

		sendMessage: function() {
			text = this.$el.find('.message-input input').val();

		}		
	});


	var AppView = Backbone.View.extend({
		
		el: $("#message-app"),

		initialize: function() {
			this.render();
		},

		template: Handlebars.compile( $('#message-app-view').html() ),
		render: function() {
			
			this.$el.append(this.template() );
		}
	});

	var App = new AppView;

	var displayedUsers = {};//currently rendered user views.

	//Socket based routes.
	socket.on('private',function(data) {
		//The user's message isn't currently displaying.
		if( !(data.from in displayedUsers) ) {
			displayedUsers[data.from] = new PersonView;//init a new object
			displayedUsers[data.from].from = data.from;
			displayedUsers[data.from].from_id = data.from_id;
			displayedUsers[data.from].render();

			PersonBox = displayedUsers[data.from].$el;
		} else {
			PersonBox = displayedUsers[data.from].$el;
		}

		//Now, we can just get to drawing the received message into that box.
		message = new MessageView;
		message.$el = PersonBox.find('message-content');//Where the append will happen.
		message.data = data;
		message.render();
	});

});