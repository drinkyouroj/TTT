<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title','Title')</title>
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
	<script>
		@if(App::environment('local'))
		window.site_url = '/tt/';//has trailing slash
		@else
		window.site_url = '/';//has trailing slash
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
<div class="header-wrapper">
	<div class="menu-wrapper visible-md visible-lg">
	  	<header class="visible-md visible-lg container menu">
	  		<div class="row">
		  		<nav class="col-md-12 navbar navbar-inverse nav-conatiner" role="navigation">
		  			<div class="container">
			  			<ul class="nav navbar-nav main-nav">
			  				@if(Auth::guest())
			  				<li>
			  					<a href="{{Config::get('app.url')}}/about">About</a>
							</li>
			  				<li>
			  					<a href="{{Config::get('app.url')}}/user/login">Sign in/Sign up</a>
							</li>
							@else
							<li class="loggedin profile">
			  					<a href="{{Config::get('app.url')}}/profile">{{Session::get('username')}}</a>
							</li>
							<li class="loggedin post">
			  					<a href="{{Config::get('app.url')}}/profile/newpost">Post</a>
							</li>
							<li class="loggedin message">
			  					<a href="{{Config::get('app.url')}}/profile/messages">Message</a>
							</li>
								@if(Session::get('admin'))
								<li class="loggedin admin">
									<a href="{{Config::get('app.url')}}/admin">Admin</a>
								</li>
								@else
								
								@endif
							@endif
			  			</ul>
			  			
			  			{{--Remember that float: right is inverse visually.--}}
			  			{{ Form::open(array('url'=> 'search', 'class' => 'navbar-form navbar-right search', 'role'=>'search' )) }}
							<div class="form-group search" id="search-box">
								<input autocomplete="off" name="term" type="text" class="form-control" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'">
								<input type="submit" value="Search" class="hidden" >
								<div class="result-box"></div>
							</div>
						{{ Form::close() }}
					    @if(!Auth::guest())
			  			<ul class="nav navbar-nav navbar-right">
			  				<li class="notifications-parent @if(count($notifications)) active-notifications @endif">
			  					
			  					<a href="#notifications">Notifications</a>
			  					<ul class="notifications">
			  						@if(count($notifications))
			  							<a class="mark-read">Mark as Read</a>
			  							{? $break = 4; $all = false; ?}
			  							{{--Below file has the foreach routine for both the top section and the full listing --}}
				  						@include('partials/notifications')
				  						
				  					@else
				  					<li class="no-notifications">
				  						<span>You have no notifications!</span>
				  					</li>
				  					@endif
			  					</ul>
			  				</li>
							<li>
			  					<a href="{{Config::get('app.url')}}/user/logout">Sign out</a>
							</li>
			  			</ul>
			  			@endif
					</div>
		  		</nav>
	  		</div>
	  	</header>
  	</div>
  
  	
  	<header id="mobile-header" class="hidden-md hidden-lg navbar-fixed-top">
		<div class="row">
			<nav role="navigation" class="mobile-menu nav navbar navbar-default">
				<div class="mobile-logo hidden-md hidden-lg">
					<a href="{{Config::get('app.url')}}"><img src="{{Config::get('app.url')}}/img/global/logo-mobile.png"></a>
				</div>
		
				<button type="button" class="hidden-md hidden-lg navbar-toggle glyphicon glyphicon-th-large" data-toggle="collapse" data-target="#mobile-menu"></button>
				<div id="mobile-menu" class="collapse">
					<ul class="nav navbar-nav main-nav">
						@if(Auth::guest())
						<li>
							<a href="{{Config::get('app.url')}}/about">About</a>
						</li>
						<li>
							<a href="{{Config::get('app.url')}}/user/login">Sign in/Sign up</a>
						</li>
						@else
						<li class="loggedin profile">
							<a href="{{Config::get('app.url')}}/profile">{{Session::get('username')}}</a>
						</li>
						<li class="loggedin post">
							<a href="{{Config::get('app.url')}}/profile/newpost">Post</a>
						</li>
						<li class="loggedin message">
							<a href="{{Config::get('app.url')}}/profile/messages">Message</a>
						</li>
						<li class="loggedin notifications">
							<a href="{{Config::get('app.url')}}/profile/notifications">Notifications</a>
						</li>
							@if(Session::get('admin'))
							<li class="loggedin admin">
								<a href="{{Config::get('app.url')}}/admin">Admin</a>
							</li>
							@else
						
							@endif
						@endif
					
					</ul>
				
					{{--Remember that float: right is inverse visually.--}}
					{{ Form::open(array('url'=> 'search', 'class' => 'navbar-form search', 'role'=>'search' )) }}
						<div class="form-group search">
							<input autocomplete="off" name="term" type="text" class="form-control" placeholder="Search">
							<input type="submit" value="Search" class="hidden" >
							<div class="result-box"></div>
						</div>
					{{ Form::close() }}
				</div>
			</nav>
		</div>
  	</header>
  	
  	<div class="banner">
  		<div class="container">
  			<div class="row">
  				<div class="col-md-12">
  					<div class="today row">{{date('l, F d, Y')}}</div>
  				</div>
  			</div>
  		</div>
  		<a href="{{Config::get('app.url')}}">
  			<h1><span>Two Thousand Times</span></h1>
  		</a>
  	</div>
</div>

@yield('filters')

	<div class="container">
		<div class="row content">
			@yield('content','Fudge no content defined.')
	 	</div>
	 	<div class="footer-exes">
	 		
	 	</div>
	</div>


	
<div class="footer-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12 footer-nav">
				<ul>
					<li>
						<a href="{{Config::get('app.url')}}/about">About</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/etiquette">etiquette</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/contact">Contact</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/privacy">Privacy Policy</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/terms">Terms of Use</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

	@if(Auth::guest())
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
				{{ Confide::makeSignupForm()->render() }}
				</div>
				
				<div class="login-form">
					<h4>Login</h4>
					{{ Confide::makeLoginForm()->render() }}
					<a href="{{Config::get('app.url')}}/user/forgot">forget your password?</a>
				</div>
				<aside class="login-disclaimer">
					Read our guidelines on <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a>.
				</aside>
		      </div>
		    </div>
		  </div>
		</div>
	@endif
    
    
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
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-15172874-37', 'twothousandtimes.com');
	  ga('send', 'pageview');
	
	</script>
    
  </body>
</html>