@extends('layouts.profile')

{{--Left Sidebar--}}
@section('left_sidebar')
	
@stop


{{--The main content area--}}
@section('main')
<div class="row">
	The Inbox
	@foreach($messages as $message)
		<div class="col-md-12">
			<p>{{substr($message->body, 0, 100)}}</p>
			<span>from {{link_to('profile/'.$message->from->username, $message->from->username, array('class'=>'')) }}</span>
			<div class="btn-group">
				{{link_to('profile/replymessage/'.$message->id, 'Reply', array('class'=>'btn btn-primary btn-sm','type'=>'button')) }}
				{{link_to('profile/deletemessage/'.$message->id, 'Delete', array('class'=>'btn btn-danger btn-sm','type'=>'button')) }}
			</div>
		</div>
	@endforeach
</div>
@stop