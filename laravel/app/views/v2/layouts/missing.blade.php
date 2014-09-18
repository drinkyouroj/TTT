@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/static.css">
@stop

@section('title')
	404 - Can't Find it! | The Twothousand Times
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3 info-container about-container nocontent-container">
			<h1>We can’t find the content you’re looking for!</h1>
			<p>You may have entered a broken link, double check and try again.</p>
		</div>
	</div>
</div>
	
	
@stop
