@extends('layouts.master')

@section('js')
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/generic-listing.js"></script>
@stop

@section('filters')
	@include('partials/generic-filter')
@stop

@section('content')
	
	@if(Auth::check())
		
	
	@endif
	
	<div class="col-md-12">
		<div class="generic-listing">
			@if(!is_string($posts))
				{?$c = 0?}
				{?$total = count($posts)?}
				@foreach($posts as $k => $post)
					@if( $c == 0 )
						<div class="row">
					@endif
						@if(isset($post->id))
							@include('partials/generic-item')
						@endif
					{?$c++?}
					@if($c == 3 || $k+1 == $total)
						</div>
						{?$c = 0 ?}
					@endif
				@endforeach
			@else
				<div class="col-md-12">
					{{$posts}}
				</div>
			@endif
		</div>
	</div>
@stop