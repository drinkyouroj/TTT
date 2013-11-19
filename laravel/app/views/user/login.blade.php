@extends('layouts.master')

@section('css')
@stop

@section('js')
@stop

@section('title')
Login
@stop

@section('content')
	<div class="col-md-6 col-md-offset-3">
		{{ Confide::makeLoginForm()->render() }}
	</div>
@stop
