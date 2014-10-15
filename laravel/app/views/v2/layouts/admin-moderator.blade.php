<?php
	$is_mod = Session::get('mod');
	$is_admin = Session::get('admin');
?>

<div id="offcanvas-admin-sidebar">
	
	<ul class="list-unstyled sidebar-options" id="admin-accordion">
		{{-- Flagged Posts. This is visible regardless of the view --}}
		<li class="admin-sidebar-option">
			<a href="#adminItemOne" data-toggle="collapse" data-parent="#admin-accordion">
				Flagged Posts
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemOne" class="collapse">
				<ul class="list-unstyled">
					@if ( count( $flagged_post_content ) )
						@foreach ( $flagged_post_content as $content )
							@if ( is_object( $content->post ) )
								<li data-flagged-content-id="{{ $content['_id'] }}">
									<a class="flagged-post-title" href="{{ URL::to('posts/'.$content->post->alias ) }}" target="_blank">{{ $content->post->title }}</a>
									<button class="mod-remove-flagged-post btn btn-xs btn-warning">x</button>
								</li>
							@endif
						@endforeach
					@else
						<li>There are currently no flagged posts.</li>
					@endif
				</ul>
			</div>
		</li>
		{{-- Flagged Comments. Visible regardless of the view --}}
		<li class="admin-sidebar-option">
			<a href="#adminItemTwo" data-toggle="collapse" data-parent="#admin-accordion">
				Flagged Comments
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemTwo" class="collapse">
				<ul class="list-unstyled">
					@if ( count( $flagged_comment_content ) )
						@foreach ( $flagged_comment_content as $content )
							@if ( is_object( $content->comment ) )
								<li data-flagged-content-id="{{ $content['_id'] }}">
									<a class="flagged-comment" href="{{ URL::to('posts/'.$content->comment->post->alias.'#comment-'.$content->comment->id ) }}" target="_blank">
										<span class="flagged-comment-body">{{ substr( $content->comment->body, 0, 40 ) }}...</span>
										<span>  by: </span>
										<span class="flagged-comment-author">{{ $content->comment->author['username'] }}</span>
									</a>
									<button class="mod-remove-flagged-comment btn btn-xs btn-warning">x</button>
								</li>
							@endif
						@endforeach
					@else
						<li>There are currently no flagged comments.</li>
					@endif
				</ul>
			</div>
		</li>

		{{-- Stats --}}
		<li class="admin-sidebar-option">
			<a href="#adminItemThree" data-toggle="collapse" data-parent="#admin-accordion">
				Stats
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemThree" class="collapse">
				<h5>User Stats</h5>
				<ul class="list-unstyled">
					<li>Total Users: {{ $num_users }}</li>
					<li>Total Confirmed Users: {{ $num_confirmed_users }}</li>
					<li>Users Created Today: {{ $num_users_created_today }}</li>
				</ul>
				<h5>Post Stats</h5>
				<ul class="list-unstyled">
					<li>Total Published Posts: {{ $num_published_posts }}</li>
					<li>Posts Published Today: {{ $num_published_posts_today }}</li>
					<li>Posts Drafted Today: {{ $num_drafts_today }}</li>
				</ul>
			</div>
		</li>

		{{-- Post Admin/Mod controls. Only visible at the post page --}}
		@if ( isset( $is_post_page ) )
		<li class="admin-sidebar-option">
			<a href="#adminItemFour" data-toggle="collapse" data-parent="#admin-accordion">
				Post Controls
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemFour" class="collapse">
				@yield('admin-mod-post-controls')				
			</div>			
		</li>
		@endif

		@if ( isset( $is_profile_page ) )
		<li class="admin-sidebar-option">
			<a href="#adminItemFive" data-toggle="collapse" data-parent="#admin-accordion">
				User Controls
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemFive" class="collapse">
				@yield('admin-mod-user-controls')	
			</div>
		</li>
		@endif

		@if ( isset( $is_categories_page ) )
		<li class="admin-sidebar-option">
			<a href="#adminItemSix" data-toggle="collapse" data-parent="#admin-accordion">
				Category
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemSix" class="collapse">
				@yield('admin-mod-category-controls')	
			</div>
		</li>
		@endif
	</ul>

</div>

<div id="offcanvas-admin-placeholder">
	<div class="toggle-admin-sidebar">
		@if ( $is_mod && !$is_admin )
		M<br>o<br>d<br>e<br>r<br>a<br>t<br>o<br>r
		@else
		A<br>d<br>m<br>i<br>n
		@endif
	</div>
</div>