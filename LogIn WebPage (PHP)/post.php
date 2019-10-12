<?php 
	error_reporting(E_ALL);
	ini_set('display_errors','Off');
	ini_set('error_log','error.log');

	session_id('curUser');
	session_start(); 
	if(isset($_SESSION['username']))
:?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="post.css">
	<link rel="icon" href="../favicon.png">
	<title>POST</title>
</head>	
<body>
	<form method = "post" enctype="multipart/form-data">
		TITLE <br><input type="text" name="title" placeholder="Your title here..." required><br><br>
		IMAGE <br><input type="file" name="image" accept="image/png, image/jpeg" required><br><br>
		DESCRIPTION <br><textarea cols='50' rows='10'  name="content" placeholder="Your start-up description here ..." required></textarea><br>
		<div id="task"></div> 
		<input type="button" value = "Add Task" onclick="addTask()"><br>
		<input type="submit" name="tryPost" value="Post">
	</form>

	<ul>
		<li><a href="./currentUser.php">BACK TO HOME</a></li>
		<li><a href="./LogOut.php">LOG OUT</a></li>
	</ul>
</body>
	<script>
	var counter = 0;
	function addTask() {
		var newTask = document.createElement("input");
		newTask.placeholder = "Task..";
		newTask.type = "Text";
		newTask.name = 'task[' + counter + ']';
		document.getElementById("task").append(newTask);

		var newReward = document.createElement("input");
		newReward.placeholder = "Reward..";
		newReward.type = "Text";
		newReward.name = 'reward[' + counter + ']';
		document.getElementById("task").append(newReward);

		document.getElementById("task").append(document.createElement("br"));
		counter += 1;
		}
	</script>
</html>
	
<?php
	if(isset($_POST['tryPost'])) {
		require 'connect.php';
		$title = $_POST['title'];
		$content = mysqli_real_escape_string ($conn , $_POST['content']);
		$taskArray = $_POST['task'];
		$rewardArray = $_POST['reward'];

		if(empty($taskArray[0])) {
			header("Location: post.php?error=notaskfound");
			exit();
		}

		if($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0) {
			$img = $_FILES['image']['tmp_name'];
			$path = '..\uploads\\'.$_FILES["image"]["name"];
			move_uploaded_file($img, $path); 
		}
		
		$prep = mysqli_stmt_init($conn);
		
		if(!mysqli_stmt_prepare($prep, "INSERT INTO posts (title, content, imagefile) VALUES (?,?,?)")) {
			header("Location: post.php?errorconnection");
			exit();
		} else {
			mysqli_stmt_bind_param($prep, 'sss', $title, $content, $path); //sss means string-string-string
			mysqli_stmt_execute($prep);
			$pid = mysqli_stmt_insert_id($prep);
			if($pid == 0) {
				header("Location: post.php?errorpostnotfound");
				exit();
			} else {
				mysqli_stmt_reset($prep);
				mysqli_stmt_prepare($prep, "INSERT INTO tasks (pid, task, reward) VALUES (?,?,?)");
				mysqli_stmt_bind_param($prep, 'iss', $pid, $tasks, $rewards); //iss means int-string-string
				$rcounter = 0;
				foreach ($taskArray as $tasks) {
					$rewards = $rewardArray[$rcounter];
					if(empty($rewards)) { $rewards = 'None/Voluntary'; }
					$rcounter += 1;
					if(!empty($tasks)) { mysqli_stmt_execute($prep); }
				} //foreach closing
			} //else closing
		}
	}
?>

<?php else : include("logout.php"); header("Location: LogIn.php"); ?>
<?php endif;  