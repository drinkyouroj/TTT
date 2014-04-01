@extends('layouts.master')

@section('js')
	<script type="text/javascript">
		window.category = '{{Request::segment(2)}}';
		window.filter = '{{Request::segment(3)}}';
	</script>
	
	
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-v1.3.0.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/generic-listing.js"></script>
	
	@include('partials/generic-handlebar-item')
@stop

@section('filters')
	@include('partials/generic-filter')
@stop

@if(isset($cat_title))
	{? $title =  $cat_title ?}
@else
	{? $title =  'Search' ?}
@endif


@section('title')
	{{$title}} | The Twothousand Times 
@stop

@section('content')
	<div class="col-md-12">
		<div class="generic-listing">
			@if(!is_string($posts))
				<div class="row">
				@foreach($posts as $k => $post)
					@if(isset($post->id) && isset($post->user->username))
						@include('partials/generic-item')
					@endif
				@endforeach
				</div>
			@else
				<div class="col-md-12">
					{{$posts}}
				</div>
			@endif
		</div>
	</div>
@stop