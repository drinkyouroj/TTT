@extends('v2.layouts.master')


@section('css')
   <link href="{{Config::get('app.staticurl')}}/css/compiled/v2/users/signup_login.css" rel="stylesheet" media="screen">
@stop

@section('js')
    <script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/user/forgot.js"></script>
@stop

@section('title')
Forgot your password?
@stop


@section('content')
    <div class="col-md-4 col-md-offset-4 signup-container forgot-container">
        <div class="signup-form">
            <h2>Forgot your password?</h2>
            <p>Enter your username and email and we can help you</p>
            {{ View::make('v2.users.forms.forgot_password') }}
        </div>
        
    <div class="clearfix"></div>
    </div>
@stop
