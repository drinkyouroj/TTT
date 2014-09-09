<script type="text/x-handlebars-template" id="comment-edit-template">
	
	<form method="POST" accept-charset="UTF-8" class="form-horizontal comment-edit" role="form">
		<input name="comment_id" type="hidden" value="{{comment_id}}">
		<div class="form-group comment-form">
			<textarea class="form-control" required="required" minlength="5" name="body" cols="50" rows="10">{{comment_body}}</textarea>
			<span class="error"></span>
		</div>

		<div class="form-group pull-right">
			<input class="btn-flat-light-gray comment-edit-cancel" type="button" value="Cancel">
			<input class="btn-flat-dark-gray" type="submit" value="Submit">	
		</div>
		
		<div class="clearfix"></div>
	</form>

</script>