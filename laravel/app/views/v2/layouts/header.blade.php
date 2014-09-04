<?php
	$is_guest = Auth::guest();
?>

<div class="header-wrapper">
	<div class="header-inner-wrapper">
		<div class="header-container container">
			<div class="row">
				<div class="col-sm-4 col-left">
					<button class="read-button toggle-sidebar">
						READ
					</button>

					@if( $is_guest )
						<a class="btn-flat-blue" data-toggle="modal" data-target="#guestSignup">POST</a>
					@else
						<a class="btn-flat-blue" href="{{Config::get('app.url')}}/myprofile/newpost">POST</a>
					@endif

				</div>
				<div class="col-sm-4 col-middle">
					<div class="header-logo">
						<a  href="{{Config::get('app.url')}}">
							THE
							<br>
							<span>TWO THOUSAND TIMES</span>
						</a>
					</div>
				</div>

				<div class="col-sm-4 col-right">
					<div class="action">
						@if( $is_guest )
							<span class="navbar-dropdown-toggle glyphicon glyphicon-cog"></span>
						@else
							<img src="" class="navbar-dropdown-toggle avatar">
							
							@if (count($notifications))
								<div class="notification-label">
									{{ count($notifications) }}
								</div>
							@endif

						@endif
					</div>

					{{ Form::open(array('url'=> 'search', 'class' => 'form-search pull-right', 'role'=>'search' )) }}
						<input class="search-input" autocomplete="off" name="search" id="search-input" type="text">
						<label class="glyphicon glyphicon-search" for="search-input">
						</label>
						<input type="submit" value="Search" class="hidden" >
						<div class="result-box hidden"></div>
					{{ Form::close() }}
					

					<div class="navbar-dropdown">
						<div class="dropdown-wrapper">
							@if( $is_guest )
								<div class="guest-actions">
									<a class="btn-flat-blue" data-toggle="modal" data-target="#guestSignup">CREATE AN ACCOUNT</a>
									<a class="btn-flat-blue" href="{{ URL::to( 'user/login' ) }}">LOG IN</a>
								</div>
							@else
								<div class="user">
									<img class="avatar" src="">
									<a href="{{Config::get('app.url')}}/myprofile">{{ Session::get('username') }}</a>
								</div>

								<div class="notifications-title">NOTIFICATIONS</div>
								
								<ul class="notifications list-unstyled">
									@if(count($notifications))
			  							@foreach($notifications as $not)
				  							@include('partials/notifications')
				  						@endforeach
				  					@else
				  					<li class="no-notifications">
				  						<span>You have no notifications!</span>
				  					</li>
				  					@endif
								</ul>
								
								<div class="view-all">
									<a class="btn-flat-blue" href="{{Config::get('app.url')}}/profile/notifications">VIEW ALL</a>
								</div>

								<div class="additional-user-actions">
									<a href="">ACCOUNT SETTINGS</a>
									<br>
									<a href="{{Config::get('app.url')}}/user/logout">SIGN OUT</a>
								</div>

							@endif
							
							<div class="additional-actions">
								<a href="{{ URL::to('privacy') }}">privacy policy</a>
								<br>
								<a href="{{ URL::to('terms') }}">terms of use</a>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>
</div> <!--End of Header Wrapper-->


{{-- ============ OFFCANVAS SIDEBAR ============= --}}
<div id="offcanvas-sidebar">
	<ul class="list-unstyled sidebar-options" id="accordion">

		<li class="sidebar-option feed {{ $is_guest ? 'disabled' : '' }}">
			@if ( $is_guest )
			<a href="#" data-toggle="modal" data-target="#guestSignup">	
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
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="itemTwo" class="collapse">
				<ul class="list-unstyled">
					<li>
						<a href="{{ URL::to('categories/featured') }}">Featured</a>
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
				<a href="#" data-toggle="modal" data-target="#guestSignup">
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
							@if(is_object($save->post->user))
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

	</ul>
</div>

<div id="offcanvas-placeholder">
	<div class="toggle-sidebar">
		<div class="circle"></div>
		<div class="circle"></div>
		<div class="circle"></div>
	</div>
</div>
