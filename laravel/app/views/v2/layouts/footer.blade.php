{{--Fixed Join banner (Not necesarrily join, could have other info)--}}
@if ( $prompt instanceof Prompt )
	<div class="join-banner">
		<div class="join-text col-md-10 col-sm-9">
			<h4>{{$prompt->body}}</h4>
			<p>
			@if ( Auth::guest() )
				Sign up to post your stories, follow, and comment.
			@else
				Write about it now.
			@endif
			</p>
		</div>
		<div class="join-button col-md-2 col-sm-3">
			<a class="btn-flat-blue" href="{{$prompt_link}}">
				
				@if( $prompt->link == 'signup' )
					Signup
				@elseif ( $prompt->link == 'post_input' )
					Post Now
				@endif

			</a>
		</div>
	</div>
@endif


@if(Request::segment(1) != 'user')
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="signupModalLabel">
					<?php 
						echo ( isset($prompt) && $prompt instanceof Prompt ) ? $prompt['body'] : 'Our Stories Live Here.';
					?>
				</h3>
				<h4 class="modal-subtitle" id="signupModalSublabel">
					Sign up to post your stories, follow, and comment.
				</h4>
			</div>
			<div class="modal-body hidden-xs">
				
				{{ View::make('v2.users.forms.signup') }}
				<p class="optional">*Emails are optional, you'll need it if you forget your password.</p>
			</div><!--End of Modal Body-->

			<div class="modal-body-mobile visible-xs">
				 <a href="{{ URL::to( 'user/signup' ) }}" class="btn btn-flat-blue signup">Signup</a>
				 <a type="button" class="no-thanks" data-dismiss="modal"><span aria-hidden="true">no thanks</a>
				 <div class="redirect-other">
	        		Already have an account? <a href="{{Config::get('app.url')}}/user/login">Login now</a>
	   			 </div>
			</div><!--End of Modal Body-->
			
		</div>
	</div>
</div>
@endif

@if(Input::get('ttt_redirect',0))
<div class="modal fade" id="sondryModal" tabindex="-1" role="dialog" aria-labelledby="sondryModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="sondryModalLabel">
					What's Sondry?
				</h3>
			</div>
			<div class="modal-body">

				<p class="bold dear">Hey Everyone,</p>
	 
				<p>Sondry originates from Middle English, and is often used to refer to sondry folk, meaning a diverse group of people. Which is exactly what we have here.</p>
				 
				<p>After receiving feedback from many of our incredible storymakers, this change is being made to better define the platform you’ve helped grow, and grow it has. 250,000 people have viewed the stories you made in under two months. But we’re just getting started. Sondry both signifies and accompanies the increased expansion of our community. With your help, we are growing exponentially. So thank you. Please continue to share our site with storymakers all over the world.</p>
				 
				<p>As always, feel free to email us directly at team@sondry.com.</p>
				 
				<p>Thanks again. Let’s change the world.</p>
				<p class="bold signoff">Team</p>
				<img class="sign-logo" src="{{ URL::to('images/global/sondry-logo-min.png') }}" alt="SONDRY">
				<img class="ttt-icon" src="{{Config::get('app.url')}}/images/global/gold-icon.png">
			</div><!--End of Modal Body-->
			<div class="modal-footer col-md-12">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
@endif

<div class="footer-container">
	<div class="container">
		<div class="row">
			<div class="footer-nav">
				@if(Request::segment(1) != 'categories')
					<ul class="main-footer-nav">
						<li>
							<a href="{{Config::get('app.url')}}/about">About</a>
						</li>
						<li> | </li>
						<li>
							<a href="{{Config::get('app.url')}}/etiquette">etiquette</a>
						</li>
					</ul>
					<ul>
						<li>
							<a href="{{Config::get('app.url')}}/contact">Contact</a>
						</li>
						<li> | </li>
						<li>
							<a href="{{Config::get('app.url')}}/privacy">Privacy Policy</a>
						</li>
						<li> | </li>
						<li>
							<a href="{{Config::get('app.url')}}/terms">Terms of Use</a>
						</li>
					</ul>
				@else
					<ul>
						<li>
							<a class="main-link" href="{{Config::get('app.url')}}/about">About</a>
						</li>
						<li> | </li>
						<li>
							<a class="main-link" href="{{Config::get('app.url')}}/etiquette">etiquette</a>
						</li>
						<li> | </li>
						<li>
							<a href="{{Config::get('app.url')}}/contact">Contact</a>
						</li>
						<li> | </li>
						<li>
							<a href="{{Config::get('app.url')}}/privacy">Privacy Policy</a>
						</li>
						<li> | </li>
						<li>
							<a href="{{Config::get('app.url')}}/terms">Terms of Use</a>
						</li>
					</ul>
				@endif
			</div>
		</div>
	</div>
</div>