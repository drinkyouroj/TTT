
<script type="text/javascript">
	Handlebars.registerHelper('ifEqual', function(v1, v2, options) {
		if(v1 === v2) {
			return options.fn(this);
		}
		return options.inverse(this);
	});
	Handlebars.registerHelper('contains', function(v1, v2, options) {
		if ( v1 instanceof Array && v1.indexOf(v2) > -1 ) {
			return options.fn(this);
		}
	});
	Handlebars.registerHelper('isEditable', function(created_date, options) {
		var created = new Date(created_date);
		var now = new Date();
		if ( now - created < 259200000 ) {
			return options.fn(this);
		}
	});
	Handlebars.registerHelper('formatDate', function(date) {
		return moment(date).fromNow();
	});
</script>

<script type="text/x-handlebars-template" id="comment-template">
	{{#ifEqual comment.depth 0}}
	<div class="thread-parent-divider"></div>
	{{/ifEqual}}
	<div id="comment-{{ comment._id }}" class="comment {{#ifEqual comment.published 1}}published{{else}}deleted{{/ifEqual}} {{#ifEqual comment.depth 0}}thread-parent{{/ifEqual}} {{#ifEqual target_comment true}}target-comment{{/ifEqual}}" style="margin-left: {{ comment.margin }}">
		<div class="left-col">
			<span class="like-comment-count">{{comment.likes.length}}</span>
			<span class="like-comment glyphicon glyphicon-thumbs-up {{#contains comment.likes active_user_id}}active{{/contains}}"></span>
			<br>
			<span class="flag-comment glyphicon glyphicon-flag {{#contains comment.flags active_user_id}}active{{/contains}}"></span>
		</div>

		<div class="right-col">
			<div class="user">
				{{#ifEqual comment.published 1 }}
					<a href="{{site_url}}profile/{{ comment.author.username }}"> {{ comment.author.username }} </a>
					<span class="published-date"> - {{formatDate comment.created_at}}</span>
					{{#if comment.edited}}
						<span class="edited-date">(edited {{formatDate comment.updated_at}})</span>
					{{/if}}
				{{else}}
					<span class="deleted">Nobody</span>
				{{/ifEqual}}
			</div>

			<p class="comment-body">{{#ifEqual comment.published 0 }}<span class="deleted">(This comment has been deleted)</span>{{else}}{{ comment.body }}{{/ifEqual}}</p>
			<div class="reply-links">
				<a class="reply {{#ifEqual active_user_id false}}auth{{/ifEqual}}" data-replyid="{{ comment._id }}" data-postid="{{ comment.post_id }}">Reply</a>
				
				{{#ifEqual comment.author.user_id active_user_id }}
					{{#ifEqual comment.published 1}}
						{{#isEditable comment.created_at}}
							<a class="edit" data-editid="{{ comment._id }}" title="Edit Comment" >Edit</a>
						{{/isEditable}}
						<a class="delete" data-delid="{{ comment._id }}" title="Delete Comment" >Delete</a>
					{{/ifEqual}}
				{{/ifEqual}}

				{{#ifEqual is_mod true }}
					{{#ifEqual comment.published 1}}
						<a class="delete mod-del-comment" data-delid="{{ comment._id }}" title="Delete Comment" >Moderator Delete </a>
					{{/ifEqual}}
				{{/ifEqual}}

				<div class="reply-box"></div>
			</div>
		</div>
	</div>

</script>