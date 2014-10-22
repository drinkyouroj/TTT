
<!--Settings Template-->
<script type="text/x-handlebars-template" id="settings-template">
	<div class="col-md-4 avatar">
		<div class="upload-form">

			<form id="uploadAvatar" method="post" action="{{this.site_url}}rest/profile/image/upload">
	            <input type="hidden" name="image" class="image">
	            <div class="thumb-container" style="background-image:url('{{user_image}}');">
	            </div>
			</form>
			<a class="btn-flat-light-gray avatar-modal">Choose an Avatar</a>

			<div id="avatarErrors"></div>

			<div id="avatarOutput" style="display:none">
	        </div>
		</div>
	</div>
	<div class="col-md-4 change-password">
		<h2>Change Your Password</h2>
		<div class="password-message">
			
		</div>
		<div class="reset-pass">
			<form role="form" class="form-horizontal" id="changePassword" method="post" action="{{this.site_url}}rest/profile/password">
				<div class="form-group">
					<div class="col-sm-12">
						<input type="password" name="current_password" class="current_password" placeholder="current password">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<input type="password" name="password" class="password" placeholder="new password">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<input type="password" name="password_confirmation" class="password_confirmation" placeholder="confirm new password">
					</div>
				</div>

				<button class="btn btn-default btn-flat-dark-gray">Change Password</button>

				<div class="message-box"></div>
			</form>

		</div>
	</div>
	<div class="col-md-4">
		
		{{#ifCond email 1}}
		<h2>Update Your Email</h2>
		{{/ifCond}}

		{{#ifCond email 0}}
		<h2>Verify Your Email</h2>
		{{/ifCond}}
		<form role="form" class="form-horizontal" id="email-update-form" method="post" action="{{this.site_url}}/rest/profile/email/update">
			<div class="form-group">
				<div class="col-sm-12">
					<input type="email" name="email" class="new-email" placeholder="new email">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<input type="password" name="password" class="current-password" placeholder="password">
				</div>
			</div>
			<button class="btn btn-default btn-flat-dark-gray">Change Email</button>
		</form>
		<p class="email-update-success hidden">
			Thank you! Please check your email for a verification link.
		</p>
		<p class="email-update-error hidden">
		</p>
	</div>

	<div class="col-md-12 del-acc">
		<h2>Deactivate Your Account</h2>
		<p>
			This will deactivate your account from the system.  All of your content will be unpublished (but they will remain in place)
		</p>
		<p>Should you decide to come back, all of your content will be republished and your user will re-appear.</p>
		<a class="delete-button" data-toggle="modal" data-target="#deleteModal">Deactivate My Account</a>
	</div>
</script>