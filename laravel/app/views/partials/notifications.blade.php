@if(isset($not->notification_type))
{? $not->users = array_reverse($not->users) ?}
<li class="{{$not->notification_type}}">
	<span class="item">
		{{--Decided to divide the Follow from the rest.--}}
		@if($not->notification_type == 'follow')
			<span class="username">
				<a href="{{Config::get('app.url')}}/profile/{{$not->users{0} }}">
					{{$not->user}}
				</a>
			</span>
			followed you.
		@else
			<span class="username">
				<a href="{{Config::get('app.url')}}/profile/{{$not->users{0} }}">
					{{$not->users{0} }}
				</a>
			</span>
			
			{{--Laravel, you don't have switch and you suck.--}}
			@if($not->notification_type == 'post')
				submitted a new post
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
			<span>
				<a href="{{Config::get('app.url')}}/posts/{{$not->post_alias}}">
					{{$not->post_title}}
				</a>
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
			
		@endif
	</span>
</li>
@endif