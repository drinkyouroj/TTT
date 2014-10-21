@if(isset($not->notification_type))
<?php $not->users = array_reverse($not->users) ?>
<li class="notification-{{$not->notification_type}}">
	{{--Decided to divide the Follow from the rest.--}}
	@if($not->notification_type == 'follow')
		<a href="{{Config::get('app.url')}}/profile/{{$not->users{0} }}">
			<span class="username">
				{{ $not->users{0} }}
			</span>
			followed you.
		</a>
	@elseif($not->notification_type == 'postview')
		<a href="{{Config::get('app.url')}}/posts/{{$not->post_alias}}">
			Your Post {{$not->post_title}} was viewed {{$not->view_count}} times!
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

			{{--Make sure to never use $user as a variable.  Stupid things happen--}}
			@if(count($not->users) > 1)
				and
				<span class="show-people">
					@if(count($not->users) == 2)
						<span>{{ $not->users[1] }}</span>
					@else
						{{ count($not->users)-1 }} other people
						{{--<ul>
						@foreach($not->users as $k => $u)
							@if($k)
							<li class="other-user-listing">
								<a class="other-user" href="{{Config::get('app.url')}}/profile/{{$u}}">
								{{$u}}
								</a>
							</li>
							@endif
						@endforeach
						</ul>--}}
					@endif
				</span>
			@endif
			
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
				replied to your comment on 			
			@endif

			@if(isset($not->post_title))
				<span class="post-title">
					{{$not->post_title}}
				</span>
			@endif
		</a>
		
	@endif
	
</li>
@endif