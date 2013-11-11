<script type="text/javascript">
	function auth_user_id() {
		return <?php echo Auth::user()->id; ?>;
	}
</script>

<!--Loading Message-->
<script type="text/x-handlebars" data-template-name="loading">
	<div class="container">
		<div class="col-md-12 col-lg-12">
			<h3>Give us a second as we load....</h3>
		</div>
	</div>
</script>

<!--Application -->
<script type="text/x-handlebars" data-template-name="application">
	<div class="container" role="main" id="main">
  	{{partial 'menu'}}
    {{outlet}}
    {{partial 'footer'}}
    </div>
</script>

<!--Main Page Display-->
<script type="text/x-handlebars" data-template-name="index">
	
	<div class="profile_info">
		{{username}} {{#link-to 'profiles.edit' id}}Edit Profile {{/link-to}}
	</div>
	<div class="container">
		{{outlet main}}
	</div>
</script>

<!--Ember Partials-->

<!--Menu-->
<script type="text/x-handlebars" data-template-name="_menu">
	<header class="container">
		<div class="col-md-12 col-lg-12">
			
			<nav>
				<ul>
					<li>
						{{#link-to 'index' }}Home{{/link-to}}
					</li>
					<li>
						{{#link-to 'posts' }}My Posts{{/link-to}}
					</li>
					<li>
						{{#link-to 'profiles' }}Profile{{/link-to}}
					</li>
					<li>
						{{#link-to 'messages' }}Messages{{/link-to}}
					</li>
				</ul>
			</nav>
			
		</div>
	</header>
</script>

<script type="text/x-handlebars" data-template-name="_footer">
	<div class="footer-wrapper">
	<footer>
		<div class="">
			
		</div>
	</footer>
	</div>
</script>