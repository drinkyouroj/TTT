@extends('v2.layouts.master')

	@section('title')
		@if(!$edit)
			New Post | Two Thousand Times
		@else
			Edit Post | Two Thousand Times
		@endif
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/posts/post_form.css">
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/js/vendor/editor/css/medium-editor.min.css">
		<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/js/vendor/editor/css/themes/default.css">
	@stop

	@section('js')


		<!--New script-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/validation/jquery.validate.min.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/editor/js/medium-editor.min.js"></script>
		{{-- <script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post/handlePaste.js"></script> --}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post/post_input.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post/post_photo.js"></script>

		
		@if($edit)
			<script type="text/javascript">
				save_post.post_id = {{$post->id}};
			</script>
		@endif
	@stop

	@section('content')

	{{--Below packs the data into the right place--}}
	<?php 
		
		$title = (!empty($post->title)) ? $post->title : '';
		$story_type = (!empty($post->story_type)) ? $post->story_type : '';

		$tagline_1 = (!empty($post->tagline_1)) ? $post->tagline_1 : '';
		$tagline_2 = (!empty($post->tagline_2)) ? $post->tagline_2 : '';
		$tagline_3 = (!empty($post->tagline_3)) ? $post->tagline_3 : '';

		$image = (!empty($post->image)) ? $post->image : ''; 
		$body = (!empty($post->body)) ? $post->body : '';
		$published = (!empty($post->published)) ? $post->published : false;
		
	?>


		{{ Form::open(array('class'=>'form-horizontal','role'=>'form', 'class'=>'post_input')) }}
		{{--hidden inputs--}}
		<input name="image" type="hidden" class="processed-image" required value="{{$image}}">
		@if($edit)
			<input name="id" type="hidden" value="{{$post->id}}">
		@endif

		<div class="top-controls">

			{{--Top Fixed Controls--}}
			<div class="controls-wrapper">
				<div class="container controls-container">
					<div class="row">
						<div class="col-sm-4 preview">
							<a class="preview-button @if(!$edit) hidden @endif" data-toggle="modal" data-target="#previewModal">
								Preview
							</a>
							<a 	class="article-link @if(!$edit) hidden @endif"
								@if($edit)
								href="{{Config::get('app.url')}}/posts/{{$post->alias}}"
								@endif
								>
								Link to Post
							</a>
						</div>
						<div class="col-sm-4 post-category">
							<a class="categorization">
								Post Type / Categories
							</a>
						</div>

						<div class="col-sm-4 draft-publish">
							
							{{--Prevent users from being able to set something published as draft--}}
							@if( !($edit && $published) ) {{--Note the encasing of edit and published--}}
							<a class="save-draft">
								Draft
							</a>
							@endif
						
							<a class="submit-post">
								@if(!$edit)
									Publish
								@else
									Update
								@endif
							</a>
							
						</div>

					</div>
				</div>
			</div>

			<div class="category-wrapper">
				<div class="category-container container">
					<div class="row">
						{{--Gotta put this in the middle--}}
						<div class="col-md-8 col-md-offset-2">
							<div class="row">
								<div class="col-md-4 story-type-box">
									<div class="{{$errors->first('story_type') ? 'has-error' : '' }}">
									{{ Form::label('story_type','Post Type', array('class'=>'control-label', 'required')) }}
									<a href="#" data-toggle="tooltip" title="Choose the type of story">?</a>
									{{ Form::select('story_type', array( 'story'=>'Story',
																	'advice'=>'Advice',
																	'thought'=>'Thought'), $story_type, array('class'=>'form-control')) }}
									<span class="error">{{ $errors->first('story_type') }}</span>
								</div>
								</div>
								<div class="col-md-8 category-box">
									{{ Form::label('category','Post Category', array('class'=>'control-label')) }}
									<a href="#" data-toggle="tooltip" title="Choose 3 categories that this story might fit in.">?</a>
									<br/>
									<div class="warning hidden">You can't select more than 3 categories.</div>

									<ul>
										{? $checked = ''; ?}
										@foreach($categories as $category)
											<li class="col-md-6">
												@if($edit)
													@if(in_array($category->id, unserialize($post->category) ) )
													{? $checked = 'checked'?}
													@else
													{? $checked = '' ?}
													@endif
												@endif											
												{{Form::checkbox('category[]', $category->id, 0, array('class'=>'category','id' => 'cat-'.$category->id, $checked ) ) }}
												<label for="cat-{{$category->id}}" data-toggle="tooltip" data-placement="top" title="{{$category->description}}">{{$category->title}}</label>
											</li>
										@endforeach
									</ul>
									
									<span class="error">{{ $errors->first('category') }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 close-category">
									<a class="btn-flat-dark-gray" href="">
										OK
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div> {{--Top Controls--}}


			{{--Wrapper is to be set as 100% and background black--}}
			<div class="top-submit-wrapper">
				{{--The big container so that we can assign the images to it. max-width 1440 or something like that--}}
				<div class="top-submit-container container" 
					
					>
					<div class="row">
						<div class="col-md-4 form-heading">
							{{--Title Input--}}
							<div class="title {{$errors->first('title') ? 'has-error' : '' }}">
								{{ Form::label('title','Title') }}
								<a href="#" data-toggle="tooltip" title="Need to pick a title!"></a>
								{{ Form::textarea('title', $title, array('class'=>'form-control input-lg title', 'required', 'minlength' =>'5', 'maxlength' => '40', 'placeholder'=>'My New Post')) }}
								<span class="error">{{ $errors->first('title') }}</span>
							</div>

							{{--Tag Input--}}
							<div class="tags">
								<div class="tag {{$errors->first('tagline_1') ? 'has-error' : '' }}">
									
									{{ Form::text('tagline_1', $tagline_1, array('class'=>'form-control', 'required', 'maxlength' => '20', 'placeholder'=>'Tag 1') ) }}
									<span class="error">{{ $errors->first('tagline_1') }}</span>
								</div>
					
								<div class="tag {{$errors->first('tagline_2') ? 'has-error' : '' }}">
									{{ Form::text('tagline_2', $tagline_2, array('class'=>'form-control', 'required', 'maxlength' => '20', 'placeholder'=>'Tag 2')) }}
									<span class="error">{{ $errors->first('tagline_2') }}</span>
								</div>
					
								<div class="tag {{$errors->first('tagline_3') ? 'has-error' : '' }}">
									{{ Form::text('tagline_3', $tagline_3, array('class'=>'form-control', 'required', 'maxlength' => '20', 'placeholder'=>'Tag 3')) }}
									<span class="error">{{ $errors->first('tagline_3') }}</span>
								</div>
								<br/>
								{{ Form::label('tagline_1','Tags', array('class'=>'control-label')) }}
								<a class="tags-tooltip" href="#" data-toggle="tooltip" title="Tags define what your story might be in less than 3 words per tag">?</a>
							</div>
						</div>
						<div class="col-md-8 image-system"
							@if(!$edit)
								style="background-image: url('{{Config::get('app.url')}}/images/posts/sample-img2.jpg');"
							@else
								style="background-image: url('{{Config::get('app.url')}}/uploads/final_images/{{$image}}');"
							@endif
							>
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

							<div class="image-edit">
								<a href="#image" class="image-select-modal btn-flat-white" data-toggle="modal" data-target="#imageModal">
									Edit Image
								</a>
							</div>

							<div class="clearfix"></div>
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
								{{ Form::textarea('body', $body, array('class'=>'form-control normal-input', 'required', 'minlength' =>'5')) }}
								<div class="text-input editable" name="body" required>
									@if($edit) {{$body}} @endif
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
						<h4 class="modal-title" id="imageModalLabel">
							Search for an image.
						</h4>
					</div>
					<div class="modal-body">
				      	<div class="photos">
							<div class="photo-system">
								<div class="input-append">
									<input type="text" class="span2 search-query" placeholder="Search Photos*">
									<a class="btn activate-search btn-flat-gray">Search</a>
									<a class="btn reset-search btn-flat-blue hidden">Reset</a>
									<br/>
									<br/>
									*must be more than 3 characters.
								</div>
								
								<div class="photo-results">
									
								</div>
								
								<div class="chosen">
									<div class="row">
										<div class="col-md-12 col-sm-12 processor-container">
											<div class="photo-processor" style="display:none;">
												<h4>Choose a Filter</h4>
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
										<div class="col-md-12 col-sm-12 ">
											<div class="chosen-label"></div>
											<div class="processed-label"></div>
											<div class="photo-chosen"></div>
										</div>
										<div class="clearfix"></div>

									</div>
								</div>
							</div>
							
							<input class="processed-image" type="hidden" name="image" value="">
							
							<div class="clearfix"></div>
						</div>

					</div><!--End of Modal Body-->
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-flat-white" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div>


		{{--Preview Modal--}}
		<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="previewModalLabel">
							Preview
						</h4>
					</div>
					<div class="modal-body">
						Preview of the post to come here soon-ish.
					</div><!--End of Modal Body-->
				</div>
			</div>
		</div>




	@stop