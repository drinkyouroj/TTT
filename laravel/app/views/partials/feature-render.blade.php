@foreach($featured as $k=>$featured)
	{? $post = $featured->post?}
	@include('partials.featured-item')
@endforeach