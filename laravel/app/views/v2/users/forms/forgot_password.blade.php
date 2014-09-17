<form method="POST" action="{{{ URL::to('/user/forgot') }}}" accept-charset="UTF-8">
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">

    

    <div class="input-append">
        
        <input placeholder="Username" type="text" name="username" id="username" value="{{{ Input::old('username') }}}">

        <input placeholder="Email" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">

        
        <div class="form-actions">
          <button type="submit" class="btn btn-flat-gray">Submit</button>
        </div>
    </div>

    @if ( Session::get('error') )
        <div class="alert alert-error">
            @if(is_string(Session::get('error')))
                {{{ Session::get('error') }}}
            @else
                <ul>
                @foreach(Session::get('error')->all() as $message)
                    <li>{{$message}}</li>
                @endforeach
                </ul>
            @endif
        </div>
    @endif

    @if ( Session::get('notice') )
        <div class="alert">{{{ Session::get('notice') }}}</div>
    @endif
</form>