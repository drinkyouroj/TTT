@extends('v2.layouts.master')


@section('css')
    <link href="{{Config::get('app.url')}}/css/views/user.css" rel="stylesheet" media="screen">
@stop

@section('js')
    <script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/validation/jquery.validate.min.js"></script>
@stop

@section('title')
Restore your user.
@stop


@section('content')
    <div class="col-md-4 col-md-offset-4 signup-container undelete-container">
        <div class="signup-form">
            <h2>Welcome back {{$user->username}}!</h2>
            <p>Your account has been restored.  Enjoy!</p>
            <p>
                <a href="{{Config::get('app.url')}}/myprofile#feed">Take me to my Feed!</a>
            </p>
        </div>
    <div class="clearfix"></div>
    </div>
@stop
