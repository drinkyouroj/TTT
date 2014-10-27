<!-- Notification template -->
<script type="text/javascript">
	Array.prototype.last = function() {
	    return this[this.length-1];
	}
	Handlebars.registerHelper('substring', function(v1, v2) {
		if ( v1.length > v2 )
			return v1.substring(0,v2) + '...';
		else
			return v1;
	});
	Handlebars.registerHelper('ifGt', function(v1, v2, options) {
		if(v1 > v2) {
			return options.fn(this);
		} 
		return options.inverse(this);
	});
	Handlebars.registerHelper('lastArr',function(v1,v2,options) {
		return v1.last();
	});
	Handlebars.registerHelper('folks', function(v1){
		if(v1.length-1 == 1) {
			return 1 + ' person';
		}
		return v1.length-1 + ' people';
	});
</script>


<!--Note that this is the collection template for holding the collection together-->
<!--Below is used for the front page.-->
<script type="text/x-handlebars-template" id="collection-template">
	<div class="collection-container">
		<div class="collection-controls generic-controls">
			<div class="col-md-12">
				<a data-type="all" class="active">All</a>
				<a data-type="post">Posts</a>
				<a data-type="repost">Reposts</a>
			</div>
		</div>
		<div id="featured-content">
		</div>
		
		<div id="collection-content" class="clearfix">
		</div>
		
		<div class="loading-container">
			<img src="{{site_url}}images/posts/comment-loading.gif">
		</div>
	
	</div>
	<div class="comment-container">
		<div class="comment-border">
		</div>
		<div id="comment-content">
			<h3>Recent Comments</h3>
		</div>
	</div>
</script>

<!--Below is used as a container for Feed/Saves/Drafts-->
<script type="text/x-handlebars-template" id="default-profile-template">
	<div class="col-md-12 default-container">
		{{#ifCond view 'feed'}}
			<div class="feed-controls generic-controls">
				<a data-type="all" class="active">All</a>
				<a data-type="post">Posts</a>
				<a data-type="repost">Reposts</a>
			</div>
		{{/ifCond}}
		<div class="row {{view}}" id="default-content">
		</div>
		<div class="loading-container">
			<img src="{{site_url}}images/posts/comment-loading.gif">
		</div>
	</div>
</script>

<script type="text/x-handlebars-template" id="feature-item-template">
	<div class="feature-item" id="post-{{post.id}}">
		<a  href="{{site_url}}posts/{{post.alias}}"
				class="image feature-inner col-md-12 col-sm-12"
				style="background-image: url('{{image_url}}/{{post.image}}');">
				{{#ifCond myprofile true }}
				{{#ifCond post.user.id user_id }}
					{{#isViews post.views }}
						<div class="views">
							<img class="views-icon" src="{{ site_url }}images/global/views-icon.png" width="15px" height="9px">
							<span>{{post.views}}</span>
						</div>
					{{/isViews}}
				{{/ifCond}}
				{{/ifCond}}
		</a>
		
		<div class="text feature-inner col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
			<h2>
				<a href="{{site_url}}posts/{{post.alias}}">
					{{post.title}}
				</a>
			</h2>
			<div class="line"></div>
			<p class="tagline">{{post.tagline_1}} | {{post.tagline_2}} | {{post.tagline_3}}</p>
			<p class="excerpt">
				{{post.excerpt}}...
			</p>
			<div class="read-more">
				<a href="{{site_url}}posts/{{post.alias}}">Read More</a>
				{{post.view}}
			</div>
		</div>

		{{#ifCond myprofile true }}
			{{#ifCond post.user.id user_id }}
				<div class="options-link"> </div>
				<div class="post-options">
					{{#ifCond editable true}}
						<a class="post-edit" href="{{site_url}}myprofile/editpost/{{post.id}}">
							Edit
						</a>
					{{/ifCond}}

					<a class="post-delete">
						Delete
					</a>
					<a class="post-delete-confirm" data-id="{{post.id}}">
						Confirm Delete?
					</a>
				</div>
			{{/ifCond}}
		{{/ifCond}}

		{{#ifCond post.nsfw 1}}
			<div class="nsfw"></div>
		{{/ifCond}}

	<div class="clearfix"></div>
	</div>
</script>

<!--Comments-->
<script type="text/x-handlebars-template" id="comment-template">
	<div class="comment-box">
		<span class="date">{{comment.created_at}}</span>
		<div class="inner-content">
			<div class="comment">
				{{#substring comment.body 200}}{{/substring}}
				<br/>
				<div class="where">
					<a href="{{site_url}}posts/{{comment.post.alias}}#comment-{{comment._id}}">{{comment.post.title}}</a>
				</div>
				<a class="readmore" href="{{site_url}}posts/{{comment.post.alias}}#comment-{{comment._id}}"> Read more<a/>
			</div>
		</div>
	</div>
</script>

<!--Below is used for Saves-->
<script type="text/x-handlebars-template" id="saves-template">
	<div class="container" id="save-{{save.id}}">
		<div class="saves-box">
			<div class="row">
				<div class="image-container col-md-3 col-sm-3">
					<a 	href="{{site_url}}posts/{{save.alias}}"
						class="image"
						style="background-image:url('{{image_url}}/{{save.image}}');">

					</a>
				</div>

				<div class="text col-md-7 col-sm-7">
					<h3>
						<a href="{{site_url}}posts/{{save.alias}}">
							{{save.title}}
						</a>
					</h3>
					<p>{{save.tagline_1}} | {{save.tagline_2}} | {{save.tagline_3}}</p>
					<p class="author">
						<a href="{{site_url}}profile/{{save.user.username}}">

							{{#if save.user.image}}
								<span class="avatar-image" style="background-image: url('{{image_url}}/{{save.user.image}}');"></span>
							{{else}}
								<span class="avatar-image" style="background-image:url('{{site_url}}images/profile/avatar-default.png');"></span>
							{{/if}}
							{{save.user.username}}
						</a>
					</p>
				</div>

				<div class="controls col-md-2 col-sm-2">
					<a class="remove-save icon-button" data-id="{{save.id}}" >Remove</a>
				</div>
			</div>
		</div>
	</div>
</script>

<!--Below is used for Drafts-->
<script type="text/x-handlebars-template" id="drafts-template">
	<div class="container" id="draft-container-{{draft.id}}">
		<div class="drafts-box">
			<div class="row">
				<div class="date col-md-2 col-sm-2 col-xs-12">
					<span>{{date}}</span>
				</div>
				<a  href="{{site_url}}myprofile/editpost/{{draft.id}}"
					class="image col-md-2 col-sm-3 col-xs-3"
					style="background-image:url('{{image_url}}/{{draft.image}}');">

				</a>

				<div class="text col-md-5 col-sm-5 col-xs-6">
					<h3>
						<a href="{{site_url}}myprofile/editpost/{{draft.id}}">
							{{draft.title}}
						</a>
					</h3>
					<p>{{draft.tagline_1}} | {{draft.tagline_2}} | {{draft.tagline_3}}</p>
				</div>

				<div class="controls col-md-3 col-sm-2 col-xs-12">
					<a class="edit-draft icon-button" href="{{site_url}}myprofile/editpost/{{draft.id}}" >Edit</a>
					<a class="delete-draft icon-link" data-id="{{draft.id}}" data-toggle="modal" data-target="#draftRemove">Remove</a>
				</div>
			</div>
		</div>
	</div>
</script>

<!--Follow Template-->
<script type="text/x-handlebars-template" id="follow-template">
	<a 
		class="follow user"
		href="{{site_url}}profile/{{username}}"
		>
		<div class="user-avatar" style="background-image:url('{{site_url}}rest/profileimage/{{user_id}}');">
		</div>
		<div class="user-name">
			{{username}}
		</div>
	</a>
</script>
