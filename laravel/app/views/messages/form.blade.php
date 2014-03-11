@extends('layouts.profile')

@section('css')
	@parent
	<link href="{{Config::get('app.url')}}/css/views/message.css" rel="stylesheet" media="screen">
@stop

{{--Left Sidebar--}}
@section('left_sidebar')


	
@stop


{{--The main content area--}}
@section('main')
<div class="row">
	{{ Form::open(array('url'=>'profile/submitmessage', 'method'=>'post','class'=>'form-horizontal','role'=>'form'))}}
		<div class="col-md-12 write-message">	
		{{--Old Messages/The Thread/Reply--}}
		@if(isset($thread) && Request::segment(2) == 'replymessage')
			
				@if($message->to_uid == Auth::user()->id)
					{{--You're replying to the other person--}} 
					{? $to_uid = $message->from_uid;  ?}
				@else
					{{--You're sending another message annoyingly to the same person before they reply--}}
					{? $to_uid = $message->to_uid; ?}
				@endif
				
				{{--Either way its from you--}}
				{? $from_uid = Auth::user()->id; ?}
				
			@foreach($thread as $item)
				<div class="message-container {{$item->from_uid == Auth::user()->id ? 'me': 'other' }}">
					<div class="message-from">
						{{$item->from->username}}
					</div>
					<div class="message">
						<div class="the-content">
							{{$item->body}}
						</div>
						<span class="date">
							{{$item->created_at->format('d.m.y')}}
						</span>
					</div>
				<div class="clearfix"> </div>
				</div>
			@endforeach
			
			<div class="message-container {{$message->from_uid == Auth::user()->id ? 'me': 'other' }}">
				<div class="original-message">
					<div class="message-from">
						{{$message->from->username}}
					</div>
					<div class="message">
						<div class="the-content">
							{{$message->body}}
						</div>
						<span class="date">
							{{$message->created_at->format('d.m.y')}}
						</span>
					</div>
				</div>
			<div class="clearfix"> </div>
			</div>
			
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