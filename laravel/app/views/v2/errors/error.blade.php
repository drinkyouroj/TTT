@extends('v2.layouts.master')

	@section('title')
		Can't edit that post!
	@stop

	@section('css')

	@stop

	@section('js')
	@stop

	@section('content')
		<div class="container">
			<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h2>Can't Edit Published Posts After 72 Hours</h2>
						<div class="body">
							<p>Once posts are published, you have 72 hours to make edits.</p>
							<p>We did this in order to add some sense of permanence to your writing.</p>
							<p>That said, if you are so embarassed or have caused some political uprisings becuase of your post, you can "unpublish" the post from your profile.</p>
							<p>If you want to perfect your writing, be sure to use the "Draft" system before hitting the publish button.</p>
						</div>
					</div>
			</div>
		</div>
	@stop
