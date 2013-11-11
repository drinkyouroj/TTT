<script type="text/x-handlebars" data-template-name="posts">
	<div class="col-md-8 col-lg-8">
		<h2>Latest Posts</h2>
		<ul>
		{{#each}}
			<li>{{#link-to 'posts.single' id}}{{title}}{{/link-to}}</li>
			
		{{/each}}
		</ul>
		<div class="row">
			{{outlet post}}
		</div>
	</div>
	<div class="col-md-4 col-lg-4">
	</div>
</script>


<!--Individual posts-->
<script type="text/x-handlebars" data-template-name="posts/single">
	<div class="col-md-12">
		<h2>{{title}}</h2>
		<p class="two-column">{{body}}</p>
	</div>
	<div class="col-md-12">
		<h2>Comments</h2>
		<div class="row">
			{{#if comments}}
			{{#each comments}}test
				<div class="col-md-12">
					{{body}}
				</div>
			{{/each}}
			{{else}}
				<div class="col-md-12">
				<p>No Comments, Add one!</p>
				</div>
			{{/if}}
		</div>
	</div>
</script>