<?php 
if(isset($_POST['update_details'])){
	$namadepan = $_POST['namadepan'];
	$namabelakang = $_POST['namabelakang'];
	$email = $_POST['email'];

	$email_check = mysqli_query($con, "SELECT *FROM user WHERE email = '$email'");
	$row = mysqli_fetch_array($email_check);
	$matched_user = $row['username'];

	if($matched_user == "" || $matched_user == $userLoggedIn){
		$message = "Details Updated!<br><br>";

		$query = mysqli_query($con, "UPDATE user SET namadepan = '$namadepan', namabelakang = '$namabelakang', email = '$email' WHERE username = '$userLoggedIn'");
	}
	else{
		$message = "That Email is Already in Use!<br><br>";
	}
}
else{
	$message = "";
}

if(isset($_POST['update_password'])){
	$old_password = strip_tags($_POST['old_password']);
	$new_password1 = strip_tags($_POST['new_password1']);
	$new_password2 = strip_tags($_POST['new_password2']);

	$password_query = mysqli_query($con, "SELECT password FROM user WHERE username = '$userLoggedIn'");
	$row = mysqli_fetch_array($password_query);
	$db_password = $row['password'];

	if(md5($old_password) == $db_password){
		if($new_password1 == $new_password2){
			if(strlen($new_password1) <= 4){
				$password_message = "Your New Password is Too Short!<br><br>";
			}
			else{
				$new_password_md5 = md5($new_password1);
				$password_query = mysqli_query($con, "UPDATE user SET password = '$new_password_md5' WHERE username = '$userLoggedIn'");
				$password_message = "Password Has Been Change!<br><br>";
			}
		}
		else{
			$password_message = "Your New Password Must Be Match!<br><br>";
		}
	}
	else{
			$password_message = "The Old Password is Incorrect<br><br>";
		}
}
else{
	$password_message = "";
}

if(isset($_POST['close_account'])){
	header('Location: close_account.php');
}
 

 ?>