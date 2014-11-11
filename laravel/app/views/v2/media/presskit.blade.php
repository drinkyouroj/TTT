@extends('v2.layouts.master')

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/presskit.css?v={{$version}}">
@stop

@section('title')
	Press Kit | Sondry
@stop

@section('content')
<div class="presskit-wrapper">
<div class="container">
	<div class="row">
		<div class="col-md-12 presskit">
			<img class="ttt-icon" src="{{Config::get('app.url')}}/images/global/gold-icon.png">
			<div class="header-container">
				<h1>The Press Kit</h1>
				<ul class="taglines list-inline">
					<li>Logos</li>
					<li>Icons</li>
					<li>Guidelines</li>
				</ul>
			</div>

			<div class="guidelines generic-container">
				<div class="col-md-3 generic-title">
					<h2>Guidelines</h2>
				</div>
				<div class="col-md-9 generic-content">
					<h3>Clear-space:</h3>
					<p>Always provide ample space around the SONDRY logo. Clear space should be equal to or greater than the cap height of the characters from the SONDRY logotype.</p>

					<img src="{{Config::get('app.url')}}/images/media/guidelines-img.png">

					<h3>Do Not:</h3>
					<p>Please do not change the appearance of the SONDRY logo versions provided in this document. </p>

					<h4><strong>For example, do not:</strong></h4>

					<ul>
						<li>Rotate</li>
						<li>Invert colors</li>
						<li>Distort proportions</li>
						<li>Texurize</li>
						<li>Colorize</li>
						<li>Apply effects (drop shadows etc.)</li>
						<li>Add / remove elements</li>
						<li>Change spacing of elements</li>
					</ul>

				</div>
			<div class="clearfix">
			</div>

			<div class="guidelines generic-container">
				<div class="col-md-3 generic-title">
					<h2>Logos</h2>
				</div>
				<div class="col-md-9 generic-content">
				</div>
			<div class="clearfix">
			</div>
			<p></p>
		</div>
	</div>
</div>
</div>
@stop