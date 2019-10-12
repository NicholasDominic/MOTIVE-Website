<?php

	if(!isset($_SESSION['curuser']))
	{
		header("Location: LogIn.php");
		exit();
			
	}