@extends('v2.layouts.master')
	<?php
		if(Auth::check()) {
			$user = Auth::user();
			$is_mod = $user->hasRole('Moderator');
			$is_admin = $user->hasRole('Admin');
		} else {
			$is_admin = false;
			$is_mod = false;
		}
		$is_guest = Auth::guest();
	?>

@section('css')
	<link rel="stylesheet" media="screen" href="{{Config::get('app.staticurl')}}/css/compiled/v2/posts/post.css?v={{$version}}">

	<?php $excerpt = substr(strip_tags($post->body), 0, 500); ?>
	{{--CSS and heading is the same so its not an issue to put that stuff here.--}}

	<meta name="description" content="{{$excerpt}}">

	<meta property="og:title" content="{{$post->title}}" />
	<meta property="og:description" content="{{$excerpt}}" />
	<meta property="og:image" content="{{ Config::get('app.imageurl') }}/{{$post->image}}" />
	<meta property="og:type" content="article" />

	<meta name="twitter:title" content="{{$post->title}}">
	<meta name="twitter:description" content="{{$excerpt}}">
	<meta name="twitter:image:src" content="{{Config::get('app.imageurl')}}/{{$post->image}}">
@stop

@section('js')
	@include( 'v2/posts/partials/comment-handlebars-template' )
	@include( 'v2/posts/partials/comment-reply-handlebars-template' )
	@include( 'v2/posts/partials/comment-edit-handlebars-template' )
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/moment/moment.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/vendor/moment-timezone/moment-timezone-with-data.min.js"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/post/comment-pagination.js?v={{$version}}"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/post/post_actions.js?v={{$version}}"></script>
	<script type="text/javascript" src="{{Config::get('app.staticurl')}}/js/v2/post/post.js?v={{$version}}"></script>
	
	<!-- Go to www.addthis.com/dashboard to customize your tools -->
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53f22b3f69014ed4"></script>

	{{-- This only applies to the case where a new user tried commenting on a post, signs up, and is redirected back to comment --}}
	@if ( isset( $restore_comment ) )
		<script type="text/javascript">
			$(document).ready(function() {
				window.scrollTo(0, $('.comment-form').offset().top - 100);
			});
		</script>
	@endif

@stop

@section('title')
	{{ $post->title }} | Sondry
@stop

@section('content')
	
	<?php
		$save_tooltip = 'Save this to your profile';
		$save_term_active = 'Saved';
		$save_term = 'Save';

		$repost_tooltip = 'Share this with your followers';
		$repost_term_active = 'Reposted';
		$repost_term = 'Repost';

		$like_tooltip = 'Give this more visibility to the community';
		$like_term_active = 'Liked';
		$like_term = 'Like';

		$follow_term_active = 'Following';
		$follow_term = 'Follow';
	?>
<article itemscope itemtype="http://schema.org/Article">
	<section class="post-action-bar-wrapper">
		<div class="post-action-bar" data-post-id="{{ $post->id }}" data-user-id="{{ $post->user->id }}">
			<div class="post-action-container">
				<div class="row">
					<div class="col-md-12">
					{{-- Only display like, repost, save if not the author --}}
					<?php
						$is_logged_in = Auth::check();
						$is_author = Auth::check() && $post->user->id == Auth::user()->id;
					?>
						<div class=" hidden hidden-sm hidden-xs utility-container">
						</div>

						@if($is_author)
							<div class="view-count-container">
								<img class="views-icon" src="{{ URL::to('images/global/views-icon.png') }}" width="20px" height="12px">
								{{$post->views}}
							</div>
						@endif

						<div class=" actions-container">
							<ul class="actions {{ $is_author ? 'author' : 'reader' }}">
								<li class="like">
									<a data-action="like" class="like-button {{ $liked ? 'active' : '' }}" href="#" title="{{ $like_tooltip }}" data-toggle="tooltip" data-placement="bottom">
										<span class="{{ $liked ? 'hidden' : '' }}"> <span class="action-counts"> {{ $liked ? $post->likes->count() - 1 : $post->likes->count() }} </span> </span>
										<span class="{{ $liked ? '' : 'hidden' }}"> <span class="action-counts"> {{ $liked ? $post->likes->count() : $post->likes->count() + 1 }} </span> </span>
									</a>
									@if($is_author)
										<ul class="liker-list">
											@foreach($post->likes as $like)
												<li>
													<a href="{{URL::to('profile/'.$like->user->username)}}">
														{{$like->user->username}}
													</a>
												</li>
											@endforeach
										</ul>
									@endif
								</li>

								<li class="repost">
									<a data-action="repost" class="repost-button {{ $reposted ? 'active' : '' }}" href="#" title="{{ $repost_tooltip }}" data-toggle="tooltip" data-placement="bottom">
										<span class="{{ $reposted ? 'hidden' : '' }}"> <span class="action-counts"> {{ $reposted ? $post->reposts->count() - 1 : $post->reposts->count() }} </span> </span>
										<span class="{{ $reposted ? '' : 'hidden' }}"> <span class="action-counts"> {{ $reposted ? $post->reposts->count() : $post->reposts->count() + 1 }} </span> </span>
									</a>
									@if($is_author)
										<ul class="reposter-list">
											@foreach($post->reposts as $repost)
												<li>
													<a href="{{URL::to('profile/'.$repost->users->username)}}">
														{{$repost->users->username}}
													</a>
												</li>
											@endforeach
										</ul>
									@endif
								</li>

								<li class="save">
									<a data-action="save" class="save-button {{ $favorited ? 'active' : '' }}" href="#" title="{{ $save_tooltip }}" data-toggle="tooltip" data-placement="bottom">
										<!-- <span class="{{ $favorited ? 'hidden' : ''}}"> <span class="action-counts"> {{ $favorited ? $post->favorites->count() - 1 : $post->favorites->count() }} </span> </span>
										<span class="{{ $favorited ? '' : 'hidden'}}"> <span class="action-counts"> {{ $favorited ? $post->favorites->count() : $post->favorites->count() + 1 }} </span> </span> -->
									</a>
								</li>
							<ul>
						</div>

						<div class=" comment-container">
							<a class="comment-button action-comment" href="#">Comment ({{ $post->comment_count }})</a>
							
							{{-- THE BELOW IS ONLY OUTPUT FOR SEARCH ENGINE BOTS/CRAWLERS FOR SEO PURPOSES --}}
								@if ( isset( $comments ) )
									@foreach ( $comments as $comment )
										@if ( $comment['depth'] == 0 )
											<div class="thread-parent-divider"></div>
										@endif
										<div id="comment-{{ $comment['_id'] }}" class="comment <?php echo $comment['published'] == 1 ? 'published' : 'deleted'; ?> <?php echo $comment['depth'] == 0 ? 'thread-parent' : ''; ?>" style="margin-left: {{ ($comment['depth'] * 5).'%' }}">
											<div class="left-col">
												<span class="like-comment-count"><?php echo count($comment['likes'])?></span>
												<span class="like-comment glyphicon glyphicon-thumbs-up"></span>
											</div>
											<div class="right-col">
												<div class="user">
													@if ( $comment['published'] == 1 )
														<a href="{{ URL::to('profile/'.$comment['author']['username'] ) }}"> {{ $comment['author']['username'] }} </a>
														<span class="published-date"> - {{ $comment['created_at'] }}</span>
													@else
														<span class="deleted">Nobody</span>
													@endif
												</div>

												<p class="comment-body">
													@if ( $comment['published'] == 0 )
														<span class="deleted">(This comment has been deleted)</span>
													@else
														{{ $comment['body'] }}
													@endif
												</p>
												<div class="reply-links">
													<a class="reply" data-replyid="{{ $comment['_id'] }}" data-postid="{{ $comment['post_id'] }}">Reply</a>
													<div class="reply-box"></div>
												</div>
											</div>
										</div>
									@endforeach
								@endif
							{{-- END OF BOT/CRAWLER CONTENT --}}

						</div>
						@if( !$is_author )
							<div class="hidden-sm hidden-xs follow-container">
								<a data-action="follow" class="follow-button follow {{ $is_following ? 'active' : '' }}" href="#">
									<span class="{{ $is_following ? 'hidden' : '' }}"> {{ $follow_term }} {{ $post->user->username }} </span>
									<span class="{{ $is_following ? '' : 'hidden' }}"> {{ $follow_term_active }} {{ $post->user->username }} </span>
								</a>
							</div>
						@else
							@if($is_editable)
							<div class="hidden-sm hidden-xs edit-container">
								<a class="edit-button" href="{{Config::get('app.url')}}/myprofile/editpost/{{$post->id}}">
									<span>Edit Post</span>
								</a>
							</div>
							@endif
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="post-heading-wrapper">
		<div class="post-heading-container">
			<div class="row">
				<div class="post-heading col-md-4">
					<h2 itemprop="name" content="{{ $post->title }}">{{ $post->title }}</h2>
					{{-- Admin edit capabilities --}}
					@if ( $is_admin )	
						<h2 class="hidden">
							<input class="admin-post-title form-control" type="text" value="{{$post->title}}">
						</h2>
					@endif
					<div class="line"></div>
					<div class="taglines-container">
						<ul class="post-taglines list-inline" itemprop="description">
							<li> {{ $post->tagline_1 }} </li>
							<li> {{ $post->tagline_2 }} </li>
							<li> {{ $post->tagline_3 }} </li>
							{{-- Admin edit capabilities --}}
							@if ( $is_admin )	
								<li class="hidden"> <input class="admin-post-tagline-1 form-control" type="text" value="{{ $post->tagline_1 }}"> </li>
								<li class="hidden"> <input class="admin-post-tagline-2 form-control" type="text" value="{{ $post->tagline_2 }}"> </li>
								<li class="hidden"> <input class="admin-post-tagline-3 form-control" type="text" value="{{ $post->tagline_3 }}"> </li>
							@endif
						</ul>
					</div>

					<div class="author" itemprop="author" content="{{$post->user->username}}">
						<?php $user_image = $post->user->image ? Config::get('app.imageurl').'/'.$post->user->image : Config::get('app.url').'/images/profile/avatar-default.png' ;?>
						<a href="{{ URL::to('profile/'.$post->user->username ) }}">
							<span class="post-author-avatar" style="background-image:url({{$user_image}});"></span>
						</a>
						{{ $post->story_type }} by <a class="author-name" href="{{ URL::to('profile/'.$post->user->username ) }}"> {{ $post->user->username }} </a>
					</div>

					<ul class="post-categories list-inline">
						@for ($i = 0; $i < count($post->categories); $i++)
							<li> 
								<a href="{{ URL::to( 'categories/'.$post->categories[$i]->alias ) }}"> {{ strtoupper( $post->categories[$i]->title ) }} </a> 
								@if ( $i != count($post->categories) - 1 )
									/
								@endif
							</li>
						@endfor
					</ul>

				</div>
				<div class="post-image col-md-8" style="background-image: url('{{Config::get('app.imageurl')}}/{{$post->image}}');">
					<img class="no-show-image" itemprop="image" src="{{Config::get('app.imageurl')}}/{{$post->image}}">
					@if($post->nsfw)
						<div class="nsfw">NSFW</div>
					@endif
				</div>
			</div>
		</div>
	</section>

	<section class="post-content-wrapper">
		<div class="post-content-container container">
			@if( $post->published )
				<?php 
					$total = count( $bodyarray );
				?>
				@foreach( $bodyarray as $c => $body )
					<div class="row">
						<div class="col-md-10 col-md-offset-1 post-content-page-wrapper">
							<div class="post-content-page" id="<?php echo $c ? '':'one' ?>">
								{{$body}}
							</div>
						</div>
						<div class="col-md-10 col-md-offset-1 row-divider">
							<span class="page-count"><?php echo $c+1 ?>/{{$total}}</span>
							<div class="clearfix"></div>
						</div>
					</div>
				@endforeach
			@else
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<h3 class="unpublished">This post has been unpublished.</h3>
					</div>
				</div>
			@endif
			<div class="row">
				<div class="col-md-10 col-md-offset-1 extra-actions">
					{{--@if( $is_guest )
						<div class="banner-mid">
							<a href="{{ URL::to( 'user/signup' ) }}">
								<img class="banner-mid-desktop" src="{{ URL::to('images/posts/banner-mid.jpg') }}" alt="Join the community. Post your own stories, follow users, save stories, comment and join the discussion. Create an Account.">
								<img class="banner-mid-mobile" src="{{ URL::to('images/posts/banner-mid-mobile.jpg') }}" alt="Join the community. Post your own stories, follow users, save stories, comment and join the discussion. Create an Account.">
							</a>
						</div>
					@endif
					@if( $is_guest )
						<div class="banner-bottom">
							<a href="{{ URL::to( 'user/signup' ) }}">
								<img class="banner-bottom-desktop" src="{{ URL::to('images/posts/banner-bottom-desktop.jpg') }}">
								<img class="banner-bottom-mobile" src="{{ URL::to('images/posts/banner-bottom-mobile.jpg') }}">
							</a>
						</div>
					@endif--}}


					<div class="sharing col-md-7">
						<div class="addthis_custom_sharing"></div>
					<div class="clearfix"></div>
					</div>

					@if( !$is_author )
						<div class="col-md-5 utility-container">
							@if($favorited)
								<a data-action="read" class="read">Mark as Read</a>
							@endif
							<a data-action="flag" class="flag-button flag <?php echo $has_flagged ? 'active' : ''; ?>">
								Flag</a>
						</div>
					@endif
					<div class="clearfix"></div>
				</div>
			</div>
			{{-- Author content --}}
			<div class="row author-container">
				<div class="col-md-10 col-md-offset-1  author-content">
					<div class="author author-info" itemprop="author" content="{{$post->user->username}}">
						<a href="{{ URL::to('profile/'.$post->user->username ) }}">
							<?php $user_image = $post->user->image ? Config::get('app.imageurl').'/'.$post->user->image : Config::get('app.url').'/images/profile/avatar-default.png' ;?>
							<span class="post-author-avatar" style="background-image:url('{{$user_image}}');"></span>
						</a>
						<div class="author-text">
							{{ $post->story_type }} by 
							<a class="author-name" href="{{ URL::to('profile/'.$post->user->username ) }}"> {{ $post->user->username }} </a>
						</div>
					</div>

					<div class="author-actions">
						<a class="read-more" href="{{ URL::to('profile/'.$post->user->username ) }}">read more by {{ $post->user->username }}</a>
						@if( !$is_author )
							<a data-action="follow" class="follow-button follow {{ $is_following ? 'active' : '' }}" href="#">
								<span class="{{ $is_following ? 'hidden' : '' }}"> {{ $follow_term }} {{ $post->user->username }} </span>
								<span class="{{ $is_following ? '' : 'hidden' }}"> {{ $follow_term_active }} {{ $post->user->username }} </span>
							</a>
						@endif
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		{{-- Admin edit capabilities --}}
		@if ( $is_admin )
			<div class="post-content-container container hidden">
				<div class="row">
					<div class="col-md-10 col-md-offset-1 post-content-page-wrapper">
						<textarea class="admin-post-body form-control" rows="20" style="margin-top: 60px;">
							{{ $post->body }}
						</textarea>
					</div>
				</div>
			</div>
		@endif
	</section>

	<section class="post-comment-wrapper">
		<div class="post-comment-container container">
			<div class="row">
				<div class="comments col-md-10 col-md-offset-1">
					
					<form method="POST" accept-charset="UTF-8" class="form-horizontal comment-reply" role="form">
						<input name="post_id" type="hidden" value="{{ $post->id }}">
						<input name="reply_id" type="hidden" value="">
							<div class="form-group comment-form ">
							<label for="body" class="control-label">Comments ({{ $post->comment_count }})</label>
							<textarea class="form-control" required="required" minlength="5" name="body" cols="50" rows="10" id="body"><?php echo isset( $restore_comment ) ? $restore_comment : ''; ?></textarea>
							<span class="error"></span>
						</div>
										
						<div class="form-group pull-right">
							<input class="btn-flat-gray" type="submit" value="Comment">
						</div>
						
						<div class="clearfix"></div>
					</form>
					
					<div class="comments-listing">
						
					</div>
					<div class="comment-loading-container">
						<img src="{{ URL::to('images/posts/comment-loading.gif') }}">
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="footer-cat-container container">
		<div class="row">
			<div class="footer-cat col-md-10 col-md-offset-1">
				@include( 'v2/partials/category-listing' )
			</div>
		</div>
	</div>

</article>
@stop


@section('admin-mod-post-controls')

	<?php 
		$featured = isset($featured) ? $featured : false; 
	?>
	<p class="post-title"> {{ $post->title }}
		<span class="post-featured-label label label-success {{ $featured ? '' : 'hidden' }}">
			@if ( $featured )
				Featured {{ $featured->position }}
			@endif
		</span>
	</p>
	@if ( $is_admin )
		<p class="post-title">Readability: {{$readability}}</p>
		<p class="post-title">Grade Level: {{$grade}}</a>
		<p class="post-title">Post Sentiment:
			<ul>
				<li> Positive: {{$sentiment->positive}}</li>
				<li> Negative: {{$sentiment->negative}}</li>
			</ul>
		</p>
		<hr>
		{{-- Manipulate Post Views --}}
		<div> Current view count: <span class="view-count">{{$post->views}}</span>
			<br>
			Modify view count: 
		</div>
		<div class="input-group">
			<input class="form-control" type="number" value="{{$post->views}}">
			<span class="input-group-btn">
		        <button class="btn btn-warning admin-update-view-count" type="button">Set</button>
		    </span>
		</div>
	@endif
	<hr>
	{{-- Admin only access to featured controls --}}
	@if ( $is_admin )
		<div class="admin-post-featured-controls">
			{{ Form::select( 'admin-featured-position', Config::get('featured'), null, array( 'class' => 'form-control' ) ) }}
			<button class="admin-set-featured btn btn-xs btn-success">Set Featured Position</button>
			<button class="admin-unset-featured btn btn-xs btn-warning {{ $featured ? '' : 'hidden' }}">Remove from Featured</button>
			<br>
		</div>
		<hr>

		<div class="admin-post-nsfw-controls">
			<button class="admin-nsfw">
				@if($post->nsfw)
					Unset NSFW
				@else
					Set NSFW
				@endif
			</button>
		</div>
		<hr>
	@endif

	<div class="mod-post-controls">		
		@if ( $is_admin )
			<button class="admin-edit-post btn btn-xs btn-warning">Edit Post</button>
			<button class="admin-edit-post-submit btn btn-xs btn-success hidden">Submit Changes</button>
		@endif
		<button class="mod-post-delete btn btn-xs btn-danger {{ $post->trashed() ? 'hidden' : '' }}">Delete This Post</button>
		<button class="mod-post-undelete btn btn-xs btn-default {{ $post->trashed() ? '' : 'hidden' }}">Un-delete This Post</button>
		<hr>
		<p class="post-categories-title">Categories</p>
		<ul class="list-unstyled">
			@foreach ( $post->categories as $category )
				<li>
					{{ $category->title }}
					<button class="mod-post-category-delete btn btn-xs btn-warning" data-category-id="{{$category->id}}">Remove</button>
				</li>
			@endforeach
		</ul>
	</div>

	
@stop
