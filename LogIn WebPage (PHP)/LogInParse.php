<?php
	error_reporting(E_ALL);
	ini_set('display_errors','Off');
	ini_set('error_log','error.log');
	session_id('LogIn');
	session_start();

	if(isset($_POST['tryLogin'])) {	
		require 'connect.php';
		$binusID = $_POST['binusID'];
		$password = $_POST['password'];
		$_SESSION['formData'] = $binusID;
		$_SESSION['log'] = array();

		if(empty($binusID)) {
			header("Location: LogIn.php?error=emptyuserfield&username=");
			$_SESSION['log'][] = "Please insert your Binusian ID.";
		}

		if(empty($password)) {
			header("Location: LogIn.php?error=emptypwfield");
			$_SESSION['log'][] = "Please insert your password.";
		}

		else if(!preg_match("/^[0-9]*$/", $binusID)) {
			header("Location: LogIn.php?error=invalidid");
			$_SESSION['log'][] = "Please insert valid Binusian ID.";
		}
		
		if(!empty($_SESSION['log'])) {
			exit();
			header("Location: LogIn.php?error");
		} else {
			$sql = "SELECT uid, binusID, pwd, email, verified, username from usertable where binusID=?"; 
			$prep = mysqli_stmt_init($conn);

			if(!mysqli_stmt_prepare($prep, $sql)) {
				header("Location: LogIn.php?error=connectioninvalid");
				$_SESSION['log'][] = "Error connection, please try again later.";
				exit();
			} else {
				mysqli_stmt_bind_param($prep, "s", $binusID);
				mysqli_stmt_execute($prep);
				mysqli_stmt_bind_result($prep, $uid, $binusID, $dbPwd, $email, $verified, $username);
				
				if(mysqli_stmt_fetch($prep)) {
					mysqli_stmt_close($prep);
					$checkpw = password_verify($password, $dbPwd);
					
					if($checkpw == true) {		
						session_unset(); session_destroy();		
						session_id('curUser');
						session_start();
						$_SESSION['username'] = $username;
						$_SESSION['binusID'] = $binusID;
						$_SESSION['email'] = $email;
						$_SESSION['uid'] = $uid;
								
						if($verified == FALSE) {
							header("Location: Unconfirmed.html");
							exit();
						}								
						
						header("Location: currentUser.php?");
						exit();					
					} else {
						header("Location: LogIn.php?error=invaliduser&");
						$_SESSION['log'][] = "Invalid ID/Password";
						exit();
					}
				} else {
					header("Location: LogIn.php?error=invaliduser");
					$_SESSION['log'][] = "Invalid ID";
					exit();
				}
				
			}	
		}
	} else {
		header("Location: ./logIn.php");
		exit();
	}