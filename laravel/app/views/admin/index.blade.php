@extends('layouts.admin')


@section('left_sidebar')


	<div class="add-category">
		<h2>Add New Category</h2>
		{{ Form::open(array('url' => 'admin/addcategory')) }}
			
			{{ Form::label('title', 'Title') }}
			{{ Form::text('title') }}
			
			{{ Form::label('description', 'Description') }}
			{{ Form::textarea('description') }}
			
			{{ Form::submit('Add!') }}
    	{{ Form::close() }}
	</div>
	
	<div class="remove-category">
		<h2>Remove Categories</h2>
		{{ Form::open(array('url' => 'admin/delcategory')) }}
			<ul>
			@foreach($categories as $category)
				<li>
					{{$category->title}}
					{{Form::checkbox('category[]', $category->id);}}
					
				</li>
			@endforeach
			</ul>
			{{ Form::submit('Delete!') }}
    	{{ Form::close() }}
	</div>
	
@stop


@section('main')
	<h1>THE BEST ADMIN SYSTEM EVER!</h1>
	<div class="col-md-12">
		<h2>Send a Message to all the users</h2>
		{{ Form::open(array('url' => 'admin/messageall')) }}
			{{ Form::textarea('body', Input::old('message'), array('class'=>'form-control', 'required', 'minlength' =>'5')) }}
			{{ Form::submit('Send') }}
    	{{ Form::close() }}
	</div>

	<div class="col-md-12">
		<h2>Currently Featured Articles</h2>
	@foreach($featured as $k=>$post)
		<div class="col-md-4">
			@include('partials.featured-item')
		</div>
	@endforeach
	</div>
@stop