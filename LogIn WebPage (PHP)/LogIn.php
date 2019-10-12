<?php 
	session_start();
	if(isset($_SESSION['username'])) {
		header("Location: currentUser.php");
		exit();
	}
	session_unset();
	session_destroy();
	session_id('LogIn');
	session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
   	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>[LOGIN] motive</title>
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="icon" type="text/css" href="../favicon.png">
</head>
<body>
	<div class="bg">
		<img src="../e.jpg" alt="BACKGROUND">
	</div>

	<div class = "login-box" >
		<div class="motive"><a href="../homepage.html"><img src="../logo.png"></a></div>
		<div class="txt">LOGIN FORM</div><br>
		<form method = "post" action="LogInParse.php">
			<div class = "input-field">
				<div class="input-binusid">
					Username<br><input type="text" name="binusID" placeholder="Binusian ID" value = "<?php if(isset($_SESSION['formData'])){ echo htmlentities($_SESSION['formData']); } ?>" required> <br><br>
				</div>
				<div class="input-pass">
					Password<br><input type="password" name="password" placeholder="Recent password" required> <br><br>
				</div>
				<input type = "submit" name="tryLogin" value = "LOG IN"> </input> <br><br>
			</div>
		</form>
		
		<div class="box-foot">
			<a href="/forgot">Forgot password?</a><br>
			<a href="./SignUp.php">Sign up now!</a>
		</div>		
	</div>

	<?php 
		if(!empty($_SESSION['log'])) {
			echo '<ul class="errlog">';
			foreach($_SESSION['log'] as $error) echo '<li class="errlogitems">'.$error.'</li>';
			echo '</ul>';
		}
	?>
	
	<?php unset($_SESSION['formData'], $_SESSION['log']) ?>
</body>
</html>