<!DOCTYPE html>
<?php
	$have_user = Auth::check();
	$is_mod = Session::get('mod');
	$is_admin = Session::get('admin');
	$is_mobile = Agent::isMobile();
?>
<html>
	<head>
	    <title>
	    	Maintenance, Two Thousand Times
	    </title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	    <!--Favicon-->
	    <link href="{{Config::get('app.staticurl')}}/images/favicon.ico" rel="icon" type="image/x-icon" />
		<link href="{{Config::get('app.staticurl')}}/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	    
	    <!--Fonts-->
	    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700|Fjalla+One' rel='stylesheet' type='text/css'>    
	    <link href='http://fonts.googleapis.com/css?family=Vollkorn:400italic,400,700' rel='stylesheet' type='text/css'>

		{{--Opengraph--}}
		<meta property="og:site_name" content="Two Thousand Times" />
		<meta property="og:url" content="{{Request::url()}}" />

		{{--Schema.org--}}
		<meta itemprop="url" content="{{Request::url()}}" />

		{{--Twitter--}}
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@twothousandx">

		<style>
			@font-face {
			  font-family: 'Garamond';
			  src: url('../../../fonts/garamond/garamondoldstylefs_regular_macroman/GaramondOldstyle-Regular-webfont.eot'); /* IE9 Compat Modes */
			  src: url('../../../fonts/garamond/garamondoldstylefs_regular_macroman/GaramondOldstyle-Regular-webfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
			       url('../../../fonts/garamond/garamondoldstylefs_regular_macroman/GaramondOldstyle-Regular-webfont.woff') format('woff'), /* Modern Browsers */
			       url('../../../fonts/garamond/garamondoldstylefs_regular_macroman/GaramondOldstyle-Regular-webfont.ttf')  format('truetype'), /* Safari, Android, iOS */
			       url('../../../fonts/garamond/garamondoldstylefs_regular_macroman/GaramondOldstyle-Regular-webfont.svg#svgGaramondOldstyle-Regular-webfont') format('svg'); /* Legacy iOS */
			}
			body {
				background-color:#fff8ee;
			}
			.main-container {
				width:100%;
				max-width:700px;
				margin:70px auto 0 auto;
				text-align:center;
		  	}
		  	h1,h2,p {
		  		color:#181919;
		  	}
		  	h1 {
		  		font-family: 'Fjalla One', sans-serif;
		  		font-size:21px;
		  		text-transform:uppercase;
		  		letter-spacing:1px;
		  		padding-bottom:20px;
		  		border-bottom:solid 2px #181919;
			}
			h2 {
			  	font-family: 'Montserrat', sans-serif;
			  	font-size:18px;
			  	text-transform:uppercase;
		  		letter-spacing:1px;
		  		margin-top:20px;
			}
			.line {
				border-top:solid 2px #181919;
				width:20px;
				margin:0 auto;
			}
			p {
	  			font-family: 'Garamond', serif;
	  			letter-spacing:1px;
	  		}
		</style>
	</head>

	<body>
		<div class="main-container">
			<h1>Two Thousand Times</h1>
			<h2>Sorry We're Currently down for Maintenance.</h2>
			<div class="line"></div>
			<p>Check back soon!</p>
		</div>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-53883988-4', 'auto');
		  ga('require', 'displayfeatures');
		  ga('send', 'pageview');
		  window.ga = ga;
		</script>
	</body>
</html>
<!-- {{ $app->environment() }}  {{$_SERVER['SERVER_ADDR']}}-->
