<?php  
if(isset($_POST['log_button'])){
	$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
	$_SESSION['log_email'] = $email;
	$pass = md5($_POST['log_pass']);
	$cek_database = mysqli_query($con, "SELECT *FROM user WHERE email = '$email' AND password = '$pass'");
	$cek_login = mysqli_num_rows($cek_database);
	if($cek_login == 1){
		$row = mysqli_fetch_array($cek_database);
		$username = $row['username'];
		$closed = mysqli_query($con, "SELECT *FROM user WHERE email = '$email' AND private = 'Yes'");
		if(mysqli_num_rows($closed) == 1){
			$reopen = mysqli_query($con, "UPDATE user SET private = 'No' WHERE email = '$email'");
		}
		$_SESSION['username'] = $username;
		header("Location: index.php");
		exit();
	}
	else {
		array_push($error_array, "Email or Password Was Incorrect<br>");
	}
}
?>