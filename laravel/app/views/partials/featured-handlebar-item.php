<script id="home-featured-template" type="text/x-handlebars-template">
{{#each featured}}
<div class="animated fadeIn featured-item generic-item w-{{width}} h-{{#realheight width height post.body}}{{/realheight}}">
	<header>
		<h3>
			<a href="{{#url}}{{/url}}posts/{{post.alias}}">
				{{post.title}}
			</a>
		</h3>
		<span class="story-type">{{post.story_type}}</span>
		<span class="author"><span class="by">by</span> 
		{{#if post.user }}
			<a href="{{#url}}{{/url}}profile/{{post.user.username}}">
				{{post.user.username}}
			</a>
		{{else}}
			<a href="{{#url}}{{/url}}profile/nobody/">
				Nobody
			</a>
		{{/if}}
		</span>
	</header>
	<section>
		<div class="the-image">
			<a href="{{#url}}{{/url}}posts/{{post.alias}}" 
				style="background-image: url('{{#url}}{{/url}}uploads/final_images/{{post.image}}')">
			</a>
			{{#ifTopText height post.body }}
			<div class="the-featured-content">
				
				{{#limitbody post.body 0 }}{{/limitbody}}...
				<div class="clearfix"></div>
			</div>
			{{/ifTopText}}
		</div>
		{{#ifBottomText height 2 }}
			<div class="the-content">
				{{#limitbody post.body height }}{{/limitbody}}...
				<a href="{{#url}}{{/url}}posts/{{post.alias}}">
					Read On.
				</a>
				<div class="clearfix"></div>
			</div>
		{{/ifBottomText}}
	</section>
	<div class="clearfix"></div>
</div>
{{/each}}
</script>