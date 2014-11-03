@extends('v2.layouts.master')


@section('css')
    <link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/static.css?v={{$version}}">
@stop

@section('js')
    <script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/validation/jquery.validate.min.js"></script>
    <script type="text/javascript">
		window.disable_signup =1;
	</script>
@stop

@section('title')
Disabled | Sondry
@stop


@section('content')
    <div class="col-md-4 col-md-offset-4 signup-container forgot-container info-container">
	    <h2>Your account has been disabled.</h2>
	    <p>After multiple bans, it’s best for this account to take some time away from the Sondry community. Please contact <a href="mailto:team@sondry.com">team@sondry.com</a> with any questions or concerns.</p>
        
    <div class="clearfix"></div>
    </div>
@stop