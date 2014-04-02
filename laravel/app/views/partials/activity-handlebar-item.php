<script id="activity-template" type="text/x-handlebars-template">
	
	<div class="generic-item ">
		<header>
			<h3><a href="{{url}}posts/{{alias}}">{{post.title}}</a></h3> 
			<span class="story-type">{{story_type}}</span>
			<span class="author"><span>by</span> <a href="{{url}}profile/{{user.username}}">{{post.user.username}}</a></span>
		</header>
		<section>
			<div class="the-image">
				<a href="{{url}}posts/{{post.alias}}" style="background-image: url('{{url}}uploads/final_images/{{post.image}}');">
				
				</a>
			</div>
			<div class="the-outer">
				<div class="the-tags">
					{{post.tagline_1}} | 
					{{post.tagline_2}} | 
					{{post.tagline_3}}
				</div>
			</div>
		</section>
	</div>
	
</script>