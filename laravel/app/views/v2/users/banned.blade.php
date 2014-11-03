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
Banned | Sondry
@stop


@section('content')
<div class="container">
    <div class="row">
	    <div class="col-md-6 col-md-offset-3 signup-container forgot-container info-container">
	        <h2>Your account has been temporarily banned.</h2>
	        <p>To avoid future bans or permanent account deactivation please review and respect the Sondry community etiquette. Please contact <a href="mailto:team@sondry.com">team@sondry.com</a> with any questions or concerns.</p>
	        
	    <div class="clearfix"></div>
	    </div>
    </div>
</div>
@stop