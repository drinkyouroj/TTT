<script type="text/x-handlebars-template" id="message-person-view">
	<div class="message-person" data-userid="{{user_id}}" data-username="{{username}}">
		<div class="person">
			Messages with {{username}}
		</div>
		<div class="message-content">
		</div>
		<div class="message-input">
			<input type="text" name="message">
			<button class="send">Send</button>
		</div>
	</div>
</script>

<script type="text/x-handlebars-template" id="message-listing-view">
	<div class="message {{direction}}">
		{{message}}
	</div>
</script>

