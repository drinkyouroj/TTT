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


{{--The main content area--}}
@section('main')
<div class="row">
	{{ Form::open(array('url'=>'profile/submitpost', 'method'=>'post','class'=>'form-horizontal','role'=>'form'))}}
		
		{{--Get the update bit done once we have the edit function down --}}
		
		@if(Request::segment(2) == 'editpost')
			{{ Form::hidden('id', Request::segment(3) ) }}
		@endif
		
		<div class="col-md-8 col-md-offset-1">
			<div class="form-group">
				{{ Form::label('title','Title') }}
				{{ Form::text('title', null, array('class'=>'form-control')) }}
				{{ $errors->first('title') }}
			</div>
			
			<div class="form-group">
				{{ Form::label('tagline_1','Tag Line 1', array('class'=>'control-label')) }}
				{{ Form::text('tagline_1', null, array('class'=>'form-control') ) }}
				{{ $errors->first('tagline_1') }}
			</div>
			
			<div class="form-group">
				{{ Form::label('tagline_2','Tag Line 2', array('class'=>'control-label')) }}
				{{ Form::text('tagline_2', null, array('class'=>'form-control')) }}
				{{ $errors->first('tagline_2') }}
			</div>
			
			<div class="form-group">
				{{ Form::label('tagline_3','Tag Line 3', array('class'=>'control-label')) }}
				{{ Form::text('tagline_3', null, array('class'=>'form-control')) }}
				{{ $errors->first('tagline_3') }}
			</div>
			
			<div class="form-group">
				{{ Form::label('body','Your Story', array('class'=>'control-label')) }}
				{{ Form::textarea('body', null, array('class'=>'form-control')) }}
				{{ $errors->first('body') }}
			</div>
			
			Put in the photo system right here!
			
			<div class="form-group">
				{{ Form::label('story_type','Story Type', array('class'=>'control-label')) }}
				{{ Form::select('story_type', array( 'story'=>'Story',
												'advice'=>'Advice',
												'thought'=>'Thought'), null, array('class'=>'form-control')) }}
				{{ $errors->first('story_type') }}
			</div>
			
			{{--This foreach is just for creating the correct format for the multiselect--}}
			{? $category_select = array(); ?}
			@foreach($categories as $category)
				{? $category_select[$category->id] = $category->title ?}
			@endforeach
			
			
			<div class="form-group">
				{{ Form::label('category','Story Category', array('class'=>'control-label')) }}
				{{ Form::select('category', $category_select , 1, array('class'=>'form-control')  ) }}
				{{ $errors->first('category') }}
			</div>
			
			<div class="form-group">
			@if(Request::segment(2) == 'newpost')
				{{ Form::submit('Submit') }}
			@else
				{{ Form::submit('Update') }}
			@endif
			</div>
		</div>
		
	{{ Form::close() }}
</div>
@stop