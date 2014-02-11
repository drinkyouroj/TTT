
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
	<ul class="profile-options">
		@if(isset($user) && $user->id != Session::get('user_id'))
			
			@if(!$is_following)
			<li>
				<a class="follow" href="{{Config::get('app.url')}}/follow/{{$user->id}}" data-user="{{$user->id}}">
					<span>Follow {{$user->username}}</span>
				</a>
			</li>
			@else
			<li>
				<a class="follow" href="{{Config::get('app.url')}}/follow/{{$user->id}}" data-user="{{$user->id}}">
					<span>UnFollow {{$user->username}}</span>
				</a>
			</li>
			@endif
			
			@if($mutual)
			<li>
				<a class="new-message" href="{{Config::get('app.url')}}/profile/newmessage/{{$user->id}}">
					<span>Message</span>
				</a>
			</li>
			@endif
			
		@else
			<li>
				<a class="new-post" href="{{Config::get('app.url')}}/profile/newpost">
					<span>Post</span>
				</a>
			</li>
			<li>
				<a class="all-notification" href="{{Config::get('app.url')}}/profile/notifications">
					<span>All Notifications</span>
				</a>
			</li>
			<li>
				<a class="profile-posts" href="{{Config::get('app.url')}}/profile/profileposts">
					<span>My Posts</span>
				</a>
			</li>
		@endif
	</ul>
	
	<div class="user">
		<h2><a href="{{Config::get('app.url')}}/profile/{{$username}}">{{$username}}</a></h2>
		<h3 class="join-date">Member since {{$date->format('m.d.Y') }}</h3>
	</div>
	
	<div class="follow-container">
		<div class="following">
			<div class="numbers">{{$following}}</div>
			<a href="#following" class="following-link" data-user="{{$user_id}}">Following</a>
		</div>
		<div class="followers">
			<div class="numbers">{{$followers}}</div>
			<a href="#followers" class="followers-link" data-user="{{$user_id}}">Followers</a>
		</div>
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
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	
	
	
	
</div>