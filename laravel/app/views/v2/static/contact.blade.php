@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css">
@stop

@section('title')
	Contact
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2  info-container contact">
			<h1>Contact Us</h1>
			<p>If you have any questions, feedback, or concerns please don’t hesitate to reach out to us.</p>
			<p>Here are some options to get in touch with us:</p>
			<div class="emails">
				<p><span>Report Bugs</span> <a href="mailto:bugs@twothousandtimes.com">bugs@twothousandtimes.com</a></p>
				<p><span>Press Enquiries:</span> <a href="mailto:press@twothousandtimes.com">press@twothousandtimes.com</a></p>	
				<p><span>General Feedback/Help:</span> <a href="team@twothousandtimes.com">team@twothousandtimes.com</a></p>	
			</div>
		</div>
	</div>
</div>
@stop
