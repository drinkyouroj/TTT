<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-1 avatar-container">
			<a href="{{URL::to('profile/'.$fuser->user->username)}}">
				<img class="avatar" src="{{Config::get('app.imageurl')}}/{{$fuser->user->image}}">
			</a>
		</div>
		<div class="col-md-6 user-info">
			<h4 class="user-name">
				<a href="{{URL::to('profile/'.$fuser->user->username)}}">
				{{$fuser->user->username}}
				</a>
			</h4>
			<div class="followers-container">
				<a href="#followers" class="followers" id="followers">
					<span class="count">{{count($fuser->user->followers)}}</span>
					<span class="text">Followers</span>
				</a>

				<a href="#following" class="following" id="following">
					<span class="count">{{count($fuser->user->following)}}</span>
					<span class="text">Following</span>
				</a>
				<a href="" class="post-count">
					<span class="count">{{count($fuser->user->posts)}}</span>
					<span class="text">Posts</span>
				</a>
			</div>
			<div class="quote">
				{{$fuser->excerpt}}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="recent-container col-md-7 col-md-offset-5">
			<div class="row">
				<h5 class="recent-label col-md-12">Most Recent Post</h4>
				<?php $recent = $fuser->user->posts{0}; ?>
			</div>
			<div class="row">
				<div class="recent-post col-md-6 col-sm-6 col-xs-7">
					<div class="recent-image" style="background-image:url('{{Config::get('app.imageurl')}}/{{$recent->image}}')">
					</div>
					<div class="recent-text">
						<p class="recent-title"> 
							<a href="{{URL::to( 'posts/'.$recent->alias)}}">
								{{$recent->title}}
							</a>
						</p>
						<ul class="recent-taglines list-inline">
							<li> {{$recent->tagline_1}} </li>
							<li> {{$recent->tagline_2}} </li>
							<li> {{$recent->tagline_3}} </li>
						</ul>
					</div>
				</div>
				<div class="user-actions col-md-4 col-sm-6 col-xs-5">
					<a class="btn-flat-light-gray profile-action" href="{{URL::to('profile/'.$fuser->user->username)}}">
						View {{$fuser->user->username}}'s Profile
					</a>
					<a class="follow-button follow-action" data-userid="{{$fuser->user_id}}">
						Follow {{$fuser->user->username}}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

