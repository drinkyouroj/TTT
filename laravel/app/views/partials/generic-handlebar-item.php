<script id="featured-template" type="text/x-handlebars-template">
	<div class="generic-item">
		<header>
			<h3><a href="{{url}}posts/{{alias}}">{{title}}</a></h3> 
			<span class="story-type">{{story_type}}</span>
			<span class="author"><span>by</span> <a href="{{url}}profile/{{user.username}}">{{user.username}}</a></span>
		</header>
		<section>
			<div class="the-image">
				<a href="{{url}}posts/{{alias}}" style="background-image: url('{{url}}/{{image}}');">
				
				</a>
			</div>
			<div class="the-outer">
				<div class="the-tags">
					{{tagline_1}} | 
					{{tagline_2}} | 
					{{tagline_3}}
				</div>
			</div>
		</section>
	</div>
</script>