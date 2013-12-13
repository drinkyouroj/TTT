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
	
	<div class="col-md-10 col-md-offset-1">
		<div class="generic-listing">
			@if(!is_string($posts))
				{?$c = 0?}
				{?$total = count($posts)?}
				@foreach($posts as $k => $post)
					@if( $c == 0 )
						<div class="row">
					@endif
						<div class="col-md-4">
							<div class="generic-item equal-height">
								<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
								<h4><span>by</span> {{link_to('profile/'.$post->user->username, $post->user->username)}}</h4>
								@if($post->image)
								<div class="the-image">
									<a href="{{ URL::to('posts/'.$post->alias) }}">
										<img src="{{Config::get('app.url')}}/uploads/final_images/{{$post->image}}">
									</a>
								</div>
								@endif
								<div class="the-content">
									{{ substr($post->body, 0, 50) }}...
									<!--{{ link_to('posts/'.$post->alias, 'read on.') }}-->
								</div>
								<div class="the-tags">
									{{$post->tagline_1}} | 
									{{$post->tagline_2}} | 
									{{$post->tagline_3}}
								</div>
							</div>
						</div>
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