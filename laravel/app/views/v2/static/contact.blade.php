@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css">
@stop

@section('title')
	Contact
@stop

@section('content')

<div class="col-md-6 col-md-offset-3  info-container">
<h2>Contact</h2>
	<p>If you have any questions, feedback, or concerns please don’t hesitate to reach out to us at contact@twothousandtimes.com. Interaction with you is, and always will be a primary focus. </p>
	<p>The Two Thousand Times would love to hear directly from you. Here are some options to get in touch with us: </p>
	<p><span>Report Spam or Inappropriate Content:</span> <a href="mailto:report@twothousandtimes.com">report@twothousandtimes.com</a></p>	
	<p><span>Feedback:</span> <a href="mailto:feedback@twothousandtimes.com">feedback@twothousandtimes.com</a></p>	
	<p><span>Press Enquiries:</span> <a href="mailto:press@twothousandtimes.com">press@twothousandtimes.com</a></p>	
	<p><span>Report Bugs:</span> <a href="mailto:report@twothousandtimes.com">report@twothousandtimes.com</a></p>	
	<p><span>General Questions/Help:</span> <a href="contact@twothousandtimes.com">contact@twothousandtimes.com</a></p>	
</div>
@stop
