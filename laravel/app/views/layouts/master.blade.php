<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title','Title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
	<link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
    
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
	  		<div class="row">
		  		<nav class="col-md-12 navbar navbar-inverse" role="navigation">
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
			  					<a href="{{Config::get('app.url')}}/profile">My Profile</a>
							</li>
							<li>
			  					<a href="{{Config::get('app.url')}}/profile/newpost">Post</a>
							</li>
							<li>
			  					<a href="{{Config::get('app.url')}}/profile/messages">Message</a>
							</li>
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
			  						
				  						@foreach($notifications as $not)
				  							
				  							{{--Favorites only--}}
				  							@if(isset($not['favorite']))
				  							<li>
				  								<span class="item">
				  									<span class="username">
				  										<a href="{{Config::get('app.url')}}/profile/{{$not['favorite'][0]->user->username}}">
				  											{{$not['favorite'][0]->user->username}}
				  										</a>
			  										</span>
				  								
				  									favorited your post
				  								
				  									<span>
				  										<a href="{{Config::get('app.url')}}/posts/{{$not['favorite'][0]->post->alias}}">
				  											{{$not['favorite'][0]->post->title}}
				  										</a>
				  									</span>
				  									{? $fav_count = count($not['favorite'])-1?}
				  									
				  									@if($fav_count)
					  									along with
					  									<span class="show-people"> 
					  									{{ $fav_count }}
					  									@if($fav_count >= 2)
					  										other people
					  									@else
					  										other person
					  									@endif
						  									<ul> 
							  								@foreach($not['favorite'] as $k => $n)
							  									{{--Have to skip the first person--}}
							  									@if(!$k)
							  									<li>
								  									<a href="{{Config::get('app.url')}}/profile/{{$n->user->username}}">
								  										{{$n->user->username}}
								  									</a>
							  									</li>
							  									@endif
							  								@endforeach
							  								</ul>
					  									</span>
					  								@endif
				  								</span>
				  							</li>
				  							@endif
				  							
				  							@if(isset($not['repost']))
				  							<li>
				  								<span class="item">
				  									<span class="username">
				  										<a href="{{Config::get('app.url')}}/profile/{{$not['repost'][0]->user->username}}">
				  											{{$not['repost'][0]->user->username}}
				  										</a>
			  										</span>
				  								
				  									reposted your post
				  								
				  									<span>
				  										<a href="{{Config::get('app.url')}}/posts/{{$not['repost'][0]->post->alias}}">
				  											{{$not['repost'][0]->post->title}}
				  										</a>
				  									</span>
				  									{? $rep_count = count($not['repost'])-1?}
				  									
				  									@if($rep_count)
					  									along with
					  									<span class="show-people"> 
					  									{{ $rep_count }}
					  									@if($rep_count >= 2)
					  										other people
					  									@else
					  										other person
					  									@endif
						  									<ul> 
							  								@foreach($not['repost'] as $k => $n)
							  									{{--Have to skip the first person--}}
							  									@if(!$k)
							  									<li>
								  									<a href="{{Config::get('app.url')}}/profile/{{$n->user->username}}">
								  										{{$n->user->username}}
								  									</a>
							  									</li>
							  									@endif
							  								@endforeach
							  								</ul>
					  									</span>
					  								@endif
				  								</span>
				  							</li>
				  							
			  								@endif
				  						@endforeach
				  						
				  					@else
				  					<li>
				  						<span>You have no notifications!</span>
				  					</li>
				  					@endif
			  					</ul>
			  				</li>
			  				<li class="message-list-parent">
			  					<ul class="message-list-container">
			  						
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
  					<div class="today row">{{date('l, F m, Y')}}</div>
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