<form method="POST" action="{{{ URL::to('/user/login') }}}" accept-charset="UTF-8">
	
	    @if( isset($error) )
            <div class="alert alert-error">
            		{? var_dump($error)  ?}
        	</div>
        @endif

        @if ( Session::get('notice') )
            <div class="alert alert-yes">{{ Session::get('notice') }}</div>
        @endif
        
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset>
        <label for="username">Username</label>
        <input tabindex="1" placeholder="Username" type="text" name="username" id="username" value="{{{ Input::old('username') }}}">

        <label for="password">
            Password
            <small>
                <a href="{{{ URL::to('/user/forgot') }}}">Forgot Password</a>
            </small>
        </label>
        <input tabindex="2" placeholder="Password" type="password" name="password" id="password">

        {{--
        <label for="remember" class="checkbox">Remember
            <input type="hidden" name="remember" value="0">
            <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
        </label>
        --}}

        <button tabindex="3" type="submit" class="btn">Login</button>        
    </fieldset>
</form>