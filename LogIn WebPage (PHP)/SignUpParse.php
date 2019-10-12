<?php

error_reporting(E_ALL);
ini_set('display_errors','Off');
ini_set('error_log','error.log');

session_id('signup');
session_start(); 

$_SESSION["error"]=array();
if(isset($_POST['trySignUp'])){
	
	require 'connect.php';
	
	mysqli_query($conn, 
	"CREATE TABLE IF NOT EXISTS usertable (
		uid int AUTO_INCREMENT PRIMARY KEY NOT NULL,
		username varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		binusID varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		pwd varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		email varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		phonenum varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		phonenum2 varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		verified tinyint(1) DEFAULT '0'
		);
	");
	
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confpwd  = $_POST['confirmpassword'];
	$email	  = $_POST['email'];
	$phonenum = $_POST['phonenumber'];
	$binusID  = $_POST['binusID'];
	if(isset($_POST['phonenumber2'])){ $phonenum2= $_POST['phonenumber2']; }
	else { $phonenum2= NULL; }
	
	$_SESSION['formData'] = array(
					'us' => $username,
					'em' => $email,
					'pho'=> $phonenum,
					'bid'=>	$binusID,
					'ph2'=> $phonenum2
					);
	if(empty($email))
	{
		header("Location: SignUp.php?error=emptyemailfield");
		$_SESSION["error"][] = "Please enter your e-mail";
	}
	else if(!filter_var($email,FILTER_VALIDATE_EMAIL))
	{
		header("Location: SignUp.php?error=mailinvalid");
		$_SESSION["error"][] = "Please enter valid an e-mail";
	}
	if(empty($username))
	{
		header("Location: SignUp.php?error=emptyuserfield&username=".$_POST['username']);
		$_SESSION["error"][] = "Please enter your username";
	}
	if(empty($phonenum))
	{
		header("Location: SignUp.php?error=emptyphonenum&username=".$_POST['username']);
		$_SESSION["error"][] = "Please enter your phone number";
	}
	if(empty($binusID))
	{
		header("Location: SignUp.php?error=emptybinusianid&username=".$_POST['username']);
		$_SESSION["error"][] = "Please enter your binusian ID";
	}
	if(empty($password))
	{
		header("Location: SignUp.php?error=emptypwfield");
		$_SESSION["error"][] = "Please enter password";
	}
	if(empty($confpwd))
	{
		header("Location: SignUp.php?error=emptypwfield");
		$_SESSION["error"][] = "Please confirm your password";
	}
	if($password !== $confpwd)
	{
		header("Location: SignUp.php?error=pwddoesntmatch");
		$_SESSION["error"][] = "Password didn't match, please try again";
	}
	
	if(!preg_match("/^[a-zA-Z0-9]*$/", $username))
	{
		header("Location: SignUp.php?error=invalidname&username=");
		$_SESSION["error"][] = "Please input valid username (numbers & alphabets)";
	}
	if(!preg_match("/^[0-9]*$/", $binusID))
	{
		header("Location: SignUp.php?error=invalidbinusID&username=");
		$_SESSION["error"][] = "Please input valid binusian ID";
	}
	if(!preg_match("/^[0-9]*$/", $phonenum))
	{
		header("Location: SignUp.php?error=invalidphonenumber&username=");
		$_SESSION["error"][] = "Please input valid phone number";
	}
	if(NULL !== $phonenum2)
	{
		if(!preg_match("/^[0-9]*$/", $phonenum2))
		{
		header("Location: SignUp.php?error=invalidphonenumber&username=");
		$_SESSION["error"][] = "Please input valid secondary phone number";
		}
	}
	if(!empty($_SESSION["error"] )){exit();}
	else
	{
		$matchk = "SELECT binusID FROM usertable WHERE binusID=?";
		$prep = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($prep, $matchk)){
			header("Location: SignUp.php?error=connectioninvalid");
			$_SESSION["error"][] = "Sorry, there\'s an error while connecting to the server, please try again later";
		}
		else{
			mysqli_stmt_bind_param($prep, "s", $binusID);
			mysqli_stmt_execute($prep);
			mysqli_stmt_store_result($prep);
			$same = mysqli_stmt_num_rows($prep);
			if ($same > 0)
			{
				header("Location: SignUp.php?error=idtaken");
				$_SESSION["error"][] = "Sorry, but that binus identification is registered.";
				exit();
			}
			else
			{
				mysqli_stmt_reset($prep);
				$insert = "INSERT INTO usertable (username, binusID, pwd, email, phonenum, phonenum2, verified) VALUES (?, ?, ?, ?, ?, ?, ?)";
				if(!mysqli_stmt_prepare($prep, $insert)){
					header("Location: SignUp.php?error=connectioninvalid");
					$_SESSION["error"][] = "Sorry, there's an error while connecting to the server, please try again later" ;
					exit();
				}
				else
				{
					$hashedpw = password_hash($password, PASSWORD_DEFAULT);
					$falselol = 0;
					mysqli_stmt_bind_param($prep, "ssssssi", $username, $binusID, $hashedpw, $email, $phonenum, $phonenum2, $falselol);
					mysqli_stmt_execute($prep);
					mysqli_stmt_store_result($prep);
					header("Location: SignUp.php?succesful");
					$_SESSION["error"][] = "Succesful register, please ask/wait for ID verification.";
					exit();
					
				}
			}
		}
	}
	mysqli_stmt_close($prep);
	mysqli_close($conn);
}
else
{
	header("Location: SignUp.php?");
	exit();
}
