<?php 
	error_reporting(E_ALL);
	ini_set('display_errors','Off');
	ini_set('error_log','error.log');

	session_id('curUser');
	session_start(); 
	if(isset($_SESSION['username'])) :
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<title>My Posts</title>
</head>
<body>
	<?php
		require 'connect.php';
		$posts = mysqli_query($conn, "SELECT * FROM posts WHERE uid = ".$_SESSION['uid']);
		$diffCheck = NULL;
		while($rowPost = mysqli_fetch_assoc($posts)) 
		{
			$tasks = mysqli_query($conn, "SELECT * FROM tasks WHERE pid = ".$rowPost['pid']);
			echo '<div><b>'.$rowPost['title'].' : </b></div><br>';
			while($rowTask = mysqli_fetch_assoc($tasks))
			{
				$nameq = mysqli_query($conn, "SELECT username FROM userTable where uid = ".$rowTask['uid']);
				$name = mysqli_fetch_assoc($nameq);
				
				echo 'Task : '.$rowTask['task'].'<br>';
				echo 'Reward : '.$rowTask['reward'].'<br>';
				if($name == NULL)
				{
				echo 'By : -No one has taken this task yet.<br>';	
				}
				else
				{
					echo 'By : '. $name['username'].'<br>';
					if($rowTask['filepath'] == NULL)
					{
						echo 'File : -The user has not submitted any work yet.<br>';
					}
					else
					{
						echo 'File : <input type="submit" name="Download" value="Download"><br>';
						echo '<input type="hidden" name ="taskID" value="'.$rowTask['tid'].'">';
						echo 'Please understand that after you download the file, this activity will be noticed to the worker<br>';
						if($rowTask['stats'] != 2 && $rowTask['stats'] != 3)
						{
							echo '<form method="post" enctype="multipart/form-data">
										<input type="radio" name="Verify" value="Approve">Approve<br>
										<input type="radio" name="Verify" value="Reject">Reject<br>
										<textarea cols="50" rows="10"  name="reject" placeholder="Your reason for rejecting"></textarea><br>
										<input type="submit" name="verFile" value="Submit"><br>
										<input type="hidden" name ="taskID" value="'.$rowTask['tid'].'">
									</form><br>';
						}
					}
				}
				echo '<br><br>';		
			}
		}
	 ?>

	 	<a href="./currentUser.php">Home</a><br>	
	 <?php 
		if(isset($_POST['Download']))
		{
			$pathq = mysqli_query($conn, "SELECT filepath, task FROM tasks where tid = ".$_POST['taskID']);
			$path = mysqli_fetch_assoc($pathq);
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename='".$path['task']."'");
			header("Content-Length: ".filesize($path['filepath']));
			readfile($path['filepath']);
			mysqli_query($conn, "UPDATE tasks SET stats = 1 WHERE tid = ".$_POST['taskID']);
		}
		if(isset($_POST['verFile'])) 
		{
		
			if($_POST['Verify'] == "Reject")
			{
				$ver = 2;
				$reason = mysqli_real_escape_string($conn , $_POST['reject']);
				mysqli_query($conn, "UPDATE tasks SET stats = ".$ver.", rejReason = '".$reason."' WHERE tid = ".$_POST['taskID']);
			}
			else
			{
				$ver = 3;
				mysqli_query($conn, "UPDATE tasks SET stats = ".$ver." WHERE tid = ".$_POST['taskID']);
			}
			
			echo 'File uploaded successfully.';
		 
		}
	?>
</body>
</html>

<?php else : include("logout.php"); header("Location: LogIn.php"); ?>
<?php endif;  