@extends('layouts.master')

@section('css')
@stop

@section('js')
@stop

@section('title')
Reset Password
@stop


@section('content')
	{{ Confide::makeResetPasswordForm($token)->render() }}
@stop


