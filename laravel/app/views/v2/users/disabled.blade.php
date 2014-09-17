@extends('v2.layouts.master')


@section('css')
    <link rel="stylesheet" media="screen" href="{{Config::get('app.url')}}/css/compiled/v2/static.css">
@stop

@section('js')
    <script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/validation/jquery.validate.min.js"></script>
@stop

@section('title')
Disabled
@stop


@section('content')
    <div class="col-md-4 col-md-offset-4 signup-container forgot-container info-container">
	    <h2>Your account has been disabled.</h2>
	    <p>After multiple bans, it’s best for this account to take some time away from the Two Thousand Times community. Please contact <a href="mailto:team@twothousandtimes.com">team@twothousandtimes.com</a> with any questions or concerns.</p>
        
    <div class="clearfix"></div>
    </div>
@stop