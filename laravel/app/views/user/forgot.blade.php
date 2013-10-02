@extends('layouts.master')

@section('css')
@stop

@section('js')
@stop

@section('title')
Forgot Password
@stop

@section('content')
	{{ Confide::makeForgotPasswordForm()->render() }}
@stop
