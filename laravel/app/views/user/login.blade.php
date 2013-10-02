@extends('layouts.master')

@section('css')
@stop

@section('js')
@stop

@section('title')
Login
@stop

@section('content')
	{{ Confide::makeLoginForm()->render() }}
@stop
