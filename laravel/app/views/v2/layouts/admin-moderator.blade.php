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
							@if ( $content->post )
								<li data-flagged-post-id="{{ $content->post->id }}">
									<a class="flagged-post-title" href="{{ URL::to('posts/'.$content->post->alias ) }}" target="_blank">{{ $content->post->title }}</a>
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
							@if ( $content->comment )
								<li data-flagged-comment-id="{{ $content->comment->id }}">
									<a class="flagged-comment" href="{{ URL::to('posts/'.$content->comment->post->alias.'#comment-'.$content->comment->id ) }}" target="_blank">
										<span class="flagged-comment-body">{{ $content->comment->body }}</span>
										<br>
										<span class="flagged-comment-author">{{ $content->comment->author['username'] }}</span>
									</a>
								</li>
							@endif
						@endforeach
					@else
						<li>There are currently no flagged comments.</li>
					@endif
				</ul>
			</div>
		</li>

		{{-- Post Admin/Mod controls. Only visible at the post page --}}
		@if ( isset( $is_post_page ) )
		<li class="admin-sidebar-option">
			<a href="#adminItemThree" data-toggle="collapse" data-parent="#admin-accordion">
				Post Controls
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemThree" class="collapse">
				@yield('admin-mod-post-controls')	
			</div>
		</li>
		@endif

		@if ( isset( $is_profile_page ) )
		<li class="admin-sidebar-option">
			<a href="#adminItemFour" data-toggle="collapse" data-parent="#admin-accordion">
				User Controls
				<span class="glyphicon glyphicon-plus pull-right"></span>
			</a>
			<div id="adminItemFour" class="collapse">
				@yield('admin-mod-user-controls')	
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