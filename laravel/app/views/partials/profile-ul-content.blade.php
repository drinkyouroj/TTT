
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
			<li>
				<a class="settings" href="{{Config::get('app.url')}}/profile/settings">
					<span>Settings</span>
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