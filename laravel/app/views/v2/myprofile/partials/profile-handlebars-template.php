<!--Note that this is the collection tempalte for holding the collection together-->
<!--Below is used for the front page.-->
<script type="text/x-handlebars-template" id="collection-template">
	<div class="collection-container">
		<div class="collection-controls generic-controls">
			<div class="col-md-12">
				<a data-type="all" class="active">All</a> |
				<a data-type="post">Post</a> |
				<a data-type="repost">Reposts</a>
			</div>
		</div>
		<div id="featured-content">
		</div>
		
		<div id="collection-content" class="clearfix">
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
				<a data-type="all" class="active">All</a> |
				<a data-type="post">Post</a> |
				<a data-type="repost">Reposts</a>
			</div>
		{{/ifCond}}
		<div class="row {{view}}" id="default-content">
		</div>
	</div>
</script>

<!--Comments-->
<script type="text/x-handlebars-template" id="comment-template">
	<div class="comment-box">
		<span class="date">{{comment.created_at}}</span>
		<div class="inner-content">
			<div class="where">
				Commented on <a href="{{site_url}}posts/{{comment.post.alias}}#comment-{{comment._id}}">{{comment.post.title}}</a>
			</div>
			<div class="comment">
				{{comment.body}}
				<br/>
				<a class="readmore" href="{{site_url}}posts/{{comment.post.alias}}#comment-{{comment._id}}"> Read more<a/>
			</div>
		</div>
	</div>
</script>

<!--Below is used for Saves-->
<script type="text/x-handlebars-template" id="saves-template">
	<div class="container">
		<div class="saves-box">
			<div class="row">
				<div class="image-container col-md-3">
					<div class="image" style="background-image:url('{{site_url}}/uploads/final_images/{{save.image}}');">

					</div>
				</div>

				<div class="text col-md-7">
					<h3>{{save.title}}</h3>
					<p>{{save.tagline_1}} | {{save.tagline_2}} | {{save.tagline_3}}</p>
				</div>

				<div class="controls col-md-2">
					<a class="remove-save icon-button" data-id="{{save.id}}" >Remove</a>
				</div>
			</div>
		</div>
	</div>
</script>

<!--Below is used for Drafts-->
<script type="text/x-handlebars-template" id="drafts-template">
	<div class="container">
		<div class="drafts-box">
			<div class="row">
				<div class="date col-md-2">
					<span>{{date}}</span>
				</div>
				<div class="image col-md-2" style="background-image:url('{{site_url}}/uploads/final_images/{{draft.image}}');">

				</div>

				<div class="text col-md-5">
					<h3>{{draft.title}}</h3>
					<p>{{draft.tagline_1}} | {{draft.tagline_2}} | {{draft.tagline_3}}</p>
				</div>

				<div class="controls col-md-3">
					<a class="edit-draft icon-button" href="{{site_url}}myprofile/editpost/{{draft.id}}" >Edit Draft</a>
					<a class="publish-draft btn-flat-blue" data-id="{{draft.id}}" >Publish</a>
					<br/>
					<a class="delete-draft icon-link" data-id="{{draft.id}}" data-toggle="tooltip" data-placement="bottom" title="Delete Forever!">Delete</a>
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
		style="background-image:url('{{site_url}}rest/profileimage/{{user_id}}');"
		>
		<div class="follow-name">
			<span>{{username}}</span>
		</div>
	</a>
</script>

<!--Script-->
<script type="text/x-handlebars-template" id="settings-template">
	<div class="col-md-12">
		<div class="col-md-5 col-md-offset-1 avatar">
			<h2>Upload your Avator</h2>
			<div class="upload-form">

				<div id="avatarErrors"></div>

				<form id="uploadAvatar" enctype="multipart/form-data" method="post" action="{{this.site_url}}rest/profile/image/upload">
                    <input type="file" name="image" id="image" />
				</form>
				<div id="avatarOutput" style="display:none">
                </div>
			</div>
		</div>
		<div class="col-md-5 change-password">
			<h2>Change Your Password</h2>
			<div class="password-message">
				
			</div>
			<div class="reset-pass">
				<form role="form" class="form-horizontal" id="changePassword" method="post" action="{{this.site_url}}rest/profile/password">
					<div class="form-group">
						<label for="current_password" class="col-sm-4 control-label">Current Password</label>
						<div class="col-sm-8">
							<input type="password" name="current_password" class="current_password">
						</div>
					</div>

					<div class="form-group">
						<label for="password" class="col-sm-4 control-label">New Password</label>
						<div class="col-sm-8">
							<input type="password" name="password" class="password">
						</div>
					</div>

					<div class="form-group">
						<label for="password_confirmation" class="col-sm-4 control-label">New Password Again</label>
						<div class="col-sm-8">
							<input type="password" name="password_confirmation" class="password_confirmation">
						</div>
					</div>

					<button class="btn btn-default">Change Password</button>

					<div class="message-box"></div>
				</form>

			</div>
		</div>
		
		<div class="col-md-5 col-md-offset-6 del-acc">
			<h2>Delete Your Account</h2>
			<p>
				This will delete your account from the system.  All of your content will be unpublished (but they will remain in place)
			</p>
			<p>Should you decide to come back, all of your content will be republished and your user will re-appear.</p>
			<button class="btn btn-warning delete-button" data-toggle="modal" data-target="#deleteModal">Yes! Delete this Account!</button>
		</div>

		

	</div>
</script>


<script type="text/x-handlebars-template" id="feature-item-template">
	<div class="feature-item row">
		<div class="text feature-inner col-md-5 col-sm-5">
			<h2>{{post.title}}</h2>
			<div class="line"></div>
			<p class="tagline">{{post.tagline_1}} | {{post.tagline_2}} | {{post.tagline_3}}</p>
			<p class="excerpt">
				{{post.excerpt}}
			</p>
			<div class="read-more">
				<a href="">Read More</a>
			</div>
		</div>
		<div class="image feature-inner col-md-7 col-sm-7" style="background-image: url('{{site_url}}uploads/final_images/{{post.image}}');">

		</div>
	</div>
</script>