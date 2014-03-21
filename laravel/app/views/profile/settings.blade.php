@extends('layouts.profile')

@section('css')
	@parent
	<link href="{{Config::get('app.url')}}/css/views/settings.css" rel="stylesheet" media="screen">
@stop

@section('title')
{{$user->username }}'s Settings
@stop

@section('main')
	<div class="row settings-content">
		<div class="col-md-10 col-md-offset-1">
			<h2>Delete Your Account</h2>
			<p>This will delete your account from the system.  All of your content will be unpublished (but they'll remain in place)</p>
			<p>Should you decide to come back, all of your content will be republished and your user will re-appear.</p>
			<button class="btn btn-warning" data-toggle="modal" data-target="#deleteModal">Yes! Delete this Account!</button>
		</div>
		
	</div>
	
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        Are you absolutely sure???
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger delete-account" data-user="{{$user->id}}">
        	Delete The Account Already!
        </button>
      </div>
    </div>
  </div>
</div>
	
	
@stop


@section('js')
	@parent
	
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/settings.js"></script>
@stop