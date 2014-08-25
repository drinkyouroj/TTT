@extends('v2.layouts.master')

	@section('title')
		New Post | Two Thousand Times
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/posts/post_form.css">
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/js/vendor/editor/css/medium-editor.min.css">
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/js/vendor/editor/css/themes/default.css">
	@stop

	@section('js')
		<!---old script-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/new-post.js"></script>

		<!--New script-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/editor/js/medium-editor.min.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post_input.js"></script>
	@stop

	@section('content')

		{{ Form::open(array('url'=>'profile/submitpost', 'method'=>'post','class'=>'form-horizontal','role'=>'form')) }}

			{{--Top Fixed Controls--}}
			<div class="controls-wrapper">
				<div class="container controls-container">
					<div class="row">

						<div class="col-sm-4 col-sm-offset-4 post-category">
							<button class="post-type">
								Post Type
							</button>
						
							<button class="categories">
								Categories
							</button>
						</div>

						<div class="col-sm-4 draft-publish">
							{{--Must place if statements for the submit buttons.--}}
							
							<button class="save-draft">
								Draft
							</button>
						
							<button class="submit-post">
								Submit
							</button>
							
						</div>

					</div>
				</div>
			</div>

		

			{{--Wrapper is to be set as 100% and background black--}}
			<div class="top-submit-wrapper">
				{{--The big container so that we can assign the images to it. max-width 1440 or something like that--}}
				<div class="top-submit-container container" style="background-image: url('{{Config::get('app.url')}}/img/photos/nashville.png');">
					<div class="row">
						<div class="col-md-8">
							{{--Title Input--}}
							<div class="title {{$errors->first('title') ? 'has-error' : '' }}">
								{{ Form::label('title','Title') }}
								<a href="#" data-toggle="tooltip" title="Need to pick a title!"></a>
								{{ Form::text('title', Input::old('title'), array('class'=>'form-control input-lg title', 'required', 'minlength' =>'5', 'maxlength' => '40')) }}
								<span class="error">{{ $errors->first('title') }}</span>
							</div>
						</div>
						<div class="col-md-5">
							{{--TL;DR Input--}}
							<div class="tldr {{$errors->first('tldr') ? 'has-error' : '' }}">
								
								{{ Form::text('tldr', Input::old('tldr'), array('class'=>'form-control', 'required', 'maxlength' => '40') ) }}
								<a href="#" data-toggle="tooltip" title="TLDR define what your story might be in less than 40 characters">?</a>
								{{ Form::label('tldr','TLDR', array('class'=>'control-label')) }}
								
								<span class="error">{{ $errors->first('tldr') }}</span>
							</div>
						</div>

						<div class="col-md-12">
							{{--Select your image --}}
							<div class="image-select">
								<a href="#image" class="image-select-modal" data-toggle="modal" data-target="#imageModal">
									<img src="{{Config::get('app.url')}}/images/posts/add-image.png">
									<br/>
									<span>
										Select Your Image.
									</span>
								</a>
							</div>
						</div>

					</div>	
				</div>
			</div>

			{{--Actual input field--}}
			<div class="body-submit-wrapper">
				<div class="body-submit-container container">
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div class="story {{$errors->first('body') ? 'has-error' : '' }}">
								{{ Form::label('body','Story', array('class'=>'control-label')) }}
								{{ Form::textarea('body', Input::old('body'), array('class'=>'form-control normal-input', 'required', 'minlength' =>'5')) }}
								<div class="text-input editable">
									
								</div>

								<span class="error">{{ $errors->first('body') }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>

		{{ Form::close() }}


		{{--Modal for the image selection system should go here--}}

		<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel">Modal title</h4>
					</div>
					<div class="modal-body">
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
									<div class="row">
										<div class="col-md-12 col-sm-12 processor-container">
											<div class="photo-processor" style="display:none;">
												<h4>Filters:</h4>
												<img src="{{Config::get('app.url')}}/img/photos/nofilter.png" data-process="nofilter"/>
												<img src="{{Config::get('app.url')}}/img/photos/gotham.png" data-process="Gotham"/>
												<img src="{{Config::get('app.url')}}/img/photos/toaster.png" data-process="Toaster"/>
												<img src="{{Config::get('app.url')}}/img/photos/nashville.png" data-process="Nashville"/>
												<img src="{{Config::get('app.url')}}/img/photos/lomo.png" data-process="Lomo"/>
												<img src="{{Config::get('app.url')}}/img/photos/kelvin.png" data-process="Kelvin"/>
												<img src="{{Config::get('app.url')}}/img/photos/tilt_shift.png" data-process="TiltShift"/>
											<div class="clearfix"></div>
											</div>
										</div>
										<div class="col-md-12 col-sm-12">
											<div class="chosen-label"></div>
											<div class="processed-label"></div>
											<div class="photo-chosen">
										</div>
									</div>
								<div class="clearfix"></div>
								</div>
								</div>
							</div>
							
							<input class="processed-image" type="hidden" name="image" value="">
							
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button
>					</div>
				</div>
			</div>
		</div>

	@stop