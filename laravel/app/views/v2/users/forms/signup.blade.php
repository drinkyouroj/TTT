<form method="POST" action="{{{ (Confide::checkAction('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8">
	
	@if ( Session::get('notice') )
		<div class="alert alert-yes">{{ Session::get('notice') }}</div>
	@endif
	
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset>
        <input id="username" placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ Input::old('username') }}}" maxlength="15" minlength="3" required>
		
		<div class="email-please">
			<a class="email-show">Verify account with your email?</a><span class="recommended"> You'll need to if you forget your password.</span>
		</div>
		
		<div class="email-group">
	        <input placeholder="Email" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
		</div>
		
        <input placeholder="Password" type="password" name="password" id="password" minlength="6">

        <input placeholder="Password Confirmation" type="password" name="password_confirmation" id="password_confirmation" minlength="6" >
		
		<br/>
		<br/>
		<img src="{{Captcha::img()}}"/><br/>
		<input type="text" name="captcha" placeholder="Captcha">


        <div class="errors">
        	<ul class="list-unstyled">
	        	<?php
	        	foreach ( $errors->all() as $message ) { ?>
	        		<li>
	        			{{ $message }}
	        		</li>
	        	<?php } 
				?>
			</ul>
        </div>
		
        <div class="form-actions">
          <button type="submit" class="btn btn-flat-gray">Submit</button>
        </div>

        <div class="terms-agree">
			By creating an account you agree to our <a class="terms" href="{{Config::get('app.url')}}/terms">Terms Of Use</a>.
		</div>

    </fieldset>
</form>