
{{--This is the top portion (username and stuff)--}}

{{--Let's determine if this is you or someone else we're looking at--}}
@if(isset($user))
	{? $username = $user->username; ?}
	{? $user_id = $user->id; ?}
	{? $date = $user->created_at; ?}
@else
	{? $username = Session::get('username'); ?}
	{? $user_id = Auth::user()->id; ?}
	{? $date =  Session::get('join_date'); ?}
@endif

<div class="col-md-12 profile-top">
	<ul class="visible-md visible-lg profile-options">
		@include('partials/profile-ul-content')
	</ul>
	
	<div class="user">
		<h2><a href="{{Config::get('app.url')}}/profile/{{$username}}">{{$username}}</a></h2>
		<h3 class="join-date">Member since {{$date->format('m.d.Y') }}</h3>
	</div>
	
	<div class="visible-md visible-lg follow-container">
		@include('partials/follow-following')
	</div>
	
	<div class="mobile-profile-options hidden-md hidden-lg">
		<ul class="profile-options">
			@include('partials/profile-ul-content')
		</ul>
		
		<div class="follow-container mobile-follow-container">
			@include('partials/follow-following')
		</div>
		<div class="clearfix"></div>
	</div>
	
	
<!--Below is the modal for the follow boxes-->
	
<div class="modal fade" id="followbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
      
      <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
      </div>
    <div class="clearfix"></div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	
	
	
	
</div>