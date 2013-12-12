@extends('layouts.master')
@section('content')

	@if(gettype($message) == 'string')
	<h2>{{$message}}</h2>
	@elseif(gettype($message) == 'array' || gettype($message) == 'object')
		<!-- Var Dump
		{? var_dump($message) ?}
		-->
	@endif
@stop
