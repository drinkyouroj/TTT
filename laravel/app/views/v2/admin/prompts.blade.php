@extends('v2.layouts.master')

@section('title')
	Admin - Prompts | Sondry
@stop

@section('css')
	<!-- <link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/category/category.css?v={{$version}}"> -->
@stop

@section('js')
	<!-- <script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/category/category.js?v={{$version}}"></script> -->
@stop

@section('content')
<div class="container">
	<h1>Prompts</h1>

	@if( count($prompts) )
		<table class="table">
			<thead>
				<tr>
					<th>Prompt</th>
					<th>Link</th>
					<th>Clicks</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
				@foreach( $prompts as $prompt )
					<tr class="admin-prompt">
						<td>{{$prompt->body}}</td>
						<td>{{$prompt->link}}</td>
						<td>{{$prompt->clicks}}</td>
						<td>
							<input class="active" type="checkbox" data-prompt-id="{{ $prompt->id }}" {{ $prompt->active ? 'checked' : '' }}>
							<button class="btn btn-xs pull-right delete">delete</button>
						</td>
					</tr>
				@endforeach
			</tbody>		
		</table>
	@endif
	
	<form role="form" method="post" action="/admin/prompts" class="form-inline">
		<div class="form-group col-md-8 col-sm-12">
			<input class="form-control" type="text" maxlength="140" name="body">
		</div>
		<div class="form-group col-md-2 col-sm-6">
			<select class="form-control" name="link">
				<option value="signup">signup</option>
				<option value="post_input">post input</option>
			</select>
		</div>
		<div class="form-group col-md-2 col-sm-6">
			<button type="submit" class="btn">Add</button>
		</div>
	</form>

</div>
@stop
