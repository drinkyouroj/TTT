<!DOCTYPE html>
<html ng-app>
  <head>
    <title>@yield('title','Two Thousand Times')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
	<link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
    
    <!-- Bootstrap -->
    <link href="{{Config::get('app.url')}}/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="{{Config::get('app.url')}}/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link href="{{Config::get('app.url')}}/css/animate.css" rel="stylesheet" media="screen">
    
    <!--Application Shared CSS-->
    <link href="{{Config::get('app.url')}}/css/views/style.css" rel="stylesheet" media="screen">
    
    <!--Favicon-->
    <link href="/favicon.ico" rel="icon" type="image/x-icon" />
	<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    
    <!--Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
    
    
    <!--{{App::environment()}}-->
	<script>
		@if(App::environment('local') || App::environment('sharktopus'))
			window.site_url = '/tt/';//has trailing slash
			window.image_url = '/tt/uploads/final_images';//no trailing on the image url
		@elseif(App::environment('web'))
			window.site_url = '/';//has trailing slash
			window.image_url = '{{ Config::get('app.imageurl') }}';//no trailing on the image url
		@else
			window.site_url = '/';//has trailing slash
			window.image_url = '/uploads/final_images';//no trailing on the image url
		@endif
	</script>
	
	@if(Auth::check())
	<script>
		window.cur_notifications = {{ json_encode($notifications_ids) }};
	</script>
	@endif
	
	<!--Page Specific CSS-->
	@yield('css')
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="{{Config::get('app.url')}}/js/html5shiv.js"></script>
      <script src="{{Config::get('app.url')}}/js/respond.min.js"></script>
    <![endif]-->
  </head>
<body>

@include('v2.layouts.header')

@yield('filters')

	<div class="container">
		@yield('content','Fudge no content defined.')
	 	<div class="footer-exes"></div>
	</div>



@include('v2.layouts.footer')
    
    
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.scrolltofixed.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/global.js"></script>
	
	@if(Auth::check())
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/global-loggedin.js"></script>
	@else
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/global-nologin.js"></script>
	@endif
	
	<!--Extra Javascript-->
	@yield('js')
    
    
    <script>
    	/*
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-15172874-37', 'twothousandtimes.com');
	  ga('send', 'pageview');
		*/
	</script>
    
  </body>
</html>
<!-- {{ $app->environment() }} -->