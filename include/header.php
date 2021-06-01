<?php
require 'config/config.php';
include("include/class/User.php");
include("include/class/Post.php");
if(isset($_SESSION['username'])){
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT *FROM user WHERE username = '$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else{
	header("Location: register.php");
}
?>
<html>
<head>
	<title>Welcome To Telepath</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/bootbox.min.js"></script>
	<script src="assets/js/telepath.js"></script>
	<script src="assets/js/jquery.Jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
</head>
<body>
	<div class="top_bar">
		<div class="logo">
			<a href="index.php">Telepath!</a>	
		</div>
		<nav>
			<a href="<?php echo $userLoggedIn; ?>">
				<?php echo $user['namadepan'];  ?>
				
			</a>
			<a href="index.php"><i class="fa fa-home fa-lg"></i></a> 

			<a href="requests.php"><i class="fa fa-users fa-lg"></i></a>
			<a href="settings.php"><i class="fa fa-cog fa-lg"></i></a>
			<a href="include/handler/logout.php"><i class="fa fa-sign-out fa-lg"></i></a>
		</nav>
	</div>

	<div class="model">
		