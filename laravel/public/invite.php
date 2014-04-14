<?php
$url = $_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]; 
		
if (isset($_POST['done'])) 
    { 

        $site = "http://".$_POST['user'].":".$_POST['pass']."@".$url.'featured'; 

        header("Location: ".$site); 
    }
?>
<html> 

<head>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.14.1/build/cssreset/cssreset-min.css">
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
 
<style type="text/css">
html{color:#000;background:#000}

body,html {
	background:url('img/landing/skyline-bg.jpg');
	background-size:cover;
}

.logo-landing {
	text-align:center;
	margin:0px;
	padding-top:200px;
}

.logo-landing img {
	width:100%;
	max-width:681px;
}

.logo-landing h2 {
	color:#fff;
	font-family: 'Montserrat', sans-serif;
	font-size:21px;
}

form.password {
	text-align:center;
}

form.password input.password-field {
	width:100%;
	max-width:250px;
	height:45px;
	border-radius: 20px;
  	-webkit-border-radius: 20px; 
  	-moz-border-radius: 20px; 
  	border-radius: 20px; 
  	
  	font-family: 'Montserrat', sans-serif;
  	text-align:center;
  	outline: none !important;
}

form.password input.submit-btn{
	width:100px;
	height:45px;
	margin:20px auto 0px auto;
	background:#fff;
	border-radius: 5px;
  	-webkit-border-radius: 5px;
  	-moz-border-radius: 5px;
  	border-radius: 5px;
  	border-bottom:3px solid #ccc;
  	border-right:3px solid #ccc; 
  	border-left:0px;
  	border-top:0px;
  	
  	font-family: 'Montserrat', sans-serif;
  	font-size:16px;
  	text-transform:uppercase;
}

</style>
</head>
	<div class="col-md-10 logo-landing">
		<h1><img src="img/global/logo-main.png" alt="The Two Thousand Times"></h1>
		<h2>BETA</h2>
	</div>
	
    <form class="password" action="<?php echo 'http://'.$url ?>" method="POST"> 
        <input type="hidden" name="user" value="twothousand"><br> 
        <input type="password" class="password-field" name="pass" placeholder="enter password here" onfocus="this.placeholder = ''" onblur="this.placeholder = 'enter password here'"><br> 
        <input type="submit" class="submit-btn" name="done" value="SUBMIT"> 
    </form> 
</html>
