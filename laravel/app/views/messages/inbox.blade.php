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
			<div class="row">
				<div class="col-md-6">
					<h2>The Inbox</h2>
				</div>
				<div class="col-md-6">
					<h3>{{link_to('profile/newmessage/','New Message',array('class'=>'new-message') )}}</h3>
				</div>
			</div>
			
			<h2 class="newest-threads">Newest Threads</h2>
			<ul class="messages">
			@foreach($threads as $thread)
				@if($thread->from_uid == Auth::user()->id )
				<li class="message sent">
					<div class="row">
						<div class="col-md-10 message-content">
							<span class="sent-to">Sent to {{link_to('profile/'.$thread->to->username, $thread->to->username, array('class'=>'')) }}</span>
							<br/><span class="the-excerpt">{{substr($thread->body, 0, 100)}}</span>
						</div>
						<div class="col-md-2 message-button">
							{{link_to('profile/replymessage/'.$thread->id, 'View', array('class'=>'btn btn-primary btn-sm','type'=>'button')) }}
						</div>
					</div>
				</li>
				@else
				<li class="message received">
					<div class="row">
						<div class="col-md-10 message-content">
							<span class="sent-from">from {{link_to('profile/'.$thread->from->username, $thread->from->username, array('class'=>'')) }}</span>
							<br/><span class="the-excerpt">{{substr($thread->body, 0, 100)}}</span>
						</div>
						<div class="col-md-2 message-button">
							{{link_to('profile/replymessage/'.$thread->id, 'Reply', array('class'=>'btn btn-primary btn-sm','type'=>'button')) }}
						</div>
					</div>
				</li>
				@endif
			@endforeach
			</ul>
		</div>
	</div>
</div>
@stop