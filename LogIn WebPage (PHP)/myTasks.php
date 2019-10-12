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
	<title>My Tasks</title>
</head>
<body>
	<?php
		require 'connect.php';
		$tasks = mysqli_query($conn, "SELECT * FROM tasks WHERE uid = ".$_SESSION['uid']);
		$diffCheck = NULL;
		while($rowTask = mysqli_fetch_assoc($tasks)) {
			if($rowTask['pid'] != $diffCheck || NULL == $diffCheck) {
				$diffCheck = $rowTask['pid'];
				$posts = mysqli_query($conn, "SELECT pid, title FROM posts WHERE pid = ".$rowTask['pid']);
				$rowPost = mysqli_fetch_assoc($posts);
				echo '<div>'.$rowPost['title'].' : </div>';
			} 

			if($rowTask['stats'] == 0)
			{
				$fin = "You have not submitted any work yet";
			}
			else
			{
				if($rowTask['stats'] == 1) {
					$fin = "The task you've submitted haven't been received yet.";
				} 
				else if($rowTask['stats'] == 2){
					$fin = "The task you've submitted was rejected.";
				}
				else if($rowTask['stats'] == 3){
					$fin = "The task you've submitted is approved, if there's any reward that was offered should be sent to you soon (please contact administrator if reward has not been accepted in due date).";
				}
			}

			echo 'Task : '.$rowTask['task'].'<br>';
			echo 'Status : '.$fin.'<br>';
			echo 'Reward : '.$rowTask['reward'].'<br>';
			if($rowTask['stats'] == 2){
				echo 'The reason of rejection are as follow : <br>';
				echo $rowTask['rejReason'].'<br>';
			}
			echo '('.$rowTask['rewardEligibility'].')';
			echo '<form method="post" enctype="multipart/form-data">
						<input type="file" name="image"  required><br>
						<input type="hidden" name ="taskID" value="'.$rowTask['tid'].'">
						<input type="submit" name="submitFile" value="Submit">
					</form><br>';
		}
	 ?>

	 <a href="./currentUser.php">Home</a><br>

	 <?php 
		 if(isset($_POST['submitFile'])) {
			if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
				$img = $_FILES['image']['tmp_name'];
				$name = $_FILES["image"]["name"];
				$path = '..\uploads\\'.$name;
				move_uploaded_file($img, $path); 
				mysqli_query($conn, "UPDATE tasks SET filepath = '".mysqli_real_escape_string($conn, $path)."' WHERE tid = ".$_POST['taskID']);
				mysqli_query($conn, "UPDATE tasks set stats = 1 WHERE tid = ".$_POST['taskID']);
				echo 'File uploaded successfully.';

			} else echo '<div>error is '.$_FILES['image']['error'].'</div>';
		}
	?>
</body>
</html>

<?php else : include("logout.php"); header("Location: LogIn.php"); ?>
<?php endif;  