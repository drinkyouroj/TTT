{{ Form::open( array('url'=>'profile/comment/'.$post->id, 'method'=>'post','class'=>'form-horizontal','role'=>'form') )}}

	{{ Form::hidden('post_id', $post->id ) }}
	@if(isset($reply_id))
		{{ Form::hidden('reply_id', $reply_id ) }}
	@endif

	<?php
		$form_title = isset( $reply_id ) ? ' ': 'Comments ('.$post->comments->count().')';
	?>
	<div class="form-group comment-form {{$errors->first('body') ? 'has-error' : '' }}">
		{{ Form::label('body', $form_title, array('class'=>'control-label')) }}
		{{ Form::textarea('body', Input::old('body'), array('class'=>'form-control', 'required', 'minlength' =>'5')) }}
		<span class="error">{{ $errors->first('body') }}</span>
	</div>
					
	<div class="form-group pull-right">
		{{ Form::submit('Comment', array('class' => 'btn-flat-dark-gray') ) }}
	</div>
	
	<div class="clearfix"></div>
{{ Form::close() }}
