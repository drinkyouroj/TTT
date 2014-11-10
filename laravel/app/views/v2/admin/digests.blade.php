@extends('v2.layouts.master')

@section('title')
	Admin - Digest Stats | Sondry
@stop

@section('css')
	<!-- <link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/category/category.css?v={{$version}}"> -->
@stop

@section('js')
	<!-- <script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/category/category.js?v={{$version}}"></script> -->
@stop

@section('content')
<div class="container">
	<h1>Weekly Digests</h1>

	@if( count($digests) )
		@foreach ( $digests as $digest )
			<h2>Weekly Digest - {{ date_format( $digest->created_at, 'l F jS Y' ) }}</h2>
			<table class="table">
				<thead>
					<tr>
						<th>Post Alias</th>
						<th>Click Throughs</th>
					</tr>
				</thead>
				<tbody>
					@foreach( $digest->posts as $digest_post )
						<tr class="">
							<td> <a href="{{ URL::to('posts/'.$digest_post['post_alias']) }}">{{$digest_post['post_alias']}}</a></td>
							<td>{{$digest_post['clicks']}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<hr>
		@endforeach
	@endif

</div>
@stop
