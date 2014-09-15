<div class="animated fadeIn user-id-{{$user->id}}">
	<div class="generic-item">
		<section>
			<a href="{{ URL::to('profile/'.$user->username) }}">
				<div class="the-image user-avatar" style="background-image: url('{{Config::get('app.url')}}/rest/profileimage/{{$user->id}}');">
				</div>
			</a>
		</section>
		<header>
			<h3 class="user-name">{{ link_to('profile/'.$user->username, $user->username) }}</h3>
		</header>
	</div>
</div>