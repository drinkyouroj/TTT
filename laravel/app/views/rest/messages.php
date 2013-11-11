<script type="text/x-handlebars" data-template-name="messages">
	
	<div class="col-md-8 col-lg-8">
		<h2>Inbox</h2>
		<ul>
		{{#each}}
			<li>{{#link-to 'messages.single' id}}  <span class="pull-right">{{created_at}}</span>{{/link-to}}</li>
			
		{{/each}}
		</ul>
		<div class="row">
			{{outlet 'message'}}
		</div>
	</div>
	<div class="col-md-4 col-lg-4">
	</div>

</script>

<script type="text/x-handlebars" data-template-name="messages/single">
	<div class="col-md-12">
		<h2>{{created_at}}</h2>
		<p>{{body}}</p>
	</div>
</script>