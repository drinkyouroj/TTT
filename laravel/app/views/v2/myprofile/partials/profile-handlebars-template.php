<!--Note that this is the collection tempalte for holding the collection together-->
<script type="text/x-handlebars-template" id="notifications-template">
	<div class="notification-container">
		{{notification}}
	</div>
</script>

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
	<div class="container" id="save-{{save.id}}">
		<div class="saves-box">
			<div class="row">
				<div class="image-container col-md-3 col-sm-3">
					<a 	href="{{site_url}}posts/{{save.alias}}"
						class="image"
						style="background-image:url('{{site_url}}uploads/final_images/{{save.image}}');">

					</a>
				</div>

				<div class="text col-md-7 col-sm-7">
					<h3>
						<a href="{{site_url}}posts/{{save.alias}}">
							{{save.title}}
						</a>
					</h3>
					<p>{{save.tagline_1}} | {{save.tagline_2}} | {{save.tagline_3}}</p>
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
	<div class="container">
		<div class="drafts-box">
			<div class="row">
				<div class="date col-md-2 col-sm-2 col-xs-12">
					<span>{{date}}</span>
				</div>
				<a  href="{{site_url}}posts/{{draft.alias}}"
					class="image col-md-2 col-sm-3 col-xs-3"
					style="background-image:url('{{site_url}}uploads/final_images/{{draft.image}}');">

				</a>

				<div class="text col-md-5 col-sm-5 col-xs-6">
					<h3>
						<a href="{{site_url}}posts/{{post.alias}}">
							{{draft.title}}
						</a>
					</h3>
					<p>{{draft.tagline_1}} | {{draft.tagline_2}} | {{draft.tagline_3}}</p>
				</div>

				<div class="controls col-md-3 col-sm-2 col-xs-12">
					<a class="edit-draft icon-button" href="{{site_url}}myprofile/editpost/{{draft.id}}" >Edit</a>
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

<!--Settings Template-->
<script type="text/x-handlebars-template" id="settings-template">
	<div class="col-md-4 avatar">
		<div class="upload-form">

			<form id="uploadAvatar" method="post" action="{{this.site_url}}rest/profile/image/upload">
	            <input type="hidden" name="image" class="image">
	            <div class="thumb-container" style="background-image:url('{{site_url}}uploads/final_images/{{user_image}}');">
	            </div>
			</form>
			<a class="btn-flat-blue avatar-modal">Choose an Avatar</a>

			<div id="avatarErrors"></div>

			<div id="avatarOutput" style="display:none">
	        </div>
		</div>
	</div>
	<div class="col-md-8 change-password">
		<h2>Change Your Password</h2>
		<div class="password-message">
			
		</div>
		<div class="reset-pass">
			<form role="form" class="form-horizontal" id="changePassword" method="post" action="{{this.site_url}}rest/profile/password">
				<div class="form-group">
					<div class="col-sm-8">
						<input type="password" name="current_password" class="current_password" placeholder="current password">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-8">
						<input type="password" name="password" class="password" placeholder="new password">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-8">
						<input type="password" name="password_confirmation" class="password_confirmation" placeholder="confirm new password">
					</div>
				</div>

				<button class="btn btn-default btn-flat-dark-gray">Change Password</button>

				<div class="message-box"></div>
			</form>

		</div>
	</div>

	<div class="col-md-12 del-acc">
		<h2>Delete Your Account</h2>
		<p>
			This will delete your account from the system.  All of your content will be unpublished (but they will remain in place)
		</p>
		<p>Should you decide to come back, all of your content will be republished and your user will re-appear.</p>
		<button class="btn btn-flat-red delete-button" data-toggle="modal" data-target="#deleteModal">Delete My Account</button>
	</div>
</script>


<script type="text/x-handlebars-template" id="feature-item-template">
	<div class="feature-item row">
		<div class="text feature-inner col-md-4 col-sm-4">
			<h2>
				<a href="{{site_url}}posts/{{post.alias}}">
					{{post.title}}
				</a>
			</h2>
			<div class="line"></div>
			<p class="tagline">{{post.tagline_1}} | {{post.tagline_2}} | {{post.tagline_3}}</p>
			<p class="excerpt">
				{{post.excerpt}}
			</p>
			<div class="read-more">
				<a href="{{site_url}}posts/{{post.alias}}">Read More</a>
			</div>
		</div>

		<a  href="{{site_url}}posts/{{post.alias}}"
				class="image feature-inner col-md-8 col-sm-8"
				style="background-image: url('{{site_url}}uploads/final_images/{{post.image}}');">
		</a>
	</div>
</script>