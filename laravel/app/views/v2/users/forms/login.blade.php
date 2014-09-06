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
        <input tabindex="1" placeholder="Username" type="text" name="username" id="username" value="{{{ Input::old('username') }}}">

        <input tabindex="2" placeholder="Password" type="password" name="password" id="password">

        {{--
        <label for="remember" class="checkbox">Remember
            <input type="hidden" name="remember" value="0">
            <input tabindex="4" type="checkbox" name="remember" id="remember" value="1">
        </label>
        --}}
        <div class="forgot-pw">
            <a href="{{Config::get('app.url')}}/user/forgot">forget your password?</a>
        </div>
        <div class="form-actions">
            <button tabindex="3" type="submit" class="btn btn-flat-gray">Login</button> 
        </div>
        <div class="login-disclaimer">
            Read our guidelines on <a href="{{Config::get('app.url')}}/etiquette">Community Etiquette</a>.
        </div>
    </fieldset>
</form>