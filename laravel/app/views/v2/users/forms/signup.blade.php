<?php
	if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$form_url = secure_url('user');
	} else {
		$form_url = URL::to('user');
	}
?>

<form method="POST" action="{{ $form_url }}" accept-charset="UTF-8">
	
	@if ( Session::get('notice') )
		<div class="alert alert-yes">{{ Session::get('notice') }}</div>
	@endif
	
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset>
        <input id="username" placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ Input::old('username') }}}" maxlength="15" minlength="3" required>
		
		<div class="email-group">
	        <input placeholder="Email*" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
		</div>

		<div class="email-subtext">
			*Emails are optional, you'll need it if you forget your password.
		</div>
		
        <input placeholder="Password" type="password" name="password" id="password" minlength="6">

        <input placeholder="Password Confirmation" type="password" name="password_confirmation" id="password_confirmation" minlength="6" >
		<br/>
		<img src="{{Config::get('app.url')}}/user/captcha">		
		<input type="text" name="captcha" placeholder="What's the answer above?">


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
        <div class="redirect-other">
	        Already have an account? <a href="{{Config::get('app.url')}}/user/login">Login now</a>
	    </div>

    </fieldset>
</form>