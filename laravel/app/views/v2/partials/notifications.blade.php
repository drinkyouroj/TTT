@if(isset($not->notification_type))
{? $not->users = array_reverse($not->users) ?}
<li class="notification-{{$not->notification_type}}">
	{{--Decided to divide the Follow from the rest.--}}
	@if($not->notification_type == 'follow')
		<a href="{{Config::get('app.url')}}/profile/{{$not->users{0} }}">
			<span class="username">
				{{$not->user}}
			</span>
			followed you.
		</a>
	@else

		@if ( $not->notification_type == 'reply' || $not->notification_type == 'comment' )
			<a href="{{Config::get('app.url')}}/posts/{{$not->post_alias}}#comment-{{$not->comment_id}}">
		@else
			<a href="{{Config::get('app.url')}}/posts/{{$not->post_alias}}">
		@endif

			<span class="action-user">
				{{$not->users{0} }}
			</span>
			
			{{--Laravel, you don't have switch and you suck.--}}
			@if($not->notification_type == 'post')
				submitted a new post
			@elseif($not->notification_type == 'like')
				liked
			@elseif($not->notification_type == 'favorite')
				favorited post
			@elseif($not->notification_type == 'repost')
				reposted post
			@elseif($not->notification_type == 'comment')
				commented on your post
			@elseif($not->notification_type == 'reply')
				replyed on your comment in post 
			@endif
			
			@if(isset($not->post_title))
				<span class="post-title">
					{{$not->post_title}}
				</span>
			@endif
			
			{{--Make sure to never use $user as a variable.  Stupid things happen--}}
			@if(count($not->users) > 1)
				along with
				<span class="show-people">
					@if(count($not->users) == 2)
						<a href="{{Config::get('app.url')}}/profile/{{$not->users[1]}}">
						{{ $not->users[1] }}
						</a>
					@else
						{{ count($not->users)-1 }} other people
						<ul>
						@foreach($not->users as $k => $u)
							@if($k)
							<li>
								<a href="{{Config::get('app.url')}}/profile/{{$u}}">
								{{$u}}
								</a>
							</li>
							@endif
						@endforeach
						</ul>
					@endif
				</span>
			@endif
		</a>
		
	@endif
	
</li>
@endif