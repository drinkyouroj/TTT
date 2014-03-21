{{--This is both the activity and my post item.--}}
<div class="animated fadeIn 
	@if(isset($featured_item) && $featured_item) 
		col-md-8 profile-featured 
	@else 
		col-md-4 
	@endif
	@if(!$act->post->published)
		deleted
	@endif
	post-id-{{$act->post->id}} 
	{{$act->post_type}}">
	<div class="generic-item activity">
		<header>
			@if($act->post_type == 'post')
				<h3 class="post">
					{{ link_to('posts/'.$act->post->alias, $act->post->title) }}
				</h3>
				<span class="story-type">{{$act->post->story_type}}</span>
				<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span> 
			@elseif($act->post_type == 'repost')
				<h3 class="repost">
					{{ link_to('posts/'.$act->post->alias, $act->post->title) }}
				</h3>
				<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span>
				<ul class="repost">
					<ul>
						<li>reposted by</li>
						<li>{{ link_to('profile/'.$act->user->username, $act->user->username) }}</li>
					</ul>
				</ul>
			@else
				<!--{{$act->post_type}}-->
				<h3 class="favorite ">{{ link_to('posts/'.$act->post->alias, $act->post->title) }}</h3>
				<span class="story-type">{{$act->post->story_type}}</span>
				<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span>
			@endif
			
			@if(Auth::check())
				{{--If you are the owner of this post show menu options--}}
				@if(Auth::user()->id == $act->post->user->id && $act->post_type == 'post')
					<ul class="user-menu">
						<li class="options">
							<a href="#post-edit">
								Options
							</a>
							<ul class="menu-listing">
								@if(strtotime(date('Y-m-d H:i:s', strtotime('-72 hours'))) <= strtotime($act->post->created_at) )
								<li class="edit">
									<a href="{{Config::get('app.url')}}/profile/editpost/{{$act->post->id}}">Edit</a>
								</li>
								@endif
								{{--Check out the fact that below has a hidden function --}}
								<li class="feature @if($act->post->id != Session::get('featured')) @else hidden @endif">
									<a href="#feature" data-id="{{$act->post->id}}">Feature</a>
								</li>
								
								<li class="delete">
									<a href="#delete" data-id="{{$act->post->id}}">
										@if($act->post->published)
											Delete
										@else
											UnDelete
										@endif
									</a>
								</li>
							</ul>
						</li>
					</ul>
				@endif
			@endif
		</header>
		<section>
			<div class="the-image">
				<a href="{{URL::to('posts/'.$act->post->alias)}}" style="background-image:url('{{Config::get('app.url')}}/uploads/final_images/{{$act->post->image}}');"> 
				
				</a>
			</div>
			<div class="the-tags @if(strlen($act->post->tagline_1.$act->post->tagline_2.$act->post->tagline_3) >= 35) long @endif">
				{{$act->post->tagline_1}} |
				{{$act->post->tagline_2}} |
				{{$act->post->tagline_3}}
			</div>
		</section>
	</div>
</div>