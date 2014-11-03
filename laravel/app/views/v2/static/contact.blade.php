@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css?v={{$version}}">
@stop

@section('title')
	Contact | Sondry
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2  info-container contact">
			<h1>Contact Us</h1>
			<p>If you have any questions, feedback, or concerns please don’t hesitate to reach out to us.</p>
			<p>Here are some options to get in touch with us:</p>
			<div class="emails">
				<p><span>Report Bugs</span> <a href="mailto:bugs@sondry.com">bugs@sondry.com</a></p>
				<p><span>Press Enquiries:</span> <a href="mailto:press@sondry.com">press@sondry.com</a></p>	
				<p><span>General Feedback/Help:</span> <a href="team@sondry.com">team@sondry.com</a></p>	
			</div>
		</div>
	</div>
</div>
@stop
