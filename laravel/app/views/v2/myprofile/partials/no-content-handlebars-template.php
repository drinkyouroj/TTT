<script type="text/x-handlebars-template" id="no-content-template">
	<div class="col-md-12 {{section}} empty-content">
	{{#ifCond section 'collection'}}
		<p><span>Fill out your collection</span> by posting stories and reposting others. Your collection is visible to anyone viewing your profile. 
		</p>
	{{/ifCond}}

	{{#ifCond section 'comment'}}
		<p><span>Unfinished posts are stored here.</span> They are only visible to you.</p>
	{{/ifCond}}
	
	{{#ifCond section 'feed'}}
		<p><span>Customize your feed</span> by following others. You can view their posts and reposts here. Your feed is only visible to you.</p>
	{{/ifCond}}
	
	{{#ifCond section 'saves'}}
		<p><span>Save your favorite posts</span> to your profile to read again or to view later. They are only visible to you.</p>
	{{/ifCond}}

	{{#ifCond section 'drafts'}}
		<p><span>Unfinished posts are stored here.</span> They are only visible to you.</p>
	{{/ifCond}}	

	{{#ifCond section 'notifications'}}
		<p><span>Oops, it appears that you have no notifications.</span> How does one get notifications you might ask? By going out there and being somebody!</p>
	{{/ifCond}}
	</div>
</script>