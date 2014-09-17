@extends('v2.layouts.master')


@section('css')
    <link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/static.css">
@stop

@section('js')
    <script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/validation/jquery.validate.min.js"></script>
@stop

@section('title')
Disabled
@stop


@section('content')
<div class="container">
    <div class="row">
	    <div class="col-md-6 col-md-offset-3 signup-container forgot-container info-container">
		    <h2>Your account has been disabled.</h2>
		    <p>We’re excited to share another post for you. Give our servers a chance to catch their breath, try posting again in a few minutes. Thanks!</p>
	        
	    <div class="clearfix"></div>
	    </div>
    </div>
</div>
@stop