
<script type="text/javascript">
	Handlebars.registerHelper('ifCond', function(v1, v2, options) {
		if(v1 === v2) {
			return options.fn(this);
		}
			return options.inverse(this);
		}
	);
</script>

<?php 
// This template is not self closing! (in case it has child comments)
?>
<script type="text/x-handlebars-template" id="comment-template">

	<div id="comment-{{ comment._id }}" class="comment {{ comment.published }}" style="margin-left: {{ comment.margin }}">
		<span>by <a href="{{ profile_url }}{{ comment.author.username }}"> {{ comment.author.username }} </a> </span>
		
		<p class="comment-body">
			{{#ifCond comment.published 'deleted' }}
				<span class="deleted">(This Comment has been deleted...)</span>
			{{else}}
				 {{ comment.body }} 	
			{{/ifCond}}
		</p>

		<a class="reply btn-flat-white" data-replyid="{{ comment._id }}" data-postid="{{ comment.post_id }}">Reply</a>
		
		{{#ifCond comment.author.user_id active_user_id }}
			<a class="delete btn-flat-dark-gray" data-delid="{{ comment._id }}" title="Delete Comment" >Delete</a>
		{{/ifCond}}

		{{#ifCond is_mod true }}
			<a class="mod-del-comment" data-delid="{{ comment._id }}" title="Delete Comment" >Moderator {{ comment.published }} </a>
		{{/ifCond}}

		<div class="reply-box"></div>
	</div>

</script>