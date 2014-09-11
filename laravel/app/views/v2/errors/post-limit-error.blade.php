@extends('v2.layouts.master')

	@section('title')
		Post Limit | Two Thousand Times
	@stop

	@section('css')

	@stop

	@section('js')
	@stop

	@section('content')
		<div class="container">
			<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h2>You have reached this page because you have posted in the past 10 minutes.</h2>
						<div class="body">
							<p>At this time, we have imposed a 10 minute waiting period between creating new posts. This helps us maintain both thoughtful and high quality content on our site while preventing users from spamming.</p>
						</div>
					</div>
			</div>
		</div>
	@stop
