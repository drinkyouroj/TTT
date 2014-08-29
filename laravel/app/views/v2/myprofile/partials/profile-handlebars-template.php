<!--Note that this is the collection tempalte for holding the collection together-->
<!--Below is used for the front page.-->
<script type="text/x-handlebars-template" id="collection-template">
	<div class="col-md-10 collection-container">
		<div class="row" id="collection-content">
		</div>
	</div>
	<div class="col-md-2 comment-container">
		<div class="row" id="comment-content">
		</div>
	</div>
</script>

<!--Below is used as a container for Feed/Saves/Drafts-->
<script type="text/x-handlebars-template" id="default-profile-template">
	<div class="col-md-12 default-container">
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
				Commented on <a href="{{site_url}}posts/{{comment.post.alias}}">{{comment.post.title}}</a>
			</div>
			<div class="comment">
				{{comment.body}}
			</div>
		</div>
	</div>
</script>

<!--Below is used for Saves-->
<script type="text/x-handlebars-template" id="saves-template">
	<div class="saves-box">
		<div class="image" style="background-image:url('{{site_url}}/uploads/final_images/{{save.image}}');">

		</div>

		<div class="text">
			<h3>{{save.title}}</h3>
			<p>{{save.tagline_1}} | {{save.tagline_2}} | {{save.tagline_3}}</p>
		</div>

		<div class="controls">
			<a class="remove-save" data-id="{{save.id}}" >Remove</a>
		</div>
	</div>
</script>

<!--Below is used for Drafts-->
<script type="text/x-handlebars-template" id="drafts-template">
	<div class="drafts-box">
		<div class="date">
			<span>{{date}}</span>
		</div>
		<div class="image" style="background-image:url('{{site_url}}/uploads/final_images/{{draft.image}}');">

		</div>

		<div class="text">
			<h3>{{draft.title}}</h3>
			<p>{{draft.tagline_1}} | {{draft.tagline_2}} | {{draft.tagline_3}}</p>
		</div>

		<div class="controls">
			<a class="edit-draft" data-id="{{draft.id}}" >Edit Draft</a>
			<a class="publish-draft" data-id="{{draft.id}}" >Publish</a>
			<br/>
			<a class="delete-draft" data-id="{{draft.id}}" data-toggle="tooltip" data-placement="bottom" title="Delete Forever!">Delete</a>
		</div>
	</div>
</script>

<!--Script-->
<script type="text/x-handlebars-template" id="settings-template">
	
</script>