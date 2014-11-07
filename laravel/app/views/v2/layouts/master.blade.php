<!DOCTYPE html>

<html>
  <head>
    <title>
    	@yield('title','Sondry')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <link href="{{Config::get('app.staticurl')}}/css/animate.css" rel="stylesheet" media="screen">
    
	<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/bs.css?v={{$version}}" rel="stylesheet" media="screen">

    <!--Application Shared CSS-->
    <link href="{{Config::get('app.staticurl')}}/css/compiled/v2/style.css?v={{$version}}" rel="stylesheet" media="screen">

    @if ( $is_mod )
		<link href="{{Config::get('app.staticurl')}}/css/compiled/v2/admin/admin-moderator.css?v={{$version}}" rel="stylesheet" media="screen">
    @endif

    <!--Favicon-->
    <link href="{{Config::get('app.staticurl')}}/images/favicon.ico" rel="icon" type="image/x-icon" />
	<link href="{{Config::get('app.staticurl')}}/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    
    <!--Fonts-->
    <link rel="stylesheet" type="text/css" href="{{Config::get('app.staticurl')}}/fonts/tradegothic/MyFontsWebfontsKit.css">
    <link href='//fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    
    <!--{{App::environment()}} {{$version}} -->
	<script>
		window.site_url = '/';//has trailing slash
		window.image_url = '{{ Config::get('app.imageurl') }}';
		window.redirect = {{ Input::get('ttt_redirect',0) }} ;
	</script>
	

	@if($have_user)
		<script>
			window.logged_in_user_id = {{ Auth::user()->id }};
		</script>
	@endif


	
	{{--Opengraph--}}
	<meta property="og:site_name" content="Sondry" />
	<meta property="og:url" content="{{Request::url()}}" />

	{{--Schema.org--}}
	<meta itemprop="url" content="{{Request::url()}}" />

	{{--Pinterest domain verification--}}
	<meta name="p:domain_verify" content="27cb8aea411ac7497efe2d433ced7114"/>

	{{--Twitter--}}
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="@sondrystories">


	<!--Page Specific CSS-->
	@yield('css')
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 12]>
      <link href="{{Config::get('app.staticurl')}}/css/compiled/v2/99_ie_problems.css?v={{$version}}" rel="stylesheet" media="screen">
    <![endif]-->
  </head>
<body>
@include('v2.layouts.header')

@if ( $is_mod )
	@include('v2.layouts.admin-moderator')
@endif
	
<div class="content-wrapper">
	@yield('filters')

	@yield('content','Fudge no content defined.')

	@include('v2.layouts.footer')
</div>

	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/handlebars/handlebars.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/sidr/jquery.sidr.min.js"></script>

	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/header.js?v={{$version}}"></script>
	
	@if( $have_user )
		<script type="text/javascript">
			window.logged_in = true;
		</script>
	@else
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/jquery.cookie.js?v={{$version}}"></script>
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/global/global-nologin.js?v={{$version}}"></script>
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/validation/jquery.validate.min.js"></script>
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/user/signup_form.js?v={{$version}}"></script>
	@endif

	@if( $is_mod )
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/admin/moderator.js?v={{$version}}"></script>
	@endif

	@if( $is_admin )
		<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/admin/admin.js?v={{$version}}"></script>
	@endif
	
	<!--Extra Javascript-->
	@yield('js')

	@if(Input::get('ttt_redirect',0))
		<script>
		$(function() {
			$('#sondryModal').modal('show');
		});
		</script>
	@endif

	@if($app->environment() == 'ec2-cluster' || $app->environment() == 'prod')
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-53883988-4', 'auto');
	  ga('require', 'displayfeatures');
	  ga('send', 'pageview');
	  window.ga = ga;
	</script>
	@endif

</body>
</html>
 <!-- {{ $app->environment() }}  {{$_SERVER['SERVER_ADDR']}} -->
 <!-- {{ Request::path() }} -->
