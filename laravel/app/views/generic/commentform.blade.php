{{ Form::open( array('url'=>'profile/comment/'.$post->id, 'method'=>'post','class'=>'form-horizontal','role'=>'form') )}}

	{{ Form::hidden('post_id', $post->id ) }}
	@if(isset($reply_id))
		{{ Form::hidden('reply_id', $reply_id ) }}
	@endif

	<div class="form-group comment-form {{$errors->first('body') ? 'has-error' : '' }}">
		{{ Form::label('body','Comment', array('class'=>'control-label')) }}
		{{ Form::textarea('body', Input::old('body'), array('class'=>'form-control', 'required', 'minlength' =>'5')) }}
		<span class="error">{{ $errors->first('body') }}</span>
	</div>
					
	<div class="form-group pull-right">
		{{ Form::submit('Comment') }}
	</div>
	
	<div class="clearfix"></div>
{{ Form::close() }}
