<div class="animated fadeIn col-md-12 user-id-{{$user->id}}">
	<div class="generic-item">
		<header>
			<h3>{{ link_to('profile/'.$user->username, $user->username) }}</h3>
		</header>
		<section>
			<div class="the-image">
				<a href="{{ URL::to('profile/'.$user->username) }}" style="background-image: url('{{Config::get('app.url')}}/rest/profileimage/{{$user->id}}');">
				
				</a>
			</div>
		</section>
	</div>
</div>