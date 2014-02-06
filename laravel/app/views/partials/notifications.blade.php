@foreach($notifications as $not)

	@if(isset($not['follow']))
	<li class="follow">
		<span class="item">
			<span class="username">
				<a href="{{Config::get('app.url')}}/profile/{{$not['follow'][0]->user->username}}">
					{{$not['follow'][0]->user->username}}
				</a>
			</span>
			
			followed you
			@if($not['follow'] > 1)
			<span class="show-people">
				<ul>
				@foreach($not['follow'] as $k => $follow)
					@if($k)
					<li>
						<a href="{{Config::get('app.url')}}/profile/{{$follow->user->username}}">
							{{$follow->user->username}}
						</a>
					</li>	
					@endif
				@endforeach
				</ul>
			</span>
			@endif
		</span>
	</li>			  							
	@endif
	
	@if(isset($not['favorite']))
	<li class="favorite">
		<span class="item">
			<span class="username">
				<a href="{{Config::get('app.url')}}/profile/{{$not['favorite'][0]->user->username}}">
					{{$not['favorite'][0]->user->username}}
				</a>
			</span>
		
			favorited your post
		
			<span>
				<a href="{{Config::get('app.url')}}/posts/{{$not['favorite'][0]->post->alias}}">
					{{$not['favorite'][0]->post->title}}
				</a>
			</span>
			{? $fav_count = count($not['favorite'])-1?}
			
			@if($fav_count)
				along with
				<span class="show-people"> 
				{{ $fav_count }}
				@if($fav_count >= 2)
					other people
				@else
					other person
				@endif
					<ul> 
					@foreach($not['favorite'] as $k => $n)
						{{--Have to skip the first person--}}
						@if($k)
						<li>
							<a href="{{Config::get('app.url')}}/profile/{{$n->user->username}}">
								{{$n->user->username}}
							</a>
						</li>
						@endif
					@endforeach
					</ul>
				</span>
			@endif
		</span>
	</li>
	@endif
	
	@if(isset($not['repost']))
	<li class="repost">
		<span class="item">
			<span class="username">
				<a href="{{Config::get('app.url')}}/profile/{{$not['repost'][0]->user->username}}">
					{{$not['repost'][0]->user->username}}
				</a>
			</span>
		
			reposted your post
		
			<span>
				<a href="{{Config::get('app.url')}}/posts/{{$not['repost'][0]->post->alias}}">
					{{$not['repost'][0]->post->title}}
				</a>
			</span>
			{? $rep_count = count($not['repost'])-1?}
			
			@if($rep_count)
				along with
				<span class="show-people"> 
				{{ $rep_count }}
				@if($rep_count >= 2)
					other people
				@else
					other person
				@endif
					<ul> 
					@foreach($not['repost'] as $k => $n)
						{{--Have to skip the first person--}}
						@if($k)
						<li>
							<a href="{{Config::get('app.url')}}/profile/{{$n->user->username}}">
								{{$n->user->username}}
							</a>
						</li>
						@endif
					@endforeach
					</ul>
				</span>
			@endif
		</span>
	</li>
	@endif
	
@endforeach