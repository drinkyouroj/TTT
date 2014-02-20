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
	<script type="text/javascript">
		window.edit_id = {{$post->id}};
	</script>
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
				
				<div class="form-group {{$errors->first('title') ? 'has-error' : '' }}">
					{{ Form::label('title','Title') }}
					<a href="#" data-toggle="tooltip" title="Need to pick a title!">?</a>
					{{ Form::text('title', $post->title, array('class'=>'form-control title', 'required', 'minlength' =>'5')) }}
					<span class="error">{{ $errors->first('title') }}</span>
				</div>
				
				<div class="form-group {{$errors->first('body') ? 'has-error' : '' }}">
					{{ Form::label('body','Your Story', array('class'=>'control-label')) }}
					{{ Form::textarea('body', $post->body, array('class'=>'form-control', 'required', 'minlength' =>'5')) }}
					<span class="error">{{ $errors->first('body') }}</span>
				</div>
				
				<div class="form-group {{$errors->first('tagline_1') ? 'has-error' : '' }}">
					{{ Form::label('tagline_1','Tag Line 1', array('class'=>'control-label')) }}
					<a href="#" data-toggle="tooltip" title="Taglines define what your story might be in less than 3 words per tag">?</a>
					{{ Form::text('tagline_1', $post->tagline_1, array('class'=>'form-control', 'required') ) }}
					<span class="error">{{ $errors->first('tagline_1') }}</span>
				</div>
				
				<div class="form-group {{$errors->first('tagline_2') ? 'has-error' : '' }}">
					{{ Form::label('tagline_2','Tag Line 2', array('class'=>'control-label')) }}
					{{ Form::text('tagline_2', $post->tagline_2, array('class'=>'form-control', 'required')) }}
					<span class="error">{{ $errors->first('tagline_2') }}</span>
				</div>
				
				<div class="form-group {{$errors->first('tagline_3') ? 'has-error' : '' }}">
					{{ Form::label('tagline_3','Tag Line 3', array('class'=>'control-label')) }}
					{{ Form::text('tagline_3', $post->tagline_3, array('class'=>'form-control', 'required')) }}
					<span class="error">{{ $errors->first('tagline_3') }}</span>
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