
<script type="text/javascript">
	Handlebars.registerHelper('ifCond', function(v1, v2, options) {
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
</script>

<script type="text/x-handlebars-template" id="comment-template">
	{{#ifCond comment.depth 0}}
	<div class="thread-parent-divider"></div>
	{{/ifCond}}
	<div id="comment-{{ comment._id }}" class="comment {{#ifCond comment.published 1}}published{{else}}deleted{{/ifCond}} {{#ifCond comment.depth 0}}thread-parent{{/ifCond}}" style="margin-left: {{ comment.margin }}">
		<div class="left-col">
			<span class="like-comment-count">{{comment.likes.length}}</span>
			<span class="like-comment glyphicon glyphicon-thumbs-up {{#contains comment.likes active_user_id}}active{{/contains}}"></span>
			<br>
			<span class="flag-comment glyphicon glyphicon-flag {{#contains comment.flags active_user_id}}active{{/contains}}"></span>
		</div>

		<div class="right-col">
			<div class="user">
				{{#ifCond comment.published 1 }}
					<a href="{{ profile_url }}{{ comment.author.username }}"> {{ comment.author.username }} </a>
				{{else}}
					<span class="deleted">Nobody</span>
				{{/ifCond}}
			</div>

			<p class="comment-body">
				{{#ifCond comment.published 0 }}
					<span class="deleted">(This comment has been deleted)</span>
				{{else}}
					 {{ comment.body }} 	
				{{/ifCond}}
			</p>
			<div class="reply-links">
				<a class="reply {{#ifCond active_user_id false}}auth{{/ifCond}}" data-replyid="{{ comment._id }}" data-postid="{{ comment.post_id }}">Reply</a>
				
				{{#ifCond comment.author.user_id active_user_id }}
					{{#ifCond comment.published 1}}			
						<a class="delete" data-delid="{{ comment._id }}" title="Delete Comment" >Delete</a>
					{{/ifCond}}
				{{/ifCond}}

				{{#ifCond is_mod true }}
					{{#ifCond comment.published 1}}
						<a class="delete mod-del-comment" data-delid="{{ comment._id }}" title="Delete Comment" >Moderator Delete </a>
					{{/ifCond}}
				{{/ifCond}}

				<div class="reply-box"></div>
			</div>
		</div>
	</div>

</script>