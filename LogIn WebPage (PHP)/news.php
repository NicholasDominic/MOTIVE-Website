<?php
session_id('curUser');
session_start(); 
if(isset($_SESSION['username']))
:?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="news.css">
	<link rel="icon" href="../favicon.png">
	<title>News</title>
</head>
<body>
	<?php
		require 'connect.php';
		$counter = 0;
		$result = mysqli_query($conn, "SELECT pid, title, content, imagefile, uid FROM posts ORDER BY pid DESC");
		while($row = mysqli_fetch_assoc($result)) {
			$title = $row['title'];
			$content = $row['content'];
			$img = $row['imagefile']; 
			$pid = $row['pid'];
			$taskArray = array();
			$nameq = mysqli_query($conn,"SELECT username from userTable WHERE uid = '".$row['uid']."'");
			$name = mysqli_fetch_assoc($nameq);
			$tasks = mysqli_query($conn, "SELECT tid, pid, task, stats, uid, reward FROM tasks WHERE pid = '".$pid."'");
			echo '<div class="titles">'.$title.'</div>';
			if(!empty($img)) echo '<img src = "'.$img.'" alt="'.$title.'-image">';
			echo 'Posted by: '.$name['username'].'<br>';
			echo '<div class="content">'.$content.'</div><br>';
			echo '<div class="task-needed">Task needed : </div>';
			echo '<ul class="tasks">';
			while($job = mysqli_fetch_assoc($tasks)) {
				$taskArray[$counter]=$job;
				echo 'Task : '.$job['task'].'<br>';
				echo 'Reward : '.$job['reward'];
				if(empty($job['uid'])) {
					echo '<form method="post">';
					echo '<input type="hidden" name ="taskID" value="'.$taskArray[$counter]['tid'].'">';
					echo '<input type="submit" name ="applyTask" value="APPLY THIS TASK">';
					echo '</form>';
				} else echo '<div>Job has been taken.</div>';
				$counter += 1;				
			}
			echo '</ul>';
		}	
	?>

	<ul>
		<li><a href="./currentUser.php">BACK TO HOME</a></li>
		<li><a href="./LogOut.php">LOG OUT</a></li>
	</ul>
	
</body>
</html>

	<?php 
		if(isset($_POST['applyTask'])) {
			mysqli_query($conn, "UPDATE tasks SET uid= ${_SESSION['uid']} WHERE tid = ".$_POST['taskID']);
			echo "<meta http-equiv='refresh' content='0'>";
		}
	 ?>

<?php else : include("logout.php"); header("Location: LogIn.php"); ?>
<?php endif;  