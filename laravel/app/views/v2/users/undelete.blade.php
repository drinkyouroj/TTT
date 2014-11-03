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
Restored  | Sondry
@stop


@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 signup-container undelete-container info-container">
                <h2>Welcome back {{$user->username}}!</h2>
                <p>Your account has been restored.  Enjoy!</p>
                <div class="line"></div>
                <p>
                    <a href="{{Config::get('app.url')}}/myprofile">Take me to my Profile!</a>
                </p>
        <div class="clearfix"></div>
        </div>
    </div>
</div>
@stop
