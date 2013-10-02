@extends('layouts.master')


@section('css')
@stop

@section('js')
@stop

@section('title')
Sign up!
@stop


@section('content')
	<div class="col-md-6 col-md-offset-2">
		<div class="row">
			<div class="col-md-4 col-md-offset-1">
				{{ Confide::makeSignupForm()->render() }}
			</div>
		</div>
	</div>
	<aside class="col-md-2">
		<p>Explantion for the signup process.  Potentially put some disclaimer here or something.</p>
	</aside>
@stop
