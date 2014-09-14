<?php
	$is_guest = Auth::guest();
?>

<div class="header-wrapper">
	<div class="header-inner-wrapper">
		<div class="header-container container">
			<div class="row">
				<div class="col-sm-4 col-xs-2 col-left">
					<button class="read-button toggle-sidebar">
						<span>
							READ
						</span>
					</button>

					@if( $is_guest )
						<a class="post-btn btn-flat-blue" href="{{ URL::to( 'user/signup' ) }}">POST</a>
					@else
						@if(Route::current()->uri() != 'myprofile/newpost')
							<a class="post-btn btn-flat-blue" href="{{Config::get('app.url')}}/myprofile/newpost">POST</a>
						@endif
					@endif

				</div>
				<div class="col-sm-4 col-xs-8 col-middle">
					<div class="header-logo">
						<a  href="{{Config::get('app.url')}}">
							THE
							<br>
							<span>TWO THOUSAND TIMES</span>
						</a> 
					</div>
				</div>

				<div class="col-sm-4 col-xs-2 col-right">
						@if( $is_guest )
							
							<a class="btn-outline-blue signup hidden-xs" href="{{ URL::to( 'user/signup' ) }}">Signup</a>
							
							<a class="login-btn login hidden-xs" href="{{ URL::to( 'user/login' ) }}">LOG IN</a>
						@else
						<div class="action">
							<img class="navbar-dropdown-toggle avatar" src="{{Config::get('app.url')}}/{{$user_image}}">
							
							@if ( count($notifications) )
								<div class="notification-label">
									{{ $notification_count > 10 ? '10+' : $notification_count }}
								</div>
							@endif
						</div>
						@endif
					

					{{ Form::open(array('url'=> 'search', 'class' => 'form-search pull-right', 'role'=>'search', 'method'=>'get' )) }}
						<input class="search-input" autocomplete="off" name="search" id="search-input" type="text">
						<label class="glyphicon glyphicon-search hidden-xs" for="search-input">
						</label>
						<input type="submit" value="Search" class="hidden" >
						<div class="result-box hidden"></div>
					{{ Form::close() }}
					
					@if( !$is_guest )
						<div class="navbar-dropdown">
							<div class="dropdown-wrapper">
								<div class="user">
									<a href="{{Config::get('app.url')}}/myprofile">										
										<img class="avatar" src="{{Config::get('app.url')}}/{{$user_image}}">
										{{ Session::get('username') }}
									</a>
								</div>

								<div class="notifications-title">NOTIFICATIONS</div>
								
								{{-- This is where the notifications are loaded in! --}}
								
								<ul class="notifications list-unstyled">
									@if(count($notifications))
			  							@foreach($notifications as $not)
				  							@include('v2/partials/notifications')
				  						@endforeach
				  					@else
				  					<li class="no-notifications">
				  						<span>You have no notifications!</span>
				  					</li>
				  					@endif
								</ul>
								
								<div class="view-all">
									<a class="btn-outline-blue" href="{{Config::get('app.url')}}/profile/notifications">VIEW ALL</a>
								</div>

								<div class="additional-user-actions">
									<a href="{{Config::get('app.url')}}/myprofile#settings">ACCOUNT SETTINGS</a>
									<br>
									<a href="{{Config::get('app.url')}}/user/logout">SIGN OUT</a>
								</div>
								
								<div class="additional-actions">
									<a href="{{ URL::to('privacy') }}">privacy policy</a>
									|
									<a href="{{ URL::to('terms') }}">terms of use</a>
								</div>
							</div>
						</div>

					@endif

				</div>

			</div>

		</div>
	</div>
</div> <!--End of Header Wrapper-->


{{-- ============ OFFCANVAS SIDEBAR ============= --}}
<div id="offcanvas-sidebar">
	<ul class="list-unstyled sidebar-options" id="accordion">
		<li class="visible-xs">
			{{ Form::open(array('url'=> 'search', 'class' => 'form-search', 'role'=>'search' )) }}
				<input class="search-input" autocomplete="off" name="search" id="search-input" type="text" placeholder="search">
				</label>
				<input type="submit" value="Search" class="hidden" >
				<div class="result-box hidden"></div>
			{{ Form::close() }}
		</li>
		@if( $is_guest )
			<li class="sidebar-option post-btn-mobile visible-xs">
				<a href="{{ URL::to( 'user/signup' ) }}">POST</a>
			</li>
		@else
			<li class="sidebar-option post-btn-mobile visible-xs">
				<a href="{{Config::get('app.url')}}/myprofile/newpost">POST</a>
			</li>
		@endif


		<li class="sidebar-option feed {{ $is_guest ? 'disabled' : '' }}">
			@if ( $is_guest )
				<a href="{{ URL::to( 'user/signup' ) }}">	
			@else
				<a href="{{ URL::to( 'myprofile' ) }}#feed">
			@endif
				MY FEED
				<span class="glyphicon glyphicon-align-right pull-right"></span>
			</a>
		</li>


		<li class="sidebar-option categories">
			<a href="#itemTwo" data-toggle="collapse" data-parent="#accordion">
				CATEGORIES
				<span class="glyphicon glyphicon-minus pull-right"></span>
			</a>
			<div id="itemTwo" class="collapse in">
				<ul class="list-unstyled">
					<li>
						<a href="{{ URL::to('featured') }}">Featured</a>
					</li>
					<li class="category-all">
						<a href="{{ URL::to('categories/all') }}">All</a>
					</li>
					@foreach( $categories as $category )
						<li> <a href="{{ URL::to('categories/'.$category->alias) }}">{{ $category->title }}</a> </li>
					@endforeach
				</ul>
			</div>
		</li>

		@if ( $is_guest )
			<li class="sidebar-option saves disabled">
				<a href="#" href="{{ URL::to( 'user/signup' ) }}">
					SAVES
					<span class="glyphicon glyphicon-plus pull-right"></span>
				</a>
			</li>
		@else
			<li class="sidebar-option saves">
				<a href="#itemThree" data-parent="#accordion" data-toggle="collapse">
					SAVES
					<span class="glyphicon glyphicon-plus pull-right"></span>
				</a>
				<div id="itemThree" class="collapse">
					@if ( count( $saves ) )
						<ul class="list-unstyled">
						@foreach ( $saves as $save )
							@if( is_object( $save->post ) && is_object( $save->post->user ) )
								<li>
									<a class="post-image-anchor" href="{{ URL::to( 'posts/'.$save->post->alias ) }}">
										<div class="post-image" style="background-image: url('{{Config::get('app.imageurl')}}/{{$save->post->image}}')"></div>
									</a>
									<div class="post-info">
										<a class="post-title" href="{{ URL::to( 'posts/'.$save->post->alias ) }}">
											{{ $save->post->title }}
										</a>
										<span>by: </span>
										<a class="post-author" href="{{ URL::to( 'profile'.$save->post->user->username ) }}">
											{{ $save->post->user->username }}
										</a>
									</div>
								</li>
							@endif
						@endforeach
						</ul>
					@else
						<p>It looks like you have no saves!</p>
					@endif
				</div>
			</li>
		@endif

		<li class="sidebar-option signup-mobile visible-xs">
			<a href="{{ URL::to( 'user/signup' ) }}">Signup</a>
		</li>
		<li class="sidebar-option login-mobile visible-xs">
			<a href="{{ URL::to( 'user/login' ) }}">Log in</a>
		</li>
	</ul>
</div>

<div id="offcanvas-placeholder">
	<div class="toggle-sidebar">
		<div class="circle"></div>
		<div class="circle"></div>
		<div class="circle"></div>
	</div>
</div>
