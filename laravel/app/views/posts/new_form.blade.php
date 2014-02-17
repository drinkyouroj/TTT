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


@section('css')
	@parent
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/views/post_form.css">
@stop

@section('js')
	@parent
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.validate.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/additional-methods.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/scrollto.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/localscrollto.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile/form.js"></script>
@stop

{{--The main content area--}}
@section('main')
<div class="row post-form">
	
	{{ Form::open(array('url'=>'profile/submitpost', 'method'=>'post','class'=>'form-horizontal','role'=>'form'))}}
		
		{{--Get the update bit done once we have the edit function down --}}
		
		@if(Request::segment(2) == 'editpost')
			{{ Form::hidden('id', Request::segment(3) ) }}
		@endif
		
		<div class="col-md-8 main-inputs">			
			
			<div class="title {{$errors->first('title') ? 'has-error' : '' }}">
				{{ Form::label('title','Title') }}
				<a href="#" data-toggle="tooltip" title="Need to pick a title!">?</a>
				{{ Form::text('title', Input::old('title'), array('class'=>'form-control title', 'required', 'minlength' =>'5', 'maxlength' => '30')) }}
				<span class="error">{{ $errors->first('title') }}</span>
			</div>
		
			<div class="photos">
				<div class="photo-system">
					<div class="input-append">
						Search Photos <input type="text" class="span2 search-query" placeholder="type in more than 3 characters!">
						<a class="btn activate-search">Search</a>
						<a class="btn reset-search hidden">Reset</a>
					</div>
					
					<div class="photo-results">
						
					</div>
					
					<div class="chosen">
						<div class="photo-processor" style="display:none;">
							<h4>Filters:</h4>
							<img src="{{Config::get('app.url')}}/img/photos/nofilter.png" data-process="nofilter"/>
							<img src="{{Config::get('app.url')}}/img/photos/gotham.png" data-process="Gotham"/>
							<img src="{{Config::get('app.url')}}/img/photos/toaster.png" data-process="Toaster"/>
							<img src="{{Config::get('app.url')}}/img/photos/nashville.png" data-process="Nashville"/>
							<img src="{{Config::get('app.url')}}/img/photos/lomo.png" data-process="Lomo"/>
							<img src="{{Config::get('app.url')}}/img/photos/kelvin.png" data-process="Kelvin"/>
							<img src="{{Config::get('app.url')}}/img/photos/tilt_shift.png" data-process="TiltShift"/>
						</div>
						<div class="chosen-label">Chosen</div>
						<div class="processed-label">Processed</div>
						<div class="photo-chosen">
							
						</div>
						<div class="photo-processed">
							
						</div>
						
					</div>
				</div>
				
				<input class="processed-image" type="hidden" name="image" value="">
				
				<div class="clearfix"></div>
			</div>
			
			<div class="story {{$errors->first('body') ? 'has-error' : '' }}">
				{{ Form::label('body','Your Story', array('class'=>'control-label')) }}
				{{ Form::textarea('body', Input::old('body'), array('class'=>'form-control', 'required', 'minlength' =>'5')) }}
				<span class="error">{{ $errors->first('body') }}</span>
			</div>
		
		
		
			
		</div>

		<div class="col-md-4 options">
			<div class="{{$errors->first('story_type') ? 'has-error' : '' }}">
				{{ Form::label('story_type','Story Type', array('class'=>'control-label', 'required')) }}
				<a href="#" data-toggle="tooltip" title="Choose the type of story">?</a>
				{{ Form::select('story_type', array( 'story'=>'Story',
												'advice'=>'Advice',
												'thought'=>'Thought'), Input::old('story_type'), array('class'=>'form-control')) }}
				<span class="error">{{ $errors->first('story_type') }}</span>
			</div>
			
				
			<div class="{{$errors->first('tagline_1') ? 'has-error' : '' }}">
				{{ Form::label('tagline_1','Tag Line 1', array('class'=>'control-label')) }}
				<a href="#" data-toggle="tooltip" title="Taglines define what your story might be in less than 3 words per tag">?</a>
				{{ Form::text('tagline_1', Input::old('tagline_1'), array('class'=>'form-control', 'required', 'maxlength' => '20') ) }}
				<span class="error">{{ $errors->first('tagline_1') }}</span>
			</div>
			
			<div class="{{$errors->first('tagline_2') ? 'has-error' : '' }}">
				{{ Form::label('tagline_2','Tag Line 2', array('class'=>'control-label')) }}
				{{ Form::text('tagline_2', Input::old('tagline_2'), array('class'=>'form-control', 'required', 'maxlength' => '20')) }}
				<span class="error">{{ $errors->first('tagline_2') }}</span>
			</div>
			
			<div class="{{$errors->first('tagline_3') ? 'has-error' : '' }}">
				{{ Form::label('tagline_3','Tag Line 3', array('class'=>'control-label')) }}
				{{ Form::text('tagline_3', Input::old('tagline_3'), array('class'=>'form-control', 'required', 'maxlength' => '20')) }}
				<span class="error">{{ $errors->first('tagline_3') }}</span>
			</div>
			
			
			<div class="category">
				{{--This foreach is just for creating the correct format for the multiselect--}}
				{? $category_select = array(); ?}
				@foreach($categories as $category)
					{? $category_select[$category->id] = $category->title ?}
				@endforeach
				
				{{ Form::label('category','Story Category', array('class'=>'control-label')) }}
				<a href="#" data-toggle="tooltip" title="Choose 3 categories that this story might fit in.">?</a>
				<!--
				{{ Form::select('category[]', $category_select , 1, array('class'=>'form-control', 'multiple'=>'multiple', 'required')  ) }}
				-->
				<br/>
				<div class="warning hidden">You can't select more than 3 categories.</div>
				<ul>
				@foreach($categories as $category)
					<li>
						<label for="cat-{{$category->id}}">{{$category->title}}</label>
						{{Form::checkbox('category[]', $category->id, 0, array('class'=>'category','id' => 'cat-'.$category->id) ) }}
					</li>
				@endforeach
				</ul>				
			 	
				<span class="error">{{ $errors->first('category') }}</span>
			</div>
			
			
			<div class="submit">
				
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