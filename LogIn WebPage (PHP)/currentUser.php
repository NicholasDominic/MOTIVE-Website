<?php 
	session_id('curUser');
	session_start(); 
	if(isset($_SESSION['username'])) :?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>[TIMELINE] motive</title>
	<link rel="stylesheet" type="text/css" href="currUser.css">
	<link rel="icon" href="../favicon.png">
</head>
<body>
	<div class="headline"></div>
	<div class="tl">
		<div class="hello">
			Hello, <br><a href="profile.php"> <?php echo htmlentities($_SESSION['username']); ?></a><br>
			<img src="../logo.png" alt="motive LOGO">
		</div>
	</div>
	<ul>
		<li><a href="./news.php">NEWS</a></li>
		<li><a href="./post.php">POST</a></li>
		<li><a href="./myPosts.php">MY POST</a></li>
		<li><a href="./myTasks.php">MY TASK</a></li>
		<li><a href="./LogOut.php">LOG OUT</a></li>
	</ul>		
</body>
</html>

<?php else : include("logout.php"); header("Location: LogIn.php"); ?>
<?php endif;