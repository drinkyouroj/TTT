{{--Modal for the image selection system should go here--}}

<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="signupModalLabel">
					For the stories we tell.
				</h3>
				<h4 class="modal-subtitle" id="signupModalSublabel">
					Sign up to post your stories, follow, share and comment.
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


			<div class="modal-footer hidden-xs">
				<div class="terms-agree">
					By creating an account you agree to our <a class="terms" href="{{Config::get('app.url')}}/terms">Terms Of Use</a> and <a class="terms" href="{{Config::get('app.url')}}/privacy">Privacy Policy</a>.
					<br/>
		            Read our guidelines on <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a>.
		        </div>
			</div>
		</div>
	</div>
</div>

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