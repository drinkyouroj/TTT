@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css?v={{$version}}">
@stop

@section('title')
	About | Two Thousand Times
@stop

@section('content')
<div class="about-wrapper">
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 info-container about-container">
			<img class="ttt-icon" width="53" height="53" src="{{Config::get('app.url')}}/images/global/ttt-icon-outline.png">
			<h1>About</h1>
			<p class="first-paragraph">A couple years ago, we got fed up with the daily grind of social media and “went off the grid,” so to speak. After leaving these networks of instant accessibility behind, we found ourselves more comfortable talking openly with strangers. They were willing to share and we were ready to listen.</p>
			<p>These revealing and genuine conversations garnered retellable stories, introspective thoughts, and life-guiding advice from individuals we’d never hear from again. The only trouble: we wanted to hear more, but had no control over when these exchanges would occur.</p>
			<p>With this in mind, we sought out to make these exchanges more accessible, which resulted in The Two Thousand Times – an online collection for the stories you love to tell and the conversations you need to have.</p>
			<p>Just keep in mind: all submissions must be two thousand words or less.</p>
			<p>Feel free to take a look around, read a post or two, and if your interest is piqued, we would love to welcome you into our community. We encourage you to visit our Community Guidelines before posting.</p>
		</div>
	</div>
</div>
</div>
	
	
@stop
