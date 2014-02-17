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
    
    
    <!--Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
	
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
	<div class="menu-wrapper">
	  	<header class="container menu">
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
							<div class="form-group search">
								<input autocomplete="off" name="term" type="text" class="form-control" placeholder="Search">
								<input type="submit" value="Search" class="hidden" >
								<div class="result-box"></div>
							</div>
						{{ Form::close() }}
					    @if(!Auth::guest())
			  			<ul class="nav navbar-nav navbar-right">
			  				<li class="notifications-parent">
			  					<a href="#notifications">Notifications</a>
			  					<ul class="notifications">
			  						@if(count($notifications))
			  							{? $break = 4; $all = false; ?}
			  							{{--Below file has the foreach routine for both the top section and the full listing --}}
				  						@include('partials/notifications')
				  						
				  					@else
				  					<li>
				  						<span>You have no notifications!</span>
				  					</li>
				  					@endif
			  					</ul>
			  				</li>
			  				<li>
			  					|
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
		        <h4 class="modal-title" id="myModalLabel">Signup and join the fun!</h4>
		      </div>
		      <div class="modal-body">
		        <div class="signup-form">
				{{ Confide::makeSignupForm()->render() }}
				</div>
		      </div>
		    </div>
		  </div>
		</div>
	@endif
    
    
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/global.js"></script>
	@if(Auth::check())
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/global-loggedin.js"></script>
	@else
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/global-nologin.js"></script>
	@endif
	
	<!--Extra Javascript-->
	@yield('js')
    
  </body>
</html>