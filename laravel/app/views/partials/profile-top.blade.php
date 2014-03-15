
{{--This is the top portion (username and stuff)--}}

{{--Let's determine if this is you or someone else we're looking at--}}
@if(isset($user))
	{? $username = $user->username; ?}
	{? $user_id = $user->id; ?}
	{? $date = $user->created_at; ?}
@else
	{? $username = Session::get('username'); ?}
	{? $user_id = Auth::user()->id; ?}
	{? $date =  Session::get('join_date'); ?}
@endif

<div class="col-md-12 profile-top">
	<ul class="visible-md visible-lg profile-options">
		@if(isset($user) && $user->id != Session::get('user_id'))
			
			@if(!$is_following)
			<li>
				<a class="follow" href="{{Config::get('app.url')}}/follow/{{$user->id}}" data-user="{{$user->id}}">
					<span><span class="follow-action">Follow</span> {{$user->username}}</span>
				</a>
			</li>
			@else
			<li>
				<a class="follow unfollow" href="{{Config::get('app.url')}}/follow/{{$user->id}}" data-user="{{$user->id}}">
					<span><span class="follow-action">UnFollow</span> {{$user->username}}</span>
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
				<a class="all-notification" href="{{Config::get('app.url')}}/profile/notifications">
					<span>All Notifications</span>
				</a>
			</li>
			<li>
				<a class="my-posts" href="{{Config::get('app.url')}}/profile/myposts">
					<span>Profile View</span>
				</a>
			</li>
		@endif
		
		@if(Auth::check())
			@if(Auth::user()->hasRole('Moderator') && isset($user))
				{{--It'd be really stupid if you banned yourself.--}}
				@if($user->id != Auth::user()->id  && (!Auth::user()->hasRole('Admin') || !Auth::user()->hasRole('Moderator')) ) 
					@if(!$user->banned)
						<a class="mod-ban" data-id="{{$user->id}}">
							Ban {{$user->username}}
						</a>
					@else
						<a class="mod-ban" data-id="{{$user->id}}">
							UnBan {{$user->username}}
						</a>
					@endif
				@endif
				
			@endif
			@if(isset($user))
				@if(Auth::user()->hasRole('Admin') && !$user->hasRole('Admin') )
					@if(!$user->hasRole('Moderator'))
						<a class="admin-mod" data-id="{{$user->id}}">Assign {{$user->username}} as a Moderator</a>
					@else
						<a class="admin-mod" data-id="{{$user->id}}">Unassign {{$user->username}} as a Moderator</a>
					@endif
				@endif
			@endif
		@endif
	</ul>
	
	<div class="user">
		<h2><a href="{{Config::get('app.url')}}/profile/{{$username}}">{{$username}}</a></h2>
		<h3 class="join-date">Member since {{$date->format('m.d.Y') }}</h3>
	</div>
	
	<div class="visible-md visible-lg follow-container">
		<div class="following">
			<a href="#following" class="following-link" data-user="{{$user_id}}">
			<div class="numbers">{{$following}}</div>
			Following
			</a>
		</div>
		<div class="followers">
			<a href="#followers" class="followers-link" data-user="{{$user_id}}">
			<div class="numbers">{{$followers}}</div>
			Followers
			</a>
		</div>
	</div>
	
	
	<div class="mobile-profile-options hidden-md hidden-lg">
	<ul class="profile-options">
		@if(isset($user) && $user->id != Session::get('user_id'))
			
			@if(!$is_following)
			<li>
				<a class="follow" href="{{Config::get('app.url')}}/follow/{{$user->id}}" data-user="{{$user->id}}">
					<span><span class="follow-action">Follow</span> {{$user->username}}</span>
				</a>
			</li>
			@else
			<li>
				<a class="follow unfollow" href="{{Config::get('app.url')}}/follow/{{$user->id}}" data-user="{{$user->id}}">
					<span><span class="follow-action">UnFollow</span> {{$user->username}}</span>
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
				<a class="all-notification" href="{{Config::get('app.url')}}/profile/notifications">
					<span>All Notifications</span>
				</a>
			</li>
			<li>
				<a class="my-posts" href="{{Config::get('app.url')}}/profile/myposts">
					<span>Profile View</span>
				</a>
			</li>
		@endif
		
		@if(Auth::check())
			@if(Auth::user()->hasRole('Moderator') && isset($user))
				{{--It'd be really stupid if you banned yourself.--}}
				@if($user->id != Auth::user()->id  && (!Auth::user()->hasRole('Admin') || !Auth::user()->hasRole('Moderator')) ) 
					@if(!$user->banned)
						<a class="mod-ban" data-id="{{$user->id}}">
							Ban {{$user->username}}
						</a>
					@else
						<a class="mod-ban" data-id="{{$user->id}}">
							UnBan {{$user->username}}
						</a>
					@endif
				@endif
				
			@endif
			@if(isset($user))
				@if(Auth::user()->hasRole('Admin') && !$user->hasRole('Admin') )
					@if(!$user->hasRole('Moderator'))
						<a class="admin-mod" data-id="{{$user->id}}">Assign {{$user->username}} as a Moderator</a>
					@else
						<a class="admin-mod" data-id="{{$user->id}}">Unassign {{$user->username}} as a Moderator</a>
					@endif
				@endif
			@endif
		@endif
	<div class="clearfix"></div>
	</ul>
	
	<div class="follow-container mobile-follow-container">
		<div class="following">
			<a href="#following" class="following-link" data-user="{{$user_id}}">
			<div class="numbers">{{$following}}</div>
			Following
			</a>
		</div>
		<div class="followers">
			<a href="#followers" class="followers-link" data-user="{{$user_id}}">
			<div class="numbers">{{$followers}}</div>
			Followers
			</a>
		</div>
	</div>
	<div class="clearfix"></div>
	</div>
	
	
<!--Below is the modal for the follow boxes-->
	
<div class="modal fade" id="followbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
      
      <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    <div class="clearfix"></div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	
	
	
	
</div>