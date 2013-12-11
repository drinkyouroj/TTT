<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title','Title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
    
    <!-- Bootstrap -->
    <link href="{{Config::get('app.url')}}/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="{{Config::get('app.url')}}/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    
    <!--Application Shared CSS-->
    <link href="{{Config::get('app.url')}}/css/views/style.css" rel="stylesheet" media="screen">
	
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
	  		<nav class="col-md-10 col-md-offset-1 navbar navbar-inverse" role="navigation">
	  			<div class="container">
		  			<ul class="nav navbar-nav">
		  				@if(Auth::guest())
		  				<li>
		  					<a href="{{Config::get('app.url')}}/about">About</a>
						</li>
		  				<li>
		  					<a href="{{Config::get('app.url')}}/user/login">Sign in</a>
						</li>
						<li>
							<a href="{{Config::get('app.url')}}/user/signup">Signup</a>
						</li>
						@else
						<li>
		  					<a href="{{Config::get('app.url')}}/profile">profile</a>
						</li>
						<li>
		  					<a href="{{Config::get('app.url')}}/profile/newpost">post</a>
						</li>
						<li>
		  					<a href="{{Config::get('app.url')}}/profile/messages">message</a>
						</li>
						@endif
		  			</ul>
		  			
		  			{{--Remember that float: right is inverse visually.--}}
		  			<form class="navbar-form navbar-right" role="search">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Search">
						</div>
				    </form>
				    @if(!Auth::guest())
		  			<ul class="nav navbar-nav navbar-right">
						<li>
		  					<a href="{{Config::get('app.url')}}/user/logout">Sign out</a>
						</li>
		  			</ul>
		  			@endif
				</div>
	  		</nav>
	  	</header>
  	</div>
  	
  	<div class="banner">
  		<div class="container">
  			<div class="row">
  				<div class="col-md-10 col-md-offset-1">
  					<div class="today">{{date('l, F m, Y')}}</div>
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
	 	<div class="row exes">
	 		<div class=""></div>
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
	
    
    
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/bootstrap.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/global.js"></script>
	
	<!--Extra Javascript-->
	@yield('js')
    
  </body>
</html>