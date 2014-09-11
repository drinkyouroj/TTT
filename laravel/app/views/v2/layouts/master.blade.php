<!DOCTYPE html>

<?php

	$have_user = Auth::check();
	$is_mod = Session::get('mod');
	$is_admin = Session::get('admin');

?>

<html ng-app>
  <head>
    <title>@yield('title','Two Thousand Times')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700|Fjalla+One' rel='stylesheet' type='text/css'>
    
    <link href="{{Config::get('app.url')}}/css/animate.css" rel="stylesheet" media="screen">
    
	<link href="{{Config::get('app.url')}}/css/compiled/v2/bs.css" rel="stylesheet" media="screen">

    <!--Application Shared CSS-->
    <link href="{{Config::get('app.url')}}/css/compiled/v2/style.css" rel="stylesheet" media="screen">

    @if ( $is_mod )
	<link href="{{Config::get('app.url')}}/css/compiled/v2/admin/admin-moderator.css" rel="stylesheet" media="screen">    
    @endif

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
	
	@if($have_user)
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

@if ( $is_mod )
	@include('v2.layouts.admin-moderator')
@endif
	

@yield('filters')


@yield('content','Fudge no content defined.')
    
 
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.scrolltofixed.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/global.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/handlebars/handlebars.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/vendor/sidr/jquery.sidr.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/header.js"></script>
	
	@if( $have_user )
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/global-loggedin.js"></script>
	@else
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/global-nologin.js"></script>
	@endif

	@if( $is_mod )
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/admin/moderator.js"></script>
	@endif

	@if( $is_admin )
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/v2/admin/admin.js"></script>
	@endif
	
	<!--Extra Javascript-->
	@yield('js')
    
    
    @if(Request::segment(1) != 'user')
		@if( !$have_user )
			<div class="modal fade" id="guestSignup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        <h4 class="modal-title" id="myModalLabel"></h4>
			      </div>
			      <div class="modal-body">
			        <div class="signup-form">
			        	<h4>Signup</h4>
				        {{ View::make('v2.users.forms.signup') }}
					</div>
					
					<div class="login-form">
						<h4>Login</h4>
						{{ View::make('v2.users.forms.login') }}
					</div>
			      </div>
			    </div>
			  </div>
			</div>
		@endif
	@endif
	@if($app->environment() == 'ec2-beta')
    	<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-53883988-3', 'auto');
		  ga('send', 'pageview');

		</script>
	@endif
    
  </body>
</html>
<!-- {{ $app->environment() }} -->