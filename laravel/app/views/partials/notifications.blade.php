@foreach($notifications as $not)

	@if(isset($not['new']))
	<li class="new">
		<span class="item">
			<a href="{{Config::get('app.url')}}/profile/{{$not['new'][0]->user->username}}">
				{{$not['new'][0]->user->username}}
			</a>
		</span>
		
		submitted a new post
		
		<span>
			<a href="{{Config::get('app.url')}}/posts/{{$not['new'][0]->post->alias}}#new-{{$not['new'][0]->new_id}}">
				{{$not['new'][0]->post->title}}
			</a>
		</span>		
	</li>
	@endif
	

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
		
			favorited post
		
			<span>
				<a href="{{Config::get('app.url')}}/posts/{{$not['favorite'][0]->post->alias}}">
					{{$not['favorite'][0]->post->title}}
				</a>
			</span>
			{? $fav_count = count($not['favorite'])-1?}
			
			@if($fav_count)
				along with
				<span class="show-people">
				@if($fav_count >= 2)
					{{ $fav_count }} other people
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
				@else
					<a href="{{Config::get('app.url')}}/profile/{{$not['favorite'][1]->user->username}}">{{$not['favorite'][1]->user->username}}</a>
				@endif

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
		
			reposted post
		
			<span>
				<a href="{{Config::get('app.url')}}/posts/{{$not['repost'][0]->post->alias}}">
					{{$not['repost'][0]->post->title}}
				</a>
			</span>
			{? $rep_count = count($not['repost'])-1?}
			
			@if($rep_count)
				along with
				<span class="show-people">
				@if($rep_count >= 2)
					{{ $rep_count }} other people
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
				@elseif($rep_count == 1)
					<a href="{{Config::get('app.url')}}/profile/{{$not['repost'][1]->user->username}}">{{$not['repost'][1]->user->username}}</a>
				@endif
					
				</span>
			@endif
		</span>
	</li>
	@endif
	
	@if(isset($not['comment']))
	<li class="comment">
		<span class="item">
			<a href="{{Config::get('app.url')}}/profile/{{$not['comment'][0]->user->username}}">
				{{$not['comment'][0]->user->username}}
			</a>
		</span>
		
		commented on your post
		
		<span>
			<a href="{{Config::get('app.url')}}/posts/{{$not['comment'][0]->post->alias}}#comment-{{$not['comment'][0]->comment_id}}">
				{{$not['comment'][0]->post->title}}
			</a>
		</span>
		
		{{-- I know its ugly to have so much stuff here, but we need it to count the unique usernames--}}
		{? $unique = array(); ?}
		{? foreach($not['comment'] as $k=> $com ){ $unique[$k] = $com->user->username; }?}
		{? $unique = array_unique($unique)  ?}
		{? $comment_count = count($unique)-1 ?}
		
		@if($comment_count)
			along with
			<span class="show-people">
				@if($comment_count >= 2)
					{{ $comment_count }} other people
					<ul> 
					@foreach($unique as $k => $n)
						{{--Have to skip the first person--}}
						@if($k)
						<li>
							<a href="{{Config::get('app.url')}}/profile/{{$n}}">
								{{$n}}
							</a>
						</li>
						@endif
					@endforeach
					</ul>
				@elseif($comment_count == 1)
					{{--This needs to have unique username.--}}
					@foreach($unique as $n)
						@if($n != $not['comment'][0]->user->username)
							<a href="{{Config::get('app.url')}}/profile/{{$n}}">{{$n}}</a>
						@endif
					@endforeach
					
				@endif
			</span>
		@endif
		
	</li>
	@endif
	
	
@endforeach