<script type="text/x-handlebars-template" id="comment-reply-template">
	
	<form method="POST" accept-charset="UTF-8" class="form-horizontal comment-reply" role="form">
		<input name="post_id" type="hidden" value="{{post_id}}">
		<input name="reply_id" type="hidden" value="{{reply_id}}">
		<div class="form-group comment-form">
			<textarea class="form-control" required="required" minlength="5" name="body" cols="50" rows="10" id="body"></textarea>
			<span class="error"></span>
		</div>
						
		<div class="form-group pull-right">
			<input class="btn-flat-light-gray comment-edit-cancel" type="button" value="Cancel">
			<input class="btn-flat-dark-gray" type="submit" value="Reply">	
		</div>
		
		<div class="clearfix"></div>
	</form>

</script>