{{--Fixed Join banner (Not necesarrily join, could have other info)--}}
@if ( $prompt instanceof Prompt )
	<div class="join-banner">
		<div class="join-text col-md-7 col-sm-8 col-xs-8">
			<h4>{{$prompt->body}}</h4>
			<p>
			@if ( Auth::guest() )
				Sign up to post your stories, follow, and comment.
			@else 
				Go out there and be somebody.
			@endif
			</p>
		</div>
		<div class="join-button col-md-5 col-sm-4 col-xs-4">
			<a class="btn-flat-blue" href="{{$prompt_link}}">
				
				@if( $prompt->link == 'signup' )
					Create An Account
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
						echo ( isset($prompt) && $prompt instanceof Prompt ) ? $prompt['body'] : 'Our Stories Live here.';
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

<div class="footer-container">
	<div class="container">
		<div class="row">
			<div class="footer-nav">
				<ul>
					<li>
						<a href="{{Config::get('app.url')}}/about">About</a>
					</li>
					<li> | </li>
					<li>
						<a href="{{Config::get('app.url')}}/etiquette">etiquette</a>
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
			</div>
		</div>
	</div>
</div>