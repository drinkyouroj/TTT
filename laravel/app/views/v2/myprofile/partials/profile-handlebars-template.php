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

<!--Below is used for Feed/Saves/Drafts-->
<script type="text/x-handlebars-template" id="default-profile-template">
	<div class="col-md-12 default-profile-container">
		<div class="row" id="default-content">
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