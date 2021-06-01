<?php 
include("include/header.php");

if(isset($_POST['cancel'])){
	header('Location: settings.php');
}
if(isset($_POST['close_account'])){
	$close_query = mysqli_query($con, "UPDATE user SET private = 'Yes' WHERE username = '$userLoggedIn'");
	session_destroy();
	header('Location: register.php');
}
 ?>

 <div class="main_column column">
 	
 	<h4>Close Account</h4>

 	Are You Sure You Want To Close Your Account?<br><br>
 	Closing Your Account Will Hide Your Profile From Other Users.<br><br>
 	You Can Re-Open Your Account By Logging In.<br><br>

 	<form action="close_account.php" method="POST"> 
 		<input type="submit" name="close_account" id="close_account" value="Yes" class="danger settings_submit">
 		<input type="submit" name="cancel" id="update_details" value="No" class="info settings_submit">
 	</form>

 </div>