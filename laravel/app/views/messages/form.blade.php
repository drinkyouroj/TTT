@extends('layouts.profile')

{{--Left Sidebar--}}
@section('left_sidebar')


	
@stop


{{--The main content area--}}
@section('main')
<div class="row">
	{{ Form::open(array('url'=>'profile/submitmessage', 'method'=>'post','class'=>'form-horizontal','role'=>'form'))}}
		
		{{--Get the update bit done once we have the edit function down --}}
		
		@if(Request::segment(2) == 'replymessage')
			<div class="col-md-8 col-md-offset-1">
				<p>{{$message->body}}</p>
			</div>
			{{ Form::hidden('reply_id', Request::segment(3) ) }}
		@else 
			{{ Form::hidden('reply_id', 0 ) }}
		@endif
		
		@if((isset($message) && $message->to_uid != 0) || Request::segment(2) == 'newmessage')
		<div class="col-md-8 col-md-offset-1">
			
			@if(!isset($message_user))
				<div class="form-group">
					{{ Form::label('to_uid','To User', array('class'=>'control-label'))}}
					<select name="to_uid">
						@foreach($mutuals as $mutual)
						<option value="{{$mutual->follower_id}}">{{$mutual->followers->username}}</option>
						@endforeach
					</select>
				</div>	
			@else
				{? $to_user = ' to '.$message_user->username?}
				{{ Form::hidden('to_uid', $message_user->id ) }}
			@endif
			
			<div class="form-group">
			@if(!isset($message_user))
				{{ Form::label('body','Message', array('class'=>'control-label')) }}
			@else	
				{{ Form::label('body','Message'.$to_user, array('class'=>'control-label')) }}
			@endif
				{{ Form::textarea('body', null, array('class'=>'form-control')) }}
				{{ $errors->first('body') }}
			</div>
			
			<div class="form-group">
			@if(Request::segment(3))
				{{ Form::submit('Reply') }}
			@else
				{{ Form::submit('Send') }}
			@endif
			</div>
		</div>
		@endif
		
	{{ Form::close() }}
</div>
@stop