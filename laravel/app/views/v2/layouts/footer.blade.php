<div class="footer-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12 footer-nav">
				<ul>
					<li>
						<a href="{{Config::get('app.url')}}/about">About</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/etiquette">etiquette</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/contact">Contact</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/privacy">Privacy Policy</a>
					</li>
					<li> x </li>
					<li>
						<a href="{{Config::get('app.url')}}/terms">Terms of Use</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

{{--If its a guest--}}
@if(Auth::guest() && Request::segment(1) != 'user')
	<div class="modal fade" id="guestSignup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel"></h4>
	      </div>
	      <div class="modal-body">
	        <div class="signup-form">
	        <h4>Signup</h4>
			{{ Confide::makeSignupForm()->render() }}
			</div>
			
			<div class="login-form">
				<h4>Login</h4>
				{{ Confide::makeLoginForm()->render() }}
				<a href="{{Config::get('app.url')}}/user/forgot">forget your password?</a>
			</div>
			<aside class="login-disclaimer">
				Read our guidelines on <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a>.
			</aside>
	      </div>
	    </div>
	  </div>
	</div>
@endif