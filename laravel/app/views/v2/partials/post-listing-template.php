<script type="text/javascript" >
// ========================= HANDLBARS TEMPLATE ===========================
	// Repost logic
	Handlebars.registerHelper('isRepost', function ( item, options ) {
	  	if ( item == 'repost' )
	  		return options.fn(this);
	  	else
	  		return options.inverse(this);
	});
	Handlebars.registerHelper('ifCond', function(v1, v2, options) {
		if(v1 === v2) {
			return options.fn(this);
		}
			return options.inverse(this);
		}
	);
	Handlebars.registerHelper('ifNotCond', function(v1, v2, options) {
		if(v1 != v2) {
			return options.fn(this);
		}
			return options.inverse(this);
		}
	);
</script>
<script type="text/x-handlebars-template" id="post-item-template">	
	<div class="post-container fade in">
		
		<div class="post-image-overlay">
			<a href="{{ site_url }}profile/{{ post.user.username }}">
				<img class="post-author-avatar" src="">
			</a>
			story by <a href="{{ site_url }}profile/{{ post.user.username }}"> {{ post.user.username }} </a>

			{{#ifCond feed_type 'repost' }}
				<div class="post-repost-container">
					<img class="post-repost-image" src="{{ site_url }}img/icons/repost-single.png">
					<div class="post-repost-count-container"> x 
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
			{{/ifCond}}

			{{#ifCond post.user.id user_id }}
				<div class="post-options">
				{{#ifCond editable true}}
					<a class="post-edit" href="{{site_url}}myprofile/editpost/{{post.id}}">Edit</a>
				{{/ifCond}}
				{{#ifNotCond featured_id post.id}}
					<a class="set-featured" data-id="{{post.id}}">Feature</a>
				{{/ifNotCond }}
				</div>
			{{/ifCond}}

		</div>

		<a class="image-link" href="{{ site_url }}posts/{{ post.alias }}">
			<div class="top-fade"> </div>
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