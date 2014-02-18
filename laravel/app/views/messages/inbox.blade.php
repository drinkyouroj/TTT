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
	<div class="col-md-8 col-md-offset-2 inbox-container">
		<div class="inbox">
			<h2>The Inbox <span>{{link_to('profile/newmessage/','New Message',array('class'=>'new-message') )}}</span></h2>
			
			
			<h2>Newest Threads</h2>
			<ul class="messages">
			@foreach($threads as $thread)
				@if($thread->from_uid == Auth::user()->id )
				<li class="message sent">
					<span class="the-excerpt">{{substr($thread->body, 0, 100)}}</span>
					<span>Sent to {{link_to('profile/'.$thread->to->username, $thread->to->username, array('class'=>'')) }}</span>
					{{link_to('profile/replymessage/'.$thread->id, 'View', array('class'=>'btn btn-primary btn-sm','type'=>'button')) }}
				</li>
				@else
				<li class="message received">
					<span class="the-excerpt">{{substr($thread->body, 0, 100)}}</span>
					<span>from {{link_to('profile/'.$thread->from->username, $thread->from->username, array('class'=>'')) }}</span>
					{{link_to('profile/replymessage/'.$thread->id, 'Reply', array('class'=>'btn btn-primary btn-sm','type'=>'button')) }}
				</li>
				@endif
			@endforeach
			</ul>
		</div>
	</div>
</div>
@stop