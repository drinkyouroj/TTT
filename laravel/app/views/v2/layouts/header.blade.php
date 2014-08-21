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
			  					
			  					<a href="{{Config::get('app.url')}}/profile/notifications">Notifications</a>
			  					<ul class="notifications">
			  						@if(count($notifications))
			  							<a class="mark-read">Mark as Read</a>
			  							@foreach($notifications as $not)
				  							@include('partials/notifications')
				  						@endforeach
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
  	
  	@if(strlen(Request::segment(1)) === 0) 
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
  	@endif

</div> <!--End of Header Wrapper-->