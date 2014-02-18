@extends('layouts.profile')

{{--Left Sidebar--}}
@section('left_sidebar')


	
@stop


{{--The main content area--}}
@section('main')
<div class="row">
	{{ Form::open(array('url'=>'profile/submitmessage', 'method'=>'post','class'=>'form-horizontal','role'=>'form'))}}
		<div class="col-md-8 col-md-offset-1">
			
		{{--Old Messages/The Thread/Reply--}}
		@if(isset($thread) && Request::segment(2) == 'replymessage')
			<div class="message">
				<div class="the-content">
					{{$message->body}}
				</div>
				<span>{{$message->created_at->format('d-m-y H:i:s')}}</span>
			</div>
			
				{? if($message->to_uid == Auth::user()->id) { $to_uid = $message->from_uid; $from_uid = $message->to_uid; } ?}
				{? if($message->from_uid == Auth::user()->id) { $to_uid = $message->to_uid; $to_uid = $message->from_uid; } ?}
			
			@foreach($thread as $item)
				<div class="message">
					<div class="the-content">
						{{$item->body}}
					</div>
					<span>{{$item->created_at->format('d-m-y H:i:s')}}</span>
				</div>
			@endforeach
			
			<input type="hidden" name="to_uid" value="{{$to_uid}}">
		@endif
		
		
		{{--New Message--}}
		@if(Request::segment(2) == 'newmessage')	
			{{--To one of your mutuals?--}}
			@if(!isset($message_user))
				<div class="form-group">
					{{ Form::label('to_uid','To User', array('class'=>'control-label'))}}
					<select name="to_uid">
						@foreach($mutuals as $mutual)
						<option value="{{$mutual->user_id}}">{{$mutual->users->username}}</option>
						@endforeach
					</select>
				</div>
			@else
			{{--To a specific user?--}}
				{{ Form::hidden('to_uid', $message_user->id ) }}
			@endif
		@endif
		
			<div class="form-group">
			
				{{ Form::label('body','Message', array('class'=>'control-label')) }}
			
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
	{{ Form::close() }}
</div>
@stop