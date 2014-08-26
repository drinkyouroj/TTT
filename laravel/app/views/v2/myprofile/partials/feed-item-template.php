<script type="text/x-handlebars-template" id="feed-item-template">	
	<div class="post-container" style="display:none">
		
		<div class="post-image-overlay">
			<a href="{{ site_url }}profile/{{ post.user.username }}">
				<img class="post-author-avatar" src="">
			</a>
			story by <a href="{{ site_url }}profile/{{ post.user.username }}"> {{ post.user.username }} </a>

			{{#isRepost feed_type}}
				<div class="post-repost-container">
					<img class="post-repost-image" src="">
					<div> x 
						<span class="post-repost-count">{{ users.length }} </span>
					</div>
					<ul class="post-repost-user-dropdown list-unstyled">
						{{#each users}}
							<li> 
								<a href="{{ ../site_url }}profile/{{ this }}"> {{ this }} </a> 
							</li>
						{{/each}}
					</ul>
				</div>
			{{/isRepost}}

		</div>

		<a href="{{ site_url }}posts/{{ post.alias }}">
			<img class="post-image" src="{{ site_url }}uploads/final_images/{{ post.image }}">
		</a>
		<p class="post-title"> 
			<a href="{{ site_url }}posts/{{ post.alias }}">
				{{ post.title }}
			</a>
		</p>
		<ul class="post-taglines list-inline">
			<li> {{ post.tagline_1 }} </li>
			<li> {{ post.tagline_2 }} </li>
			<li> {{ post.tagline_3 }} </li>
		</ul>
		
	</div>
</script>