<!DOCTYPE html>	
<?php 
		session_id('signup');
		session_start();
		
 ?>
		
<html>
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	
	<title>LOGIN</title>
	<link rel="stylesheet" href="SignUp.css">
	</head>
	
	<body>
	<div class = "signup-box" >	
			<form method = "post" action="SignUpParse.php">
			<div class="input-field">
				<div class="input-email">
					E-Mail : <input type="text" name="email" placeholder="E-mail" value="<?php if(isset($_SESSION['formData']['em'])){ echo htmlentities($_SESSION['formData']['em']); } ?>"> 
				</div>
				<div class="input-username">
					Username : <input type="text" name="username" placeholder="Username" value="<?php if(isset($_SESSION['formData']['us'])){ echo htmlentities($_SESSION['formData']['us']); } ?>"> 
				</div>
				<div class="input-binusID">
					Binusian ID : <input type="text" name="binusID" placeholder="Binusian ID" value="<?php if(isset($_SESSION['formData']['bid'])){ echo htmlentities($_SESSION['formData']['bid']); } ?>"> 
				</div>
				<div class="input-pass">
					Password : <input type="password" name="password" placeholder="Password"> 
				</div>
				<div class="input-confpass">
					Confirm Password : <input type="password" name="confirmpassword" placeholder="Confirm Password"> 
				</div>
				<div class="input-phone">
					Phone Number : <input type="text" name="phonenumber" placeholder="Phone Number" value="<?php if(isset($_SESSION['formData']['pho'])){ echo htmlentities($_SESSION['formData']['pho']); } ?>"> 
				</div>
				<div class="input-phone2">
					Other Phone Number: <input type="text" name="phonenumber2" placeholder="Other Phone Number (Optional)" value="<?php if(!empty($_SESSION['formData']['ph2'])){ echo htmlentities($_SESSION['formData']['ph2']	); } ?>"> 
				</div>
					<input type = "submit" name="trySignUp" value = "SignUp"> </input>
			</div>
			<?php 
			if(!empty($_SESSION['error']))
			{
				echo '<ul class="errlog">';
					foreach($_SESSION['error'] as $error )
					{
						echo '<li class="errlogitems">'.$error.'</li>';
					}
				echo '</ul>';
			}
			?>
			</form>
			<?php unset($_SESSION['formData'], $_SESSION['error']) ?>
			
			<a href="./LogIn.php">Log in</a>
	</body>
	
</html>
