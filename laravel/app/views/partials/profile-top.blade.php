
{{--This is the top portion (username and stuff)--}}

{{--Let's determine if this is you or someone else we're looking at--}}
@if(isset($user))
	{? $username = $user->username; ?}
	{? $date = $user->created_at; ?}
@else
	{? $username = Session::get('username'); ?}
	{? $date =  Session::get('join_date'); ?}
@endif

<div class="col-md-12 profile-top">
	<ul class="profile-options">
		@if(isset($user) && $user->id != Session::get('user_id'))
			
			@if(!$is_following)
			<li>
				<a class="follow" href="{{Config::get('app.url')}}/follow/{{$user->id}}" data-user="{{$user->id}}">
					<span>Follow {{$user->username}}</span>
				</a>
			</li>
			@endif
			
			@if($mutual)
			<li>
				<a class="new-message" href="{{Config::get('app.url')}}/profile/newmessage/{{$user->id}}">
					<span>Message</span>
				</a>
			</li>
			@endif
			
		@else
			<li>
				<a class="new-post" href="{{Config::get('app.url')}}/profile/newpost">
					<span>Post</span>
				</a>
			</li>
			<li>
				<a class="edit-profile" href="{{Config::get('app.url')}}/profile/edit">
					<span>Edit Info</span>
				</a>
			</li>
		@endif
	</ul>
	
	<div class="user">
		<h2>{{$username}}</h2>
		<h3 class="join-date">Member since {{$date->format('m.d.Y') }}</h3>
	</div>
	
	<div class="follow-container">
		<div class="following">
			{{$following}}<br/>
			Following
		</div>
		<div class="followers">
			{{$followers}}<br/>
			Followers
		</div>
	</div>
</div>