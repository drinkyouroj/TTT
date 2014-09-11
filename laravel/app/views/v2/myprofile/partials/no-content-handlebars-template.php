<script type="text/x-handlebars-template" id="no-content-template">
	<div class="col-md-12 {{section}}">
	{{#ifCond section 'collection'}}
		<p>There is no content in your collection! Put a message telling what Collection is and how to get content here.</p>
	{{/ifCond}}
	
	{{#ifCond section 'feed'}}
		<p>There is no content in your feed! Put a message telling what Feed is and how to get content here.</p>
	{{/ifCond}}
	
	{{#ifCond section 'saves'}}
		<p>There is no content in your saves! Put a message telling what Saves is and how to get content here.</p>
	{{/ifCond}}

	{{#ifCond section 'drafts'}}
		<p>There is no content in your drafts! Put a message telling what Drafts is and how to get content here.</p>
	{{/ifCond}}	

	{{#ifCond section 'notifications'}}
		<p>Oops, it appears that you have no notifications. How does one get notifications you might ask? By going out there and being somebody!</p>
	{{/ifCond}}
	</div>
</script>