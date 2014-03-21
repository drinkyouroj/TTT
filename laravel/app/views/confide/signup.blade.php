<form method="POST" action="{{{ (Confide::checkAction('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8">

	@if ( Session::get('error') )
	<div class="alert alert-error">
		@if ( is_array(Session::get('error')) )
			{{ head(Session::get('error')) }}
		@endif
		
	</div>
	@endif
	
	
	@if ( Session::get('notice') )
		<div class="alert alert-yes">{{ Session::get('notice') }}</div>
	@endif
	
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset>
        <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
        <input placeholder="{{{ Lang::get('confide::confide.username') }}}" type="text" name="username" id="username" value="{{{ Input::old('username') }}}">
		
		<div class="email-please">
			<a class="email-show">Verify account with your email?</a><span class="recommended"> You'll need to if you forget your password.</span>
		</div>
		
		<div class="email-group">
	        <label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
	        <input placeholder="{{{ Lang::get('confide::confide.e_mail') }}}" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
		</div>
		
        <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
        <input placeholder="{{{ Lang::get('confide::confide.password') }}}" type="password" name="password" id="password" minlength="6">

        <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
        <input placeholder="{{{ Lang::get('confide::confide.password_confirmation') }}}" type="password" name="password_confirmation" id="password_confirmation" minlength="6" >
		
		{{Form::captcha()}}
		
        <div class="form-actions">
          <button type="submit" class="btn">{{{ Lang::get('confide::confide.signup.submit') }}}</button>
        </div>

    </fieldset>
</form>
