<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title','Title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
    
    <!-- Bootstrap -->
    <link href="{{Config::get('app.url')}}/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="{{Config::get('app.url')}}/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
	
	<!--Application CSS-->
	@yield('css')
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

<div class="header-wrapper">
  	<header class="container">
  		<nav>

  			<ul>
  				@if(Auth::guest())
  				<li>
  					<a href="{{Config::get('app.url')}}/">Home</a>
				</li>
  				<li>
  					<a href="{{Config::get('app.url')}}/user/login">Login</a>
				</li>
				<li>
					<a href="{{Config::get('app.url')}}/user/signup">Signup</a>
				</li>
				<li>
					<a href="{{Config::get('app.url')}}/user/signup">Signup</a>
				</li>
				@else
				<li>
  					<a href="{{Config::get('app.url')}}/user/logout">Logout</a>
				</li>
				@endif
  			</ul>
  		</nav>
  	</header>
</div>
  	
	<div class="container">
		<div class="row">
			@yield('content','Fudge no content defined.')
	 	</div>
	</div>
	
    <!--Extra Javascript-->
	@yield('js')
    
  </body>
</html>