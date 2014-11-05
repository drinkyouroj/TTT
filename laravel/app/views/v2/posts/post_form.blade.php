@extends('v2.layouts.master')

	@section('title')
		@if(!$edit)
			New Post | Sondry
		@else
			Edit Post | Sondry
		@endif
	@stop

	@section('css')
		<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/posts/post_form.css?v={{$version}}">
		<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/js/vendor/editor/css/medium-editor.min.css">
		<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/js/vendor/editor/css/themes/default.css">
	@stop

	@section('js')

		@include( 'v2/partials/photo-input' )
		@include( 'v2/posts/partials/post-preview-handlebars-template' )

		<!--New script-->
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/validation/jquery.validate.min.js"></script>
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/editor/js/medium-editor.min.js"></script>
		{{-- <script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/post/handlePaste.js"></script> --}}
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/post/post_input.js?v={{$version}}"></script>
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/photo/photo.js?v={{$version}}"></script>
		{{--
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/post/post_photo.js"></script>
		--}}
		
		@if($edit)
			<script type="text/javascript">
				save_post.post_id = {{$post->id}};
				save_post.draft = {{$post->draft}};
				save_post.published = {{$post->published}};
			</script>
		@else
			<script type="text/javascript">
				save_post.post_id = 0;
				save_post.draft = 1;
				save_post.published = 0;
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
						<div class="col-sm-4 preview hidden-xs">
							<a class="preview-button" data-toggle="modal" data-target="#previewModal">
								Preview
							</a>
							<a class="article-link @if(!$edit) hidden @endif"
								@if($edit)
								href="{{Config::get('app.url')}}/posts/{{$post->alias}}"
								@endif
								>
								Link to Post
							</a>
						</div>
						<div class="col-sm-4 col-xs-6 post-category">
							<a class="categorization">
								Post Type / Categories
							</a>
						</div>

						<div class="col-sm-4 col-xs-6 draft-publish">
							
							{{--Prevent users from being able to set something published as draft--}}
							@if( !($edit && $published) ) {{--Note the encasing of edit and published--}}
							<a class="save-draft">
								Draft
							</a>
							@endif
						
							<a class="submit-post">
								Publish
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
									{{ Form::select('story_type', array( 
																	''=>'(Choose One)',
																	'story'=>'Story',
																	'thought'=>'Thought',
																	'advice'=>'Advice'), $story_type, array('class'=>'story-type form-control')) }}
									<span class="error">{{ $errors->first('story_type') }}</span>
								</div>
								</div>
								<div class="col-md-8 category-box">
									{{ Form::label('category','Post Category', array('class'=>'control-label')) }}
									<span>(choose up to 2)</span>
									<div class="warning hidden">You can only select up to 2 categories.</div>

									<ul>
										<?php
											$checked = '';
											if($edit) {
												$unserialized = unserialize($post->category);
												$unserialized = is_array($unserialized) ? $unserialized : array();
											}
										?>
										@foreach($categories as $category)
											<li class="col-md-6 col-xs-6">
												@if($edit)
													@if( in_array($category->id, $unserialized ) )
													<?php $checked = 'checked'?>
													@else
													<?php $checked = '' ?>
													@endif
												@endif											
												{{Form::checkbox('category[]', $category->id, 0, array('class'=>'category','id' => 'cat-'.$category->id, $checked, 'data-title' => $category->title ) ) }}
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
				<div class="top-submit-container">
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
									
									{{ Form::text('tagline_1', $tagline_1, array('class'=>'form-control', 'maxlength' => '20', 'placeholder'=>'Dib 1') ) }}
									<span class="error">{{ $errors->first('tagline_1') }}</span>
								</div>
					
								<div class="tag {{$errors->first('tagline_2') ? 'has-error' : '' }}">
									{{ Form::text('tagline_2', $tagline_2, array('class'=>'form-control', 'maxlength' => '20', 'placeholder'=>'Dib 2')) }}
									<span class="error">{{ $errors->first('tagline_2') }}</span>
								</div>
					
								<div class="tag {{$errors->first('tagline_3') ? 'has-error' : '' }}">
									{{ Form::text('tagline_3', $tagline_3, array('class'=>'form-control', 'maxlength' => '20', 'placeholder'=>'Dib 3')) }}
									<span class="error">{{ $errors->first('tagline_3') }}</span>
								</div>
								<br/>
								{{ Form::label('tagline_1','What&#39;s the Gist?', array('class'=>'control-label')) }}
								<a class="tags-tooltip" href="#" data-toggle="tooltip" title="Tags define what your story might be in less than 3 words per tag">?</a>
							</div>
						</div>
						<div class="col-md-8 image-system"
							@if(!$edit)
								style="background-image: url('{{Config::get('app.url')}}/images/posts/sample-image.jpg');"
							@else
								style="background-image: url('{{Config::get('app.url')}}/uploads/final_images/{{$image}}');"
							@endif
							>
							<?php 
								if($edit) {
									$draft = $post->draft;
								} else {
									$draft = false;
								}
							?>
							{{--Select your image --}}
							<div class="image-select <?php if ( $draft ) { echo 'hidden'; } ?>">
								{{ Form::label('image','Image') }}
								<div class="image-select-modal image-link" data-toggle="modal" data-target="#imageModal">
									<img src="{{Config::get('app.url')}}/images/posts/gold-plus.png">
									<br/>
									<span>
										Select Your Image.
									</span>
								</div>
							</div>

							<div class="image-edit" <?php if ( $draft ) { echo 'style="display:block"'; } ?>>
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
						<div class="col-md-10 col-md-offset-1">
							<div class="story {{$errors->first('body') ? 'has-error' : '' }}">
								{{ Form::label('body','Post', array('class'=>'control-label')) }}
								{{ Form::textarea('body', $body, array('class'=>'form-control normal-input', 'required', 'minlength' =>'5')) }}
								<div id="post-content-editable" class="text-input editable" name="body" required>
									@if($edit) {{$body}} @endif
								</div>

								<span class="error">{{ $errors->first('body') }}</span>
							</div>
						</div>
					</div>
				</div>
			</div>

		{{ Form::close() }}
		<div class="bottom-controls-container container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="info">
						{{ Form::label('info','Info') }}
						<a class="categorization">
							Post Type / Categories
						</a>
					</div>
					<span class="draft-publish">
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
								Publish
							@endif
						</a>
					</span>
				</div>
			</div>
		</div>


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
						
					</div><!--End of Modal Body-->
					<div class="modal-footer hidden">
						<button type="button" class="btn btn-default btn-flat-white pull-right accept-photo" data-dismiss="modal">OK</button>
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
						
					</div><!--End of Modal Body-->
				</div>
			</div>
		</div>




	@stop