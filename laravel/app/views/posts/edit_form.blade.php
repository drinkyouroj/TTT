@extends('layouts.profile')

{{--Left Sidebar--}}
@section('left_sidebar')

	@if(Request::segment(2) == 'newpost')
		{{--This is the new post section--}}
		<p>Add your new post here.</p>
		
	@else
		{{--This is the Edit post section--}}
		<p>Please note that you can only edit your posts within 24hr period.</p>
		
	@endif

	
@stop

@section('js')
	@parent
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/additional-methods.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile/form.js"></script>
@stop

{{--The main content area--}}
@section('main')
<div class="row post-form">
	
	<div class="form-nav col-md-3">
		<ul>
			<li>
				<a href="#page-1" class="active">Content</a>
			</li>
		</ul>
	</div>
	
	{{ Form::open(array('url'=>'profile/submitpost', 'method'=>'post','class'=>'form-horizontal','role'=>'form'))}}
		
		{{--Get the update bit done once we have the edit function down --}}
		
		@if(Request::segment(2) == 'editpost')
			{{ Form::hidden('id', Request::segment(3) ) }}
		@endif
		<!--{{$errors}}-->
		<div class="col-md-8 form-container edit-form">			
			<div id="page-1" class="page title-text">
				
				<div class="form-group {{$errors->first('body') ? 'has-error' : '' }}">
					{{ Form::label('body','Your Story', array('class'=>'control-label')) }}
					{{ Form::textarea('body', $post->body, array('class'=>'form-control', 'required', 'minlength' =>'5')) }}
					<span class="error">{{ $errors->first('body') }}</span>
				</div>
				
				<div class="clearfix"></div>
					
				<div class="col-md-12 submit">
					<div class="form-group">
					
						{{ Form::submit('Update') }}
					
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		
	{{ Form::close() }}
</div>
@stop