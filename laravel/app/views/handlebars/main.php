<script type="text/javascript">
	function user_id() {
		return <?php echo Auth::user()->id; ?>
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


<!--Main Page Display-->
<script type="text/x-handlebars">
	{{partial 'menu'}}
	<div class="container">
		{{outlet main}}
	</div>
</script>


<!--Ember Partials-->
<script type="text/x-handlebars" data-template-name="_menu">
	<header class="container">
		<div class="col-md-12 col-lg-12">
			
			<nav>
				<ul>
					<li>
						{{#link-to 'index' }}Home{{/link-to}}
					</li>
					<li>
						{{#link-to 'posts' }}Posts{{/link-to}}
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
