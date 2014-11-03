@extends('layouts.master')

@section('css')
	<link href="{{Config::get('app.url')}}/css/views/info-pages.css" rel="stylesheet" media="screen">
@stop

@section('title')
	Contact
@stop

@section('content')

<div class="col-md-10 col-md-offset-1 info-container">
<h2>Contact</h2>
	<p>If you have any questions, feedback, or concerns please don’t hesitate to reach out to us at <a href="mailto:team@sondry.com">team@sondry.com</a>. Interaction with you is, and always will be a primary focus. </p>
	<p>Sondry would love to hear directly from you. Here are some options to get in touch with us: </p>
	<p><span>Report Spam or Inappropriate Content:</span> <a href="mailto:report@sondry.com">report@sondry.com</a></p>	
	<p><span>Feedback:</span> <a href="mailto:feedback@sondry.com">feedback@sondry.com</a></p>	
	<p><span>Press Enquiries:</span> <a href="mailto:press@sondry.com">press@sondry.com</a></p>	
	<p><span>Report Bugs:</span> <a href="mailto:report@sondry.com">report@sondry.com</a></p>	
	<p><span>General Questions/Help:</span> <a href="contact@sondry.com">contact@sondry.com</a></p>	
</div>
@stop
