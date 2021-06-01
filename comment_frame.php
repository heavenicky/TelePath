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
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

	<style type="text/css">
		*{
			font-size: 12px;
			font-family: Sans-serif;

		}
	</style>

	<script>
		function toggle(){
			var element = document.getElementById("comment_section");
			if(element.style.display == "block")
				element.style.display = "none";
			else
				element.style.display = "block";
			
		}
	</script>

	<?php 
	//Get ID Post
	if(isset($_GET['post_id'])){
		$post_id = $_GET['post_id'];
	}

	$user_query = mysqli_query($con, "SELECT pengirim, penerima FROM posts where id = '$post_id'");
	$row = mysqli_fetch_array($user_query);

	$penerima = $row['pengirim'];

	if(isset($_POST['postComment' . $post_id])){
		$post_body = $_POST['post_body'];
		$post_body = mysqli_escape_string($con, $post_body);
		$waktusekarang = date("Y-m-d H:i:s"); 
		$insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('','$post_body','$userLoggedIn','$penerima','$waktusekarang','no','$post_id')");
		echo "<p> Comment Success! </p>";
	}

	?>
	<form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
		<textarea name="post_body"></textarea>
		<input type="submit" name="postComment<?php echo $post_id; ?>" value="POST!">
	</form>

	<!-- ngeload comment --->
	<?php 
	$get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
	$count = mysqli_num_rows($get_comments);

	if($count != 0){
		while ($comment = mysqli_fetch_array($get_comments)) {
			$comment_body = $comment['post_body'];
			$penerima = $comment['penerima'];
			$pengirim = $comment['pengirim'];
			$tanggalcomment = $comment['tanggalcomment'];
			$removed = $comment['removed'];

			$waktusekarang = date("Y-m-d H:i:s");
			$start_date = new DateTime($tanggalcomment);
			$end_date = new DateTime($waktusekarang);
			$interval = $start_date->diff($end_date);
			if($interval->y>=1){
				if($interval == 1){
					$time_message = $interval->y . " Year Ago" ;
				}
				else{
					$time_message = $interval->y . " Years Ago" ;
				}
			}
			else if($interval->m >= 1){
				if($interval->d == 0){
					$days = " Ago";
				}
				else if($interval->d == 1){
					$days = $interval->d . " Day Ago";
				}
				else{
					$days = $interval->d . " Days Ago";
				}
				if($interval->m == 1){
					$time_message = $interval->m . " Month". $days;
				}
				else{
					$time_message = $interval->m . " Months". $days;
				}
			}
			else if($interval->d >= 1){
				if($interval->d == 1){
					$time_message = "Yesterday";
				}
				else{
					$time_message = $interval->d . " Days Ago";
				}
			}
			else if($interval->h >= 1){
				if($interval->h == 1){
					$time_message = $interval->h . " Hour Ago";
				}
				else{
					$time_message = $interval->h . " Hours Ago";
				}
			}
			else if($interval->i >= 1){
				if($interval->i == 1){
					$time_message = $interval->i . " Minute Ago";
				}
				else{
					$time_message = $interval->i . " Minutes Ago";
				}
			}
			else{
				if($interval->s < 30){
					$time_message = "Just Now";
				}
				else{
					$time_message = $interval->s . " Seconds Ago";
				}
			}
			$user_obj = new User($con, $pengirim);
			?>
			<div class="comment_section">
				<a href="<?php echo $pengirim?>" target="_parent"><img src="<?php echo $user_obj->GetProfilePic();?>" title="<?php echo $pengirim; ?>" style="float:left;" height="30"></a>
				<a href="<?php echo $pengirim?>" target="_parent"> <b> <?php echo $user_obj->GetFirstAndLastName(); ?> </b></a>
				&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . "<br>" . $comment_body; ?> 
				<hr>
			</div>
			<?php
		}
	}
	else{
		echo "<center><br><br>No Comments to Show!</center>";
	}
	?>

	


</body>
</html>