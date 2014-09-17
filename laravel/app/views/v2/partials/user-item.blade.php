<div class="animated fadeIn user-id-{{$user->id}}">
	<div class="generic-item">
		<section>
			<a href="{{ URL::to('profile/'.$user->username) }}">
				<?php
				$image = isset($user->image) && $user->image ? URL::to('uploads/final_images/'.$user->image) : URL::to('images/profile/avatar-default.png'); 
				?>
				<div class="the-image user-avatar" style="background-image: url('{{$image}}');">
				</div>
			</a>
		</section>
		<header>
			<h3 class="user-name">{{ link_to('profile/'.$user->username, $user->username) }}</h3>
		</header>
	</div>
</div>