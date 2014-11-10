@if(isset($fuser))
<?php $recent = $fuser_recent ?>
<div class="featured-user-container">	
		<h3 class="user-label">- Featured User -</h3>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-1 avatar-container">
				<a href="{{URL::to('profile/'.$fuser->user->username)}}">
					<?php $fuser_image = $fuser->user->image ? Config::get('app.imageurl').'/'.$fuser->user->image : Config::get('app.url').'/images/profile/avatar-default.png' ;?>
					<span class="avatar" style="background-image:url({{$fuser_image}});"></span>
				</a>
			</div>
			<div class="col-md-6">
				<div class="col-md-12 user-info">
					<h4 class="user-name">
						<a href="{{URL::to('profile/'.$fuser->user->username)}}">
						{{$fuser->user->username}}
						</a>
					</h4>
					<div class="followers-container">
						<div class="featured-stats followers" id="followers">
							<span class="count">{{count($fuser->user->followers)}}</span>
							<span class="text">Followers</span>
						</div>

						<div class="featured-stats following" id="following">
							<span class="count">{{count($fuser->user->following)}}</span>
							<span class="text">Following</span>
						</div>
						<div class="featured-stats post-count">
							<span class="count">{{$post_count}}</span>
							<span class="text">Posts</span>
						</div>
					</div>
					<div class="quote">
						{{$fuser->excerpt}}
					</div>
					<div class="clearfix"></div>
				</div>
		
				<div class="recent-container col-md-12">
					<div class="row">
						<h5 class="recent-label col-md-12">Most Recent Post</h4>
						<?php $recent = $fuser_recent ?>
					</div>
					<div class="row">
						<div class="recent-post col-md-7 col-sm-6">
							<a href="{{URL::to( 'posts/'.$recent->alias)}}">
								<div class="recent-image" style="background-image:url('{{Config::get('app.imageurl')}}/{{$recent->image}}')">
								</div>
								<div class="recent-text">
									<p class="recent-title"> 
										{{$recent->title}}
									</p>
									<ul class="recent-taglines list-inline">
										<li> {{$recent->tagline_1}} </li>
										<li> {{$recent->tagline_2}} </li>
										<li> {{$recent->tagline_3}} </li>
									</ul>
								</div>
							</a>
						</div>
						<div class="user-actions col-md-5 col-sm-6">
							<a class="btn-outline-gold profile-action" href="{{URL::to('profile/'.$fuser->user->username)}}">
								View {{$fuser->user->username}}'s Profile
							</a>
							<a class="follow-button follow-action {{ $fuser_follow ? 'following' : '' }}" data-userid="{{$fuser->user_id}}" href="{{ URL::to('user/signup') }}">
								@if($fuser_follow)
									Following {{$fuser->user->username}}
								@else
									Follow {{$fuser->user->username}}
								@endif
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
